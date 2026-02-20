<?php
require_once("config/config.php");
require_once("libraries/conexion.php");

function describeTable($conn, $tableName)
{
    echo "Table: $tableName\n";
    $stmt = $conn->prepare("DESCRIBE $tableName");
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($rows as $row) {
        echo $row['Field'] . " | " . $row['Type'] . "\n";
    }
    echo "\n";
}

try {
    describeTable($connect, 'mov_compras');
    describeTable($connect, 'tbl_bancos');
    describeTable($connect, 'tbl_cliente_empresa');
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
