<?php
require_once "config/config.php";
require_once "libraries/conexion.php";

echo "Schema mov_compras:\n";
$stmt = $connect->query("DESCRIBE mov_compras");
foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $col) {
    if ($col['Field'] == 'movkey') echo $col['Field'] . " (" . $col['Type'] . ")\n";
}

echo "\nSchema mov_compras_det:\n";
$stmt = $connect->query("DESCRIBE mov_compras_det");
foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $col) {
    if ($col['Field'] == 'movkey') echo $col['Field'] . " (" . $col['Type'] . ")\n";
}

echo "\nSample mov_compras:\n";
$stmt = $connect->query("SELECT movkey FROM mov_compras LIMIT 5");
foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    echo $row['movkey'] . " (Len: " . strlen($row['movkey']) . ")\n";
}

echo "\nSample mov_compras_det:\n";
$stmt = $connect->query("SELECT movkey FROM mov_compras_det LIMIT 5");
foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    echo $row['movkey'] . " (Len: " . strlen($row['movkey']) . ")\n";
}
