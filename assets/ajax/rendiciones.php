<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');

require_once("../../config/config.php");
require_once("../../helpers/helpers.php"); 
require_once("../../libraries/conexion.php");
session_start();

$idempresa = $_SESSION['id_empresa'] ?? null;

if (empty($idempresa)) {
    echo json_encode(['error' => 'No se ha definido la empresa']);
    exit;
}

//var_dump($_GET);

$variable = $_GET['op'] ?? '';

switch ($variable) 
{
    
    case 'eliminardet':
        
        
        $mensaje = array();  
        $iddocumento = $_POST['id_usuario'];
        $importe_pago = $_POST['importe_pago'];
        $movkey       = $_POST['movkey'];
        
        
        $stmt = $connect->prepare("DELETE FROM mov_rendicion_cab WHERE id = :id");
        $resultado = $stmt->execute([
           
            ':id' => $iddocumento
        ]);
        
        $stmt = $connect->prepare("UPDATE mov_compras SET importe_pago = importe_pago - :importe_pago WHERE movkey = :id");
        $resultado = $stmt->execute([
            ':importe_pago' => $importe_pago,
            ':id' => $_POST["id_usuario"]
        ]);
        

        if ($resultado) {
            $mensaje['respuesta'] = "Registro eliminado correctamente";
        } else {
            $mensaje['respuesta'] = 'Error al eliminar el registro';
        }
        
        
        echo json_encode($mensaje);
        break;
    
    case 'guardadetalle' :
        
       //var_dump($_GET);
          
      $importe_pago    = $_GET["importe_pago"];
      $iddocumento     = $_GET["iddocumento"];
      $idrendicion     = $_GET["idrendicion"];
      $idcliente       = $_GET["idcliente"];
      $centrocostos    = $_GET["centrocostos"];
      $presupuestos    = $_GET["presupuestos"];
      $conceptogasto   = $_GET['conceptogasto'];
      $condicion       = $_GET['condicion'];
      $glosacompra     = $_GET['glosacompra'];
      
      
       $stmt = $connect->prepare("INSERT INTO mov_rendicion_cab(idrendicion,idcliente,glosa,iddocumento,cc,pre,importepago,idconceptogasto,condicion) VALUES(
                   :idrendicion,
                   :idcliente,
                   :glosa,
                   :iddocumento,
                   :cc,
                   :pre,
                   :importepago,
                   :idconceptogasto,
                   :condicion)");
                   

    $resultado = $stmt->execute(
        array(
            ':idrendicion'           => $idrendicion,
            ':idcliente'             => $idcliente,
            ':glosa'                 => $glosacompra,
            ':iddocumento'           => $iddocumento,
            ':cc'                    => $centrocostos,
            ':pre'                   => $presupuestos,
            ':importepago'           => $importe_pago,
            ':idconceptogasto'       => $conceptogasto ,
            'condicion'              => $condicion
           
        )
    );
    
    
    
     $stmt = $connect->prepare("UPDATE mov_compras SET 
                                   importe_pago= importe_pago + :importe_pago
                                   
                                   WHERE movkey = :movkey");

    
       $resultado = $stmt->execute(
        array(
            ':importe_pago'       => $importe_pago,
          
            ':movkey'          => $iddocumento
        )
    );

        if (!empty($resultado)) 
        {
            $mensaje['respuesta'] = 'Registro creado correctamente';
        } 
        else 
        {
            $mensaje['respuesta'] = 'Error al registrar';
        }

      
      
      echo json_encode($mensaje);
  
        break;
        
    case 'vwcompras':

$draw = $_POST['draw'];
$row = $_POST['start'];
$rowperpage = $_POST['length']; 
$columnIndex = 0;
$columnSortOrder = 'asc';

// Validar que exista 'order' antes de acceder
if (isset($_POST['order']) && is_array($_POST['order']) && count($_POST['order']) > 0) {
    $columnIndex = $_POST['order'][0]['column'] ?? 0;
    $columnSortOrder = $_POST['order'][0]['dir'] ?? 'asc';
}
$searchValue = $_POST['search']['value']; 

$idrendicion = $_GET['idrendicion'];

// Obtener cliente
$query_data = "SELECT * FROM mov_rendicion WHERE id = :idrendicion";
$resultado = $connect->prepare($query_data);
$resultado->bindValue(':idrendicion', $idrendicion, PDO::PARAM_INT);
$resultado->execute();
$row_empresa = $resultado->fetch(PDO::FETCH_ASSOC);
$idcliente  = $row_empresa['idcliente'];
$cc         = $row_empresa['cc'];
$pre        = $row_empresa['pre'];

// Columnas válidas
$columns = ['movkey', 'fechadocsire', 'rucemisor', 'razonemisor', 'tipdoc', 'seriedoc', 'numdoc', 'totaldocsire', 'importe_pago', 'saldo'];

// 1️⃣ Total de registros sin filtro
$stmt_total = $connect->prepare("SELECT COUNT(*) AS total FROM vw_compras WHERE idcliente = :idcliente");
$stmt_total->bindValue(':idcliente', $idcliente, PDO::PARAM_INT);
$stmt_total->execute();
$totalRecords = $stmt_total->fetch(PDO::FETCH_ASSOC)['total'];

// 2️⃣ Consulta base con filtro
$query = "SELECT * FROM vw_compras WHERE idcliente = :idcliente ";

if (!empty($searchValue)) {
    $query .= "AND (glosa LIKE :busqueda OR razonemisor LIKE :busqueda OR movkey LIKE :busqueda) ";
}

// 3️⃣ Total de registros filtrados
$stmt_filtered = $connect->prepare(str_replace('*', 'COUNT(*) AS total', $query));
$stmt_filtered->bindValue(':idcliente', $idcliente, PDO::PARAM_INT);
if (!empty($searchValue)) {
    $stmt_filtered->bindValue(':busqueda', "%$searchValue%", PDO::PARAM_STR);
}
$stmt_filtered->execute();
$totalRecordwithFilter = $stmt_filtered->fetch(PDO::FETCH_ASSOC)['total'];

// 4️⃣ Ordenamiento
$columna = $columns[$columnIndex] ?? 'fechadocsire';
$direccion = ($columnSortOrder === 'desc') ? 'DESC' : 'ASC';
$query .= "ORDER BY $columna $direccion ";

// 5️⃣ Paginación
if ($rowperpage != -1) {
    $query .= "LIMIT :start, :length";
}

// 6️⃣ Consulta principal
$stmt = $connect->prepare($query);
$stmt->bindValue(':idcliente', $idcliente, PDO::PARAM_INT);
if (!empty($searchValue)) {
    $stmt->bindValue(':busqueda', "%$searchValue%", PDO::PARAM_STR);
}
if ($rowperpage != -1) {
    $stmt->bindValue(':start', intval($row), PDO::PARAM_INT);
    $stmt->bindValue(':length', intval($rowperpage), PDO::PARAM_INT);
}
$stmt->execute();

$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);


// 🔹 Obtener conceptos activos del cliente
$cliente_id = $_SESSION['id_empresa'] ?? 0;
$conceptosStmt = $connect->prepare("SELECT id, nombre FROM tbl_concepto_gasto WHERE estado='ACTIVO' AND cliente=:cliente ORDER BY nombre ASC");
$conceptosStmt->bindValue(':cliente', $idcliente, PDO::PARAM_INT);
$conceptosStmt->execute();
$conceptos = $conceptosStmt->fetchAll(PDO::FETCH_ASSOC);


$combocc = $connect->prepare("SELECT id, nombre FROM tbl_centro_costo WHERE estado='ACTIVO' AND cliente=:cliente ORDER BY nombre ASC");
$combocc->bindValue(':cliente', $idcliente, PDO::PARAM_INT);
$combocc->execute();
$conceptoscc = $combocc->fetchAll(PDO::FETCH_ASSOC);


$combopre = $connect->prepare("SELECT id, nombre FROM tbl_presupuestos WHERE estado='ACTIVO' AND cliente=:cliente ORDER BY nombre ASC");
$combopre->bindValue(':cliente', $idcliente, PDO::PARAM_INT);
$combopre->execute();
$conceptospre = $combopre->fetchAll(PDO::FETCH_ASSOC);

// 🧾 Combobox con valor seleccionado


$datos = [];
foreach ($resultado as $fila) {
    
//concepto de gasto    
$combo = '<select class="form-select form-select-lg conceptogasto" data-id="' . $fila["movkey"] . '">';
foreach ($conceptos as $c)
{
    
   
    $combo .= "<option value='{$c['id']}' selected>{$c['nombre']}</option>";
}
$combo .= '</select>';


$combocc = '<select class="form-select form-select-lg centro-costos centrocostos" data-id="' . $fila["movkey"] . '">';
foreach ($conceptoscc as $ccx)
{
   
    $combocc .= "<option value='{$ccx['id']}' selected>{$ccx['nombre']}</option>";
}
$combocc .= '</select>';


$combopre = '<select class="form-select form-select-lg presupuesto-cb presupuestos" data-id="' . $fila["movkey"] . '">';
foreach ($conceptospre as $prex)
{
   
    $combopre .= "<option value='{$prex['id']}' selected>{$prex['nombre']}</option>";
}

$combopre .= '</select>';

$combocond = '<select class="form-select form-select-lg condicion-cb condicion" data-id="' . $fila["movkey"] . '">
               "<option value="PROPIO" selected>PROPIO</option>";
               "<option value="CLIENTE" >CLIENTE</option>";
               "<option value="AMBOS" >50/50</option>";';

$combocond .= '</select>';


//fin de concepto de gasto


    $datos[] = [
        $fila["movkey"],
        '<input type="text" class="form-control  izquierda glosacompra" maxlength="60"   id="glosacompra" name="glosacompra" value="'.$fila['glosa'].'" style="font-size:10px" onkeyup="javascript:this.value=this.value.toUpperCase();">',
        $fila["fechadocsire"],
        $fila["rucemisor"],
        $fila["razonemisor"],
        $fila["tipdoc"],
        $fila["seriedoc"] . '-' . $fila["numdoc"],
        number_format($fila["totaldocsire"], 2),
        
        '<input type="text" class="form-control text-right derecha importe_pago" min="0" max="'.$fila["saldo"].'"  id="importe_pago" name="importe_pago" value="'. number_format($fila["saldo"], 2).'" style="font-size:10px">
         <input type="hidden" class="iddocumento" value="'.$fila["movkey"].'">
         <input type="hidden" class="idrendicion" value="'.$idrendicion.'">
         <input type="hidden" class="idcliente" value="'.$idcliente.'">
         <input type="hidden" class="totaldocsire" value="'.$fila["totaldocsire"].'">
         <input type="hidden" class="pagado" value="'.($fila["totaldocsire"]-$fila["saldo"]).'">',
       
        number_format($fila["totaldocsire"]-$fila["saldo"], 2),
        $combo,
        $combocc,
        $combopre,
        $combocond,
        '<button type="button" name="agregacab" id="'.$fila["movkey"].'" class="btn btn-danger btn-xs btn-agregar-detalle"><i class="fas fa-plus"></i></button>'
    ];
}

// 7️⃣ Respuesta a DataTables
$response = [
    "draw" => intval($draw),
    "recordsTotal" => intval($totalRecords),
    "recordsFiltered" => intval($totalRecordwithFilter),
    "data" => $datos
];

echo json_encode($response);
break;

    
    
    
    
   /* case 'rendicion_cab':
    $idrendicion = $_GET['idrendicion'] ?? null;
    $columns = ['id', 'descripcion', 'fechadocsire', 'cc', 'pre', 'importepago', 'estado'];

    $query = "SELECT * FROM vw_mov_rendicion_cab WHERE idrendicion = :idrendicion ";

    // 🔍 Filtro de búsqueda
    $busqueda = $_POST["search"]["value"] ?? '';
    if (!empty($busqueda)) {
        $query .= "AND (descripcion LIKE :busqueda OR fechadocsire LIKE :busqueda) ";
    }

    // 🔽 Ordenamiento seguro
    if (isset($_POST['order']) && is_array($_POST['order']) && !empty($_POST['order'][0])) {
        $columnaIdx = $_POST['order'][0]['column'] ?? 0;
        $columna = $columns[$columnaIdx] ?? 'fechadocsire';
        $direccion = ($_POST['order'][0]['dir'] ?? 'DESC') === 'desc' ? 'DESC' : 'ASC';
        $query .= "ORDER BY $columna $direccion ";
    } else {
        $query .= "ORDER BY fechadocsire DESC ";
    }

    // 📄 Paginación segura
    $start = isset($_POST['start']) ? intval($_POST['start']) : 0;
    $length = isset($_POST['length']) ? intval($_POST['length']) : 10;
    if ($length != -1) {
        $query .= "LIMIT :start, :length";
    }

    $stmt = $connect->prepare($query);
    $stmt->bindValue(':idrendicion', $idrendicion, PDO::PARAM_INT);

    if (!empty($busqueda)) {
        $stmt->bindValue(':busqueda', "%$busqueda%", PDO::PARAM_STR);
    }

    if ($length != -1) {
        $stmt->bindValue(':start', $start, PDO::PARAM_INT);
        $stmt->bindValue(':length', $length, PDO::PARAM_INT);
    }

    $stmt->execute();
    $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $filtered_rows = count($resultado);

    $datos = [];
    foreach ($resultado as $fila) {
       
        $documento = $fila["seriedoc"]."-".$fila["numdoc"];
        $sub_array = [];
        $sub_array[] = $fila["id"];
$sub_array[] = $fila["razonemisor"];
$sub_array[] = $fila["descripcion"];
$sub_array[] = $fila["tipodoc"];
$sub_array[] = $documento;
        
        $sub_array[] = $fila["fechadocsire"];
        $sub_array[] = $fila["cc"];
        $sub_array[] = $fila["pre"];
        $sub_array[] = $fila["importepago"];
        $sub_array[] = '<button type="button" name="deletecab" id="'.$fila["id"].'" 
                         data-documento ="'.$documento.'" 
                         data-movkey    ="'.$fila["movkey"].'" 
                         data-pago      ="'.$fila["importepago"].'" class="btn btn-danger btn-xs deletecab"><i class="fas fa-trash"></i></button>';
        $datos[] = $sub_array;
    }

    // 🧮 Total de registros sin filtro
    $total = obtener_todos_rendiciones_cab($idrendicion, $connect);

    $salida = [
        "draw"            => intval($_POST["draw"] ?? 0),
        "recordsTotal"    => $total,
        "recordsFiltered" => $filtered_rows,
        "data"            => $datos
    ];

    echo json_encode($salida);
    break;*/

    case 'rendicion_cab':
    $idrendicion = $_GET['idrendicion'] ?? null;
    $columns = ['id', 'descripcion', 'fechadocsire', 'cc', 'pre', 'importepago', 'estado'];

    // Consulta base
    $queryBase = "FROM vw_mov_rendicion_cab WHERE idrendicion = :idrendicion ";

    // 🔍 Filtro de búsqueda
    $busqueda = $_POST["search"]["value"] ?? '';
    if (!empty($busqueda)) {
        $queryBase .= "AND (descripcion LIKE :busqueda OR fechadocsire LIKE :busqueda) ";
    }

    // 🔽 Ordenamiento seguro
    $columnaIdx = $_POST['order'][0]['column'] ?? 0;
    $columna = $columns[$columnaIdx] ?? 'fechadocsire';
    $direccion = (($_POST['order'][0]['dir'] ?? 'desc') === 'desc') ? 'DESC' : 'ASC';

    // 📄 Paginación segura
    $start = isset($_POST['start']) ? intval($_POST['start']) : 0;
    $length = isset($_POST['length']) ? intval($_POST['length']) : 10;

    // 🔢 Contar total sin filtro
    $stmtTotal = $connect->prepare("SELECT COUNT(*) $queryBase");
    $stmtTotal->bindValue(':idrendicion', $idrendicion, PDO::PARAM_INT);
    if (!empty($busqueda)) $stmtTotal->bindValue(':busqueda', "%$busqueda%", PDO::PARAM_STR);
    $stmtTotal->execute();
    $recordsFiltered = $stmtTotal->fetchColumn();

    // 🔢 Total general (sin filtros)
    $stmtAll = $connect->prepare("SELECT COUNT(*) FROM vw_mov_rendicion_cab WHERE idrendicion = :idrendicion");
    $stmtAll->bindValue(':idrendicion', $idrendicion, PDO::PARAM_INT);
    $stmtAll->execute();
    $recordsTotal = $stmtAll->fetchColumn();

    // 🚀 Traer los registros de la página actual
    $queryData = "SELECT * $queryBase ORDER BY $columna $direccion ";
    if ($length != -1) {
        $queryData .= "LIMIT :start, :length";
    }

    $stmt = $connect->prepare($queryData);
    $stmt->bindValue(':idrendicion', $idrendicion, PDO::PARAM_INT);
    if (!empty($busqueda)) $stmt->bindValue(':busqueda', "%$busqueda%", PDO::PARAM_STR);
    if ($length != -1) {
        $stmt->bindValue(':start', $start, PDO::PARAM_INT);
        $stmt->bindValue(':length', $length, PDO::PARAM_INT);
    }
    $stmt->execute();
    $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 🧩 Construir filas para DataTables
    $datos = [];
    foreach ($resultado as $fila) {
        $documento = $fila["seriedoc"] . "-" . $fila["numdoc"];
        $sub_array = [];
        $sub_array[] = $fila["id"];
        $sub_array[] = $fila["razonemisor"];
        $sub_array[] = $fila["descripcion"];
        $sub_array[] = $fila["tipodoc"];
        $sub_array[] = $documento;
        $sub_array[] = $fila["fechadocsire"];
        $sub_array[] = $fila["cc"];
        $sub_array[] = $fila["pre"];
        $sub_array[] = $fila["importepago"];
        $sub_array[] = '<button type="button" name="deletecab" id="' . $fila["id"] . '" 
                            data-documento="' . $documento . '" 
                            data-movkey="' . $fila["movkey"] . '" 
                            data-pago="' . $fila["importepago"] . '" 
                            class="btn btn-danger btn-xs deletecab"><i class="fas fa-trash"></i></button>';
        $datos[] = $sub_array;
    }

    // 🧮 Enviar respuesta a DataTables
    $salida = [
        "draw"            => intval($_POST["draw"] ?? 0),
        "recordsTotal"    => intval($recordsTotal),
        "recordsFiltered" => intval($recordsFiltered),
        "data"            => $datos
    ];

    echo json_encode($salida);
    break;



    
    case 'centrocostos':
        $cliente = $_GET['cliente'];
        $query = "SELECT * FROM tbl_centro_costo WHERE cliente = :cliente AND estado='ACTIVO' ORDER BY id ASC";
        $stmt = $connect->prepare($query);
        $stmt->bindValue(':cliente', $cliente, PDO::PARAM_INT);
        $stmt->execute();
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        break;
    
    case 'presupuestos':
        $cliente = $_GET['cliente'];
        $query = "SELECT * FROM tbl_presupuestos WHERE cliente = :cliente AND estado='ACTIVO' ORDER BY id ASC";
        $stmt = $connect->prepare($query);
        $stmt->bindValue(':cliente', $cliente, PDO::PARAM_INT);
        $stmt->execute();
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        break;

    // =====================================
    // CASE: CLIENTES (si luego lo usas)
    // =====================================
    case 'clientes':
        $query = "SELECT * FROM tbl_cliente_empresa WHERE idempresa = :idempresa ORDER BY ruc ASC";
        $stmt = $connect->prepare($query);
        $stmt->bindValue(':idempresa', $idempresa, PDO::PARAM_INT);
        $stmt->execute();
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        break;

    // =====================================
    // CASE: CENTRO DE COSTOS
    // =====================================
    case 'rendiciones':
        $clienteSeleccionado = $_GET['cliente'] ?? null;
        $idlocal = $_SESSION['local'];
        $columns = ['id', 'fecha', 'descripcion', 'cc','pre','importe'];
        $query = "SELECT * FROM mov_rendicion WHERE idcliente = :idempresa AND idlocal=:idlocal ";

        // 🔍 Filtro de búsqueda
        if (!empty($_POST["search"]["value"])) {
            $busqueda = trim($_POST["search"]["value"]);
            $query .= "AND (nombre LIKE :busqueda OR descripcion LIKE :busqueda) ";
        }

        // 🔽 Ordenamiento
        if (isset($_POST["order"])) {
            $columna = $columns[$_POST['order'][0]['column']] ?? 'id';
            $direccion = ($_POST["order"][0]["dir"] === 'desc') ? 'DESC' : 'ASC';
            $query .= "ORDER BY $columna $direccion ";
        } else {
            $query .= "ORDER BY id DESC ";
        }

        // 📄 Paginación
        if ($_POST["length"] != -1) {
            $query .= "LIMIT :start, :length";
        }

        $stmt = $connect->prepare($query);
       $stmt->bindValue(':idempresa', $clienteSeleccionado, PDO::PARAM_INT);
        $stmt->bindValue(':idlocal', $idlocal, PDO::PARAM_INT);

        if (!empty($_POST["search"]["value"])) {
            $stmt->bindValue(':busqueda', "%$busqueda%", PDO::PARAM_STR);
        }

        if ($_POST["length"] != -1) {
            $stmt->bindValue(':start', intval($_POST["start"]), PDO::PARAM_INT);
            $stmt->bindValue(':length', intval($_POST["length"]), PDO::PARAM_INT);
        }

        $stmt->execute();
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $filtered_rows = $stmt->rowCount();

        $datos = [];
        foreach ($resultado as $fila) 
        {
            $estado = $fila["estado"];
            
            if($estado =='ACTIVO')
            {
                $color = 'success';
            }
            else
            {
                $color = 'danger';
            }
            
            $sub_array = [];
            $sub_array[] = $fila["id"];
            $sub_array[] = $fila["descripcion"];
            $sub_array[] = $fila["fecharendicion"];
            $sub_array[] = $fila["cc"];
            $sub_array[] = $fila["pre"];
            $sub_array[] = $fila["importe"];
            $sub_array[] = '<span class="badge bg-'.$color.' btn-xs editar">'.$fila["estado"].'</span>';
            $sub_array[] = '  
                    <div class="dropdown">
                      <button class="btn btn-dark dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Acciones
                      </button>
                      <ul class="dropdown-menu">
                        <li><a href="'.base_url().'rendicion_cab/'.$fila["id"].'"  class="dropdown-item detalle">Detalle</a></li>
                         <li><a href="'.base_url().'reporte_rendiciones/'.$fila["id"].'"  class="dropdown-item detalle2">Reporte</a></li>
                        <li><button type="button" name="borrar" id="'.$fila["id"].'" class="dropdown-item editar">Editar</button></li>
                        <li><button type="button" name="borrar" id="'.$fila["id"].'" class="dropdown-item borrar">Borrar</button></li>
                      </ul>
                    </div>';
    



            $datos[] = $sub_array;
        }

        // 🧮 Total de registros sin filtro
        $total = obtener_todos_rendiciones($clienteSeleccionado, $connect);

        $salida = [
            "draw"            => intval($_POST["draw"]),
            "recordsTotal"    => $total,
            "recordsFiltered" => $filtered_rows,
            "data"            => $datos
        ];

        echo json_encode($salida);
        break;
        
        
    case 'guardar':
    $mensaje = array();
    
    if($_POST['operacion']=='Crear')
    {
        
        $idlocal = $_SESSION["local"];
        $stmt = $connect->prepare("INSERT INTO mov_rendicion(idcliente,fecharendicion,descripcion,cc,pre,importe,idlocal) VALUES(:idcliente, 
                   :fecharendicion,
                   :descripcion,
                   :cc,
                   :pre,
                   :importe,
                   :idlocal)");

    $resultado = $stmt->execute(
        array(
            ':idcliente'         => $_GET["cliente"],
            ':fecharendicion'    => $_POST["fecha"],
            ':descripcion'       => $_POST["nombre"],
            ':cc'               => $_POST["cc"],
            ':pre'               => $_POST["pre"],
            ':importe'           => $_POST["importe"],
            ':idlocal'           => $idlocal
           
        )
    );

        if (!empty($resultado)) 
        {
            $mensaje['respuesta'] = 'Registro creado correctamente';
        } 
        else 
        {
            $mensaje['respuesta'] = 'Error al registrar';
        }

    }
    else
    {
       
        $stmt = $connect->prepare("UPDATE mov_rendicion SET 
                                   fecharendicion=:fecharendicion,
                                   descripcion=:descripcion,
                                   cc=:cc,
                                   pre=:pre,
                                   importe=:importe 
                                   WHERE id = :id");

    
       $resultado = $stmt->execute(
        array(
            ':fecharendicion'       => $_POST["fecha"],
            ':descripcion'          => $_POST["nombre"],
            ':cc'                   => $_POST["cc"],
            ':pre'                  => $_POST["pre"],
            ':importe'              => $_POST["importe"],
            ':id'                   => $_POST["id_usuario"]
        )
    );

     if (!empty($resultado)) 
        {
            $mensaje['respuesta'] = 'Registro Actualizado correctamente';
        } 
        else 
        {
            $mensaje['respuesta'] = 'Error al Actualizar';
        }
        
    }
    
            
        echo json_encode($mensaje);
        
    break;
    
    
    case 'buscar':
    
    $salida = array();
    $stmt = $connect->prepare("SELECT * FROM mov_rendicion WHERE id = '".$_POST["id_usuario"]."' LIMIT 1");
    $stmt->execute();
    $resultado = $stmt->fetchAll();
    foreach($resultado as $fila){
        $salida["fecharendicion"] = $fila["fecharendicion"];
        $salida["descripcion"]    = $fila["descripcion"];
        $salida["cc"]             = $fila["cc"];
        $salida["pre"]            = $fila["pre"];
        $salida["importe"]        = $fila["importe"];
    }

    echo json_encode($salida);    
        
        
    break;
    
    case 'eliminar':
    $mensaje = array();    

    $stmt = $connect->prepare("SELECT estado FROM mov_rendicion WHERE id = :id LIMIT 1");
    $stmt->execute([':id' => $_POST["id_usuario"]]);
    $fila = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($fila) {
        $estado = ($fila['estado'] == 'ACTIVO') ? 'INACTIVO' : 'ACTIVO';

        $stmt = $connect->prepare("UPDATE mov_rendicion SET estado = :estado WHERE id = :id");
        $resultado = $stmt->execute([
            ':estado' => $estado,
            ':id' => $_POST["id_usuario"]
        ]);

        if ($resultado) {
            $mensaje['respuesta'] = "Registro actualizado correctamente a estado $estado";
        } else {
            $mensaje['respuesta'] = 'Error al actualizar el registro';
        }
    } else {
        $mensaje['respuesta'] = 'Registro no encontrado';
    }

    echo json_encode($mensaje);
    break;
    
    
    case 'reporte_excel':
    $idrendicion = $_POST['idrendicion'] ?? 0;
    $idcliente   = $_POST['idcliente'] ?? 0;
    
    $query_empresa = $connect->prepare("SELECT * FROM tbl_cliente_empresa WHERE id = $idcliente");
    $query_empresa->execute();
    $row_config=$query_empresa->fetch(PDO::FETCH_ASSOC);
    
    $razempresa = $row_config['razon'];
    $rucempresa = $row_config['ruc'];

    // 🔸 Obtener la fecha de la rendición (puede venir de otra tabla si existe)
    $stmtFecha = $connect->prepare("SELECT fecharendicion FROM mov_rendicion WHERE id = :id");
    $stmtFecha->bindValue(':id', $idrendicion, PDO::PARAM_INT);
    $stmtFecha->execute();
    $fecha = $stmtFecha->fetchColumn();
    if (!$fecha) $fecha = date('Y-m-d');

    // 🔸 Conceptos de venta (tipo = 'V')
    $stmt1 = $connect->prepare("
        SELECT c.nombre, SUM(r.importepago) AS total
        FROM mov_rendicion_cab r
        LEFT JOIN tbl_concepto_gasto c ON r.idconceptogasto = c.id
        WHERE r.idrendicion = :id AND r.tipo = 'V'
        GROUP BY c.nombre
    ");
    $stmt1->bindValue(':id', $idrendicion, PDO::PARAM_INT);
    $stmt1->execute();
    $conceptos = $stmt1->fetchAll(PDO::FETCH_ASSOC);

    // 🔸 Detalles de gasto (tipo = 'G')
    $stmt2 = $connect->prepare("
        SELECT glosa, iddocumento, importepago
        FROM mov_rendicion_cab
        WHERE idrendicion = :id AND tipo = 'G'
    ");
    $stmt2->bindValue(':id', $idrendicion, PDO::PARAM_INT);
    $stmt2->execute();
    $detalles = $stmt2->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "razempresa"  => $razempresa,
        "rucempresa"  => $rucempresa,
        "fecha"       => $fecha,
        "conceptos"   => $conceptos,
        "detalles"    => $detalles
    ]);
    break;

    case 'guardaroth':
    // Variables recibidas
    $tipoDoc = $_POST['docoth'];
    $moneda = $_POST['monedaoth'];
    $serieNroDoc = $_POST['tdocoth'] . "-" . $_POST['ndocoth'];
    $idrendicion = $_GET['idrendicion'];

    // Obtener datos del cliente desde la rendición
    $query_data = "SELECT idcliente FROM mov_rendicion WHERE id = ?";
    $stmt = $connect->prepare($query_data);
    $stmt->execute([$idrendicion]);
    $row_empresa = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row_empresa) {
        echo json_encode(['status' => 'error', 'msg' => 'No se encontró la rendición.']);
        break;
    }

    $cliente_id = $row_empresa['idcliente'];

    // Generar clave única
    $movkey = $_POST['rucoth'] . '-' . $tipoDoc . '-' . $serieNroDoc;

    // Verificar duplicado
    $query = "SELECT COUNT(*) FROM mov_compras WHERE movkey = ?";
    $stmt = $connect->prepare($query);
    $stmt->execute([$movkey]);
    $existe = $stmt->fetchColumn();

    // Datos comunes
    $fecEmision = $_POST['fechaoth'];
    $periodo = date('Ym', strtotime($fecEmision)); // Ej: 202511
    $rucemisor = $_POST['rucoth'];
    $razonemisor = $_POST['razonoth'];
    $tipdoc = $tipoDoc;
    $seriedoc = $_POST['tdocoth'];
    $numdoc = $_POST['ndocoth'];
    $tcambio = $_POST['tcambiooth'];
    $glosa = $_POST['glosaoth'];

    // Valores monetarios
    $baseimp = floatval($_POST['baseimpoth']);
    $igv = floatval($_POST['igvoth']);
    $basenograv = floatval($_POST['baseimpoth']);
    $total = floatval($_POST['totaloth']);

    // Si el documento no tiene IGV
    if ($igv == 0) {
        $baseimp = 0;
        $basenograv = $total;
        $total = $total;
    }

    if ($existe == 0) {
        // Insertar nuevo registro
        $sql = "INSERT INTO mov_compras 
                (movkey, periodouso, rucemisor, razonemisor, tipdoc, seriedoc, numdoc, 
                 fechadocsire, totaldocsire, moneda, idcliente, basedocsire, igvdocsire, 
                 glosasire, tcambiosire, nogravado) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
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
            $baseimp,
            $igv,
            $glosa,
            $tcambio,
            $basenograv
        ]);

        echo json_encode(['status' => 'ok', 'msg' => 'Registro guardado correctamente']);
    } else {
        echo json_encode(['status' => 'error', 'msg' => 'El documento ya existe en mov_compras']);
    }

    break;
    
    
    case 'reporte':

    // ======== PARÁMETROS DE DATATABLES ========
    $draw   = isset($_POST['draw'])   ? intval($_POST['draw'])   : 0;
    $start  = isset($_POST['start'])  ? intval($_POST['start'])  : 0;
    $length = isset($_POST['length']) ? intval($_POST['length']) : 10;

    // ======== FILTROS ADICIONALES ========
    $idcliente = isset($_POST['cliente']) && $_POST['cliente'] != '' ? intval($_POST['cliente']) : 0;
    $fechai    = isset($_POST['fechai']) && $_POST['fechai'] != '' ? $_POST['fechai'] : null;
    $fechaf    = isset($_POST['fechaf']) && $_POST['fechaf'] != '' ? $_POST['fechaf'] : null;

    // ======== VALIDACIÓN DE CLIENTE ========
    if ($idcliente == 0) {
        $response = [
            "draw" => $draw,
            "recordsTotal" => 0,
            "recordsFiltered" => 0,
            "data" => []
        ];
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        break;
    }

    // ======== CONSTRUCCIÓN DE CONSULTA ========
    $where = " WHERE 1=1 ";

    if ($idcliente) {
        $where .= " AND r.idcliente = :idcliente ";
    }

    if ($fechai && $fechaf) {
        $where .= " AND y.fecharendicion BETWEEN :fechai AND :fechaf ";
    } elseif ($fechai) {
        $where .= " AND y.fecharendicion >= :fechai ";
    } elseif ($fechaf) {
        $where .= " AND y.fecharendicion <= :fechaf ";
    }

    // ======== CONTAR TOTAL DE REGISTROS ========
    $count_sql = "
        SELECT COUNT(*)
        FROM mov_rendicion_cab r
        LEFT JOIN mov_compras c ON r.iddocumento = c.movkey
        LEFT JOIN mov_rendicion y ON r.idrendicion = y.id
        $where
    ";

    $stmt_count = $connect->prepare($count_sql);
    if ($idcliente) $stmt_count->bindParam(':idcliente', $idcliente, PDO::PARAM_INT);
    if ($fechai)    $stmt_count->bindParam(':fechai', $fechai);
    if ($fechaf)    $stmt_count->bindParam(':fechaf', $fechaf);
    $stmt_count->execute();
    $recordsTotal = $stmt_count->fetchColumn();

    // ======== CONSULTA PRINCIPAL ========
    $sql = "
        SELECT
            r.id,
            r.idrendicion,
            r.idcliente,
            c.movkey,
            c.rucemisor,
            c.razonemisor,
            c.tipdoc,
            c.seriedoc,
            c.numdoc,
            c.fechadocsire,
            c.totaldocsire,
            c.importe_pago,
            (c.totaldocsire - c.importe_pago) AS saldo,
            r.glosa AS descripcion,
            c.estado,
            x.nombre AS cc,
            IFNULL(p.nombre, '') AS pre,
            r.importepago,
            IFNULL(g.nombre, '') AS nombre_gasto,
            r.condicion,
            y.fecharendicion
        FROM mov_rendicion_cab r
        LEFT JOIN mov_compras c ON r.iddocumento = c.movkey
        LEFT JOIN tbl_centro_costo x ON r.cc = x.id
        LEFT JOIN tbl_presupuestos p ON r.pre = p.id
        LEFT JOIN tbl_concepto_gasto g ON r.idconceptogasto = g.id
        LEFT JOIN mov_rendicion y ON r.idrendicion = y.id
        $where
        ORDER BY c.fechadocsire DESC
        LIMIT :start, :length
    ";

    $stmt = $connect->prepare($sql);

    // ======== ENLACE DE PARÁMETROS ========
    if ($idcliente) $stmt->bindParam(':idcliente', $idcliente, PDO::PARAM_INT);
    if ($fechai)    $stmt->bindParam(':fechai', $fechai);
    if ($fechaf)    $stmt->bindParam(':fechaf', $fechaf);
    $stmt->bindParam(':start', $start, PDO::PARAM_INT);
    $stmt->bindParam(':length', $length, PDO::PARAM_INT);
    $stmt->execute();

    // ======== PROCESAMIENTO DE DATOS ========
    $data = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // Procesar movkey (si aplica)
        $movkey = $row['movkey'];
        $parts = explode('-', $movkey);
        $tipo = isset($parts[1]) ? $parts[1] : $row['tipdoc'];
        $serie = isset($parts[2]) ? $parts[2] : $row['seriedoc'];
        $numero = isset($parts[3]) ? $parts[3] : $row['numdoc'];

        // Construir fila (usa claves para DataTables)
        $data[] = [
            "id" => $row['id'],
            "razonemisor" => htmlspecialchars($row['razonemisor']),
            "descripcion" => htmlspecialchars($row['descripcion']),
            "movkey" => htmlspecialchars($movkey),
            "fechadocsire" => htmlspecialchars($row['fechadocsire']),
            "cc" => htmlspecialchars($row['cc']),
            "pre" => htmlspecialchars($row['pre']),
            "condicion" => htmlspecialchars($row['condicion'])
        ];
    }

    // ======== RESPUESTA A DATATABLES ========
    $response = [
        "draw" => $draw,
        "recordsTotal" => intval($recordsTotal),
        "recordsFiltered" => intval($recordsTotal),
        "data" => $data
    ];

    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    break;



    
    default:
        echo json_encode(['error' => 'Operación no válida']);
        break;
}
?>
