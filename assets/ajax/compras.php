<?php
require_once("../../config/config.php");
require_once("../../helpers/helpers.php");
require_once("../../libraries/conexion.php");
session_start();
$anio = $_POST['anio'] ?? date('Y');
$mes  = $_POST['mes']  ?? date('m');
$periodo = $anio.$mes;
$cliente_id = $_POST['cliente_id'];
$empresa = $_SESSION['id_empresa'];


/*cambiar por un update para que ponga en 0 los datos de sire
$query=$connect->prepare("DELETE FROM tbl_venta_sire  WHERE movper = ? and empid=?");
$resultado = $query->execute([$periodo,$empresa]);
*/

$query_data = "SELECT * FROM tbl_cliente_empresa WHERE id=$cliente_id";
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



$passol = str_replace("*", "%2A", $passol);
//$clave = str_replace("/", "%2F", $clave);
//$clave=urldecode($clave);
$mensaje['clave']=$clave;
$mensaje['id']=$idsunat;

//SOPE SUNAT
$scope='https%3A%2F%2Fapi-cpe.sunat.gob.pe';
//SOPE SIRE
$scope='https%3A%2F%2Fapi-sire.sunat.gob.pe';

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://api-seguridad.sunat.gob.pe/v1/clientessol/'.$idsunat.'/oauth2/token/',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => 'grant_type=password&scope='.$scope.'&client_id='.$idsunat.'&client_secret='.$clave.'&username='.$username.'&password='.$passol,
CURLOPT_HTTPHEADER => array(
'Content-Type: application/x-www-form-urlencoded',
'Cookie: TS019e7fc2=019edc9eb82dcd8fec0a3bd848e49fb99eec6d2c3bf4b04081df2d440b003ae1fb1930ddaa2a6bc63ebbdcca3f4f2ff9c2d23a32af'
),
));

$response = curl_exec($curl);
//var_dump($response);
curl_close($curl);
$response=json_decode($response);
$token_access=$response->access_token;

$codOrigenEnvio='1';
$codLibro='140000';
$codProceso='43';
$codTipoArchivo='0';
$fecha_inicio='01/10/2023';
$fecha_fin='31/10/2023';
$pagina='1';
$cantidadpagina='100';
$codTipoOpe='1';
$periodo= $periodo;


$urlsire2='https://api-sire.sunat.gob.pe/v1/contribuyente/migeigv/libros/rce/propuesta/web/propuesta/'.$periodo.'/busqueda?codTipoOpe='.$codTipoOpe.'&page='.$pagina.'&perPage='.$cantidadpagina;

$curl = curl_init();
    curl_setopt_array($curl, array(
CURLOPT_URL => $urlsire2,
CURLOPT_RETURNTRANSFER => true,
CURLOPT_ENCODING => '',
CURLOPT_MAXREDIRS => 10,
CURLOPT_TIMEOUT => 0,
CURLOPT_FOLLOWLOCATION => true,
CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
CURLOPT_HTTPHEADER => array(
        'Authorization: Bearer '. $token_access,
        'Content-Type: application/json',
        'Accept: application/json'
      ),
    ));
        
$response2 = curl_exec($curl);
//echo    $token_access;
$response2=json_decode($response2,true);


$per_pag =  $response2['paginacion']['perPage'];
$tot_reg =  $response2['paginacion']['totalRegistros'];

$tot_pag = ceil($tot_reg/$per_pag);
//echo $tot_pag;

for($i=1;$i<=$tot_pag;$i++)
{



$idsunat     = $idgre  ;
$clavesunat  = $secretgre;
$username    = $ruc.$usersunat;
$passol      = $passsunat;  

$clave = str_replace(" ", "+", $clavesunat);
$clave = str_replace("+", "%2B", $clave);
$clave = str_replace("==", "%3D%3D", $clave);
//$clave = str_replace("/", "%2F", $clave);
//$clave=urldecode($clave);
$mensaje['clave']=$clave;
$mensaje['id']=$idsunat;

//SOPE SUNAT
$scope='https%3A%2F%2Fapi-cpe.sunat.gob.pe';
//SOPE SIRE
$scope='https%3A%2F%2Fapi-sire.sunat.gob.pe';

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://api-seguridad.sunat.gob.pe/v1/clientessol/'.$idsunat.'/oauth2/token/',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => 'grant_type=password&scope='.$scope.'&client_id='.$idsunat.'&client_secret='.$clave.'&username='.$username.'&password='.$passol,
CURLOPT_HTTPHEADER => array(
'Content-Type: application/x-www-form-urlencoded',
'Cookie: TS019e7fc2=019edc9eb82dcd8fec0a3bd848e49fb99eec6d2c3bf4b04081df2d440b003ae1fb1930ddaa2a6bc63ebbdcca3f4f2ff9c2d23a32af'
),
));

$response = curl_exec($curl);
//var_dump($response);
curl_close($curl);
$response=json_decode($response);
$token_access=$response->access_token;

$codOrigenEnvio='1';
$codLibro='140000';
$codProceso='43';
$codTipoArchivo='0';
$fecha_inicio='01/10/2023';
$fecha_fin='31/10/2023';
$pagina=$i;
$cantidadpagina='100';
$codTipoOpe='1';
$periodo=$periodo;

