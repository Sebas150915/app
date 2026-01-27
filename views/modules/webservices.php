<?php

if (isset($_SERVER['HTTP_ORIGIN'])) {
	header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
	header('Access-Control-Allow-Credentials: true');
	header('Access-Control-Max-Age: 86400');
}

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
	if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
		header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

	if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
		header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
}

include '../../config/config.php';
include '../../helpers/helpers.php';
include '../../libraries/conexion.php';
header('Content-Type: application/json');
$ruce    = $_GET['ruc'];
$fechai  = $_GET['fechai'];
$fechaf  = $_GET['fechaf'];

$no_permitidas = array("á", "é", "í", "ó", "ú", "Á", "É", "Í", "Ó", "Ú", "À", "Ã", "Ì", "Ò", "Ù", "Ã™", "Ã ", "Ã¨", "Ã¬", "Ã²", "Ã¹", "ç", "Ç", "Ã¢", "ê", "Ã®", "Ã´", "Ã»", "Ã", "ÃŠ", "ÃŽ", "Ã", "Ã›", "ü", "Ã¶", "Ã–", "Ã¯", "Ã¤", "«", "Ò", "Ã", "Ã", "Ã‹", "*", "%", "'", '"');
$permitidas = array("a", "e", "i", "o", "u", "A", "E", "I", "O", "U", "N", "A", "E", "I", "O", "U", "a", "e", "i", "o", "u", "c", "C", "a", "e", "i", "o", "u", "A", "E", "I", "O", "U", "u", "o", "O", "i", "a", "e", "U", "I", "A", "E", ".", ".", "", "");

$query = "SELECT * FROM tbl_cliente_empresa WHERE ruc = $ruce";
//echo $query;
$query_empresa = $connect->prepare($query);

$query_empresa->execute();
$row_config = $query_empresa->fetch(PDO::FETCH_ASSOC);

$idcliente        = $row_config['id'];


