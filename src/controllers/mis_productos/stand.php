<?php
// src/controllers/mis_productos/stand.php

require_once ROOT_PATH . 'src/functions/image_uploader.php';

// ==========================================
// HANDLE POST REQUEST (UPDATE/CREATE)
// ==========================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');

    try {
        if (!isset($_SESSION['id_user'])) {
            echo json_encode(['success' => false, 'message' => 'Usuario no autenticado']);
            exit;
        }

        $db = Database::getInstance();

        // Get Producer ID
        $stmtProd = $db->ejecutar('obtenerIdProductor', [':id_user' => $_SESSION['id_user']]);
        $id_productor = $stmtProd->fetchColumn();

        if (!$id_productor) {
            echo json_encode(['success' => false, 'message' => 'Productor no encontrado']);
            exit;
        }

        // Check if stand exists
        $stmtCheck = $db->ejecutar('verificarStand', [':id_p' => $id_productor]);
        $id_stand = $stmtCheck->fetchColumn();

        // Handle image uploads (returns path or null)
        $targetDir = ROOT_PATH . 'images/stands/';
        $prefix = 'stand_' . $id_productor . '_';
        
        $img_stand = null;
        if (isset($_FILES['img_stand']) && $_FILES['img_stand']['error'] === UPLOAD_ERR_OK) {
            $res = handleImageUpload($_FILES['img_stand'], $targetDir, $prefix . 'logo_', 'images/stands/');
            if (!$res['success']) {
                echo json_encode(['success' => false, 'message' => 'Error Logo: ' . $res['message']]);
                exit;
            }
            $img_stand = $res['path'];
        }

        $portada_stand = null;
        if (isset($_FILES['portada_stand']) && $_FILES['portada_stand']['error'] === UPLOAD_ERR_OK) {
            $res = handleImageUpload($_FILES['portada_stand'], $targetDir, $prefix . 'cover_', 'images/stands/');
            if (!$res['success']) {
                echo json_encode(['success' => false, 'message' => 'Error Portada: ' . $res['message']]);
                exit;
            }
            $portada_stand = $res['path'];
        }

        // Get text data
        $nom_stand = $_POST['nom_stand'] ?? null;
        $slogan_stand = $_POST['slogan_stand'] ?? null;
        $descripcion_stand = $_POST['descripcion_stand'] ?? null;

        if ($id_stand) {
            // UPDATE existing stand
            $stmt = $db->ejecutar('actualizarStand', [
                ':id_productor' => $id_productor,
                ':id_stand' => $id_stand,
                ':nom_stand' => $nom_stand,
                ':slogan_stand' => $slogan_stand,
                ':descripcion_stand' => $descripcion_stand,
                ':img_stand' => $img_stand,
                ':portada_stand' => $portada_stand
            ]);
            $message = 'Stand actualizado correctamente';
        } else {
            // CREATE new stand with defaults if needed
            $stmt = $db->ejecutar('registrarStand', [
                ':id_productor' => $id_productor,
                ':nom_stand' => $nom_stand ?: 'Mi Emprendimiento',
                ':slogan_stand' => $slogan_stand ?: 'Bienvenidos a mi stand virtual',
                ':descripcion_stand' => $descripcion_stand ?: 'Aquí encontrarás mis mejores productos artesanales.',
                ':img_stand' => $img_stand ?: 'images/default.jpg',
                ':portada_stand' => $portada_stand ?: 'images/default_cover.jpg'
            ]);
            $message = 'Stand creado correctamente';
        }

        // Check function result
        $result = $stmt->fetchColumn();
        if ($result === true || $result === 't') {
            echo json_encode(['success' => true, 'message' => $message]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al guardar en base de datos']);
        }

    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
    
    exit;
}

// ==========================================
// HANDLE GET REQUEST (RENDER VIEW)
// ==========================================

// Get stand data ($id_productor is from mis_productos.php)
$stmtStand = $db->ejecutar('obtenerStand', [':id_p' => $id_productor]);
$stand = $stmtStand->fetch(PDO::FETCH_ASSOC);

require_once ROOT_PATH . "src/views/mis_productos/stand.view.php";
