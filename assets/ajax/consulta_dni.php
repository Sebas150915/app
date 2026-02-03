<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json; charset=utf-8');

$dni = isset($_GET['dni']) ? trim($_GET['dni']) : '';
if (empty($dni) || !preg_match('/^\d{8}$/', $dni)) {
  echo json_encode(['error' => 'DNI inválido']);
  exit;
}

$url = "http://smartbase.club/webservices/dni.php?dni=" . urlencode($dni);
$curl = curl_init();
curl_setopt_array($curl, [
  CURLOPT_URL => $url,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 5,
  CURLOPT_TIMEOUT => 10,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => [
    'Accept: application/json'
  ],
]);
$response = curl_exec($curl);
$err = curl_error($curl);
$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
curl_close($curl);

if ($err || $status !== 200 || !$response) {
  echo json_encode(['error' => 'No se pudo consultar DNI']);
  exit;
}

// Pasar respuesta sin alterar (esperado: {"dni":"...","apellidoPaterno":"...","apellidoMaterno":"...","nombres":"..."})
echo $response;
