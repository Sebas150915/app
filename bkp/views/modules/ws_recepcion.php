<?php
header("Content-Type: application/json");

date_default_timezone_set('America/Lima');

$method = $_GET['op'];



function eliminarcaracteres(string $string): string {
    // Normalizar espacios y trim
    $string = trim($string);
    
    // Tabla de reemplazos (mejor mantenibilidad)
    $reemplazos = [
        // Corregir caracteres mal codificados
        'N?' => 'Ñ',
        'n?' => 'ñ',
        '?' => '',
        
        // Entidades HTML comunes
        '&period;' => '.',
        '&aacute;' => 'á',
        '&eacute;' => 'é',
        '&iacute;' => 'í',
        '&oacute;' => 'ó',
        '&uacute;' => 'ú',
        '&Aacute;' => 'Á',
        '&Eacute;' => 'É',
        '&Iacute;' => 'Í',
        '&Oacute;' => 'Ó',
        '&Uacute;' => 'Ú',
        '&ntilde;' => 'ñ',
        '&Ntilde;' => 'Ñ',
        
        // Caracteres especiales comunes
        'Ã±' => 'ñ',
        'Ã‘' => 'Ñ',
        'Ã¡' => 'á',
        'Ã©' => 'é',
        'Ã­' => 'í',
        'Ã³' => 'ó',
        'Ãº' => 'ú',
        'Ã' => 'Á',
        'Ã‰' => 'É',
        'Ã' => 'Í',
        'Ã“' => 'Ó',
        'Ãš' => 'Ú'
    ];
    
    // Aplicar reemplazos
    $string = str_replace(array_keys($reemplazos), array_values($reemplazos), $string);
    
    // Eliminar otros caracteres no deseados (personalizable)
    $string = preg_replace('/[^\p{L}\p{N}\s\.\,\-\_]/u', '', $string);
    
    // Normalizar espacios múltiples
    $string = preg_replace('/\s+/', ' ', $string);
    
    return $string;
}






function limpiarJsonCompleto($data) {
    if (is_string($data)) {
        return limpiarTextoUnicode($data);
    } elseif (is_array($data)) {
        foreach ($data as $key => $value) {
            $data[$key] = limpiarJsonCompleto($value);
        }
        return $data;
    } elseif (is_object($data)) {
        foreach ($data as $key => $value) {
            $data->$key = limpiarJsonCompleto($value);
        }
        return $data;
    } else {
        return $data; // No es string, array ni objeto
    }
}

// Tu función original mejorada
function limpiarTextoUnicode($texto) {
    if (is_string($texto)) {
        // Si es JSON escapado (como \u00ed)
        if (preg_match('/\\\\u/', $texto)) {
            return json_decode('"' . str_replace('"', '\"', $texto) . '"');
        }
        // Si es HTML (como &iacute;)
        return html_entity_decode($texto, ENT_QUOTES, 'UTF-8');
    }
    return $texto;
}




