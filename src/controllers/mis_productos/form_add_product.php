<?php
// src/controllers/mis_productos/form_add_product.php

// La conexión $db es provista por el controlador principal

try {
    $categorias = $db->ejecutar('obtenerCategorias')->fetchAll(PDO::FETCH_ASSOC);
    $colores = $db->ejecutar('obtenerColores')->fetchAll(PDO::FETCH_ASSOC);
    $oficios = $db->ejecutar('obtenerOficios')->fetchAll(PDO::FETCH_ASSOC);
    $materias = $db->ejecutar('obtenerMaterias')->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $error = "Error al cargar datos del formulario: " . $e->getMessage();
}

require_once ROOT_PATH . "src/views/mis_productos/form_add_product.view.php";
