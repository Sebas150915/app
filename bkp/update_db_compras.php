<?php
require_once("config/config.php");
require_once("libraries/conexion.php");

try {
    // Add columns if they don't exist
    $columns = ['xml_descargado', 'cdr_descargado', 'pdf_descargado'];
    foreach ($columns as $col) {
        // Check if column exists
        $check = $connect->query("SHOW COLUMNS FROM mov_compras LIKE '$col'");
        if ($check->rowCount() == 0) {
            $sql = "ALTER TABLE mov_compras ADD COLUMN $col TINYINT(1) DEFAULT 0";
            $connect->exec($sql);
            echo "Column $col added successfully.\n";
        } else {
            echo "Column $col already exists.\n";
        }
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
