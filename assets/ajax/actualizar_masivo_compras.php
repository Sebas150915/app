<?php
require_once("../../config/config.php");
require_once("../../helpers/helpers.php");
require_once("../../libraries/conexion.php");

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ids = $_POST['ids'] ?? [];
    $centro_costos = $_POST['centro_costos'] ?? '';
    $presupuesto = $_POST['presupuesto'] ?? '';
    $tipo_cambio = $_POST['tipo_cambio'] ?? '';
    $glosasire = $_POST['glosasire'] ?? '';
    
    if (empty($ids)) {
        echo json_encode(['success' => false, 'message' => 'No se seleccionaron registros']);
        exit;
    }
    
    // Verificar que al menos un campo tenga datos
    if (empty($centro_costos) && empty($presupuesto) && empty($tipo_cambio) && empty($glosasire)) {
        echo json_encode(['success' => false, 'message' => 'Debe completar al menos un campo para actualizar']);
        exit;
    }
    
    try {
        // Convertir a array si es un string
        if (is_string($ids)) {
            $ids = explode(',', $ids);
        }
        
        // Preparar placeholders para la consulta
        $placeholders = str_repeat('?,', count($ids));
        $types = str_repeat('i', count($ids));
        
        // Construir la consulta dinámicamente
        $update_fields = [];
        $params = [];
        
        if (!empty($centro_costos)) {
            $update_fields[] = "centro_costos = ?";
            $params[] = $centro_costos;
        }
        
        if (!empty($presupuesto)) {
            $update_fields[] = "presupuesto = ?";
            $params[] = $presupuesto;
        }
        
        if (!empty($tipo_cambio)) {
            $update_fields[] = "tipo_cambio = ?";
            $params[] = $tipo_cambio;
        }
        
        if (!empty($glosasire)) {
            $update_fields[] = "glosasire = ?";
            $params[] = $glosasire;
        }
        
        if (empty($update_fields)) {
            echo json_encode(['success' => false, 'message' => 'No hay campos para actualizar']);
            exit;
        }
        
        $sql = "UPDATE mov_compras SET " . implode(', ', $update_fields) . " WHERE movkey IN ($placeholders)";
        $stmt = $connect->prepare($sql);
        
        // Agregar los IDs al final de los parámetros
        $params = array_merge($params, $ids);
        
        $result = $stmt->execute($params);
        
        if ($result) {
            $affected_rows = $stmt->rowCount();
            echo json_encode([
                'success' => true, 
                'message' => "Se actualizó $affected_rows registro(s) correctamente"
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
