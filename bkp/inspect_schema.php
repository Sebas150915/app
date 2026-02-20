<?php
require_once 'config/config.php';

try {
    // Fix charset issue
    $dsn = "mysql:host=" . BD_HOST . ";dbname=" . BD_NAME . ";charset=utf8";
    $pdo = new PDO($dsn, BD_USER, BD_PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Connected successfully to " . BD_NAME . "\n";

    $tables = ['tbl_empresas', 'tbl_clienteempresas', 'tbl_movimientos_banco'];

    foreach ($tables as $table) {
        echo "\n--- Schema for $table ---\n";
        try {
            $stmt = $pdo->query("DESCRIBE $table");
            $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($columns as $col) {
                echo $col['Field'] . " (" . $col['Type'] . ")\n";
            }
        } catch (PDOException $e) {
            echo "Table $table not found or error: " . $e->getMessage() . "\n";
        }
    }
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
