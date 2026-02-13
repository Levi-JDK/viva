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

// Verificar si es una petición POST y hay archivos
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['imagen_producto'])) {
    
    $target_directory = __DIR__ . '/../../images/products/';
    
    $uploaded_paths = [];
    $errors = [];
    
    // Normalizar estructura de $_FILES (PHP lo entrega agrupado por propiedades)
    $files = $_FILES['imagen_producto'];
    $count = is_array($files['name']) ? count($files['name']) : 1;

    // Si es un solo archivo (caso borde o legacy), lo tratamos como array de 1
    if (!is_array($files['name'])) {
        $files = [
            'name' => [$files['name']],
            'type' => [$files['type']],
            'tmp_name' => [$files['tmp_name']],
            'error' => [$files['error']],
            'size' => [$files['size']]
        ];
    }

    for ($i = 0; $i < $count; $i++) {
        // Construir array de archivo individual para handleImageUpload
        $current_file = [
            'name' => $files['name'][$i],
            'type' => $files['type'][$i],
            'tmp_name' => $files['tmp_name'][$i],
            'error' => $files['error'][$i],
            'size' => $files['size'][$i]
        ];

        // Ignorar archivos vacíos o con error de subida inicial
        if ($current_file['error'] !== UPLOAD_ERR_OK) {
            $errors[] = "Error al subir {$current_file['name']}";
            continue;
        }

        // Procesar imagen
        $result = handleImageUpload($current_file, $target_directory, 'prod_', 'images/products/');
        
        if ($result['success']) {
            $uploaded_paths[] = [
                'path' => $result['path'],
                'filename' => $result['filename']
            ];
        } else {
            $errors[] = "{$current_file['name']}: {$result['message']}";
        }
    }
    
    // Respuesta
    header('Content-Type: application/json');
    
    if (!empty($uploaded_paths)) {
        // Al menos una imagen se subió
        echo json_encode([
            'success' => true,
            'message' => 'Imágenes procesadas',
            'uploaded' => $uploaded_paths,
            'errors' => $errors
        ]);
    } else {
        // Ninguna imagen se pudo subir
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'No se pudieron subir las imágenes',
            'errors' => $errors
        ]);
    }
    exit;
} else {
    // Acceso directo no permitido
    header('Location: ' . BASE_URL . 'micuenta');
    exit;
}
?>
