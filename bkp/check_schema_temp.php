<?php
require_once "config/config.php";
require_once "libraries/conexion.php";

$sql = "DESCRIBE mov_compras_det";
$stmt = $connect->prepare($sql);
$stmt->execute();
$columns = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "Columns in mov_compras_det:\n";
foreach ($columns as $col) {
    echo $col['Field'] . " (" . $col['Type'] . ")\n";
}
