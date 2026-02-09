<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "INICIO_TEST|"; // Marcador para ver si hay algo antes

try {
    require_once __DIR__ . '/Database.php';
    if (class_exists('Database')) {
        $db = Database::getInstance();
        echo "|CONEXION_OK";
    } else {
        echo "|CLASE_NO_ENCONTRADA";
    }
} catch (Throwable $e) {
    echo "|ERROR: " . $e->getMessage();
}

echo "|FIN_TEST";
?>
