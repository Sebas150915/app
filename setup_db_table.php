<?php
require_once 'config/config.php';

try {
    $dsn = "mysql:host=" . BD_HOST . ";dbname=" . BD_NAME . ";charset=utf8";
    $pdo = new PDO($dsn, BD_USER, BD_PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "CREATE TABLE IF NOT EXISTS tbl_movimientos_banco (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        id_cliente INT(11) NOT NULL,
        id_banco VARCHAR(50) NOT NULL,
        fecha DATE,
        glosa TEXT,
        i_s CHAR(1),
        importe DECIMAL(10,2),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_cliente (id_cliente),
        INDEX idx_fecha (fecha)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

    $pdo->exec($sql);
    echo "Table tbl_movimientos_banco created successfully.";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
