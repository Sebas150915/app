<?php
require_once("config/config.php");
require_once("libraries/conexion.php");

function describe($table, $conn)
{
    try {
        echo "Table: $table\n";
        $stmt = $conn->query("DESCRIBE $table");
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($columns as $col) {
            echo "  " . $col['Field'] . " (" . $col['Type'] . ")\n";
        }
        echo "\n";
    } catch (Exception $e) {
        echo "Error describing $table: " . $e->getMessage() . "\n";
    }
}

describe('tbl_categoria_laboral', $connect);
describe('tbl_tipo_contrato', $connect);
