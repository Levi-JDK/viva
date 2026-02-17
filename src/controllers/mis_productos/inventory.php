<?php
// src/controllers/mis_productos/inventory.php

// Ensure DB connection is available (provided by main controller)
// $db, $id_user, $id_productor are expected to be set

$productos = [];
if (isset($id_productor) && $id_productor) {
    try {
        $productos_raw = $db->ejecutar('obtenerProductos', [':id_productor' => $id_productor])->fetchAll(PDO::FETCH_ASSOC);
        
        // Process images (JSON to Array/String)
        $productos = array_map(function($prod) {
            $imgs = json_decode($prod['imagenes'], true);
            // Assuming view expects 'imagen' as the main image URL
            $prod['imagen'] = !empty($imgs) && isset($imgs[0]['url']) ? $imgs[0]['url'] : 'images/default_product.png';
            return $prod;
        }, $productos_raw);
    } catch (Exception $e) {
        $error = "Error al cargar productos: " . $e->getMessage();
    }
}

// Support for KPI cards if they need data
// ...

require_once ROOT_PATH . "src/views/mis_productos/kpi_cards.view.php";
require_once ROOT_PATH . "src/views/mis_productos/inventory.view.php";
