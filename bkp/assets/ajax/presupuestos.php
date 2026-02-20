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
    case 'presupuestos':
    $clienteSeleccionado = $_GET['cliente'] ?? null;

    $columns = ['id', 'nombre', 'codigo', 'estado'];
    $query = "SELECT * FROM tbl_presupuestos WHERE cliente = :idempresa ";

    // 🔍 Filtro de búsqueda
    $busqueda = $_POST["search"]["value"] ?? '';
    if (!empty($busqueda)) {
        $query .= "AND (nombre LIKE :busqueda OR codigo LIKE :busqueda) ";
    }

    // 🔽 Ordenamiento seguro
    if (isset($_POST['order']) && is_array($_POST['order']) && !empty($_POST['order'][0])) {
        $columnaIdx = $_POST['order'][0]['column'] ?? 0;
        $columna = $columns[$columnaIdx] ?? 'id';
        $direccion = ($_POST['order'][0]['dir'] ?? 'DESC') === 'desc' ? 'DESC' : 'ASC';
        $query .= "ORDER BY $columna $direccion ";
    } else {
        $query .= "ORDER BY id DESC ";
    }

    // 📄 Paginación segura
    $start = isset($_POST['start']) ? intval($_POST['start']) : 0;
    $length = isset($_POST['length']) ? intval($_POST['length']) : 10;
    if ($length != -1) {
        $query .= "LIMIT :start, :length";
    }

    $stmt = $connect->prepare($query);
    $stmt->bindValue(':idempresa', $clienteSeleccionado, PDO::PARAM_INT);

    if (!empty($busqueda)) {
        $stmt->bindValue(':busqueda', "%$busqueda%", PDO::PARAM_STR);
    }

    if ($length != -1) {
        $stmt->bindValue(':start', $start, PDO::PARAM_INT);
        $stmt->bindValue(':length', $length, PDO::PARAM_INT);
    }

    $stmt->execute();
    $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $filtered_rows = count($resultado);

    $datos = [];
    foreach ($resultado as $fila) {
        $estado = $fila["estado"];
        $color = ($estado == 'ACTIVO') ? 'success' : 'danger';
        
        $sub_array = [];
        $sub_array[] = $fila["id"];
        $sub_array[] = $fila["nombre"];
        $sub_array[] = $fila["codigo"];
        $sub_array[] = '<span class="badge bg-'.$color.' btn-xs editar">'.$fila["estado"].'</span>';
        $sub_array[] = '<button type="button" name="editar" id="'.$fila["id"].'" class="btn btn-warning btn-xs editar">Editar</button>';
        $sub_array[] = '<button type="button" name="borrar" id="'.$fila["id"].'" class="btn btn-danger btn-xs borrar">Borrar</button>';
        $datos[] = $sub_array;
    }

    // 🧮 Total de registros sin filtro
    $total = obtener_todos_presupuestos($clienteSeleccionado, $connect);

    $salida = [
        "draw"            => intval($_POST["draw"] ?? 0),
        "recordsTotal"    => $total,
        "recordsFiltered" => $filtered_rows,
        "data"            => $datos
    ];

    echo json_encode($salida);
    break;

        
        
    case 'guardar':
        
        //var_dump($_POST);
    $mensaje = array();
    
    if($_POST['operacion']=='Crear')
    {
        $stmt = $connect->prepare("INSERT INTO tbl_presupuestos(cliente, nombre, codigo) VALUES(:cliente, :nombre, :codigo)");

    $resultado = $stmt->execute(
        array(
            ':cliente'    => $_GET["cliente"],
            ':nombre'    => $_POST["nombre"],
            ':codigo'    => $_POST["codigo"]
           
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
       
        $stmt = $connect->prepare("UPDATE tbl_presupuestos SET nombre=:nombre, codigo=:codigo WHERE id = :id");

    
       $resultado = $stmt->execute(
        array(
            ':nombre'    => $_POST["nombre"],
            ':codigo'    => $_POST["codigo"],
           
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
    $stmt = $connect->prepare("SELECT * FROM tbl_presupuestos WHERE id = '".$_POST["id_usuario"]."' LIMIT 1");
    $stmt->execute();
    $resultado = $stmt->fetchAll();
    foreach($resultado as $fila){
        $salida["nombre"] = $fila["nombre"];
        $salida["codigo"] = $fila["codigo"];
        
        
    }

    echo json_encode($salida);    
        
        
    break;
    
    case 'eliminar':
    $mensaje = array();    

    $stmt = $connect->prepare("SELECT estado FROM tbl_presupuestos WHERE id = :id LIMIT 1");
    $stmt->execute([':id' => $_POST["id_usuario"]]);
    $fila = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($fila) {
        $estado = ($fila['estado'] == 'ACTIVO') ? 'INACTIVO' : 'ACTIVO';

        $stmt = $connect->prepare("UPDATE tbl_presupuestos SET estado = :estado WHERE id = :id");
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
