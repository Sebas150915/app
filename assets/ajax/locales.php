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

    // =====================================
    // CASE: CLIENTES (si luego lo usas)
    // =====================================
    case 'clientes':
        $query = "SELECT * FROM tbl_cliente_empresa WHERE idempresa = :idempresa ORDER BY ruc ASC";
        $stmt = $connect->prepare($query);
        $stmt->bindValue(':idempresa', $idempresa, PDO::PARAM_INT);
        $stmt->execute();
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        break;

    // =====================================
    // CASE: CENTRO DE COSTOS
    // =====================================
    case 'locales':

    $clienteSeleccionado = $_GET['cliente'] ?? null;
    $draw = $_POST['draw'] ?? 1;
    $start = $_POST['start'] ?? 0;
    $length = $_POST['length'] ?? 10;
    $searchValue = $_POST['search']['value'] ?? '';

    // 🔒 Prevención de errores en ordenamiento
    $columnIndex = 0;
    $columnSortOrder = 'asc';
    if (isset($_POST['order']) && is_array($_POST['order']) && count($_POST['order']) > 0) {
        $columnIndex = $_POST['order'][0]['column'] ?? 0;
        $columnSortOrder = $_POST['order'][0]['dir'] ?? 'asc';
    }

    $columns = ['id', 'nombre', 'estado'];

    // 1️⃣ Total sin filtro
    $stmt_total = $connect->prepare("SELECT COUNT(*) AS total FROM tbl_locales WHERE idcliente = :idempresa");
    $stmt_total->bindValue(':idempresa', $clienteSeleccionado, PDO::PARAM_INT);
    $stmt_total->execute();
    $totalRecords = $stmt_total->fetch(PDO::FETCH_ASSOC)['total'];

    // 2️⃣ Consulta base con filtros
    $query = "SELECT * FROM tbl_locales WHERE idcliente = :idempresa ";
    if (!empty($searchValue)) {
        $query .= "AND (nombre LIKE :busqueda OR codigo LIKE :busqueda) ";
    }

    // 3️⃣ Total con filtro
    $stmt_filtered = $connect->prepare(str_replace('*', 'COUNT(*) AS total', $query));
    $stmt_filtered->bindValue(':idempresa', $clienteSeleccionado, PDO::PARAM_INT);
    if (!empty($searchValue)) {
        $stmt_filtered->bindValue(':busqueda', "%$searchValue%", PDO::PARAM_STR);
    }
    $stmt_filtered->execute();
    $totalRecordwithFilter = $stmt_filtered->fetch(PDO::FETCH_ASSOC)['total'];

    // 4️⃣ Ordenamiento
    $columna = $columns[$columnIndex] ?? 'id';
    $direccion = ($columnSortOrder === 'desc') ? 'DESC' : 'ASC';
    $query .= "ORDER BY $columna $direccion ";

    // 5️⃣ Paginación
    if ($length != -1) {
        $query .= "LIMIT :start, :length";
    }

    // 6️⃣ Consulta principal
    $stmt = $connect->prepare($query);
    $stmt->bindValue(':idempresa', $clienteSeleccionado, PDO::PARAM_INT);
    if (!empty($searchValue)) {
        $stmt->bindValue(':busqueda', "%$searchValue%", PDO::PARAM_STR);
    }
    if ($length != -1) {
        $stmt->bindValue(':start', intval($start), PDO::PARAM_INT);
        $stmt->bindValue(':length', intval($length), PDO::PARAM_INT);
    }
    $stmt->execute();
    $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 7️⃣ Preparar los datos
    $datos = [];
    foreach ($resultado as $fila) {
        $color = ($fila["estado"] === 'ACTIVO') ? 'success' : 'danger';

        $sub_array = [];
        $sub_array[] = $fila["id"];
        $sub_array[] = htmlspecialchars($fila["nombre"]);
        
        $sub_array[] = '<span class="badge bg-'.$color.' btn-xs editar">'.$fila["estado"].'</span>';
        $sub_array[] = '<button type="button" name="editar" id="'.$fila["id"].'" class="btn btn-warning btn-xs editar">Editar</button>';
        $sub_array[] = '<button type="button" name="borrar" id="'.$fila["id"].'" class="btn btn-danger btn-xs borrar">Borrar</button>';
        $datos[] = $sub_array;
    }

    // 8️⃣ Respuesta JSON para DataTables
    $salida = [
        "draw" => intval($draw),
        "recordsTotal" => intval($totalRecords),
        "recordsFiltered" => intval($totalRecordwithFilter),
        "data" => $datos
    ];

    echo json_encode($salida);
    break;
    
        
        
    case 'guardar':
    $mensaje = array();
    
    if($_POST['operacion']=='Crear')
    {
        $stmt = $connect->prepare("INSERT INTO tbl_locales(idcliente, nombre) VALUES(:cliente, :nombre)");

    $resultado = $stmt->execute(
        array(
            ':cliente'    => $_GET["cliente"],
            ':nombre'    => $_POST["nombre"]
           
        )
    );

        if (!empty($resultado)) 
        {
            $mensaje['respuesta'] = 'Registro creado correctamente';
        } 
        else 
        {
            $mensaje['respuesta'] = 'Error al registrar';
        }

    }
    else
    {
       
        $stmt = $connect->prepare("UPDATE tbl_locales SET nombre=:nombre WHERE id = :id");

    
       $resultado = $stmt->execute(
        array(
            ':nombre'    => $_POST["nombre"],
           
           
            ':id'    => $_POST["id_usuario"]
        )
    );

     if (!empty($resultado)) 
        {
            $mensaje['respuesta'] = 'Registro Actualizado correctamente';
        } 
        else 
        {
            $mensaje['respuesta'] = 'Error al Actualizar';
        }
        
    }
    
            
        echo json_encode($mensaje);
        
    break;
    
    
    case 'buscar':
    
    $salida = array();
    $stmt = $connect->prepare("SELECT * FROM tbl_locales WHERE id = '".$_POST["id_usuario"]."' LIMIT 1");
    $stmt->execute();
    $resultado = $stmt->fetchAll();
    foreach($resultado as $fila){
        $salida["nombre"] = $fila["nombre"];
        
        
        
    }

    echo json_encode($salida);    
        
        
    break;
    
    case 'eliminar':
    $mensaje = array();    

    $stmt = $connect->prepare("SELECT estado FROM tbl_locales WHERE id = :id LIMIT 1");
    $stmt->execute([':id' => $_POST["id_usuario"]]);
    $fila = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($fila) {
        $estado = ($fila['estado'] == 'ACTIVO') ? 'INACTIVO' : 'ACTIVO';

        $stmt = $connect->prepare("UPDATE tbl_locales SET estado = :estado WHERE id = :id");
        $resultado = $stmt->execute([
            ':estado' => $estado,
            ':id' => $_POST["id_usuario"]
        ]);

        if ($resultado) {
            $mensaje['respuesta'] = "Registro actualizado correctamente a estado $estado";
        } else {
            $mensaje['respuesta'] = 'Error al actualizar el registro';
        }
    } else {
        $mensaje['respuesta'] = 'Registro no encontrado';
    }

    echo json_encode($mensaje);
    break;

    
    default:
        echo json_encode(['error' => 'Operación no válida']);
        break;
}
?>