if ($ruce == '20493223641') 
{

  //datos de compras
	$origen_compras       = $row_config['origencompras'];
	$cta_igv_compra       = $row_config['cuenta40igv'];
	$cta_42_s_compra      = $row_config['cuenta42soles'];
	$cta_42_d_compra      = $row_config['cuenta42dolar'];
	//migramos las ventas que han sido usadas


	$data = array();

	$query = "SELECT idrendicion,fecharendicion
				FROM vw_mov_rendicion_cab 
				WHERE fecharendicion 
				BETWEEN :fechai AND :fechaf 
				AND idcliente = :idcliente
				GROUP BY idrendicion,fecharendicion";

	$stmt = $connect->prepare($query);
	$stmt->execute([
		':fechai' => $fechai,
		':fechaf' => $fechaf,
		':idcliente' => $idcliente
	]);

	$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
	$vou = 1;
	foreach ($result as $datos) {

		$idrendicion = $datos['idrendicion'];

		$queryws = "SELECT * 
						FROM vw_mov_rendicion_cab 
						WHERE fecharendicion 
						BETWEEN :fechai AND :fechaf 
						AND idcliente = :idcliente";

		$stmtws = $connect->prepare($queryws);
		$stmtws->execute([
			':fechai' => $datos['fecharendicion'],
			':fechaf' => $datos['fecharendicion'],
			':idcliente' => $idcliente
		]);

		$resultws = $stmtws->fetchAll(PDO::FETCH_ASSOC);

		$totalrendicion = 0;

		foreach ($resultws as $datosws) {
			$moneda = 'D';
			$cta_42 = $cta_42_d_compra;
			if ($datosws['moneda'] == 'PEN') {
				$moneda = 'S';
				$cta_42 = $cta_42_s_compra;
			}
			$glosa = $datosws['descripcion'];

			if ($datosws['tipodoc'] == '00' || $datosws['tipodoc'] == '05' || $datosws['tipodoc'] == '14') {
				$cta_42 = $datosws['cuenta_codigo'];
			}

			if ($datosws['tipodoc'] == '02') {
				$cta_42 = '4241111';
			}

			$data[] = array(
				'origen' => $origen_compras,
				'vou' => '' . $vou,
				'fecha' => date("d/m/Y", strtotime($datosws['fecharendicion'])),
				'cuenta' => '' . $cta_42,
				'debe' => '' . $datosws['importepago'],
				'haber' => '0.00',
				'moneda' => $moneda,
				'tc' => '' . $datosws['tcambio'],
				'doc' => $datosws['tipodoc'],
				'numero' => $datosws['seriedoc'] . "-" . $datosws['numdoc'],
				'fechad' => date("d/m/Y", strtotime($datosws['fechadocsire'])),
				'fechav' => date("d/m/Y", strtotime($datosws['fechadocsire'])),
				'codigo' => $datosws['rucemisor'],
				'cc' => '',
				'pre' => '',
				'fe' => '',
				'glosa' => $glosa,
				'tl' => 'C',
				'neto1' => '',
				'neto2' => '',
				'neto3' => '',
				'neto4' => '',
				'neto5' => '',
				'neto6' => '',
				'neto7' => '',
				'neto8' => '',
				'neto9' => '',
				'igv' => '',
				'rdoc' => '',
				'rnum' => '',
				'rfec' => '',
				'snum' => '',
				'sfec' => '',
				'ruc' => '' . $datosws['rucemisor'],
				'rs' => '',
				'tipo' => '2',
				'tdoci' => '6',
				'mpago' => '',
				'ape1' => '',
				'ape2' => '',
				'nombre' => '',
				'tbien' => '',
				'refmonto' => '0.00'
			);






			$totalrendicion = $totalrendicion + $datosws['importepago'];
		}


		//sumatoria

		$glosa = 'RENDICION DE GASTOS';
		$data[] = array(
			'origen' => $origen_compras,
			'vou' => '' . $vou,
			'fecha' => date("d/m/Y", strtotime($datosws['fecharendicion'])),
			'cuenta' => '1011111',
			'debe' => '0.00',
			'haber' => '' . $totalrendicion,
			'moneda' => $moneda,
			'tc' => '' . $datosws['tcambio'],
			'doc' => '00',
			'numero' => '',
			'fechad' => date("d/m/Y", strtotime($datosws['fechadocsire'])),
			'fechav' => date("d/m/Y", strtotime($datosws['fechadocsire'])),
			'codigo' => '',
			'cc' => '',
			'pre' => '',
			'fe' => '',
			'glosa' => $glosa,
			'tl' => '',
			'neto1' => '',
			'neto2' => '',
			'neto3' => '',
			'neto4' => '',
			'neto5' => '',
			'neto6' => '',
			'neto7' => '',
			'neto8' => '',
			'neto9' => '',
			'igv' => '',
			'rdoc' => '',
			'rnum' => '',
			'rfec' => '',
			'snum' => '',
			'sfec' => '',
			'ruc' => '',
			'rs' => '',
			'tipo' => '',
			'tdoci' => '',
			'mpago' => '',
			'ape1' => '',
			'ape2' => '',
			'nombre' => '',
			'tbien' => '',
			'refmonto' => ''
		);
		$vou++;
	//fin sumatoria
	}





	echo json_encode($data);
	exit;

}

