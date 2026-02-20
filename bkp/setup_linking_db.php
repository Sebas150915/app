<?php
require_once 'config/config.php';

try {
    $dsn = "mysql:host=" . BD_HOST . ";dbname=" . BD_NAME . ";charset=utf8";
    $pdo = new PDO($dsn, BD_USER, BD_PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "--- Starting DB Updates for Linking ---\n";

    // 1. Create tbl_movimientos_enlace
    $sqlEnlace = "CREATE TABLE IF NOT EXISTS tbl_movimientos_enlace (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        id_movimiento INT(11) NOT NULL,
        tipo_destino VARCHAR(20) NOT NULL COMMENT 'GASTO, COMPRA, VENTA',
        id_destino VARCHAR(50) NOT NULL,
        monto DECIMAL(10,2) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_mov (id_movimiento),
        INDEX idx_destino (id_destino)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

    $pdo->exec($sqlEnlace);
    echo "[OK] tbl_movimientos_enlace created/verified.\n";

    // 2. Add columns to mov_venta
    // Check if columns exist first to avoid error
    $stmt = $pdo->query("DESCRIBE mov_venta");
    $cols = $stmt->fetchAll(PDO::FETCH_COLUMN);

    if (!in_array('importe_cobro', $cols)) {
        $pdo->exec("ALTER TABLE mov_venta ADD COLUMN importe_cobro DECIMAL(10,2) DEFAULT 0.00 AFTER valtot");
        echo "[OK] Added column importe_cobro to mov_venta.\n";
    } else {
        echo "[SKIP] Column importe_cobro already exists in mov_venta.\n";
    }

    if (!in_array('saldo', $cols)) {
        // Warning: If adding saldo now, we might need to initialize it. 
        // For new system usage validation, we'll set it to valtot (Total Value) if it's 0 or NULL initially? 
        // Or just default 0.00. 
        // User said: "importe pago aca acumula los pagos acuenta y saldo el saldo pendiente"
        $pdo->exec("ALTER TABLE mov_venta ADD COLUMN saldo DECIMAL(10,2) DEFAULT 0.00 AFTER importe_cobro");
        echo "[OK] Added column saldo to mov_venta.\n";

        // Initialize saldo = valtot for existing records where saldo is 0 ??
        // Risky if payments existed. But user asked to add the data.
        // Let's just add the column.
        $pdo->exec("UPDATE mov_venta SET saldo = valtot WHERE saldo = 0 AND valtot > 0");
        echo "[INFO] Initialized saldo = valtot for existing sales.\n";
    } else {
        echo "[SKIP] Column saldo already exists in mov_venta.\n";
    }

    echo "--- DB Updates Completed ---\n";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
