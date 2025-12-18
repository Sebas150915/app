<?php 
require_once("../../config/config.php");
require_once("../../helpers/helpers.php"); 
require_once("../../libraries/conexion.php"); 

$dni = $_POST['dni'];
$tip = $_POST['tip'];
$l = strlen($dni);

if($tip=='1' && $l == 8)
{
$data = file_get_contents('https://dniruc.apisperu.com/api/v1/dni/'.$dni.'?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6IkptdmFsdmVyZGVjQGdtYWlsLmNvbSJ9.-XVl-oKrL-Jvu0H5ndUgqrIhDMlDwztn3qNPOA-usq0');
	$info = json_decode($data, true);

	if($data=='[]')
		{
			$datos = array(0 => 'nada');
			json_encode($datos);
		}

	else
		{
			$datos = array(
			0 => $info['dni'],
			1 => $tip,
			2 => $info['nombres'],
			3 => $info['apellidoPaterno'],
			4 => $info['apellidoMaterno']
		);
			echo json_encode($datos);
		}

}
elseif($tip=='6' && $l == 11)
{


$token =substr($dni, -3);
$api_key = ($token*50) + 100;
$link = "https://www.smartbase.club/sunat/ruc2.php?ruc=".$dni."&api_key=".$api_key;
//echo $link;
$data = file_get_contents($link);
	$info = json_decode($data, true);

	if($data==='[]' )
		{
			$datos = array(0 => 'nada');
			echo json_encode($datos);
		}
	else
		{
		
		$datos = array(
			0 => $info['ruc'], 
			1 => $tip,
			2 => $info['razon_social'],
			3 => $info['estadoRuc'],
			4 => $info['ubigeo'],
			5 => $info['condDomiRuc'],
			6 => $info['direccion'],
			7 => $info['departamento'],
			8 => $info['provincia'],
			9 => $info['distrito']
		);
			echo json_encode($datos);
		}


}

?>