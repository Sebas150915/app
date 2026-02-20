<?php
declare(strict_types=1);
header('Content-Type: application/json; charset=utf-8');

date_default_timezone_set('America/Lima');

require_once("../../../config/config.php");
require_once("../../../helpers/helpers.php"); 
require_once("../../../libraries/conexion.php");
session_start();
require __DIR__ . '/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

function cleanDecimal($value) {
    if ($value === null || $value === '') return null;
    $value = trim((string)$value);
    $value = str_replace(',', '', $value); // quita separador de miles
    return is_numeric($value) ? (float)$value : null;
}

function cleanDate($value) {
    if ($value === null || $value === '') return null;

    // Caso: número serial de Excel
    if (is_numeric($value)) {
        try {
            return ExcelDate::excelToDateTimeObject($value)->format('Y-m-d');
        } catch (\Throwable $e) {
            return null;
        }
    }

    // Caso: texto dd/mm/yyyy o dd-mm-yyyy
    $value = trim((string)$value);
    $value = str_replace(['.', ','], '/', $value); // normaliza separadores
    $ts = strtotime(str_replace('/', '-', $value));
    return $ts ? date('Y-m-d', $ts) : null;
}

try {
    if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
        throw new RuntimeException('Archivo no recibido.');
    }

    $fileTmp = $_FILES['file']['tmp_name'];
    $spreadsheet = IOFactory::load($fileTmp);
    $sheet = $spreadsheet->getActiveSheet();

    // recorrer por filas directamente
    for ($i = 2; $i <= $sheet->getHighestRow(); $i++) {
        $r = [];
        foreach (range('A', 'U') as $col) {
            $r[$col] = $sheet->getCell($col . $i)->getValue();
        }

        if (!$r || empty(array_filter($r))) continue;

        $fechapago = cleanDate($r['S'] ?? null);

        $stmt = $pdo->prepare("
            INSERT INTO mov_documentos_pagados (
                numero, sociedad, fecha_documento, tipo_documento, nro_documento,
                moneda, saldo_documento, nro_registro_sap, codigo_barras,
                monto_documento, afecto_retencion_igv, monto_retencion,
                afecto_detraccion, monto_detraccion, nro_orden_compra,
                banco, via_pago, nro_pago, fecha_pago, moneda_pago, importe_pagado
            ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)
        ");

        $stmt->execute([
            $r['A'] ?? null,                     // Numero
            $r['B'] ?? null,                     // Sociedad
            cleanDate($r['C'] ?? null),          // Fecha Documento
            $r['D'] ?? null,                     // Tipo Documento
            $r['E'] ?? null,                     // Nro Documento
            $r['F'] ?? null,                     // Moneda
            cleanDecimal($r['G'] ?? null),       // Saldo Documento
            $r['H'] ?? null,                     // Nro Registro SAP
            $r['I'] ?? null,                     // Código Barras
            cleanDecimal($r['J'] ?? null),       // Monto Documento
            ($r['K'] === 'SI' ? 1 : 0),          // Afecto Retención IGV
            cleanDecimal($r['L'] ?? null),       // Monto Retención
            ($r['M'] === 'SI' ? 1 : 0),          // Afecto Detracción
            cleanDecimal($r['N'] ?? null),       // Monto Detracción
            $r['O'] ?? null,                     // Nro Orden Compra
            $r['P'] ?? null,                     // Banco
            $r['Q'] ?? null,                     // Vía Pago
            $r['R'] ?? null,                     // N° Pago
            $fechapago,                          // Fecha Pago
            $r['T'] ?? null,                     // Moneda Pago
            cleanDecimal($r['U'] ?? null)        // Importe Pagado
        ]);
    }

    echo json_encode(['success' => true, 'message' => 'Archivo cargado y guardado con éxito']);
} catch (Throwable $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
