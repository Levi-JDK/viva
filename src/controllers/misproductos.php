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
$id_user = 1;
require_once(__DIR__ . '/../functions/Database.php');
try {
    $db = Database::getInstance();
    $params = [':id_user' => $id_user];
    $stmt = $db->ejecutar('validarProductor', $params);
    // Obtener la primera columna de la primera fila
    $es_productor = $stmt->fetchColumn();
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit;
}
