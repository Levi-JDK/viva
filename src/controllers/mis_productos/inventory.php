<?php
// src/controllers/mis_productos/inventory.php

// La conexión $db, $id_user y $id_productor son provistos por el controlador principal

$productos = [];
if (isset($id_productor) && $id_productor) {
    try {
        $productos_raw = $db->ejecutar('obtenerProductos', [':id_productor' => $id_productor])->fetchAll(PDO::FETCH_ASSOC);
        
        // Procesar imágenes (de JSON a array/string)
        $productos = array_map(function($prod) {
            $imgs = json_decode($prod['imagenes'], true);
            // La vista espera 'imagen' como URL principal de la imagen
            $prod['imagen'] = !empty($imgs) && isset($imgs[0]['url']) ? $imgs[0]['url'] : 'images/default_product.png';
            return $prod;
        }, $productos_raw);
    } catch (Exception $e) {
        $error = "Error al cargar productos: " . $e->getMessage();
    }
}

// Datos para las tarjetas de KPIs (a implementar si se necesitan)
// ...

require_once ROOT_PATH . "src/views/mis_productos/kpi_cards.view.php";
require_once ROOT_PATH . "src/views/mis_productos/inventory.view.php";
