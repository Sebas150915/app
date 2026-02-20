<?php
require_once("../../config/config.php");
require_once("../../helpers/helpers.php");
require_once("../../libraries/conexion.php");

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ids = $_POST['ids'] ?? [];
    $siscont = $_POST['siscont'] ?? '';
    
    if (empty($ids) || !in_array($siscont, ['SI', 'NO'])) {
        echo json_encode(['success' => false, 'message' => 'Parámetros inválidos']);
        exit;
    }
    
    try {
        // Convertir a array si es un string
        if (is_string($ids)) {
            $ids = explode(',', $ids);
        }
        
        // Preparar placeholders para la consulta
        $placeholders = str_repeat('?,', count($ids));
        $placeholders = rtrim($placeholders, ','); // Eliminar la última coma
        
        $sql = "UPDATE mov_compras SET siscont = ? WHERE movkey IN ($placeholders)";
        $stmt = $connect->prepare($sql);
        
        // Agregar el valor de siscont al principio del array de parámetros
        $params = array_merge([$siscont], $ids);
        
        $result = $stmt->execute($params);
        
        if ($result) {
            $affected_rows = $stmt->rowCount();
            echo json_encode([
                'success' => true, 
                'message' => "Se actualizó SISCONT a '$siscont' para $affected_rows registro(s)"
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al actualizar los registros']);
        }
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}
?>
