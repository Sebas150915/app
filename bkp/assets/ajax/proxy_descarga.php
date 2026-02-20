<?php
ob_start();
$data = json_decode(file_get_contents("php://input"), true);

$tipo      = $data['tipo'];
$ruc       = $data['ruc'];
$tipodoc   = $data['tipodoc'];
$seriedoc  = $data['seriedoc'];
$numerodoc = $data['numerodoc'];
$cliente_id = $data['cliente_id'];

$ruc_cliente = $data['ruc']; // El repositorio envia "ruc"

// Conexión a Base de Datos
require_once '../../config/config.php';
$conn = new mysqli(BD_HOST, BD_USER, BD_PASSWORD, BD_NAME);
if ($conn->connect_error) {
    echo json_encode(['error' => 'Error de conexión a BD']);
    exit;
}
$conn->set_charset("utf8");

// Buscar credenciales SOL
$sql = "SELECT * FROM tbl_cliente_empresa WHERE id = ? LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $cliente_id);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 0) {
    echo json_encode(['error' => 'Cliente no encontrado en BD']);
    exit;
}

$row = $res->fetch_assoc();
$stmt->close();
$conn->close();

$ruccontribuyente = $row['ruc'];
$usercontribuyente = $row['usuario_sol'];
$clavecontribuyente = $row['clave_sol'];

$curl = curl_init();
$url = "https://www.smartbase.club/sunat/xml_cdr.php";
curl_setopt_array($curl, array(
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => '{
"ruc"      : "' . $ruc . '",
"tipodoc"   : "' . $tipodoc . '",
"seriedoc"  : "' . $seriedoc . '",
"numerodoc" : "' . $numerodoc . '",
"tipo"      : "' . $tipo . '",
"tl"        : "2",
"ruccontribuyente" : "' . $ruccontribuyente  . '",
"usercontribuyente" : "' . $usercontribuyente . '",
"clavecontribuyente" : "' . $clavecontribuyente . '"}',
    CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json',
        'Cookie: PHPSESSID=81v855vkdt4k33k8mt19vp8gbr; SITE_TOTAL_ID=aVw--oCvyxvlKrMwwcJw4gAAAYw'
    ),
));

$response = curl_exec($curl);

curl_close($curl);
echo $response;
