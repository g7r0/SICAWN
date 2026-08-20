<?php
/**
 * config/conexion.php
 * Conexión a la base de datos MySQL del SICAWN usando PDO.
 */

// --- Datos de conexión (ajustar según el entorno) ---
define('DB_HOST', 'localhost');
define('DB_PORT', '3306');
define('DB_NAME', 'sicawn_db');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

function conectarDB(): PDO {
    static $pdo = null;

    if ($pdo === null) {
        $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;

        $opciones = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $opciones);
        } catch (PDOException $e) {
            // En producción esto debe loguearse, no mostrarse tal cual al usuario
            die('Error de conexión a la base de datos: ' . $e->getMessage());
        }
    }

    return $pdo;
}