switch ($method) 
{
    case 'leer':
        $id_empresa = $_GET['empresa'];
        // Obtener todos los usuarios
        $stmt = $connect->query("SELECT * FROM transacciones_contables WHERE empresa = $id_empresa");
        $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($categorias);
        break;
        
        
         case 'insertar':
        // Leer el JSON de entrada
        $input = file_get_contents("php://input");
        $data = json_decode($input,JSON_UNESCAPED_UNICODE);
        $data = limpiarJsonCompleto($data);
        
      
        
// Convertir el array de vuelta a JSON para guardarlo
$jsonData = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
// Nombre del archivo donde se guardará
$filename = 'datos_guardados.txt';

// Guardar en el archivo
file_put_contents($filename, $jsonData);

        if (isset($data[0])) 
        {
            $records = $data; 
        } 
        else 
        {
            $records = [$data]; 
        }
        
        $results = [];
        $successCount = 0;
        $i=0;
        
        $records = limpiarJsonCompleto($records);
// Mostramos el resultado (o lo guardamos)
//$records =json_encode($records, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
//$records = json_decode($records);


        foreach ($records as $record) 
        {
            if($record['analisis_cuenta']=='S')
            {
                $detalle[]=$record;
            }
              
            $nombre_cuenta = $record['nombre_cuenta'];
            $glosa         = $record['glosa'];
            
            
            $buscar = ["\u2013"];
            $reemplazar = [" "];
            $nombre_cuenta = str_replace($buscar, $reemplazar, $nombre_cuenta);
            $glosa = str_replace($buscar, $reemplazar, $glosa);
            
             $nombre_cuenta = mb_convert_encoding($nombre_cuenta, 'ISO-8859-1', 'UTF-8');
             $nombre_cuenta = eliminarcaracteres($nombre_cuenta); 
             
             $glosa = mb_convert_encoding($glosa, 'ISO-8859-1', 'UTF-8');
             $glosa = eliminarcaracteres($glosa); 
            
            $mtv = trim(isset($record['mtv']) ? $record['mtv'] : '');
            $empresa = trim(isset($record['empresa']) ? $record['empresa'] : '');
            $periodo = trim(isset($record['periodo']) ? $record['periodo'] : '');
            $mes = trim(isset($record['mes']) ? $record['mes'] : '');
            $voucher = trim(isset($record['voucher']) ? $record['voucher'] : '');
            $cuenta = trim(isset($record['cuenta']) ? $record['cuenta'] : '');
            $moneda = trim(isset($record['moneda']) ? $record['moneda'] : 'S');
            $glosa = trim(isset($record['glosa']) ? $record['glosa'] : '');
            $debe = trim(isset($record['debe']) ? $record['debe'] : '');
            $haber = trim(isset($record['haber']) ? $record['haber'] : '');
            $tc = trim(isset($record['tc']) ? $record['tc'] : '0.000');
            $origen = trim(isset($record['origen']) ? $record['origen'] : '');
            $fecha = trim(isset($record['fecha']) ? $record['fecha'] : date('Y-m-d'));
            $documento = trim(isset($record['documento']) ? $record['documento'] : '');
            $nombre_doc = trim(isset($record['nombre_doc']) ? $record['nombre_doc'] : '');
            $numero = trim(isset($record['numero']) ? $record['numero'] : '');
            $fecha_doc = trim(isset($record['fecha_doc']) ? $record['fecha_doc'] : date('Y-m-d'));
            $fecha_ven = trim(isset($record['fecha_ven']) ? $record['fecha_ven'] : date('Y-m-d'));
            $ruc = trim(isset($record['ruc']) ? $record['ruc'] : '');
            $razon_social = trim(isset($record['razon_social']) ? $record['razon_social'] : '');
            $cc = trim(isset($record['cc']) ? $record['cc'] : '');
            $nombre_cc = trim(isset($record['nombre_cc']) ? $record['nombre_cc'] : '');
            $presupuesto = trim(isset($record['presupuesto']) ? $record['presupuesto'] : '');
            $nombre_pre = trim(isset($record['nombre_pre']) ? $record['nombre_pre'] : '');
            $flujo_efectivo = trim(isset($record['flujo_efectivo']) ? $record['flujo_efectivo'] : '');
            $nombre_fe = trim(isset($record['nombre_fe']) ? $record['nombre_fe'] : '');
            $neto1 = trim(isset($record['neto1']) ? $record['neto1'] : '0.00');
            $neto2 = trim(isset($record['neto2']) ? $record['neto2'] : '0.00');
            $neto3 = trim(isset($record['neto3']) ? $record['neto3'] : '0.00');
            $neto4 = trim(isset($record['neto4']) ? $record['neto4'] : '0.00');
            $neto5 = trim(isset($record['neto5']) ? $record['neto5'] : '0.00');
            $neto6 = trim(isset($record['neto6']) ? $record['neto6'] : '0.00');
            $neto7 = trim(isset($record['neto7']) ? $record['neto7'] : '0.00');
            $neto8 = trim(isset($record['neto8']) ? $record['neto8'] : '0.00');
            $neto9 = trim(isset($record['neto9']) ? $record['neto9'] : '0.00');
            $igv = trim(isset($record['igv']) ? $record['igv'] : '0.000');
            $tl = trim(isset($record['tl']) ? $record['tl'] : '');
            $doc_referencia1 = trim(isset($record['doc_referencia1']) ? $record['doc_referencia1'] : '');
            $doc_referencia2 = trim(isset($record['doc_referencia2']) ? $record['doc_referencia2'] : '');
            $fecha_referencia = trim(isset($record['fecha_referencia']) ? $record['fecha_referencia'] : date('Y-m-d'));
            $numero_referencia = trim(isset($record['numero_referencia']) ? $record['numero_referencia'] : '');
            $monto_referencia = trim(isset($record['monto_referencia']) ? $record['monto_referencia'] : '0.00');
            $igv_referencia = trim(isset($record['igv_referencia']) ? $record['igv_referencia'] : '0.00');
            $nombre_origen = trim(isset($record['nombre_origen']) ? $record['nombre_origen'] : '');
            $tipo_cuenta = trim(isset($record['tipo_cuenta']) ? $record['tipo_cuenta'] : '');
            $analisis_cuenta = trim(isset($record['analisis_cuenta']) ? $record['analisis_cuenta'] : '');
             $llave = trim(isset($record['llave']) ? $record['llave'] : '');
            
            if($fecha_referencia == ''){$fecha_referencia=date('Y-m-d');}
            if($fecha_referencia == '1970-01-01'){$fecha_referencia=date('Y-m-d');}
            
            
           
        }
        
         $archivo = 'json2025.txt';
          //$consulta = json_encode($input);
          file_put_contents($archivo, $consultax);             
                      
        $detalle=json_encode($detalle, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);     
        $headers = array(
            "Content-Type: application/json; charset=UTF-8",
            "Cache-Control: no-cache",
            "Pragma: no-cache"
        );

        
        
        
        
        // Respuesta final
        echo json_encode([
            //'total_records' => count($records),
            'success_count' => 'Procesado con exito',
            //'failed_count' => count($records) - $successCount,
            //'results' => $results
        ]);
        break;
}

?>