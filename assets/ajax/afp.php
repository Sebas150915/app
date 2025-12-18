<?php
header('Content-Type: application/json');
require_once('../../config/config.php');
require_once('../../libraries/conexion.php');
session_start();
$idempresa = $_SESSION['id_empresa'] ?? null;
if (!$idempresa) { echo json_encode(['error'=>'No empresa']); exit; }
$op = $_GET['op'] ?? '';
switch($op){
  case 'listar':
    $draw = $_POST['draw'] ?? 1; $start = $_POST['start'] ?? 0; $length = $_POST['length'] ?? 10; $search = $_POST['search']['value'] ?? '';
    $base = "FROM tbl_afp WHERE idempresa=:idempresa";
    if ($search) $base .= " AND nombre LIKE :search";
    $stmt = $connect->prepare("SELECT COUNT(*) " . $base); $stmt->bindValue(':idempresa',$idempresa); if($search) $stmt->bindValue(':search','%'.$search.'%'); $stmt->execute(); $total = $stmt->fetchColumn();
    $stmt = $connect->prepare("SELECT * " . $base . " ORDER BY idafp DESC LIMIT :start, :length"); $stmt->bindValue(':idempresa',$idempresa); if($search) $stmt->bindValue(':search','%'.$search.'%'); $stmt->bindValue(':start',intval($start),PDO::PARAM_INT); $stmt->bindValue(':length',intval($length),PDO::PARAM_INT); $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC); $data=[];
    foreach($rows as $r){ $estado = $r['estado']? 'ACTIVO':'INACTIVO'; $badge = "<span class='badge bg-".($r['estado']?'success':'danger')."'>$estado</span>"; $data[] = [$r['idafp'],htmlspecialchars($r['nombre']),htmlspecialchars($r['descripcion']),$badge,"<button class='btn btn-warning btn-sm editar' id='{$r['idafp']}'>Editar</button>","<button class='btn btn-danger btn-sm borrar' id='{$r['idafp']}'>Borrar</button>"]; }
    echo json_encode(['draw'=>intval($draw),'recordsTotal'=>$total,'recordsFiltered'=>$total,'data'=>$data]);
    break;
  case 'guardar':
    $id = intval($_POST['idafp'] ?? 0); $nombre = $_POST['nombre'] ?? ''; $desc = $_POST['descripcion'] ?? '';
    if ($id==0){ $stmt=$connect->prepare('INSERT INTO tbl_afp (idempresa,nombre,descripcion) VALUES (:idempresa,:nombre,:desc)'); $ok=$stmt->execute([':idempresa'=>$idempresa,':nombre'=>$nombre,':desc'=>$desc]); echo json_encode(['respuesta'=>$ok?'Registro creado':'Error']); }
    else { $stmt=$connect->prepare('UPDATE tbl_afp SET nombre=:nombre, descripcion=:desc WHERE idafp=:id'); $ok=$stmt->execute([':nombre'=>$nombre,':desc'=>$desc,':id'=>$id]); echo json_encode(['respuesta'=>$ok?'Registro actualizado':'Error']); }
    break;
  case 'buscar':
    $id = intval($_POST['id'] ?? 0); $stmt=$connect->prepare('SELECT * FROM tbl_afp WHERE idafp=:id'); $stmt->execute([':id'=>$id]); echo json_encode($stmt->fetch(PDO::FETCH_ASSOC) ?: []);
    break;
  case 'eliminar':
    $id = intval($_POST['id'] ?? 0); $stmt=$connect->prepare('SELECT estado FROM tbl_afp WHERE idafp=:id'); $stmt->execute([':id'=>$id]); $f=$stmt->fetch(PDO::FETCH_ASSOC); if($f){ $ne = $f['estado']==1?0:1; $stmt=$connect->prepare('UPDATE tbl_afp SET estado=:e WHERE idafp=:id'); $stmt->execute([':e'=>$ne,':id'=>$id]); echo json_encode(['respuesta'=>'Estado actualizado']); } else echo json_encode(['respuesta'=>'No encontrado']); break;
  default: echo json_encode(['error'=>'Operacion no valida']); break;
}
?>