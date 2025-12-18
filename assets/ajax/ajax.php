<?php

header('Content-Type: application/json');
require_once('../../config/config.php');
require_once('../../libraries/conexion.php');
session_start();



$op = $_GET['op'];


switch ($op) {
    case 'buscarCliente':
        
        $ruc = $_GET['ruc'];
        $query = "SELECT * FROM vw_conrtribuyente WHERE ruc = :ruc";
        $stmt  = $connect->prepare($query);
        $stmt->bindValue(':ruc', $ruc, PDO::PARAM_STR);
        $stmt->execute();
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        
    break;
    
    default:
        // code...
        break;
}




?>