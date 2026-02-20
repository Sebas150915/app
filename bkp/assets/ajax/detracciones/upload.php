<?php
require_once("../../../config/config.php");
require_once("../../../helpers/helpers.php"); 
require_once("../../../libraries/conexion.php");
session_start();


$idcliente = $_GET['idcliente'];

if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
    http_response_code(400);
    echo "No se recibió el archivo o hubo un error al subirlo.";
    exit;
}

$handle = fopen($_FILES['file']['tmp_name'], 'r');
if (!$handle) {
    http_response_code(500);
    echo "No se pudo abrir el archivo.";
    exit;
}

$lineNumber = 0;
$inserted = 0;
$skipped  = 0;

$connect->beginTransaction();
try {
    while (($line = fgets($handle)) !== false) {
        $lineNumber++;
        if ($lineNumber === 1) { continue; } // Saltar cabecera

        // Quitar saltos y separar por |
        $cols = array_map('trim', explode('|', rtrim($line, "\r\n")));

        if (count($cols) < 17) { $skipped++; continue; }

        list(
            $tipo_cuenta,
            $numero_cuenta,
            $numero_constancia,
            $periodo_tributario,
            $ruc_proveedor,
            $nombre_proveedor,
            $tipo_doc_adq,
            $num_doc_adq,
            $razon_adq,
            $fecha_pago_txt,
            $monto_txt,
            $tipo_bien,
            $tipo_operacion,
            $tipo_comprobante,
            $serie_comprobante,
            $numero_comprobante,
            $numero_pago
        ) = array_slice($cols, 0, 17);

        // Parsear fecha
        $fecha_pago_sql = null;
        if ($fecha_pago_txt !== '') {
            $dt = DateTime::createFromFormat('d/m/Y', $fecha_pago_txt)
               ?: DateTime::createFromFormat('d/m/y', $fecha_pago_txt);
            if ($dt) {
                $fecha_pago_sql = $dt->format('Y-m-d');
            }
        }

        // Normalizar monto
        $monto_norm = str_replace(',', '.', $monto_txt); 
        $monto_deposito = is_numeric($monto_norm) ? (float)$monto_norm : 0.0;

        // Generar código único
        $codigo_unico = $ruc_proveedor."-".$tipo_comprobante."-".$serie_comprobante."-".((int)$numero_comprobante);

        // Verificar duplicado
        $check = $connect->prepare("SELECT COUNT(*) FROM mov_detracciones WHERE codigo_unico = ?");
        $check->execute([$codigo_unico]);
        if ($check->fetchColumn() > 0) {
            $skipped++;
            continue;
        }

        // Insertar
        $stmt = $connect->prepare("
            INSERT INTO mov_detracciones
            (tipo_cuenta, numero_cuenta, numero_constancia, periodo_tributario,
             ruc_proveedor, nombre_proveedor, tipo_doc_adquiriente, num_doc_adquiriente,
             razon_social_adquiriente, fecha_pago, monto_deposito, tipo_bien,
             tipo_operacion, tipo_comprobante, serie_comprobante, numero_comprobante,
             numero_pago_detraccion, codigo_unico, idcliente)
            VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)
        ");

        $stmt->execute([
            $tipo_cuenta,
            $numero_cuenta,
            $numero_constancia,
            $periodo_tributario,
            $ruc_proveedor,
            $nombre_proveedor,
            $tipo_doc_adq,
            $num_doc_adq,
            $razon_adq,
            $fecha_pago_sql,
            $monto_deposito,
            $tipo_bien,
            $tipo_operacion,
            $tipo_comprobante,
            $serie_comprobante,
            $numero_comprobante,
            $numero_constancia ?: null,
            $codigo_unico,
            $idcliente
        ]);

        $inserted++;
    }

    $connect->commit();
    fclose($handle);
    echo "Archivo procesado. Registros insertados: {$inserted}. Duplicados omitidos: {$skipped}.";
} catch (Exception $e) {
    $connect->rollBack();
    if (is_resource($handle)) fclose($handle);
    http_response_code(500);
    echo "Error en la carga (línea {$lineNumber}): " . $e->getMessage();
}
