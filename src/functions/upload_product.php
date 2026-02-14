<?php
/**
 * Handler for Product Uploads
 */

require_once 'image_uploader.php';
require_once __DIR__ . '/../../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();
require_once 'Database.php';

// Detectar BASE_URL
$protocolo = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https" : "http";
$host = $_SERVER['HTTP_HOST'];
$script_dir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
$proyecto_folder = str_replace('/src/functions', '', $script_dir);
$proyecto_folder = rtrim($proyecto_folder, '/');
if (!defined('BASE_URL')) {
    define('BASE_URL', $protocolo . "://" . $host . $proyecto_folder . "/");
}

header('Content-Type: application/json');

// 1. Session & Auth Check
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params(0, '/');
    session_start();
}

if (!isset($_SESSION['id_user'])) {
    echo json_encode(['success' => false, 'message' => 'Usuario no autenticado']);
    exit;
}

// 2. Main Logic
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $db = Database::getInstance();
        $conn = $db->connection;
        
        // --- Input Validation ---
        $nom_producto = $_POST['nom_producto'] ?? '';
        $precio = $_POST['precio_producto'] ?? 0;
        $stock = $_POST['stock_productor'] ?? 0;
        $id_categoria = $_POST['id_categoria'] ?? null;
        $id_color = $_POST['id_color'] ?? null;
        $id_oficio = $_POST['id_oficio'] ?? null;
        $id_materia = $_POST['id_materia'] ?? null;
        $desc = $_POST['desc_prod_personal'] ?? '';
        
        if (empty($nom_producto) || empty($id_categoria) || empty($id_color)) {
            throw new Exception("Faltan campos obligatorios.");
        }

        // --- Server-Side Validation ---
        
        // 1. Numeric Validation
        if (!is_numeric($precio) || $precio < 1) {
            throw new Exception("El precio debe ser un número válido mayor a 0.");
        }
        if (!is_numeric($stock) || $stock < 1) {
            throw new Exception("El stock debe ser un número válido mayor a 0.");
        }

        // 2. Text Sanitization & Validation (Prevent Special Characters)
        // Allow letters, numbers, spaces, and basic punctuation: . , - _
        if (!preg_match('/^[a-zA-Z0-9\s\.\,\-\_áéíóúÁÉÍÓÚñÑüÜ]+$/', $nom_producto)) {
            throw new Exception("El nombre del producto contiene caracteres no permitidos.");
        }
        
        if (!empty($desc) && !preg_match('/^[a-zA-Z0-9\s\.\,\-\_áéíóúÁÉÍÓÚñÑüÜ\(\)\!\?]+$/', $desc)) {
             throw new Exception("La descripción contiene caracteres no permitidos.");
        }

        // Sanitize for DB (though PDO binding handles most)
        $nom_producto = strip_tags($nom_producto);
        $desc = strip_tags($desc);

        // Get id_productor
        $stmtProd = $db->ejecutar('obtenerIdProductor', [':id_user' => $_SESSION['id_user']]);
        $id_productor = $stmtProd->fetchColumn();

        if (!$id_productor) {
            throw new Exception("No se encontró el perfil de productor.");
        }

        $conn->beginTransaction();

        // --- Generate ID ---
        // Simple generation strategy: timestamp + random (or sequence if DB supports it)
        // Since schema has DECIMAL(12,0), we need a number.
        // Let's use microtime-based ID to ensure uniqueness.
        $id_producto = (int)(microtime(true) * 100); 

        // --- Handle Images ---
        $uploaded_paths = [];
        $target_directory = __DIR__ . '/../../images/products/';
        
        if (isset($_FILES['imagen_producto'])) {
            $files = $_FILES['imagen_producto'];
            $count = is_array($files['name']) ? count($files['name']) : 1;

            if (!is_array($files['name'])) {
                // Normalize single file
                $files = [
                    'name' => [$files['name']],
                     'type' => [$files['type']],
                    'tmp_name' => [$files['tmp_name']],
                    'error' => [$files['error']],
                    'size' => [$files['size']]
                ];
            }

            for ($i = 0; $i < $count; $i++) {
                if ($files['error'][$i] !== UPLOAD_ERR_OK) continue;
                
                $current_file = [
                    'name' => $files['name'][$i],
                    'type' => $files['type'][$i],
                    'tmp_name' => $files['tmp_name'][$i],
                    'error' => $files['error'][$i],
                    'size' => $files['size'][$i]
                ];

                $result = handleImageUpload($current_file, $target_directory, 'prod_' . $id_producto . '_', 'images/products/');
                if ($result['success']) {
                    $uploaded_paths[] = $result['path']; // Store relative path
                } else {
                    // Log error but continue? Or fail? Let's fail if 0 images.
                }
            }
        }

        if (empty($uploaded_paths)) {
            // throw new Exception("Debe subir al menos una imagen válida.");
            // Allow for testing without images if needed, but requirements said max 4.
            // Requirement said "at least one" via JS. Backend should probably enforce.
             throw new Exception("Debe subir al menos una imagen válida.");
        }

        $main_image = $uploaded_paths[0]; // First image is main

        // --- Insert tab_productos ---
        $db->ejecutar('registrarProducto', [
            ':id_productor' => $id_productor,
            ':id_producto' => $id_producto,
            ':nom_producto' => $nom_producto,
            ':stock_productor' => $stock,
            ':id_categoria' => $id_categoria, // Ensuring int
            ':id_color' => $id_color,
            ':id_oficio' => $id_oficio,
            ':id_materia' => $id_materia,
            ':precio_producto' => $precio,
            ':descripcion_producto' => substr($desc, 0, 255) // Trucate if needed for varchar(255)
        ]);

        // --- Insert tab_producto_productor ---
        $db->ejecutar('registrarProductoProductor', [
            ':id_producto' => $id_producto,
            ':id_productor' => $id_productor,
            ':precio_prod' => $precio, // Assuming same price
            ':stock_prod' => $stock,
            ':desc_prod_personal' => $desc,
            ':img_personal' => $main_image,
            ':activo' => 'true'
        ]);

        // --- Insert tab_imagenes ---
        foreach ($uploaded_paths as $index => $path) {
            // id_imagen can be a simple counter for this product
            $id_imagen = $index + 1;
            $db->ejecutar('registrarImagen', [
                ':id_producto' => $id_producto,
                ':id_imagen' => $id_imagen, 
                ':url_imagen' => $path
            ]);
        }

        $conn->commit();
        echo json_encode(['success' => true, 'message' => 'Producto publicado exitosamente.']);

    } catch (Exception $e) {
        if (isset($conn)) $conn->rollBack();
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}
