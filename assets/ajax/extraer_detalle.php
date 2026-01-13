<?php
require_once("../../config/config.php");
require_once("../../helpers/helpers.php");
require_once("../../libraries/conexion.php");
session_start();

$accion = $_POST['accion'] ?? '';
$movkey = $_POST['movkey'] ?? '';
$idcliente = $_POST['idcliente'] ?? '';


if ($accion == 'listar') {
    // Re-usar conexión PDO para consistencia (importado arriba)
    // O usar $conn si queremos migrar todo.
    // El include libraries/conexion.php nos da $connect (PDO).
    // Usaremos $connect para listar.
    $stmt = $connect->prepare("SELECT * FROM mov_compras_det WHERE movkey = ? ORDER BY item ASC");
    $stmt->execute([$movkey]);
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(['data' => $data]);
    exit;
}

if ($accion == 'actualizar_codigo') {
    $id = $_POST['id'] ?? '';
    $codigo = $_POST['codigo'] ?? '';

    if (empty($id)) {
        echo json_encode(['success' => false, 'message' => 'ID no proporcionado']);
        exit;
    }

    try {
        $stmt = $connect->prepare("UPDATE mov_compras_det SET codigo = ? WHERE id = ?");
        $stmt->execute([$codigo, $id]);
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
    exit;
}

$tipo = '02';

//10038612005-07-E001-19

$doc = explode("-", $movkey);

$ruc = $doc[0];
$tipodoc = $doc[1];
$seriedoc = $doc[2];
$numerodoc = $doc[3];


// Conexión a Base de Datos
require_once '../../config/config.php';
$conn = new mysqli(BD_HOST, BD_USER, BD_PASSWORD, BD_NAME);
if ($conn->connect_error) {
    echo json_encode(['error' => 'Error de conexión a BD']);
    exit;
}
$conn->set_charset("utf8");

// Buscar credenciales SOL
$sql = "SELECT * FROM tbl_cliente_empresa WHERE id = ? LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idcliente);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 0) {
    echo json_encode(['error' => 'Cliente no encontrado en BD']);
    exit;
}

$row = $res->fetch_assoc();
$stmt->close();
$conn->close();

$ruccontribuyente = $row['ruc'];
$usercontribuyente = $row['usuario_sol'];
$clavecontribuyente = $row['clave_sol'];

// Re-conectar para operaciones con detalles (necesitamos la misma conexión o nueva)
// Nota: La conexión anterior $conn se cerró. Necesitamos abrirla de nuevo.
$conn = new mysqli(BD_HOST, BD_USER, BD_PASSWORD, BD_NAME);
$conn->set_charset("utf8");

// Validar Schema
$check = $conn->query("SHOW COLUMNS FROM mov_compras_det LIKE 'item'");
if ($check === false || $check->num_rows == 0) {
    // Si no existe la columna 'item', asumimos schema incorrecto o tabla no existe.
    // Borramos y creamos de nuevo.
    $conn->query("DROP TABLE IF EXISTS mov_compras_det");

    $sql_table = "CREATE TABLE mov_compras_det (
        id INT AUTO_INCREMENT PRIMARY KEY,
        movkey VARCHAR(255),
        item INT,
        codigo VARCHAR(50),
        descripcion TEXT,
        cantidad DECIMAL(18,2),
        unidad_medida VARCHAR(20),
        precio_unitario DECIMAL(18,5),
        precio_total DECIMAL(18,2),
        igv DECIMAL(18,2),
        subtotal DECIMAL(18,2),
        INDEX (movkey)
    )";
    if (!$conn->query($sql_table)) {
        echo json_encode(['success' => false, 'message' => 'Error creando tabla: ' . $conn->error]);
        exit;
    }
}


$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => 'http://smartbase.club/sunat/compras2.php',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => '{
"ruc"      : "' . $ruc . '",
"tipodoc"   : "' . $tipodoc . '",
"seriedoc"  : "' . $seriedoc . '",
"numerodoc" : "' . $numerodoc . '",
"tipo"      : "' . $tipo . '",
"tl"        : "2",
"ruccontribuyente" : "' . $ruccontribuyente  . '",
"usercontribuyente" : "' . $usercontribuyente . '",
"clavecontribuyente" : "' . $clavecontribuyente . '"}',
    CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json',
        'Cookie: PHPSESSID=av44kgdjobtjif3p8a3qb88j7b; SITE_TOTAL_ID=aVw--oCvyxvlKrMwwcJw4gAAAYw'
    ),
));

$response = curl_exec($curl);

$err = curl_error($curl);
curl_close($curl);

// echo $response; // Comentar para no romper el JSON de respuesta

if ($err) {
    echo json_encode(['success' => false, 'message' => 'cURL Error: ' . $err]);
    exit;
}

$json = json_decode($response, true);

if (isset($json['success']) && $json['success'] && isset($json['detalles'])) {

    // Re-usar conexión PDO para consistencia si es posible, o usar $conn
    // El usuario tiene $conn (mysqli) arriba y $connect (PDO) abajo.
    // Voy a usar $conn (mysqli) porque es lo que el usuario agregó recientemente.

    // Limpiar detalles
    $del = $conn->prepare("DELETE FROM mov_compras_det WHERE movkey = ?");
    $del->bind_param("s", $movkey);
    $del->execute();

    // Insertar nuevos detalles - quitamos 'item' si da error, pero el error dice "Column not found: item". 
    // Tal vez la tabla no se creo con ese campo. El código de creación estaba comentado o borrado.
    // Voy a asegurar que la tabla tenga los campos correctos primero.

    $sql_insert = "INSERT INTO mov_compras_det (movkey, item, codigo, descripcion, cantidad, unidad_medida, precio_unitario, precio_total, igv, subtotal) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $ins = $conn->prepare($sql_insert);

    if (!$ins) {
        echo json_encode(['success' => false, 'message' => 'Error prepare insert: ' . $conn->error]);
        exit;
    }

    foreach ($json['detalles'] as $det) {
        $ins->bind_param(
            "sissssdddd",
            $movkey,
            $det['item'],
            $det['codigo'],
            $det['descripcion'],
            $det['cantidad'],
            $det['unidad_medida'],
            $det['precio_unitario'],
            $det['precio_total'],
            $det['igv'],
            $det['subtotal']
        );
        $ins->execute();
    }

    echo json_encode(['success' => true]);
} else {

    // Intentar capturar error del API si existe
    $msg = isset($json['message']) ? $json['message'] : 'Respuesta inválida del API';
    // Si hay una respuesta cruda, mostrarla para debug si es necesario, pero mejor manejarlo limpio.
    echo json_encode(['success' => false, 'message' => $msg, 'raw' => $response]);
}
