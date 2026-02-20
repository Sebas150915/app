<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');

require_once("../../config/config.php");
require_once("../../helpers/helpers.php"); 
require_once("../../libraries/conexion.php");
session_start();

$idempresa = $_SESSION['id_empresa'] ?? null;

if (empty($idempresa)) {
    echo json_encode(['error' => 'No se ha definido la empresa']);
    exit;
}

$variable = $_GET['op'] ?? '';

switch ($variable) 
{
    case 'lista':
        try {
            $stmt = $connect->prepare("SELECT id, nombre, codigo FROM tbl_tipo_cambio WHERE cliente = :idempresa AND estado = 'ACTIVO' ORDER BY nombre ASC");
            $stmt->bindValue(':idempresa', $idempresa, PDO::PARAM_INT);
            $stmt->execute();
            
            $tipos_cambio = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            echo json_encode($tipos_cambio);
            
        } catch (Exception $e) {
            echo json_encode(['error' => 'Error: ' . $e->getMessage()]);
        }
        break;
        
    default:
        echo json_encode(['error' => 'Operación no válida']);
        break;
}
?>
