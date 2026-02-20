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

$op = $_GET['op'] ?? '';

switch ($op) {

  case 'listar':
    $draw = $_POST['draw'] ?? 1;
    $start = $_POST['start'] ?? 0;
    $length = $_POST['length'] ?? 10;
    $search = $_POST['search']['value'] ?? '';

    // Cliente seleccionado (si no llega usa empresa de sesión)
    $cliente_id = $_POST['cliente_id'] ?? $idempresa;
    // Base Condition
    $cond = "WHERE p.idempresa = :idempresa";
    if ($search) $cond .= " AND (p.dni LIKE :search OR p.nombres LIKE :search OR p.apellido_paterno LIKE :search)";

    // Count Total
    $stmtTotal = $connect->prepare("SELECT COUNT(*) FROM tbl_personal p $cond");
    $stmtTotal->bindValue(':idempresa', $cliente_id, PDO::PARAM_INT);
    if ($search) $stmtTotal->bindValue(':search', "%$search%", PDO::PARAM_STR);
    $stmtTotal->execute();
    $total = $stmtTotal->fetchColumn();

    // Main Query with SAFE Joins (only Tables we are sure exist)
    $sql = "SELECT 
              p.idpersonal, p.dni, 
              CONCAT(IFNULL(p.apellido_paterno,''),' ',IFNULL(p.apellido_materno,''),' ',p.nombres) AS nombre_completo, 
              p.cargo, 
              b.nombre as banco, 
              a.nombre as afp, 
              c.nombre as centro, 
              -- Fallback to IDs for missing tables
              p.idcategoria,
              p.idcontrato_tipo, 
              p.estado,
              cl.nombre as categoria,
              tc.nombre as contrato
            FROM tbl_personal p
            LEFT JOIN tbl_banco b ON p.idbanco = b.idbanco
            LEFT JOIN tbl_afp a ON p.idafp = a.idafp
            LEFT JOIN tbl_centro_costo c ON p.idcentro = c.id
            LEFT JOIN tbl_categoria_laboral cl ON p.idcategoria = cl.idcategoria
            LEFT JOIN tbl_tipo_contrato tc ON p.idcontrato_tipo = tc.idcontrato_tipo
            $cond
            ORDER BY p.idpersonal DESC LIMIT :start, :length";

    $stmt = $connect->prepare($sql);
    $stmt->bindValue(':idempresa', $cliente_id, PDO::PARAM_INT);
    if ($search) $stmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
    $stmt->bindValue(':start', intval($start), PDO::PARAM_INT);
    $stmt->bindValue(':length', intval($length), PDO::PARAM_INT);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $data = [];
    foreach ($rows as $r) {
      $estado = $r['estado'] ? 'ACTIVO' : 'INACTIVO';
      $badge = "<span class='badge bg-" . ($r['estado'] ? 'success' : 'danger') . "'>$estado</span>";
      $btnEdit = "<button class='btn btn-warning btn-sm editar' id='{$r['idpersonal']}'>Editar</button>";
      $btnDel = "<button class='btn btn-danger btn-sm borrar' id='{$r['idpersonal']}'>Borrar</button>";
      $data[] = [
        $r['idpersonal'],
        htmlspecialchars($r['dni']),
        htmlspecialchars($r['nombre_completo']),
        htmlspecialchars($r['cargo']),
        htmlspecialchars($r['banco'] ?? '-'),
        htmlspecialchars($r['afp'] ?? '-'),
        htmlspecialchars($r['centro'] ?? '-'),
        htmlspecialchars($r['contrato'] ?? '-'),
        $badge,
        $btnEdit,
        $btnDel
      ];
    }

    echo json_encode(["draw" => intval($draw), "recordsTotal" => $total, "recordsFiltered" => $total, "data" => $data]);
    break;

  case 'guardar':
    $id = intval($_POST['idpersonal'] ?? 0);
    // asegurar columnas adicionales si no existen
    try {
      $cols = [
        'direccion VARCHAR(255) NULL',
        'tipo_doc VARCHAR(10) NULL',
        'sexo VARCHAR(10) NULL',
        'estado_civil VARCHAR(20) NULL',
        'fecha_nacimiento DATE NULL',
        'nacionalidad VARCHAR(50) NULL',
        'telefono VARCHAR(30) NULL',
        'movil VARCHAR(30) NULL',
        'email VARCHAR(120) NULL',
        'lib_militar VARCHAR(30) NULL',
        'n_hijos INT NULL',
        'grado_inst VARCHAR(50) NULL',
        'ocupacion VARCHAR(120) NULL',
        'prof_titulo VARCHAR(120) NULL',
        'estado_laboral VARCHAR(30) NULL',
        'tipo_personal VARCHAR(30) NULL',
        'area VARCHAR(120) NULL',
        'departamento VARCHAR(120) NULL',
        'empresa_prestadora VARCHAR(120) NULL',
        'turno VARCHAR(50) NULL',
        'bono_extra DECIMAL(10,2) NULL',
        'seguro_autogenerado VARCHAR(50) NULL',
        'ruc_eps VARCHAR(20) NULL',
        'senati TINYINT(1) NULL',
        'afecto_impuesto TINYINT(1) NULL',
        'cci VARCHAR(30) NULL',
        'cta_soles VARCHAR(30) NULL',
        'cta_dolares VARCHAR(30) NULL',
        'cuenta_cts_usd VARCHAR(50) NULL',
        'fecha_contrato DATE NULL',
        'moneda VARCHAR(3) NULL',
        'tipo_pago VARCHAR(20) NULL',
        'essalud_regimen VARCHAR(100) NULL',
        'afp_fecha_inscripcion DATE NULL',
        'banco_cts VARCHAR(100) NULL',
        'cuenta_cts VARCHAR(50) NULL'
      ];
      foreach ($cols as $def) {
        $col = explode(' ', $def)[0];
        $chk = $connect->query("SHOW COLUMNS FROM tbl_personal LIKE '$col'");
        if ($chk && $chk->rowCount() === 0) {
          $connect->exec("ALTER TABLE tbl_personal ADD COLUMN $def");
        }
      }
    } catch (Throwable $e) {}
    $cliente_id = $_POST['cliente_id'] ?? $idempresa;
    $data = [
      'idempresa' => $cliente_id,
      'dni' => $_POST['dni'] ?? null,
      'tipo_doc' => $_POST['tipo_doc'] ?? null,
      'apellido_paterno' => $_POST['apellido_paterno'] ?? null,
      'apellido_materno' => $_POST['apellido_materno'] ?? null,
      'nombres' => $_POST['nombres'] ?? null,
      'direccion' => $_POST['direccion'] ?? null,
      'sexo' => $_POST['sexo'] ?? null,
      'estado_civil' => $_POST['estado_civil'] ?? null,
      'fecha_nacimiento' => $_POST['fecha_nacimiento'] ?? null,
      'nacionalidad' => $_POST['nacionalidad'] ?? null,
      'telefono' => $_POST['telefono'] ?? null,
      'movil' => $_POST['movil'] ?? null,
      'email' => $_POST['email'] ?? null,
      'lib_militar' => $_POST['lib_militar'] ?? null,
      'n_hijos' => isset($_POST['n_hijos']) ? intval($_POST['n_hijos']) : null,
      'grado_inst' => $_POST['grado_inst'] ?? null,
      'ocupacion' => $_POST['ocupacion'] ?? null,
      'prof_titulo' => $_POST['prof_titulo'] ?? null,
      'estado_laboral' => $_POST['estado_laboral'] ?? null,
      'tipo_personal' => $_POST['tipo_personal'] ?? null,
      'area' => $_POST['area'] ?? null,
      'departamento' => $_POST['departamento'] ?? null,
      'empresa_prestadora' => $_POST['empresa_prestadora'] ?? null,
      'turno' => $_POST['turno'] ?? null,
      'bono_extra' => isset($_POST['bono_extra']) ? floatval($_POST['bono_extra']) : null,
      'cargo' => $_POST['cargo'] ?? null,
      'idbanco' => $_POST['idbanco'] ?: null,
      'cuenta_bancaria' => $_POST['cuenta_bancaria'] ?? null,
      'idafp' => $_POST['idafp'] ?: null,
      'tipo_comision' => $_POST['tipo_comision'] ?? null,
      'cuspp' => $_POST['cuspp'] ?? null,
      'idcentro' => $_POST['idcentro'] ?: null,
      'idcategoria' => $_POST['idcategoria'] ?: null,
      'idcontrato_tipo' => $_POST['idcontrato_tipo'] ?: null,
      'fecha_ingreso' => $_POST['fecha_ingreso'] ?: null,
      'fecha_contrato' => $_POST['fecha_contrato'] ?: null,
      'fecha_cese' => $_POST['fecha_cese'] ?: null,
      'basico' => floatval($_POST['basico'] ?? 0),
      'asignacion_familiar' => floatval($_POST['asignacion_familiar'] ?? 0),
      'moneda' => $_POST['moneda'] ?? null,
      'tipo_pago' => $_POST['tipo_pago'] ?? null,
      'essalud_regimen' => $_POST['essalud_regimen'] ?? null,
      'afp_fecha_inscripcion' => $_POST['afp_fecha_inscripcion'] ?? null,
      'banco_cts' => $_POST['banco_cts'] ?? null,
      'cuenta_cts' => $_POST['cuenta_cts'] ?? null,
      'cuenta_cts_usd' => $_POST['cuenta_cts_usd'] ?? null,
      'seguro_autogenerado' => $_POST['seguro_autogenerado'] ?? null,
      'ruc_eps' => $_POST['ruc_eps'] ?? null,
      'senati' => isset($_POST['senati']) ? 1 : 0,
      'afecto_impuesto' => isset($_POST['afecto_impuesto']) ? 1 : 0,
      'cci' => $_POST['cci'] ?? null,
      'cta_soles' => $_POST['cta_soles'] ?? null,
      'cta_dolares' => $_POST['cta_dolares'] ?? null
    ];

    if ($id === 0) {
      $fields = implode(", ", array_keys(array_filter($data, function ($v) {
        return $v !== null;
      })));
      $placeholders = ":" . implode(", :", array_keys(array_filter($data, function ($v) {
        return $v !== null;
      })));
      $sql = "INSERT INTO tbl_personal (" . $fields . ") VALUES (" . $placeholders . ")";
      $stmt = $connect->prepare($sql);
      $exec = $stmt->execute(array_filter($data, function ($v) {
        return $v !== null;
      }));
      echo json_encode(['respuesta' => $exec ? 'Registro creado correctamente' : 'Error al crear registro']);
    } else {
      $updateParts = [];
      $params = [];
      foreach ($data as $k => $v) {
        if ($k === 'idempresa') continue;
        $updateParts[] = "$k = :$k";
        $params[":$k"] = $v;
      }
      $params[':idpersonal'] = $id;
      $sql = "UPDATE tbl_personal SET " . implode(", ", $updateParts) . " WHERE idpersonal = :idpersonal";
      $stmt = $connect->prepare($sql);
      $exec = $stmt->execute($params);
      echo json_encode(['respuesta' => $exec ? 'Registro actualizado correctamente' : 'Error al actualizar registro']);
    }
    break;

  case 'buscar':
    $id = intval($_POST['id'] ?? 0);
    $stmt = $connect->prepare("SELECT * FROM tbl_personal WHERE idpersonal = :id LIMIT 1");
    $stmt->execute([':id' => $id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode($row ?: []);
    break;

  case 'eliminar':
    $id = intval($_POST['id'] ?? 0);
    $stmt = $connect->prepare("SELECT estado FROM tbl_personal WHERE idpersonal = :id LIMIT 1");
    $stmt->execute([':id' => $id]);
    $fila = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($fila) {
      $estado = ($fila['estado'] == 1) ? 0 : 1;
      $stmt = $connect->prepare("UPDATE tbl_personal SET estado = :estado WHERE idpersonal = :id");
      $stmt->execute([':estado' => $estado, ':id' => $id]);
      echo json_encode(['respuesta' => 'Estado actualizado correctamente']);
    } else {
      echo json_encode(['respuesta' => 'Registro no encontrado']);
    }
    break;

  case 'selects':
    // Devuelve listas para selects
    $cliente_id = $_POST['cliente_id'] ?? $idempresa;
    $out = [];
    $stm = $connect->prepare("SELECT idbanco, nombre FROM tbl_banco WHERE estado = 1 ORDER BY nombre");
    $stm->execute();
    $out['bancos'] = $stm->fetchAll(PDO::FETCH_ASSOC);

    $stm = $connect->prepare("SELECT idafp, nombre FROM tbl_afp WHERE idempresa = :idempresa AND estado = 1 ORDER BY nombre");
    $stm->execute([':idempresa' => $cliente_id]);
    $out['afps'] = $stm->fetchAll(PDO::FETCH_ASSOC);

    // Adjusted columns for JS compatibility
    $stm = $connect->prepare("SELECT id as idcentro, nombre as descripcion FROM tbl_centro_costo WHERE cliente = :idempresa AND estado = 1 ORDER BY nombre");
    $stm->execute([':idempresa' => $cliente_id]);
    $out['centros'] = $stm->fetchAll(PDO::FETCH_ASSOC);

    $stm = $connect->prepare("SELECT idcategoria, nombre FROM tbl_categoria_laboral WHERE idempresa = :idempresa AND estado = 1 ORDER BY nombre");
    $stm->execute([':idempresa' => $cliente_id]);
    $out['categorias'] = $stm->fetchAll(PDO::FETCH_ASSOC);

    $stm = $connect->prepare("SELECT idcontrato_tipo, nombre FROM tbl_tipo_contrato WHERE idempresa = :idempresa AND estado = 1 ORDER BY nombre");
    $stm->execute([':idempresa' => $cliente_id]);
    $out['contratos'] = $stm->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($out);
    break;

  default:
    echo json_encode(['error' => 'Operación no válida']);
    break;
}
