<?php
require_once("../../../config/config.php");
require_once("../../../helpers/helpers.php"); 
require_once("../../../libraries/conexion.php");
session_start();

// Validar si llegan los parámetros necesarios
if (empty($_POST['id']) || empty($_POST['nueva_fecha'])) {
    http_response_code(400);
    echo json_encode([
        "success" => false,
        "message" => "ID o fecha no válidos."
    ]);
    exit;
}

$id = (int) $_POST['id'];
$nueva_fecha = $_POST['nueva_fecha'];

try {
    $stmt = $pdo->prepare("UPDATE mov_detracciones SET fecha_pago = ? WHERE id = ?");
    $stmt->execute([$nueva_fecha, $id]);

    echo json_encode([
        "success" => true,
        "message" => "Fecha actualizada correctamente."
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "message" => "Error al actualizar: " . $e->getMessage()
    ]);
}
