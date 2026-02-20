<?php
header('Content-Type: application/json');
session_start();
require_once("../../config/config.php");
require_once("../../helpers/helpers.php");
require_once("../../libraries/conexion.php");

$idempresa = $_SESSION['id_empresa'] ?? null;
if (empty($idempresa)) {
    echo json_encode(['error' => 'No se ha definido la empresa']);
    exit;
}

$op = $_GET['op'] ?? '';

switch ($op) {

    // ==========================================================
    // 📋 LISTAR CLIENTES (DataTables)
    // ==========================================================
    case 'clientes':
        $columns = ['id', 'ruc', 'razon', 'estado'];
        $query = "SELECT * FROM tbl_cliente_empresa WHERE idempresa = :idempresa ";

        if (!empty($_POST["search"]["value"])) {
            $busqueda = trim($_POST["search"]["value"]);
            $query .= "AND (razon LIKE :busqueda OR ruc LIKE :busqueda) ";
        }

        if (isset($_POST['order']) && isset($_POST['order'][0]['column'])) {
    $columna = $columns[$_POST['order'][0]['column']] ?? 'id';
    $direccion = ($_POST["order"][0]["dir"] === 'desc') ? 'DESC' : 'ASC';
} else {
    // Valores por defecto si no se envía "order"
    $columna = 'id';
    $direccion = 'ASC';
}
        $query .= "ORDER BY $columna $direccion ";

        if ($_POST["length"] != -1) {
            $query .= "LIMIT :start, :length";
        }

        $stmt = $connect->prepare($query);
        $stmt->bindValue(':idempresa', $idempresa, PDO::PARAM_INT);

        if (!empty($_POST["search"]["value"])) {
            $stmt->bindValue(':busqueda', "%$busqueda%", PDO::PARAM_STR);
        }

        if ($_POST["length"] != -1) {
            $stmt->bindValue(':start', intval($_POST["start"]), PDO::PARAM_INT);
            $stmt->bindValue(':length', intval($_POST["length"]), PDO::PARAM_INT);
        }

        $stmt->execute();
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $filtered_rows = $stmt->rowCount();

        $data = [];
        foreach ($resultado as $fila) {
            $color = ($fila["estado"] == 'ACTIVO') ? 'success' : 'danger';
            $sub_array = [];
            $sub_array[] = $fila["id"];
            $sub_array[] = $fila["razon"];
            $sub_array[] = $fila["ruc"];
            $sub_array[] = '<span class="badge bg-' . $color . '">' . $fila["estado"] . '</span>';
            $sub_array[] = '<button type="button" id="' . $fila["id"] . '" class="btn btn-warning btn-sm editar"><i class="fa-solid fa-pen-to-square"></i></button>';
            $sub_array[] = '<button type="button" id="' . $fila["id"] . '" class="btn btn-danger btn-sm borrar"><i class="fa-solid fa-trash"></i></button>';
            $data[] = $sub_array;
        }

        $total = obtener_todos_cliente_empresa2($idempresa, $connect);

        echo json_encode([
            "draw" => intval($_POST["draw"]),
            "recordsTotal" => $total,
            "recordsFiltered" => $filtered_rows,
            "data" => $data
        ]);
    break;

    // ==========================================================
    // 💾 GUARDAR / EDITAR CLIENTE
    // ==========================================================
   case 'guardar':
    session_start();
    $idempresa = $_SESSION['id_empresa'] ?? null;
    $operacion = $_POST['operacion'] ?? '';
    $mensaje = [];

    if ($operacion == 'Crear') {
        $stmt = $connect->prepare("
            INSERT INTO tbl_cliente_empresa 
            (idempresa, ruc, razon, usuario_sol, clave_sol, idgre, secretgre, origendt, cuentadt, origencompras, 
             cuenta42soles, cuenta42dolar, origenventas, cuenta12soles, cuenta12dolar, 
             origenhonorarios, cuentarhsoles, cuentarhdolar, cuenta40igv, estado)
            VALUES 
            (:idempresa, :ruc, :razon, :usuario_sol, :clave_sol, :idgre, :secretgre, :origendt, :cuentadt, :origencompras, 
             :cuenta42soles, :cuenta42dolar, :origenventas, :cuenta12soles, :cuenta12dolar, 
             :origenhonorarios, :cuentarhsoles, :cuentarhdolar, :cuenta40igv, 'ACTIVO')
        ");
        $stmt->bindValue(':idempresa', $idempresa, PDO::PARAM_INT);
    } else {
        $stmt = $connect->prepare("
            UPDATE tbl_cliente_empresa SET
                ruc = :ruc, razon = :razon, usuario_sol = :usuario_sol, clave_sol = :clave_sol, 
                idgre = :idgre, secretgre = :secretgre, origendt = :origendt, cuentadt = :cuentadt, 
                origencompras = :origencompras, cuenta42soles = :cuenta42soles, cuenta42dolar = :cuenta42dolar, 
                origenventas = :origenventas, cuenta12soles = :cuenta12soles, cuenta12dolar = :cuenta12dolar, 
                origenhonorarios = :origenhonorarios, cuentarhsoles = :cuentarhsoles, 
                cuentarhdolar = :cuentarhdolar, cuenta40igv = :cuenta40igv
            WHERE id = :id
        ");
        $stmt->bindValue(':id', $_POST['id_usuario'] ?? null, PDO::PARAM_INT);
    }

    // Campos que coinciden con la tabla y el formulario
    $campos = [
        'ruc' => 'dni', // del formulario “dni” → en BD “ruc”
        'razon' => 'razon',
        'usuario_sol' => 'usuario_sol',
        'clave_sol' => 'clave_sol',
        'idgre' => 'idgre',
        'secretgre' => 'secretgre',
        'origendt' => 'origendt',
        'cuentadt' => 'cuentact', // en formulario es cuentact, en BD cuentadt
        'origencompras' => 'origencompras',
        'cuenta42soles' => 'cuenta42soles',
        'cuenta42dolar' => 'cuenta42dolar',
        'origenventas' => 'origenventas',
        'cuenta12soles' => 'cuenta12soles',
        'cuenta12dolar' => 'cuenta12dolar',
        'origenhonorarios' => 'origenhonorarios',
        'cuentarhsoles' => 'cuentarhsoles',
        'cuentarhdolar' => 'cuentarhdolar',
        'cuenta40igv' => 'cuenta40igv'
    ];

    // Asignar los valores
    foreach ($campos as $param => $campo_form) {
        $stmt->bindValue(":$param", $_POST[$campo_form] ?? null);
    }

    $resultado = $stmt->execute();

    $mensaje['respuesta'] = $resultado
        ? "Registro {$operacion}do correctamente"
        : "Error al guardar";

    echo json_encode($mensaje);
break;


    // ==========================================================
    // 🔍 BUSCAR CLIENTE
    // ==========================================================
    case 'buscar':
        $stmt = $connect->prepare("SELECT * FROM tbl_cliente_empresa WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $_POST["id_usuario"]]);
        echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
    break;

    // ==========================================================
    // ❌ ELIMINAR / CAMBIAR ESTADO
    // ==========================================================
    case 'eliminar':
        $stmt = $connect->prepare("SELECT estado FROM tbl_cliente_empresa WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $_POST["id_usuario"]]);
        $fila = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($fila) {
            $nuevoEstado = ($fila['estado'] == 'ACTIVO') ? 'INACTIVO' : 'ACTIVO';
            $stmt = $connect->prepare("UPDATE tbl_cliente_empresa SET estado = :estado WHERE id = :id");
            $stmt->execute([':estado' => $nuevoEstado, ':id' => $_POST["id_usuario"]]);
            echo json_encode(['respuesta' => "Registro actualizado a $nuevoEstado"]);
        } else {
            echo json_encode(['respuesta' => 'Registro no encontrado']);
        }
    break;

    default:
        echo json_encode(['error' => 'Operación no válida']);
    break;
}


?>
