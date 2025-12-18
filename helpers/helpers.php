<?php 
function base_url()
{
  return BASE_URL;
}

function media()
{
	return BASE_URL."assets";
}

function webservices()
{
	return BASE_URL."webservices/";
}


function nombre()
{
    return  NOMBRE;
}

function logo()
{
    return  LOGO;
}

function corto()
{
    return  CORTO;
}

function empresa()
{
    return  EMPRESA;
}


/*funciones globales*/

function limpiarCadena($cadena)
{
    $cadena = trim($cadena);
    $cadena = stripslashes($cadena);
    $cadena = htmlspecialchars($cadena, ENT_QUOTES, 'UTF-8');
    return $cadena;
}


/**funcion fecha y hora**/

function formatDate($fecha)
{
    return date('d/m/Y', strtotime($fecha));
}

function formatDateTime($fecha)
{
    return date('d/m/Y H:i:s', strtotime($fecha));
}

function getMonthName($mes)
{
    $meses = [
        1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
        5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
        9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
    ];
    return $meses[(int)$mes] ?? '';
}


/*funciones de moneda*/

function formatMoney($cantidad, $simbolo)
{
    return $simbolo . ' ' . number_format($cantidad, 2, '.', ',');
}

function formatPercent($valor)
{
    return number_format($valor, 2) . '%';
}






function obtener_todos_registros($clienteSeleccionado, $connect){
    $stmt = $connect->prepare("SELECT COUNT(*) AS total FROM tbl_centro_costo WHERE cliente = :empresa");
    $stmt->bindValue(':empresa', $clienteSeleccionado, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row['total'] ?? 0;
}


function obtener_todos_presupuestos($clienteSeleccionado, $connect){
    $stmt = $connect->prepare("SELECT COUNT(*) AS total FROM tbl_presupuestos WHERE cliente = :empresa");
    $stmt->bindValue(':empresa', $clienteSeleccionado, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row['total'] ?? 0;
}

function obtener_todos_concepto_gasto($clienteSeleccionado, $connect){
    $stmt = $connect->prepare("SELECT COUNT(*) AS total FROM tbl_concepto_gasto WHERE cliente = :empresa");
    $stmt->bindValue(':empresa', $clienteSeleccionado, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row['total'] ?? 0;
}


function obtener_todos_bancos($clienteSeleccionado, $connect){
    $stmt = $connect->prepare("SELECT COUNT(*) AS total FROM tbl_bancos WHERE cliente = :empresa");
    $stmt->bindValue(':empresa', $clienteSeleccionado, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row['total'] ?? 0;
}


function obtener_todos_cliente_empresa($clienteSeleccionado, $connect){
    $stmt = $connect->prepare("SELECT COUNT(*) AS total FROM tbl_cliente_empresa WHERE idempresa = :empresa");
    $stmt->bindValue(':empresa', $clienteSeleccionado, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row['total'] ?? 0;
}


function obtener_todos_rendiciones($clienteSeleccionado, $connect){
    $stmt = $connect->prepare("SELECT COUNT(*) AS total FROM mov_rendicion WHERE idcliente = :empresa");
    $stmt->bindValue(':empresa', $clienteSeleccionado, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row['total'] ?? 0;
}


function obtener_todos_rendiciones_cab($idrendicion, $connect){
    $stmt = $connect->prepare("SELECT COUNT(*) AS total FROM vw_mov_rendicion_cab WHERE idrendicion = :idrendicion");
    $stmt->bindValue(':idrendicion', $idrendicion, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row['total'] ?? 0;
}


function obtener_compras($idcliente, $connect){
    $stmt = $connect->prepare("SELECT COUNT(*) AS total FROM vw_compras WHERE idcliente = :idcliente");
    $stmt->bindValue(':idcliente', $idcliente, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row['total'] ?? 0;
}



function obtener_todos_cliente_empresa2($idempresa, $connect) {
    $stmt = $connect->prepare("SELECT COUNT(*) FROM tbl_cliente_empresa WHERE idempresa = :idempresa");
    $stmt->execute([':idempresa' => $idempresa]);
    return $stmt->fetchColumn();
}



?>