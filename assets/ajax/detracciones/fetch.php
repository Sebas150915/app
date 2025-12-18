<?php
declare(strict_types=1);
header('Content-Type: application/json; charset=utf-8');
require_once("../../../config/config.php");
require_once("../../../helpers/helpers.php"); 
require_once("../../../libraries/conexion.php");
session_start();

try {
    // Traer todos los registros
    $stmt = $pdo->query("SELECT 
        id,
        numero,
        sociedad,
        fecha_documento,
        tipo_documento,
        nro_documento,
        moneda,
        documento,
        saldo_documento,
        nro_registro_sap,
        codigo_barras,
        monto_documento,
        afecto_retencion_igv,
        monto_retencion,
        afecto_detraccion,
        monto_detraccion,
        nro_orden_compra,
        banco,
        via_pago,
        nro_pago,
        fecha_pago,
        moneda_pago,
        importe_pagado,
        uploaded_at
    FROM mov_documentos_pagados");

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "success" => true,
        "data" => $rows
    ], JSON_UNESCAPED_UNICODE);

} catch (Throwable $e) {
    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
}
