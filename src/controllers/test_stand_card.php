<?php
// src/controllers/test_stand_card.php
// Test page to demonstrate the stand card component

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// For testing, we'll get all stands or a specific one
require_once __DIR__ . '/../functions/Database.php';

try {
    $db = Database::getInstance();
    
    // Get all active stands for testing (direct query since we don't have a registered statement)
    $stmt = $db->connection->prepare('SELECT * FROM tab_stand WHERE is_deleted = FALSE LIMIT 10');
    $stmt->execute();
    $stands = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Load the view
    require_once ROOT_PATH . 'src/views/test_stand_card.view.php';
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
    exit;
}
