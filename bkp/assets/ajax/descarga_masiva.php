<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');

require_once("../../config/config.php");
require_once("../../helpers/helpers.php");
require_once("../../libraries/conexion.php");

session_start();

$id_cliente = $_POST['cliente_id'] ?? 0;
$anio = $_POST['anio'] ?? date('Y');
$mes  = $_POST['mes']  ?? date('m');
$periodo = $anio . $mes;

if (empty($id_cliente)) {
    echo json_encode(['status' => 'error', 'message' => 'Falta el ID del cliente']);
    exit;
}

// Get client credentials
$stmt_cli = $connect->prepare("SELECT * FROM tbl_cliente_empresa WHERE id = ? LIMIT 1");
$stmt_cli->execute([$id_cliente]);
$cliente = $stmt_cli->fetch(PDO::FETCH_ASSOC);

if (!$cliente) {
    echo json_encode(['status' => 'error', 'message' => 'Cliente no encontrado']);
    exit;
}

$ruc_cliente = $cliente['ruc'];
$ruccontribuyente = $cliente['ruc'];
$usercontribuyente = $cliente['usuario_sol'];
$clavecontribuyente = $cliente['clave_sol'];

// Ensure directory exists
$base_dir = __DIR__ . "/../../SUNAT/" . $ruc_cliente;
if (!file_exists($base_dir)) {
    mkdir($base_dir, 0777, true);
}

// Find pending downloads
// Skip banks (tipdoc = 13)
// Limit to 5 per batch to avoid timeouts
$limit = 5;
$files_downloaded = 0;
$errors = 0;

$sql = "SELECT movkey, rucemisor, tipdoc, seriedoc, numdoc, 
               xml_descargado, cdr_descargado, pdf_descargado 
        FROM mov_compras 
        WHERE periodouso = ? 
          AND idcliente = ? 
          AND tipdoc != '13' 
          AND (xml_descargado = 0 OR cdr_descargado = 0 OR pdf_descargado = 0) 
        ORDER BY movkey ASC 
        LIMIT $limit";

$stmt = $connect->prepare($sql);
$stmt->execute([$periodo, $id_cliente]);
$pendientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($pendientes) === 0) {
    echo json_encode(['status' => 'finished', 'message' => 'No hay más archivos pendientes']);
    exit;
}

foreach ($pendientes as $row) {
    $types = [
        '02' => ['col' => 'xml_descargado', 'ext' => '.xml', 'folder' => 'xml'], // XML
        '03' => ['col' => 'cdr_descargado', 'ext' => '.zip', 'folder' => 'cdr'], // CDR
        '01' => ['col' => 'pdf_descargado', 'ext' => '.pdf', 'folder' => 'pdf']  // PDF
    ];

    foreach ($types as $tipo_cod => $info) {
        if ($row[$info['col']] == 0) {

            // Call external API
            $response = downloadFromProxy($tipo_cod, $row, $ruccontribuyente, $usercontribuyente, $clavecontribuyente);

            if ($response && isset($response['archivo_b64'])) {
                $content = base64_decode($response['archivo_b64']);
                $filename = $response['nombre_archivo'] ?? ($row['rucemisor'] . '-' . $row['tipdoc'] . '-' . $row['seriedoc'] . '-' . $row['numdoc'] . $info['ext']);

                // Save file
                if (file_put_contents($base_dir . '/' . $filename, $content)) {
                    // Update DB
                    $upd = $connect->prepare("UPDATE mov_compras SET {$info['col']} = 1 WHERE movkey = ?");
                    $upd->execute([$row['movkey']]);
                    $files_downloaded++;
                } else {
                    $errors++;
                    // Write Error (2) - Failed to save to disk
                    $upd = $connect->prepare("UPDATE mov_compras SET {$info['col']} = 2 WHERE movkey = ?");
                    $upd->execute([$row['movkey']]);
                }
            } else {
                $errors++;
                // API Error (2) - Failed to download from SUNAT/Proxy
                // Mark as 2 so we don't retry in the next batch immediately
                $upd = $connect->prepare("UPDATE mov_compras SET {$info['col']} = 2 WHERE movkey = ?");
                $upd->execute([$row['movkey']]);
            }
        }
    }
}

echo json_encode([
    'status' => 'ongoing',
    'processed' => count($pendientes),
    'downloaded' => $files_downloaded,
    'errors' => $errors
]);


function downloadFromProxy($tipo, $row, $ruccontribuyente, $usercontribuyente, $clavecontribuyente)
{
    $url = "https://www.smartbase.club/sunat/xml_cdr.php";

    $payload = json_encode([
        "ruc"      => $row['rucemisor'],
        "tipodoc"   => $row['tipdoc'],
        "seriedoc"  => $row['seriedoc'],
        "numerodoc" => $row['numdoc'],
        "tipo"      => $tipo,
        "tl"        => "2",
        "ruccontribuyente" => $ruccontribuyente,
        "usercontribuyente" => $usercontribuyente,
        "clavecontribuyente" => $clavecontribuyente
    ]);

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30, // Increased timeout 
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $payload,
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
        ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);

    if ($err) {
        return null;
    } else {
        return json_decode($response, true);
    }
}
