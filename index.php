<?php
require_once __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

// 1. Detectar el protocolo y host
$protocolo = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https" : "http";
$host = $_SERVER['HTTP_HOST'];

// Detectar carpeta del proyecto
$proyecto_folder = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
$proyecto_folder = rtrim($proyecto_folder, '/');

// 3. Definir BASE_URL = url https
define('BASE_URL', $protocolo . "://" . $host . $proyecto_folder . "/");

// Definir Root_path para la direccion de la carpeta

define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT'] . $proyecto_folder . DIRECTORY_SEPARATOR);

// Enrutar
$request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$relative_uri = str_replace($proyecto_folder, '', $request_uri);
$relative_uri = '/' . ltrim($relative_uri, '/');

// Normalizar URI (quitar slash final si no es la raíz)
if ($relative_uri !== '/' && substr($relative_uri, -1) === '/') {
    $relative_uri = rtrim($relative_uri, '/');
}
// Definir rutas
$routes = [
    '/'             => 'src/controllers/index.php',
    '/index.php'    => 'src/controllers/index.php',
    '/login'        => 'src/controllers/login.php',
    '/dashboard'    => function() {
        header('Location: ' . BASE_URL . 'perfil');
        exit;
    },
    '/perfil'       => 'src/controllers/perfil.php',
    '/vender'       => 'src/controllers/registro_vendedor.php',
    '/logout'       => 'src/controllers/logout.php',
    '/mis_productos'=> 'src/controllers/mis_productos.php',
    '/catalogo'     => 'src/controllers/catalogo.php',
    '/producto'     => 'src/controllers/producto.php',
    '/stand'        => 'src/controllers/stand_detail.php',
    '/test-stands'  => 'src/controllers/test_stand_card.php'
];

if (array_key_exists($relative_uri, $routes)) {
    $route = $routes[$relative_uri];
    if (is_callable($route)) {
        $route();
    } else {
        require_once ROOT_PATH . $route;
    }
} else {
    require_once ROOT_PATH . "src/views/404.php";
}

?>