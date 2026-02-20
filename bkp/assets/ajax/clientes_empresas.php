<?php
header('Content-Type: application/json');
require_once("../../config/config.php");
require_once("../../libraries/conexion.php");
session_start();

$op = $_GET['op'] ?? '';

switch ($op) {
  case 'listar':
    $draw = intval($_POST['draw'] ?? 1);
    $start = intval($_POST['start'] ?? 0);
    $length = intval($_POST['length'] ?? 10);
    $search = $_POST['search']['value'] ?? '';

    $base = "FROM tbl_empresas";
    $params = [];
    if ($search) {
      $base .= " WHERE razon LIKE :busqueda OR ruc LIKE :busqueda";
      $params[':busqueda'] = "%$search%";
    }

    $stmt = $connect->prepare("SELECT COUNT(*) " . $base);
    foreach ($params as $k => $v) $stmt->bindValue($k, $v);
    $stmt->execute();
    $total = intval($stmt->fetchColumn());

    $stmt = $connect->prepare("SELECT id, ruc, razon, direccion, fecha_inicio, fecha_fin, paquetes, estado " . $base . " ORDER BY id DESC LIMIT :start, :length");
    foreach ($params as $k => $v) $stmt->bindValue($k, $v);
    $stmt->bindValue(':start', $start, PDO::PARAM_INT);
    $stmt->bindValue(':length', $length, PDO::PARAM_INT);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $data = [];
    foreach ($rows as $r) {
      $badge = "<span class='badge bg-" . ($r['estado'] == '1' ? 'success' : 'danger') . "'>" . ($r['estado'] == '1' ? 'Activo' : 'Inactivo') . "</span>";
      $data[] = [
        $r['id'],
        htmlspecialchars($r['ruc']),
        htmlspecialchars($r['razon']),
        htmlspecialchars($r['direccion']),
        htmlspecialchars($r['fecha_inicio']),
        htmlspecialchars($r['fecha_fin']),
        htmlspecialchars($r['paquetes']),
        $badge,
        "<button class='btn btn-warning btn-sm editar' id='{$r['id']}'>Editar</button>",
        "<button class='btn btn-danger btn-sm borrar' id='{$r['id']}'>Borrar</button>"
      ];
    }

    echo json_encode([
      'draw' => $draw,
      'recordsTotal' => $total,
      'recordsFiltered' => $total,
      'data' => $data
    ]);
    break;

  case 'guardar':
    $id = intval($_POST['id'] ?? 0);
    $payload = [
      ':ruc' => $_POST['ruc'] ?? '',
      ':razon' => $_POST['razon'] ?? '',
      ':direccion' => $_POST['direccion'] ?? '',
      ':fecha_inicio' => $_POST['fecha_inicio'] ?? null,
      ':fecha_fin' => $_POST['fecha_fin'] ?? null,
      ':paquetes' => $_POST['paquetes'] ?? '',
      ':estado' => ($_POST['estado'] ?? '1') === '1' ? '1' : '0',
    ];
    if ($id === 0) {
      $stmt = $connect->prepare("INSERT INTO tbl_empresas (ruc, razon, direccion, fecha_inicio, fecha_fin, paquetes, estado) VALUES (:ruc,:razon,:direccion,:fecha_inicio,:fecha_fin,:paquetes,:estado)");
    } else {
      $stmt = $connect->prepare("UPDATE tbl_empresas SET ruc=:ruc, razon=:razon, direccion=:direccion, fecha_inicio=:fecha_inicio, fecha_fin=:fecha_fin, paquetes=:paquetes, estado=:estado WHERE id=:id");
      $payload[':id'] = $id;
    }
    $ok = $stmt->execute($payload);
    echo json_encode(['respuesta' => $ok ? 'Registro guardado' : 'Error']);
    break;

  case 'buscar':
    $id = intval($_POST['id'] ?? 0);
    $stmt = $connect->prepare("SELECT * FROM tbl_empresas WHERE id=:id");
    $stmt->execute([':id' => $id]);
    echo json_encode($stmt->fetch(PDO::FETCH_ASSOC) ?: []);
    break;

  case 'eliminar':
    $id = intval($_POST['id'] ?? 0);
    $stmt = $connect->prepare("SELECT estado FROM tbl_empresas WHERE id=:id");
    $stmt->execute([':id' => $id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
      $new = $row['estado'] == '1' ? '0' : '1';
      $stmt = $connect->prepare("UPDATE tbl_empresas SET estado=:e WHERE id=:id");
      $stmt->execute([':e' => $new, ':id' => $id]);
      echo json_encode(['respuesta' => 'Estado actualizado']);
    } else {
      echo json_encode(['respuesta' => 'No encontrado']);
    }
    break;

  default:
    echo json_encode(['error' => 'Operación no válida']);
    break;
}
?>