$urlsire2='https://api-sire.sunat.gob.pe/v1/contribuyente/migeigv/libros/rce/propuesta/web/propuesta/'.$periodo.'/busqueda?codTipoOpe='.$codTipoOpe.'&page='.$pagina.'&perPage='.$cantidadpagina;

$curl = curl_init();
    curl_setopt_array($curl, array(
CURLOPT_URL => $urlsire2,
CURLOPT_RETURNTRANSFER => true,
CURLOPT_ENCODING => '',
CURLOPT_MAXREDIRS => 10,
CURLOPT_TIMEOUT => 0,
CURLOPT_FOLLOWLOCATION => true,
CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
CURLOPT_HTTPHEADER => array(
        'Authorization: Bearer '. $token_access,
        'Content-Type: application/json',
        'Accept: application/json'
      ),
    ));
        
$response2 = curl_exec($curl);
//echo    $token_access;
$response2=json_decode($response2,true);
 
/*
 ["montos"]=> array(14) { 
 ["mtoBIGravadaDG"]=> float(0) 
 ["mtoIgvIpmDG"]=> float(0) 
 ["mtoBIGravadaDGNG"]=> float(0) 
 ["mtoIgvIpmDGNG"]=> float(0) 
 ["mtoBIGravadaDNG"]=> float(0) 
 ["mtoIgvIpmDNG"]=> float(0) 
 ["mtoValorAdqNG"]=> float(120) 
 ["mtoIcbp"]=> float(0) 
 ["mtoOtrosTrib"]=> float(0) 
 ["mtoTotalCp"]=> float(120) 
 ["mtoISC"]=> float(0) 
 ["mtoIMB"]=> float(0) 
 ["mtoBIGravadaDGOriginal"]=> NULL 
 ["mtoIgvIpmDGOriginal"]=> NULL }
*/ 
 
//var_dump($response2['registros'][0]['montos']);
/*curl_close($curl);
header('Content-type: application/json');
echo json_encode($response2);*/


    foreach ($response2['registros'] as $data) 
    {

        $tipocambiosire = $data["tipoCambio"]['mtoCambioMonedaExtranjera'];
        $movkey = $data['numDocIdentidadProveedor'].'-'.$data['codTipoCDP'].'-'.$data['numSerieCDP'].'-'.$data['numCDP'];

         /*buscar documento*/
                $query_bpro = "SELECT * FROM mov_compras WHERE movkey = '$movkey' ";
                $resultado_bpro = $connect->prepare($query_bpro);
                $resultado_bpro->execute();
                $num_reg_bpro=$resultado_bpro->rowCount();
               //echo $query_bpro.'->'.$num_reg_bpro;
                /*fin buscar producto  mtoValorAdqNG*/
                /*producto nuevo*/

                $mtoBIGravadaDG = $data['montos']['mtoBIGravadaDG'];
                $mtoIgvIpmDG    = $data['montos']['mtoIgvIpmDG'];
                $mtoOtrosTrib   = $data['montos']['mtoOtrosTrib'];
                $mtoValorAdqNG  = $data['montos']['mtoValorAdqNG'];
                $mtoTotalCp     = $data['montos']['mtoTotalCp'];

                if($num_reg_bpro == 0)
                {
                    /*importes a insertar*/                    
                    
                    
                    /*fin de importes*/
                    
                    if($data['codMoneda']=='USD')
                    {
                        $mtoBIGravadaDG = round($mtoBIGravadaDG/$tipocambiosire,2);
                        $mtoIgvIpmDG    = round($mtoIgvIpmDG/$tipocambiosire,2);
                        $mtoOtrosTrib   = round($mtoOtrosTrib/$tipocambiosire,2);
                        $mtoValorAdqNG  = round($mtoValorAdqNG/$tipocambiosire,2);
                        $mtoTotalCp     = round($mtoTotalCp/$tipocambiosire,2);
                        
                    }
                    
                    
                    
                    
                    $stmt = $connect->prepare("INSERT INTO mov_compras 
                    (movkey, periodouso, rucemisor, razonemisor, tipdoc, seriedoc, numdoc, fechadocsire, basedocsire, igvdocsire, othdocsire, totaldocsire,moneda,idcliente,nogravado,tcambiosire) 
                    VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
            $stmt->execute([ $movkey,
                             $periodo,
                             $data['numDocIdentidadProveedor'],
                             $data['nomRazonSocialProveedor'],
                             $data['codTipoCDP'],
                             $data['numSerieCDP'],
                             $data['numCDP'],
                             $data['fecEmision'],
                             $mtoBIGravadaDG,
                             $mtoIgvIpmDG,
                             $mtoOtrosTrib,
                             $mtoTotalCp,
                             $data['codMoneda'],
                             $cliente_id,
                             $mtoValorAdqNG,
                             $tipocambiosire]);
                }

               else
{
    $stmt = $connect->prepare("UPDATE mov_compras 
        SET fechadocsire=?, 
            basedocsire=?, 
            igvdocsire=?, 
            othdocsire=?, 
            totaldocsire=?, 
            moneda=? ,
            nogravado=?,
            tcambiosire=?
        WHERE movkey=?");
    
    $stmt->execute([
        $data['fecEmision'],
        $mtoBIGravadaDG,
        $mtoIgvIpmDG,
        $mtoOtrosTrib,
        $mtoTotalCp,
        $data['codMoneda'],
        $mtoValorAdqNG,
        $tipocambiosire,
        $movkey
    ]);
}
    }
}
    exit;







?>