else if($ruce == '20407919905')
{
	$query = "SELECT * 
          FROM vw_mov_rendicion_cab 
          WHERE fecharendicion 
          BETWEEN :fechai AND :fechaf 
          AND idcliente = :idcliente";

	$stmt = $connect->prepare($query);
	$stmt->execute([
		':fechai' => $fechai,
		':fechaf' => $fechaf,
		':idcliente' => $idcliente
	]);

	$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
	$vou = 1;
	foreach ($result as $datos) {
		$moneda = 'D';
		$cta_42 = $cta_42_d_compra;
		if ($datos['moneda'] == 'PEN') {
			$moneda = 'S';
			$cta_42 = $cta_42_s_compra;
		}
		$glosa = $datos['descripcion'];
		$data[] = array(
			'origen' => $origen_compras,
			'vou' => '' . $vou,
			'fecha' => date("d/m/Y", strtotime($datos['fechadocsire'])),
			'cuenta' => '' . $cta_42,
			'debe' => '0.00',
			'haber' => '' . $datos['total'],
			'moneda' => $moneda,
			'tc' => '' . $datos['tcambio'],
			'doc' => $datos['tipodoc'],
			'numero' => $datos['seriedoc'] . "-" . $datos['numdoc'],
			'fechad' => date("d/m/Y", strtotime($datos['fechadocsire'])),
			'fechav' => date("d/m/Y", strtotime($datos['fechadocsire'])),
			'codigo' => $datos['rucemisor'],
			'cc' => '',
			'pre' => '',
			'fe' => '',
			'glosa' => $glosa,
			'tl' => '',
			'neto1' => '',
			'neto2' => '',
			'neto3' => '',
			'neto4' => '',
			'neto5' => '',
			'neto6' => '',
			'neto7' => '',
			'neto8' => '',
			'neto9' => '',
			'igv' => '',
			'rdoc' => '',
			'rnum' => '',
			'rfec' => '',
			'snum' => '',
			'sfec' => '',
			'ruc' => $datos['rucemisor'],
			'rs' => $datos['razonemisor'],
			'tipo' => '2',
			'tdoci' => '6',
			'mpago' => '',
			'ape1' => '',
			'ape2' => '',
			'nombre' => '',
			'tbien' => '',
			'refmonto' => '0.00'
		);

		if ($datos['tipodoc'] <> '00') {
			$data[] = array(
				'origen' => $origen_compras,
				'vou' => '' . $vou,
				'fecha' => date("d/m/Y", strtotime($datos['fechadocsire'])),
				'cuenta' => '' . $cta_igv_compra,
				'debe' => '' . $datos['igvdocsire'],
				'haber' => '0.00',
				'moneda' => $moneda,
				'tc' => '' . $datos['tcambio'],
				'doc' => $datos['tipodoc'],
				'numero' => $datos['seriedoc'] . "-" . $datos['numdoc'],
				'fechad' => date("d/m/Y", strtotime($datos['fechadocsire'])),
				'fechav' => date("d/m/Y", strtotime($datos['fechadocsire'])),
				'codigo' => $datos['rucemisor'],
				'cc' => '',
				'pre' => '',
				'fe' => '',
				'glosa' => $glosa,
				'tl' => 'C',
				'neto1' => '' . $datos['basedocsire'],
				'neto2' => '',
				'neto3' => '',
				'neto4' => '',
				'neto5' => '',
				'neto6' => '' . $datos['nogravado'],
				'neto7' => '' . $datos['othdocsire'],
				'neto8' => '',
				'neto9' => '',
				'igv' => '' . $datos['igvdocsire'],
				'rdoc' => '',
				'rnum' => '',
				'rfec' => '',
				'snum' => '',
				'sfec' => '',
				'ruc' => $datos['rucemisor'],
				'rs' => $datos['razonemisor'],
				'tipo' => '2',
				'tdoci' => '6',
				'mpago' => '',
				'ape1' => '',
				'ape2' => '',
				'nombre' => '',
				'tbien' => '',
				'refmonto' => '0.00'
			);
		}

		$baseimponible = $datos['basedocsire'] + $datos['nogravado'];
		$cc  = $datos['cc_codigo'];
		$pre = $datos['pre_codigo'];

		if ($cc == '0') {
			$cc = '';
		}

		if ($pre == '0') {
			$pre = '';
		}
		$data[] = array(
			'origen' => $origen_compras,
			'vou' => '' . $vou,
			'fecha' => date("d/m/Y", strtotime($datos['fechadocsire'])),
			'cuenta' => '' . $datos['cuenta_codigo'],
			'debe' => '' . $baseimponible,
			'haber' => '0.00',
			'moneda' => $moneda,
			'tc' => '' . $datos['tcambio'],
			'doc' => $datos['tipodoc'],
			'numero' => $datos['seriedoc'] . "-" . $datos['numdoc'],
			'fechad' => date("d/m/Y", strtotime($datos['fechadocsire'])),
			'fechav' => date("d/m/Y", strtotime($datos['fechadocsire'])),
			'codigo' => $datos['rucemisor'],
			'cc' => '' . $cc,
			'pre' => '' . $pre,
			'fe' => '',
			'glosa' => $glosa,
			'tl' => '',
			'neto1' => '',
			'neto2' => '',
			'neto3' => '',
			'neto4' => '',
			'neto5' => '',
			'neto6' => '',
			'neto7' => '',
			'neto8' => '',
			'neto9' => '',
			'igv' => '',
			'rdoc' => '',
			'rnum' => '',
			'rfec' => '',
			'snum' => '',
			'sfec' => '',
			'ruc' => $datos['rucemisor'],
			'rs' => $datos['razonemisor'],
			'tipo' => '2',
			'tdoci' => '6',
			'mpago' => '',
			'ape1' => '',
			'ape2' => '',
			'nombre' => '',
			'tbien' => '',
			'refmonto' => '0.00'
		);

		$vou++;
	}

    echo json_encode($data);
	exit;
}

