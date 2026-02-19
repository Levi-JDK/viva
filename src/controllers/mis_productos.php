<?php
// Dashboard de vendedor - Mis Productos
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params(0, '/');
    session_start();
}
// Verificar que el usuario esté autenticado
if (!isset($_SESSION['id_user'])) {
    header('Location: ' . BASE_URL . 'login');
    exit;
}
$id_user = $_SESSION['id_user'];
require_once(__DIR__ . '/../functions/Database.php');
try {
    $db = Database::getInstance();
    $params = [':id_user' => $id_user];
    $stmt = $db->ejecutar('validarProductor', $params);
    $es_productor = $stmt->fetchColumn();
    // Si NO es productor, redirigir al registro
    if (!$es_productor) {
        header('Location: ' . BASE_URL . 'registro_vendedor');
        exit;
    }
    $stmtUser = $db->ejecutar('obtenerUsuarioPorId', [':id' => $id_user]);
    $usuario = $stmtUser->fetch(PDO::FETCH_ASSOC);
    $nombre_usuario = $usuario['nom_user'] ?? 'Usuario';
    // Asegurar que foto_user tenga una ruta válida o usar la imagen por defecto
    $foto_usuario = !empty($usuario['foto_user']) ? $usuario['foto_user'] : 'images/default.jpg';
    
    // Obtener ID del productor (necesario para el inventario y otras secciones)
    $stmtProd = $db->ejecutar('obtenerIdProductor', [':id_user' => $id_user]);
    $id_productor = $stmtProd->fetchColumn();

    // Determinar qué sección cargar según el parámetro 'view'
    $view = $_GET['view'] ?? 'inventory';
    
    $allowed_views = [
        'inventory'     => 'inventory.php',
        'add_product'   => 'form_add_product.php',
        'stand'         => 'stand.php',
        'statistics'    => 'statistics.php',
        'configuration' => 'configuration.php'
    ];

    if (array_key_exists($view, $allowed_views)) {
        $current_controller = __DIR__ . '/mis_productos/' . $allowed_views[$view];
    } else {
        $current_controller = __DIR__ . '/mis_productos/inventory.php'; // Vista por defecto
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (file_exists($current_controller)) {
            require_once $current_controller;
            exit;
        }
    }

    require_once ROOT_PATH . "src/views/mis_productos.view.php";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit;
}
