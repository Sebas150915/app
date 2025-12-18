<?php
require_once 'config/config.php';
require_once 'assets/plugins/vendor/autoload.php';

use Smalot\PdfParser\Parser;

echo "--- Verification Start ---\n";

// 1. Check Parser
try {
    $parser = new Parser();
    echo "[OK] PDF Parser instantiated successfully.\n";
} catch (Throwable $e) {
    echo "[FAIL] PDF Parser instantiation failed: " . $e->getMessage() . "\n";
}

// 2. Check DB Table
try {
    $dsn = "mysql:host=" . BD_HOST . ";dbname=" . BD_NAME . ";charset=utf8";
    $pdo = new PDO($dsn, BD_USER, BD_PASSWORD);
    $stmt = $pdo->query("DESCRIBE tbl_movimientos_banco");
    $cols = $stmt->fetchAll(PDO::FETCH_COLUMN);

    $required = ['id_cliente', 'id_banco', 'fecha', 'glosa', 'importe'];
    $missing = array_diff($required, $cols);

    if (empty($missing)) {
        echo "[OK] tbl_movimientos_banco has required columns.\n";
    } else {
        echo "[FAIL] Missing columns: " . implode(', ', $missing) . "\n";
    }
} catch (PDOException $e) {
    echo "[FAIL] DB Check failed: " . $e->getMessage() . "\n";
}

echo "--- Verification End ---\n";
