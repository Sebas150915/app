<?php
require_once("../../../config/config.php");
require_once("../../../helpers/helpers.php"); 
require_once("../../../libraries/conexion.php");
session_start();

if (empty($_POST['desde']) || empty($_POST['hasta'])) {
    http_response_code(400);
    echo "Rango de fechas no válido.";
    exit;
}

$desde = $_POST['desde'];
$hasta = $_POST['hasta'];

$stmt = $connect->prepare("DELETE FROM mov_detracciones WHERE fecha_pago BETWEEN ? AND ?");
$stmt->execute([$desde, $hasta]);

echo "Registros eliminados correctamente.";
