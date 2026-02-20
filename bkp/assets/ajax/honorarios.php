<?php
require_once("../../config/config.php");
require_once("../../helpers/helpers.php");
require_once("../../libraries/conexion.php");
session_start();
$anio = $_POST['anio'] ?? date('Y');
$mes  = $_POST['mes']  ?? date('m');
$periodo = $anio.$mes;
$cliente_id = $_POST['cliente_id'];/*empresa del contador*/
$empresa = $_SESSION['id_empresa'];/*contador*/

$query_data = "SELECT * FROM tbl_cliente_empresa WHERE id=$cliente_id";
//echo $query_data.'|';
$resultado = $connect->prepare($query_data);
$resultado->execute();
$row_empresa = $resultado->fetch(PDO::FETCH_ASSOC);

$idgre      = $row_empresa['idgre'];
$secretgre  = $row_empresa['secretgre'];
$usersunat  = $row_empresa['usuario_sol'];
$passsunat  = $row_empresa['clave_sol'];
$ruc        = $row_empresa['ruc'];

$idsunat     = $idgre  ;
$clavesunat  = $secretgre;
$username    = $ruc.$usersunat;
$passol      = $passsunat;    

/*GENERACION DE TOKEN PAR TRAER INFORMACION DE SIRE*/

$clave = str_replace(" ", "+", $clavesunat);
$clave = str_replace("+", "%2B", $clave);
$clave = str_replace("==", "%3D%3D", $clave);
//$clave = str_replace("/", "%2F", $clave);
//$clave=urldecode($clave);
$mensaje['clave']=$clave;
$mensaje['id']=$idsunat;


$curl = curl_init();

$ruta = 'https://www.smartbase.club/sunat/consulta_rh.php?periodo='.$anio.'_'.$mes.'&token=SMARTBASE_API_2025';

curl_setopt_array($curl, array(
  CURLOPT_URL => $ruta,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>'{
    "ruc": "'.$ruc.'",
    "usuario_sol": "'.$usersunat.'",
    "clave_sol": "'.$passol.'"
}
',
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json',
    'Cookie: SITE_TOTAL_ID=aPHOv7y3wM03phI24kQkKQAAAJU'
  ),
));

$response = curl_exec($curl);
curl_close($curl);

// Decodificar la respuesta (elimina barras invertidas y convierte a array)
$response = json_decode($response, true);

// Si aún es un string JSON dentro del JSON principal, decodifícalo otra vez
if (is_string($response)) {
    $response = json_decode($response, true);
}

//var_dump($response['registros']);
//exit();

if (isset($response['registros'])) {
    foreach ($response['registros'] as $data) {
        
         $tipoDoc = '02';
         $moneda = 'USD';
         if($data['moneda'] === 'SOLES')
         {
             $moneda = 'PEN';
         }

        // Ejemplo: generar una clave única
        $movkey = $data['clientNroDoc'] . '-' . $tipoDoc . '-' . $data['serieNroDoc'];

        // Verificar si ya existe
        $query = "SELECT COUNT(*) FROM mov_compras WHERE movkey = ?";
        $stmt = $connect->prepare($query);
        $stmt->execute([$movkey]);
        $existe = $stmt->fetchColumn();

        // Datos comunes
        $fecEmision = date('Y-m-d', strtotime(str_replace('/', '-', $data['fecEmision'])));
        $rucemisor  = $data['clientNroDoc'];
        $razonemisor = $data['clientRzSocial'];
        $tipdoc =  $tipoDoc;
        $seriedoc = explode('-', $data['serieNroDoc'])[0];
        $numdoc = explode('-', $data['serieNroDoc'])[1];
        $moneda = $moneda;
        $neto4  = $data['rentaBruta'];
        $rta4   = $data['impuestoRenta'];
        $total  = $data['montoNetoPago'];

        if ($existe == 0) {
            // INSERTAR
            $sql = "INSERT INTO mov_compras (movkey, periodouso, rucemisor, razonemisor, tipdoc, seriedoc, numdoc, fechadocsire, totaldocsire, moneda, idcliente,basedocsire,igvdocsire) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?,?)";
            $stmt = $connect->prepare($sql);
            $stmt->execute([
                $movkey,
                $periodo,
                $rucemisor,
                $razonemisor,
                $tipdoc,
                $seriedoc,
                $numdoc,
                $fecEmision,
                $total,
                $moneda,
                $cliente_id,
                $neto4,
                $rta4
            ]);
        } else {
            // ACTUALIZAR
            $sql = "UPDATE mov_compras 
                    SET fechadocsire=?, totaldocsire=?, moneda=? 
                    WHERE movkey=?";
            $stmt = $connect->prepare($sql);
            $stmt->execute([$fecEmision, $total, $moneda, $movkey]);
        }
    }

    echo json_encode(['status' => 'ok', 'msg' => 'Registros guardados correctamente']);
} else {
    echo json_encode(['status' => 'error', 'msg' => 'No se encontraron registros en la respuesta']);
}

exit;



?>
