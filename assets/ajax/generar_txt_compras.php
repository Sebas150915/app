<?php
require_once("../../config/config.php");
require_once("../../helpers/helpers.php"); 
require_once("../../libraries/conexion.php"); 
session_start();

//var_dump($_POST);

$empresa = $_SESSION['empresa'];
$ruc = $_SESSION['ruc'];
$anio = $_POST['anio'];
$mes = $_POST['mes'];
$periodo = $anio.$mes;

// Debug: verificar variables de sesión
error_log("Empresa: " . $empresa);
error_log("RUC: " . $ruc);
error_log("Año: " . $anio);
error_log("Mes: " . $mes);
error_log("Periodo: " . $periodo);


// Verificar que sea una petición POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'mensaje' => 'Método no permitido']);
    exit;
}

// Obtener parámetros
$anio = isset($_POST['anio']) ? intval($_POST['anio']) : 0;
$mes = isset($_POST['mes']) ? $_POST['mes'] : '';

// Validar parámetros
if ($anio < 2000 || $anio > 2100 || !preg_match('/^(0[1-9]|1[0-2])$/', $mes)) {
    echo json_encode(['status' => 'error', 'mensaje' => 'Parámetros de año o mes inválidos']);
    exit;
}

try {
    // Crear conexión a la base de datos
  
    
    // Consultar datos de compras para el período especificado
    $sql = "SELECT 
                *
            FROM mov_compras 
            WHERE YEAR(fechadocple) = :anio 
            AND MONTH(fechadocple) = :mes 
            ORDER BY fechadocple ASC";
    
    $stmt = $connect->prepare($sql);
    $stmt->bindParam(':anio', $anio, PDO::PARAM_INT);
    $stmt->bindParam(':mes', $mes, PDO::PARAM_STR);
    $stmt->execute();
    
    $compras = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($compras)) {
        echo json_encode(['status' => 'error', 'mensaje' => 'No se encontraron datos de compras para el período seleccionado']);
        exit;
    }
    
    // Generar contenido del archivo TXT
    $contenidoTxt = generarContenidoTxt($compras, $anio, $mes);
    
    // Crear archivo ZIP
    $zip = new ZipArchive();

    $nombrearchivo = 'LE'.$ruc.$periodo.'0008040003111201';
    $nombreZip = $nombrearchivo.".zip";
    $nombreTxt = $nombrearchivo.".txt";
    
    // Debug: log del nombre del archivo
    error_log("Nombre del archivo generado: " . $nombrearchivo);
    
    
    // Crear archivo ZIP temporal
    $tempZip = tempnam(sys_get_temp_dir(), 'zip_');
    
    if ($zip->open($tempZip, ZipArchive::CREATE) !== TRUE) {
        throw new Exception('No se pudo crear el archivo ZIP');
    }
    
    // Agregar archivo TXT al ZIP
    $zip->addFromString($nombreTxt, $contenidoTxt);
    $zip->close();
    
    // Leer contenido del ZIP y convertir a base64
    $zipContent = file_get_contents($tempZip);
    $zipBase64 = base64_encode($zipContent);
    
    // Limpiar archivo temporal
    unlink($tempZip);
    
    // Debug: log de la respuesta JSON
    $respuesta = [
        'status' => 'success',
        'mensaje' => 'Archivo generado exitosamente',
        'zip_base64' => $zipBase64,
        'nombre_archivo' => $nombrearchivo,
        'registros' => count($compras),
        'periodo' => "{$anio}-{$mes}"
    ];
    
    error_log("Respuesta JSON enviada: " . json_encode($respuesta));
    
    // Devolver respuesta exitosa
    echo json_encode($respuesta);
    
} catch (Exception $e) {
    error_log("Error en generar_txt_compras.php: " . $e->getMessage());
    echo json_encode([
        'status' => 'error', 
        'mensaje' => 'Error interno del servidor: ' . $e->getMessage()
    ]);
}

/**
 * Genera el contenido del archivo TXT con formato específico
 */
function generarContenidoTxt($compras, $anio, $mes) {
    $contenido = "";
    

    $periodo = $anio.$mes;
    // Datos de las compras
    foreach ($compras as $compra) {
        
        // Limpiar caracteres especiales si es necesario
        $ruc = preg_replace('/[^a-zA-Z0-9]/', '', $_SESSION['ruc']);
        $empresa = preg_replace('/[^a-zA-Z0-9\s]/', '', $_SESSION['empresa']);
        $periodo = preg_replace('/[^a-zA-Z0-9]/', '', $periodo);
        $car='';
        $fecha = date('d/m/Y', strtotime($compra['fechadocple']));
        $fechaven = date('d/m/Y', strtotime($compra['fechadocple']));
        $tipoDoc = $compra['tipdoc'] ?? '';
        $serieDoc = $compra['seriedoc'] ?? '';
        $anioDoc = '';
        $numdoc = $compra['numdoc'] ?? '';
        $numdocfin = '';
        $tipdocident = '6';
        $rucemisor = $compra['rucemisor'] ?? '';
        $razonemisor = $compra['razonemisor'] ?? '';
        $basedocple = $compra['basedocple'] ?? '0.00';
        $igvdocple = $compra['igvdocple'] ?? '0.00';
        $basedocple2 = $compra['basedocple'] ?? '0.00';
        $igvdocple2 = $compra['igvdocple'] ?? '0.00';
        $basedocple3 = $compra['basedocple'] ?? '0.00';
        $igvdocple3 = $compra['igvdocple'] ?? '0.00';
        $nogravado = '0.00';
        $isc = '0.00';
        $icbper = '0.00';
        $othdocple = $compra['othdocple'] ?? '0.00';
        $totaldocple = $compra['totaldocple'] ?? '0.00';
        $moneda = $compra['moneda'] ?? '';
        $tipocambio = $compra['tipocambio'] ?? '3.00';
        $fechamodifica =  '';
        $tipomodifica =  '';
        $seriemodifica = '';
        $coddam =  '';
        $numeromodifica =  '';
        $clasibbss = '';
        $idproy =  '';
        $porpart = '';
        $imb = '';
        
        



                $contenido .= "{$ruc}|{$empresa}|{$periodo}|{$car}|{$fecha}|{$fechaven}|{$tipoDoc}|{$serieDoc}|{$anioDoc}|{$numdoc}|{$numdocfin}|{$tipdocident}|{$rucemisor}|{$razonemisor}|{$basedocple}|{$igvdocple}|{$basedocple2}|{$igvdocple2}|{$basedocple3}|{$igvdocple3}|{$nogravado}|{$isc}|{$icbper}|{$othdocple}|{$totaldocple}|{$moneda}|{$tipocambio}|{$fechamodifica}|{$tipomodifica}|{$seriemodifica}|{$coddam}|{$numeromodifica}|{$clasibbss}|{$idproy}|{$porpart}|{$imb}\n";
    }
    

    
    return $contenido;
}
?>
