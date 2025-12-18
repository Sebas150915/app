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

    // =====================================
    // 📋 LISTAR PLANILLA
    // =====================================
    case 'listar':
        $anio = $_POST['anio'] ?? date('Y');
        $mes  = $_POST['mes'] ?? date('m');

        $draw = $_POST['draw'] ?? 1;
        $start = $_POST['start'] ?? 0;
        $length = $_POST['length'] ?? 10;

        $sqlTotal = "SELECT COUNT(*) AS total FROM mov_pla_cab WHERE idempresa = :idempresa";
        $stmt = $connect->prepare($sqlTotal);
        $stmt->execute([':idempresa' => $idempresa]);
        $totalRecords = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

        $query = "SELECT p.idpersonal, p.nombres, c.anio, c.mes, c.total_bruto, c.total_descuento, c.total_neto
                  FROM mov_pla_cab c
                  INNER JOIN tbl_personal p ON c.idpersonal = p.idpersonal
                  WHERE c.idempresa = :idempresa AND c.anio = :anio AND c.mes = :mes
                  ORDER BY p.nombres ASC
                  LIMIT :start, :length";
        $stmt = $connect->prepare($query);
        $stmt->bindValue(':idempresa', $idempresa, PDO::PARAM_INT);
        $stmt->bindValue(':anio', $anio, PDO::PARAM_INT);
        $stmt->bindValue(':mes', $mes, PDO::PARAM_INT);
        $stmt->bindValue(':start', intval($start), PDO::PARAM_INT);
        $stmt->bindValue(':length', intval($length), PDO::PARAM_INT);
        $stmt->execute();
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode([
            "draw" => intval($draw),
            "recordsTotal" => intval($totalRecords),
            "recordsFiltered" => intval($totalRecords),
            "data" => $resultado
        ]);
    break;

    // =====================================
    // ⚙️ CALCULAR PLANILLA
    // =====================================
    case 'calcular':
        $anio = $_POST['anio'] ?? date('Y');
        $mes  = $_POST['mes'] ?? date('m');

        // Obtener personal activo
        $stmt = $connect->prepare("SELECT idpersonal, sueldo_basico FROM tbl_personal WHERE idempresa = :empresa AND estado = 1");
        $stmt->execute(['empresa' => $idempresa]);
        $trabajadores = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($trabajadores as $trab) {
            $bruto = $trab['sueldo_basico'];
            $afp = round($bruto * 0.10, 2);
            $neto = $bruto - $afp;

            $stmtIns = $connect->prepare("
                INSERT INTO mov_pla_cab (idempresa, idpersonal, anio, mes, total_bruto, total_descuento, total_neto)
                VALUES (:idempresa, :idpersonal, :anio, :mes, :bruto, :desc, :neto)
                ON DUPLICATE KEY UPDATE total_bruto=:bruto, total_descuento=:desc, total_neto=:neto
            ");
            $stmtIns->execute([
                ':idempresa' => $idempresa,
                ':idpersonal' => $trab['idpersonal'],
                ':anio' => $anio,
                ':mes' => $mes,
                ':bruto' => $bruto,
                ':desc' => $afp,
                ':neto' => $neto
            ]);
        }

        echo json_encode(['respuesta' => 'Planilla calculada correctamente']);
    break;

    default:
        echo json_encode(['error' => 'Operación no válida']);
    break;
}
?>
