<?php
require_once("../../config/config.php");
require_once("../../helpers/helpers.php");
require_once("../../libraries/conexion.php");
$noPermitidos = ["Ñ", "ñ", "'","\xD1","\xF1"];
$permitidos   = ["N", "n", " ","N","n"]; // aquí ' se reemplaza por espacio
/*
        "\xF1" => "n",   // ñ problemática
        "\xC1" => "A",   // Á problemática
        "\xE1" => "a",   // á problemática
        "\xC9" => "E",   // É problemática
        "\xE9" => "e",   // é problemática
        "\xCD" => "I",   // Í problemática
        "\xED" => "i",   // í problemática
        "\xD3" => "O",   // Ó problemática
        "\xF3" => "o",   // ó problemática
        "\xDA" => "U",   // Ú problemática
        "\xFA" => "u",   // ú problemática*/

$anio = $_POST['anio'] ?? date("Y");
$mes  = $_POST['mes']  ?? date("m");
$periodo = $anio.$mes;

if (isset($_FILES['archivo']['tmp_name'])) {
    $file = fopen($_FILES['archivo']['tmp_name'], "r");

    $si_insertados = 0;
    $no_insertados = 0;
    $mensaje = array();

    while (($line = fgets($file)) !== false) {
        $data = explode("|", trim($line));

        $movkey     = $data[11].'-'.$data[5].'-'.$data[6].'-'.$data[8];
        $rucemisor  = $data[11];
        $razonemisor= $data[12];
        
        $razonemisor = str_replace($noPermitidos, $permitidos, $razonemisor);
        
        
        
        $periodoUso = substr($data[0],0,6);
        $tipodoc    = $data[5];
        $seriedoc   = $data[6];
        $numdoc     = $data[8];

        $f = explode('/',$data[3]);
        $fecha = (count($f)==3) ? $f[2].'-'.$f[1].'-'.$f[0] : null;

        $baseimp = (!empty($data[13]) && is_numeric($data[13])) ? $data[13] : '0.00';
        $igv     = (!empty($data[14]) && is_numeric($data[14])) ? $data[14] : '0.00';
        $oth     = (!empty($data[19]) && is_numeric($data[19])) ? $data[19] : '0.00';
        $total   = (!empty($data[23]) && is_numeric($data[23])) ? $data[23] : '0.00';
 

        

        $moneda     = $data[24];

        $query_data = "SELECT movkey FROM mov_compras WHERE movkey = ?";
        $resultado = $connect->prepare($query_data);
        $resultado->execute([$movkey]);
        $row_empresa = $resultado->fetch(PDO::FETCH_ASSOC);

        $idmovkey = $row_empresa['movkey'] ?? null;

        if(!$idmovkey){  
            $stmt = $connect->prepare("INSERT INTO mov_compras 
                (movkey, periodouso, rucemisor, razonemisor, tipdoc, seriedoc, numdoc, fechadocple, basedocple, igvdocple, othdocple, totaldocple,moneda) 
                VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)");
            $stmt->execute([$movkey,$periodoUso,$rucemisor,$razonemisor,$tipodoc,$seriedoc,$numdoc,$fecha,$baseimp,$igv,$oth,$total,$moneda]);
            $si_insertados++;
        } else {
            $no_insertados++;
        }
    }
    fclose($file);

    $mensaje['mensaje'] = "Procesado correctamente";
    $mensaje['si_insertados'] = $si_insertados;
    $mensaje['no_insertados'] = $no_insertados;
    $mensaje['status'] = 'ok';

    echo json_encode($mensaje);
}
?>
