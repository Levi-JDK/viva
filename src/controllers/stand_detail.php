<?php
// src/controllers/stand_detail.php
// Página de detalle de stand individual — muestra información completa de un stand específico

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../functions/Database.php';

try {
    // Obtener el ID del stand desde el parámetro de la URL
    // Formato de URL: /stand?id=id_productor
    if (!isset($_GET['id']) || empty($_GET['id'])) {
        // Redirigir a la página de prueba o mostrar error
        header('Location: ' . BASE_URL . 'test-stands');
        exit;
    }

    $id_productor = (int)$_GET['id'];
    
    $db = Database::getInstance();
    
    // Obtener información del stand
    $stmt = $db->ejecutar('obtenerStand', [':id_p' => $id_productor]);
    $stand = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Si el stand no existe, redirigir
    if (!$stand) {
        header('Location: ' . BASE_URL . 'test-stands');
        exit;
    }
    
    // Obtener productos de este stand (opcional — para mejora futura)
    // $stmtProductos = $db->ejecutar('obtenerProductos', [':id_productor' => $id_productor]);
    // $productos = $stmtProductos->fetchAll(PDO::FETCH_ASSOC);
    
    // Cargar la vista
    require_once ROOT_PATH . 'src/views/stand_detail.view.php';
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
    exit;
}
