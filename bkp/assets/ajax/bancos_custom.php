<?php
header('Content-Type: application/json');
require_once('../../config/config.php');
require_once('../../libraries/conexion.php');
session_start();
$idempresa = $_SESSION['id_empresa'] ?? null; if(!$idempresa){ echo json_encode(['error'=>'No empresa']); exit; }
$op = $_GET['op'] ?? '';
switch($op){
  case 'clientes':
    $stmt = $connect->prepare('SELECT idempresa as id, ruc, razon_social as razon FROM tbl_cliente_empresa WHERE idempresa = :idempresa'); $stmt->execute([':idempresa'=>$idempresa]); echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC)); break;
  case 'listar':
    $draw=$_POST['draw']??1;$start=$_POST['start']??0;$length=$_POST['length']??10;$search=$_POST['search']['value']??'';
    $base='FROM tbl_banco WHERE idempresa=:idempresa'; if($search) $base.=' AND (nombre LIKE :s OR codigo_banco LIKE :s)';
    $stmt=$connect->prepare('SELECT COUNT(*) '.$base); $stmt->bindValue(':idempresa',$idempresa); if($search) $stmt->bindValue(':s','%'.$search.'%'); $stmt->execute(); $total=$stmt->fetchColumn();
    $stmt=$connect->prepare('SELECT * '.$base.' ORDER BY idbanco DESC LIMIT :start,:length'); $stmt->bindValue(':idempresa',$idempresa); if($search) $stmt->bindValue(':s','%'.$search.'%'); $stmt->bindValue(':start',intval($start),PDO::PARAM_INT); $stmt->bindValue(':length',intval($length),PDO::PARAM_INT); $stmt->execute(); $rows=$stmt->fetchAll(PDO::FETCH_ASSOC);
    $data=[]; foreach($rows as $r){ $estado=$r['estado']? 'ACTIVO':'INACTIVO'; $badge="<span class='badge bg-".($r['estado']?'success':'danger')."'>$estado</span>"; $data[] = [$r['idbanco'],htmlspecialchars($r['nombre']),htmlspecialchars($r['codigo_banco']),$badge,"<button class='btn btn-warning btn-sm editar' id='{$r['idbanco']}'>Editar</button>","<button class='btn btn-danger btn-sm borrar' id='{$r['idbanco']}'>Borrar</button>"]; }
    echo json_encode(['draw'=>intval($draw),'recordsTotal'=>$total,'recordsFiltered'=>$total,'data'=>$data]); break;
  case 'guardar':
    $id=intval($_POST['idbanco']??0); $nombre=$_POST['nombre']??''; $codigo=$_POST['codigo']??'';
    if($id==0){ $stmt=$connect->prepare('INSERT INTO tbl_banco (idempresa,nombre,codigo_banco) VALUES(:e,:n,:c)'); $ok=$stmt->execute([':e'=>$idempresa,':n'=>$nombre,':c'=>$codigo]); echo json_encode(['respuesta'=>$ok?'Creado':'Error']); }
    else{ $stmt=$connect->prepare('UPDATE tbl_banco SET nombre=:n,codigo_banco=:c WHERE idbanco=:id'); $ok=$stmt->execute([':n'=>$nombre,':c'=>$codigo,':id'=>$id]); echo json_encode(['respuesta'=>$ok?'Actualizado':'Error']); } break;
  case 'buscar': $id=intval($_POST['id']??0); $stmt=$connect->prepare('SELECT * FROM tbl_banco WHERE idbanco=:id'); $stmt->execute([':id'=>$id]); echo json_encode($stmt->fetch(PDO::FETCH_ASSOC)?:[]); break;
  case 'eliminar': $id=intval($_POST['id']??0); $stmt=$connect->prepare('SELECT estado FROM tbl_banco WHERE idbanco=:id'); $stmt->execute([':id'=>$id]); $f=$stmt->fetch(PDO::FETCH_ASSOC); if($f){ $ne=$f['estado']==1?0:1; $stmt=$connect->prepare('UPDATE tbl_banco SET estado=:e WHERE idbanco=:id'); $stmt->execute([':e'=>$ne,':id'=>$id]); echo json_encode(['respuesta'=>'Estado actualizado']); } else echo json_encode(['respuesta'=>'No encontrado']); break; default: echo json_encode(['error'=>'op no valido']); break; }
?>