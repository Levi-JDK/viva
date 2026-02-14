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
// $id_usuario = $_SESSION['id_user'];
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
    // Ensure foto_user has a valid path or default
    $foto_usuario = !empty($usuario['foto_user']) ? $usuario['foto_user'] : 'images/default.jpg';
    // Fetch data for product registration form
    $categorias = $db->ejecutar('obtenerCategorias')->fetchAll(PDO::FETCH_ASSOC);
    $colores = $db->ejecutar('obtenerColores')->fetchAll(PDO::FETCH_ASSOC);
    $oficios = $db->ejecutar('obtenerOficios')->fetchAll(PDO::FETCH_ASSOC);
    $colores = $db->ejecutar('obtenerColores')->fetchAll(PDO::FETCH_ASSOC);
    $oficios = $db->ejecutar('obtenerOficios')->fetchAll(PDO::FETCH_ASSOC);
    $materias = $db->ejecutar('obtenerMaterias')->fetchAll(PDO::FETCH_ASSOC);

    // Get Producer ID and fetch products
    $stmtProd = $db->ejecutar('obtenerIdProductor', [':id_user' => $id_user]);
    $id_productor = $stmtProd->fetchColumn();
    
    $productos = [];
    if ($id_productor) {
        $productos = $db->ejecutar('obtenerProductosPorProductor', [':id_productor' => $id_productor])->fetchAll(PDO::FETCH_ASSOC);
    }
    require_once ROOT_PATH . "src/views/mis_productos.view.php";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit;
}
