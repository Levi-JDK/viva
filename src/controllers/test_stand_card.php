<?php
// src/controllers/test_stand_card.php
// Página de prueba para demostrar el componente de tarjeta de stand

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Para pruebas: obtenemos todos los stands o uno específico
require_once __DIR__ . '/../functions/Database.php';

try {
    $db = Database::getInstance();
    
    // Obtener todos los stands activos para pruebas (consulta directa, sin statement registrado)
    $stmt = $db->connection->prepare('SELECT * FROM tab_stand WHERE is_deleted = FALSE LIMIT 10');
    $stmt->execute();
    $stands = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Cargar la vista
    require_once ROOT_PATH . 'src/views/test_stand_card.view.php';
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
    exit;
}
