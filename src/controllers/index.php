<?php
require_once ROOT_PATH . 'src/functions/Database.php';

// Obtener stands destacados para la sección de afiliados (máx 3)
$featured_stands = [];
try {
    $db = Database::getInstance();
    $stmt = $db->connection->prepare('SELECT * FROM tab_stand WHERE is_deleted = FALSE ORDER BY RANDOM() LIMIT 3');
    $stmt->execute();
    $featured_stands = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    error_log("Error al obtener stands destacados: " . $e->getMessage());
}

// Obtener productos destacados para el landing (máx 4)
$featured_products = [];
try {
    $stmt = $db->ejecutar('obtenerProductosDestacados', [':limit' => 4]);
    $featured_products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    error_log("Error al obtener productos destacados: " . $e->getMessage());
}

// Obtener categorías con contador de productos
$categorias_destacadas = [];
try {
    $stmt = $db->ejecutar('obtenerFiltrosCategorias');
    $categorias_destacadas = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    error_log("Error al obtener categorías destacadas: " . $e->getMessage());
}

// Usamos ROOT_PATH para que el include sea absoluto desde el disco
require_once ROOT_PATH . "src/views/index.view.php";
?>