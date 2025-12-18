<?php
$data = json_decode(file_get_contents("php://input"), true);

$tipo      = $data['tipo'];
$ruc       = $data['ruc'];
$tipodoc   = $data['tipodoc'];
$seriedoc  = $data['seriedoc'];
$numerodoc = $data['numerodoc'];

$payload = [
    "tipo" => $tipo,
    "ruc" => $ruc,
    "tipodoc" => $tipodoc,
    "seriedoc" => $seriedoc,
    "numerodoc" => $numerodoc,

    // Clave SOL - segura (NO del cliente)
    "ruccontribuyente" => "20493223641",
    "usercontribuyente" => "TARNORTO",
    "clavecontribuyente" => "Multicines1$"
];




$ch = curl_init("https://www.smartbase.club/sunat/xml_cdr.php");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

$response = curl_exec($ch);
$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
curl_close($ch);

if ($httpcode !== 200 || empty($response)) {
    header("Content-Type: application/json");
    echo json_encode(["error" => "SUNAT no devolvió archivo"]);
    exit;
}

// Detectar extensión real según Content-Type de xml_cdr.php
$ext = $contentType === "application/pdf" ? "pdf" :
       ($contentType === "application/xml" ? "xml" : "zip");

header("Content-Type: $contentType");
header("Content-Disposition: attachment; filename=\"{$ruc}-{$tipodoc}-{$seriedoc}-{$numerodoc}.{$ext}\"");

echo $response;
