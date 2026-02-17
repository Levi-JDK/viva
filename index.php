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

if ($relative_uri === '/' || $relative_uri === '/index.php'){
    require_once ROOT_PATH . "src/controllers/index.php";
}else if($relative_uri === '/login'){
    require_once ROOT_PATH . "src/controllers/login.php";
}else if($relative_uri === '/dashboard'){
    header('Location: ' . BASE_URL . 'perfil');
    exit;
}else if($relative_uri === '/perfil'){
    require_once ROOT_PATH . "src/controllers/perfil.php";
}else if($relative_uri === '/vender'){
    require_once ROOT_PATH . "src/controllers/registro_vendedor.php";
}else if($relative_uri === '/logout'){
    require_once ROOT_PATH . "src/controllers/logout.php";
}else if($relative_uri === '/mis_productos'){
    require_once ROOT_PATH . "src/controllers/mis_productos.php";
}else if($relative_uri === '/catalogo'){
    require_once ROOT_PATH . "src/controllers/catalogo.php";
}else if($relative_uri === '/producto'){
    require_once ROOT_PATH . "src/controllers/producto.php";
}else if($relative_uri === '/stand'){
    require_once ROOT_PATH . "src/controllers/stand_detail.php";
}else if($relative_uri === '/test-stands'){
    require_once ROOT_PATH . "src/controllers/test_stand_card.php";
}else{
    require_once ROOT_PATH . "src/views/404.php";
}

?>