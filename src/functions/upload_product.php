<?php
/**
 * Handler for Product Image Uploads
 * Reuses the generic image_uploader.php logic.
 */

require_once 'image_uploader.php';

// Cargar variables de entorno si es necesario (para DB, etc.)
require_once __DIR__ . '/../../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

// Detectar BASE_URL (copiado de upload.php para consistencia)
$protocolo = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https" : "http";
$host = $_SERVER['HTTP_HOST'];
$script_dir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
$proyecto_folder = str_replace('/src/functions', '', $script_dir);
$proyecto_folder = rtrim($proyecto_folder, '/');
if (!defined('BASE_URL')) {
    define('BASE_URL', $protocolo . "://" . $host . $proyecto_folder . "/");
}

// Verificar si es una petición POST y hay archivo
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['imagen_producto'])) {
    
    // Directorio para productos
    $target_directory = __DIR__ . '/../../images/products/';
    
    // USAR LA FUNCIÓN GENÉRICA
    // Prefijo 'prod_' para identificar imágenes de productos, y ruta relativa para BD 'images/products/'
    $result = handleImageUpload($_FILES['imagen_producto'], $target_directory, 'prod_', 'images/products/');
    
    if ($result['success']) {
        // Aquí iría la lógica para guardar en BD asociado a un producto
        // Por ahora, devolvemos JSON para que pueda ser consumido por AJAX en el dashboard
        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'message' => 'Imagen de producto subida correctamente',
            'path' => $result['path'], // Ruta relativa para guardar en BD
            'filename' => $result['filename']
        ]);
        exit;
    } else {
        // Error
        header('Content-Type: application/json');
        http_response_code(400); // Bad Request
        echo json_encode([
            'success' => false,
            'message' => $result['message']
        ]);
        exit;
    }
} else {
    // Acceso directo no permitido
    header('Location: ' . BASE_URL . 'micuenta'); // O donde corresponda
    exit;
}
?>
