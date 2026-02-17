<?php
// src/controllers/stand_detail.php
// Individual stand detail page - shows full information for a specific stand

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../functions/Database.php';

try {
    // Get stand ID from URL parameter
    // URL format: /stand?id=productor_id
    if (!isset($_GET['id']) || empty($_GET['id'])) {
        // Redirect to test page or show error
        header('Location: ' . BASE_URL . 'test-stands');
        exit;
    }

    $id_productor = (int)$_GET['id'];
    
    $db = Database::getInstance();
    
    // Get stand information
    $stmt = $db->ejecutar('obtenerStand', [':id_p' => $id_productor]);
    $stand = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // If stand doesn't exist, redirect
    if (!$stand) {
        header('Location: ' . BASE_URL . 'test-stands');
        exit;
    }
    
    // Get products from this stand (optional - for future enhancement)
    // $stmtProducts = $db->ejecutar('obtenerProductos', [':id_productor' => $id_productor]);
    // $products = $stmtProducts->fetchAll(PDO::FETCH_ASSOC);
    
    // Load the view
    require_once ROOT_PATH . 'src/views/stand_detail.view.php';
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
    exit;
}
