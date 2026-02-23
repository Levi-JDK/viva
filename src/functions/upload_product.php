<?php
/**
 * Manejador de Subida de Productos
 */

require_once __DIR__ . '/image_uploader.php';

require_once __DIR__ . '/Database.php';

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



if (!isset($_SESSION['id_user'])) {
    echo json_encode(['success' => false, 'message' => 'Usuario no autenticado']);
    exit;
}

// 2. Lógica principal
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $db = Database::getInstance();
        $conn = $db->connection;

        // --- Validación de campos de entrada ---
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

        // --- Validación del lado del servidor ---

        // 1. Validación numérica
        if (!is_numeric($precio) || $precio < 1) {
            throw new Exception("El precio debe ser un número válido mayor a 0.");
        }
        if (!is_numeric($stock) || $stock < 1) {
            throw new Exception("El stock debe ser un número válido mayor a 0.");
        }

        // 2. Sanitización y validación de texto (evitar caracteres especiales)
        // Se permiten letras, números, espacios y puntuación básica: . , - _
        if (!preg_match('/^[a-zA-Z0-9\s\.\,\-\_áéíóúÁÉÍÓÚñÑüÜ]+$/u', $nom_producto)) {
            throw new Exception("El nombre del producto contiene caracteres no permitidos.");
        }

        // Se permite un rango más amplio de caracteres para la descripción, incluyendo saltos de línea
        if (!empty($desc) && strlen($desc) > 5000) {
            throw new Exception("La descripción es demasiado larga (máx 5000 caracteres).");
        }

        // Sanitizar para la BD (aunque el binding de PDO ya lo maneja en su mayoría)
        $nom_producto = strip_tags($nom_producto);
        $desc = strip_tags($desc); // Protección básica contra XSS

        // Obtener id_productor
        $stmtProd = $db->ejecutar('obtenerIdProductor', [':id_user' => $_SESSION['id_user']]);
        $id_productor = $stmtProd->fetchColumn();

        if (!$id_productor) {
            throw new Exception("No se encontró el perfil de productor.");
        }

        $conn->beginTransaction();

        // 1. Insertar producto
        $stmt = $db->ejecutar('registrarProducto', [
            ':id_productor'       => $id_productor,
            ':nom_producto'       => $nom_producto,
            ':stock_productor'    => $stock,
            ':id_categoria'       => $id_categoria,
            ':id_color'           => $id_color,
            ':id_oficio'          => $id_oficio,
            ':id_materia'         => $id_materia,
            ':precio_producto'    => $precio,
            ':descripcion_producto' => $desc,
            ':is_active'          => 'true'
        ]);

        $result_insert = $stmt->fetchColumn();

        if (!$result_insert) {
            throw new Exception("Error al crear el producto en la base de datos.");
        }

        // Recuperar el ID generado (ya que la función devuelve boolean)
        // NOTA: Asume que somos el único insertando. En alta concurrencia podría fallar.
        $stmtId = $conn->query("SELECT MAX(id_producto) FROM tab_productos");
        $id_producto = $stmtId->fetchColumn();

        // 2. Gestionar imágenes con el ID generado
        $uploaded_paths = [];
        $target_directory = __DIR__ . '/../../images/products/';

        if (isset($_FILES['imagen_producto'])) {
            $files = $_FILES['imagen_producto'];
            $count = is_array($files['name']) ? count($files['name']) : 1;

            if (!is_array($files['name'])) {
                // Normalizar archivo único al formato de múltiples archivos
                $files = [
                    'name'     => [$files['name']],
                    'type'     => [$files['type']],
                    'tmp_name' => [$files['tmp_name']],
                    'error'    => [$files['error']],
                    'size'     => [$files['size']]
                ];
            }

            for ($i = 0; $i < $count; $i++) {
                if ($files['error'][$i] !== UPLOAD_ERR_OK)
                    continue;

                $current_file = [
                    'name'     => $files['name'][$i],
                    'type'     => $files['type'][$i],
                    'tmp_name' => $files['tmp_name'][$i],
                    'error'    => $files['error'][$i],
                    'size'     => $files['size'][$i]
                ];

                // Usar el ID generado por la BD para nombrar el archivo
                $result = handleImageUpload($current_file, $target_directory, 'prod_' . $id_producto . '_', 'images/products/');
                if ($result['success']) {
                    $uploaded_paths[] = $result['path'];
                }
            }
        }

        if (empty($uploaded_paths)) {
            throw new Exception("Debe subir al menos una imagen válida.");
        }

        // 3. Insertar imágenes
        foreach ($uploaded_paths as $index => $path) {
            $db->ejecutar('registrarImagen', [
                ':id_producto' => $id_producto,
                ':url_imagen'  => $path
            ]);
        }

        $conn->commit();
        echo json_encode(['success' => true, 'message' => 'Producto publicado exitosamente.']);

    }
    catch (Exception $e) {
        if (isset($conn))
            $conn->rollBack();
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}
else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}
