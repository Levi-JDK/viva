<?php
// Controlador para la vista de catálogo
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params(0, '/');
    session_start();
}

require_once(__DIR__ . '/../functions/Database.php');

$db = Database::getInstance();

// Inicializar variables de usuario para el header
$is_logged_in = false;
$nombre_usuario = '';
$foto_usuario = 'images/default.jpg';
$es_productor = false;

if (isset($_SESSION['id_user'])) {
    $id_user = $_SESSION['id_user'];
    try {
        $stmtUser = $db->ejecutar('obtenerUsuarioPorId', [':id' => $id_user]);
        $usuario = $stmtUser->fetch(PDO::FETCH_ASSOC);
        if ($usuario) {
            $is_logged_in = true;
            $nombre_usuario = $usuario['nom_user'];
            $foto_usuario = !empty($usuario['foto_user']) ? $usuario['foto_user'] : 'images/default.jpg';
        }
        $stmtProductor = $db->ejecutar('validarProductor', [':id_user' => $id_user]);
        $es_productor = $stmtProductor->fetchColumn();
    } catch (Exception $e) {
        error_log("Error cargando usuario: " . $e->getMessage());
    }
}

// Capturar filtros iniciales de la URL para la primera carga
$filtros = [
    'search' => isset($_GET['q']) ? $_GET['q'] : null,
    'categoria' => isset($_GET['cat']) ? $_GET['cat'] : null,
    'oficio' => isset($_GET['oficio']) ? $_GET['oficio'] : null,
    'materia' => isset($_GET['materia']) ? $_GET['materia'] : null,
    'min_price' => isset($_GET['min_price']) ? $_GET['min_price'] : null,
    'max_price' => isset($_GET['max_price']) ? $_GET['max_price'] : null,
];

try {
    // 1. Obtener listas para los filtros laterales
    $stmtCats = $db->ejecutar('obtenerFiltrosCategorias');
    $categorias_list = $stmtCats->fetchAll(PDO::FETCH_ASSOC);

    $stmtOficios = $db->ejecutar('obtenerFiltrosOficios');
    $oficios_list = $stmtOficios->fetchAll(PDO::FETCH_ASSOC);

    $stmtMaterias = $db->ejecutar('obtenerFiltrosMaterias');
    $materias_list = $stmtMaterias->fetchAll(PDO::FETCH_ASSOC);

    // 2. Obtener productos iniciales
    $productos = $db->obtenerProductosCatalogoFiltrado($filtros);
    
} catch (Exception $e) {
    $error_message = "Error al cargar información del catálogo: " . $e->getMessage();
    error_log($error_message);
    $productos = [];
    $categorias_list = [];
    $oficios_list = [];
    $materias_list = [];
}

// Cargar vista
require_once ROOT_PATH . "src/views/catalogo.view.php";