else
{


/*
DATOS PARA ASIENTOS DE COMPRAS
 */

/*********************/
/*DATOS PARA COMPRAS*/
/*******************/
$origen_compras       = $row_config['origencompras'];
$cta_igv_compra       = $row_config['cuenta40igv'];
$cta_42_s_compra      = $row_config['cuenta42soles'];
$cta_42_d_compra      = $row_config['cuenta42dolar'];
//migramos las ventas que han sido usadas
$data = array();

$query = "SELECT * FROM mov_compras WHERE idcliente = :idcliente AND fechadocsire BETWEEN :fechai AND :fechaf ORDER BY fechadocsire ASC";
$stmt = $connect->prepare($query);
$stmt->execute([
	':idcliente' => $idcliente,
	':fechai' => $fechai,
	':fechaf' => $fechaf
]);
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
$vou = 1;
foreach ($result as $datos) {
	$moneda = 'D';
	$cta_42 = $cta_42_d_compra;
	if ($datos['moneda'] == 'PEN') {
		$moneda = 'S';
		$cta_42 = $cta_42_s_compra;
	}
    
    $tl = 'C';
	if($datos['tipdoc'] == '02'){
		$tl = 'H';
		$origen_compras = $row_config['origenhonorarios'];
		$cta_42 = $row_config['cuentarhsoles'];
		$cta_igv_compra = $row_config['cuenta40rh'];
	}

	

	$glosa = $datos['glosasire'];
	$data[] = array(
		'origen' => $origen_compras,
		'vou' => '' . $vou,
		'fecha' => date("d/m/Y", strtotime($datos['fechadocsire'])),
		'cuenta' => '' . $cta_42,
		'debe' => '0.00',
		'haber' => '' . $datos['totaldocsire'],
		'moneda' => $moneda,
		'tc' => '' . $datos['tcambiosire'],
		'doc' => $datos['tipdoc'],
		'numero' => $datos['seriedoc'] . "-" . $datos['numdoc'],
		'fechad' => date("d/m/Y", strtotime($datos['fechadocsire'])),
		'fechav' => date("d/m/Y", strtotime($datos['fechadocsire'])),
		'codigo' => $datos['rucemisor'],
		'cc' => '',
		'pre' => '',
		'fe' => '',
		'glosa' => $glosa,
		'tl' => '',
		'neto1' => '',
		'neto2' => '',
		'neto3' => '',
		'neto4' => '',
		'neto5' => '',
		'neto6' => '',
		'neto7' => '',
		'neto8' => '',
		'neto9' => '',
		'igv' => '',
		'rdoc' => '',
		'rnum' => '',
		'rfec' => '',
		'snum' => '',
		'sfec' => '',
		'ruc' => $datos['rucemisor'],
		'rs' => $datos['razonemisor'],
		'tipo' => '2',
		'tdoci' => '6',
		'mpago' => '',
		'ape1' => '',
		'ape2' => '',
		'nombre' => '',
		'tbien' => '',
		'refmonto' => ''
	);

	$data[] = array(
		'origen' => $origen_compras,
		'vou' => '' . $vou,
		'fecha' => date("d/m/Y", strtotime($datos['fechadocsire'])),
		'cuenta' => '' . $cta_igv_compra,
		'debe' => '0.00',
		'haber' => '' . $datos['igvdocsire'],
		'moneda' => $moneda,
		'tc' => '' . $datos['tcambiosire'],
		'doc' => $datos['tipdoc'],
		'numero' => $datos['seriedoc'] . "-" . $datos['numdoc'],
		'fechad' => date("d/m/Y", strtotime($datos['fechadocsire'])),
		'fechav' => date("d/m/Y", strtotime($datos['fechadocsire'])),
		'codigo' => $datos['rucemisor'],
		'cc' => '',
		'pre' => '',
		'fe' => '',
		'glosa' => $glosa,
		'tl' => ''.$tl,
		'neto1' => $datos['basedocsire'],
		'neto2' => '',
		'neto3' => '',
		'neto4' => '',
		'neto5' => '',
		'neto6' => $datos['nogravado'],
		'neto7' => '',
		'neto8' => '',
		'neto9' => '',
		'igv' => $datos['igvdocsire'],
		'rdoc' => '',
		'rnum' => '',
		'rfec' => '',
		'snum' => '',
		'sfec' => '',
		'ruc' => $datos['rucemisor'],
		'rs' => $datos['razonemisor'],
		'tipo' => '2',
		'tdoci' => '6',
		'mpago' => '',
		'ape1' => '',
		'ape2' => '',
		'nombre' => '',
		'tbien' => '',
		'refmonto' => ''
	);



	/*BUSCAMOS EL DETALLE DE LA COMPRA */
	$query = "SELECT * FROM mov_compras_det WHERE movkey = :movkey";
	$stmt = $connect->prepare($query);
	$stmt->execute([
		':movkey' => $datos['movkey']
	]);
	$result = $stmt->fetchAll(PDO::FETCH_ASSOC);



	if (empty($result)) {
		$data[] = array(
			'origen' => $origen_compras,
			'vou' => '' . $vou,
			'fecha' => date("d/m/Y", strtotime($datos['fechadocsire'])),
			'cuenta' => '6011111',
			'debe' => '0.00',
			'haber' => '' . $datos['igvdocsire'],
			'moneda' => $moneda,
			'tc' => '' . $datos['tcambiosire'],
			'doc' => $datos['tipdoc'],
			'numero' => $datos['seriedoc'] . "-" . $datos['numdoc'],
			'fechad' => date("d/m/Y", strtotime($datos['fechadocsire'])),
			'fechav' => date("d/m/Y", strtotime($datos['fechadocsire'])),
			'codigo' => $datos['rucemisor'],
			'cc' => '',
			'pre' => '',
			'fe' => '',
			'glosa' => $glosa,
			'tl' => 'C',
			'neto1' => $datos['basedocsire'],
			'neto2' => '',
			'neto3' => '',
			'neto4' => '',
			'neto5' => '',
			'neto6' => $datos['nogravado'],
			'neto7' => '',
			'neto8' => '',
			'neto9' => '',
			'igv' => $datos['igvdocsire'],
			'rdoc' => '',
			'rnum' => '',
			'rfec' => '',
			'snum' => '',
			'sfec' => '',
			'ruc' => $datos['rucemisor'],
			'rs' => $datos['razonemisor'],
			'tipo' => '2',
			'tdoci' => '6',
			'mpago' => '',
			'ape1' => '',
			'ape2' => '',
			'nombre' => '',
			'tbien' => '',
			'refmonto' => ''
		);
	} else {
		foreach ($result as $detalle) {
			$data[] = array(
				'origen' => $origen_compras,
				'vou' => '' . $vou,
				'fecha' => date("d/m/Y", strtotime($datos['fechadocsire'])),
				'cuenta' => '' . $detalle['codigo'],
				'debe' => '0.00',
				'haber' => '' . $detalle['subtotal'],
				'moneda' => $moneda,
				'tc' => '' . $datos['tcambiosire'],
				'doc' => $datos['tipdoc'],
				'numero' => $datos['seriedoc'] . "-" . $datos['numdoc'],
				'fechad' => date("d/m/Y", strtotime($datos['fechadocsire'])),
				'fechav' => date("d/m/Y", strtotime($datos['fechadocsire'])),
				'codigo' => $datos['rucemisor'],
				'cc' => '',
				'pre' => '',
				'fe' => '',
				'glosa' => $detalle['descripcion'],
				'tl' => '',
				'neto1' => $detalle['subtotal'],
				'neto2' => '',
				'neto3' => '',
				'neto4' => '',
				'neto5' => '',
				'neto6' => $datos['nogravado'],
				'neto7' => '',
				'neto8' => '',
				'neto9' => '',
				'igv' => $datos['igvdocsire'],
				'rdoc' => '',
				'rnum' => '',
				'rfec' => '',
				'snum' => '',
				'sfec' => '',
				'ruc' => $datos['rucemisor'],
				'rs' => $datos['razonemisor'],
				'tipo' => '2',
				'tdoci' => '6',
				'mpago' => '',
				'ape1' => '',
				'ape2' => '',
				'nombre' => '',
				'tbien' => '',
				'refmonto' => ''
			);
			$vou++;
		}
	}


	$vou++;
}

echo json_encode($data);
exit;
}










