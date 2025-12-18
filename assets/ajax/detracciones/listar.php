<?php
require_once("../../../config/config.php");
require_once("../../../helpers/helpers.php"); 
require_once("../../../libraries/conexion.php");
session_start();

// ✅ Edición de fecha
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['accion'] ?? '') === 'editar_fecha') {
    $id = $_POST['id'] ?? null;
    $nueva_fecha = $_POST['nueva_fecha'] ?? null;

    if ($id && $nueva_fecha) {
        $stmt = $connect->prepare("UPDATE mov_detracciones SET fecha_pago = ? WHERE id = ?");
        if ($stmt->execute([$nueva_fecha, $id])) {
            echo json_encode(["success" => true, "message" => "Fecha actualizada"]);
        } else {
            echo json_encode(["success" => false, "message" => "Error al actualizar"]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Datos incompletos"]);
    }
    exit;
}

// ========= LISTADO ========= //
$where  = [];
$params = [];

// Filtros dinámicos
if (!empty($_GET['fecha_desde']) && !empty($_GET['fecha_hasta'])) {
    $where[] = "fecha_pago BETWEEN ? AND ?";
    $params[] = $_GET['fecha_desde'];
    $params[] = $_GET['fecha_hasta'];
}
if (!empty($_GET['ruc_emisor'])) {
    $where[] = "ruc_proveedor LIKE ?";
    $params[] = "%".$_GET['ruc_emisor']."%";
}
if (!empty($_GET['ruc_receptor'])) {
    $where[] = "num_doc_adquiriente LIKE ?";
    $params[] = "%".$_GET['ruc_receptor']."%";
}

// Siempre filtrar por RUC fijo
$where[] = "ruc_proveedor = ?";
$params[] = "10441689166";

$sql = "SELECT * FROM mov_detracciones";
if ($where) {
    $sql .= " WHERE " . implode(" AND ", $where);
}
$sql .= " ORDER BY fecha_pago DESC, id DESC";

$stmt = $connect->prepare($sql);
$stmt->execute($params);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Render tabla
echo '<table id="tablaDetracciones" class="table table-striped table-bordered">';
echo '<thead class="thead-dark"><tr>
        <th>Fecha Pago</th>
        <th>RUC Emisor</th>
        <th>RUC Receptor</th>
        <th>Razón Social</th>
        <th>Monto</th>
        <th>Comprobante</th>
        <th>Acciones</th>
      </tr></thead><tbody>';

foreach ($rows as $r) {
    $fechaBD      = $r['fecha_pago'] ?? '';
    $fechaMostrar = $fechaBD ? date('d/m/Y', strtotime($fechaBD)) : '';
    $fechaISO     = $fechaBD ? date('Y-m-d', strtotime($fechaBD)) : ''; // ✅ formato para input date
    $montoMostrar = number_format((float)$r['monto_deposito'], 2, '.', ',');

    $serie  = $r['serie_comprobante'] ?? '';
    $numero = isset($r['numero_comprobante']) ? (int)$r['numero_comprobante'] : '';
    $comp   = trim(($r['tipo_comprobante'] ?? '').' '.$serie.'-'.$numero, " -");

    echo "<tr>
            <td>{$fechaMostrar}</td>
            <td>{$r['ruc_proveedor']}</td>
            <td>{$r['num_doc_adquiriente']}</td>
            <td>{$r['razon_social_adquiriente']}</td>
            <td class='text-right'>{$montoMostrar}</td>
            <td>{$comp}</td>
            <td>
              <button class='btn btn-warning btn-sm btnEditar' 
                      data-id='{$r['id']}' 
                      data-fecha='{$fechaISO}'>
                ✏ Editar
              </button>
            </td>
          </tr>";
}
echo "</tbody></table>";
