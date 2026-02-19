<?php
/**
 * PARTIAL: TARJETA DE PRODUCTO — Componente Reutilizable
 * 
 * Muestra un producto como una tarjeta con imagen, nombre, precio (opcional) e info del stand.
 * 
 * ==========================================
 * ESTRUCTURA DE BASE DE DATOS:
 * ==========================================
 * 
 * El array $product debe provenir de una consulta con JOIN:
 * 
 * SELECT 
 *     p.*,
 *     s.nom_stand,
 *     s.img_stand,
 *     (SELECT url_imagen FROM tab_imagenes WHERE id_producto = p.id_producto LIMIT 1) as primera_imagen
 * FROM tab_productos p
 * LEFT JOIN tab_stand s ON p.id_productor = s.id_productor
 * WHERE p.is_deleted = FALSE AND p.is_active = TRUE
 * 
 * Columnas esperadas:
 * - id_producto (DECIMAL)           - ID del producto
 * - nom_producto (VARCHAR)          - Nombre del producto
 * - precio_producto (DECIMAL)       - Precio del producto
 * - id_productor (DECIMAL)          - ID del productor
 * - nom_stand (VARCHAR)             - Nombre del stand
 * - img_stand (VARCHAR)             - Logo del stand
 * - primera_imagen (VARCHAR)        - URL de la primera imagen del producto
 * 
 * ==========================================
 * CÓMO USAR:
 * ==========================================
 * 
 * OPCIÓN 1 — Landing page (sin precio):
 * <?php
 * // $show_price = false; // Ocultar precio en el landing
 * // foreach ($products as $product) {
 * //     require __DIR__ . '/partials/card_producto.php';
 * // }
 * ?>
 * 
 * OPCIÓN 2 — Catálogo (con precio):
 * <?php
 * // $show_price = true; // Mostrar precio en el catálogo
 * // foreach ($products as $product) {
 * //     require __DIR__ . '/partials/card_producto.php';
 * // }
 * ?>
 * 
 * ==========================================
 * PERSONALIZACIÓN:
 * ==========================================
 * 
 * Variables opcionales que puedes definir antes de incluir el partial:
 * - $show_price: Si se muestra el precio (por defecto: true)
 * 
 */

// Valores por defecto si no se proporcionan
$show_price = $show_price ?? true;
// IMPORTANTE: NO usar ?? aquí — $product_url debe recalcularse en cada iteración del foreach.
// Usar ?? cachearía el URL del primer producto para todas las tarjetas siguientes.
$product_url = BASE_URL . 'producto?id=' . ($product['id_producto'] ?? '');

// Verificar que $product exista
if (!isset($product) || empty($product)) {
    echo '<div class="text-red-500 p-4 border border-red-300 rounded">Error: No se proporcionaron datos del producto</div>';
    return;
}

// Obtener primera imagen o usar placeholder
$product_image = !empty($product['primera_imagen']) ? BASE_URL . $product['primera_imagen'] : BASE_URL . 'images/default_product.jpg';
$stand_logo = !empty($product['img_stand']) ? BASE_URL . $product['img_stand'] : BASE_URL . 'images/default.jpg';
?>

<!-- Componente: Tarjeta de Producto -->
<a href="<?= htmlspecialchars($product_url) ?>" class="product-card bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-xl transition-all duration-300 flex flex-col group h-full">
    <!-- Imagen del Producto -->
    <div class="h-64 bg-gradient-to-br from-tierra-claro to-beige-suave relative overflow-hidden">
        <img src="<?= $product_image ?>" 
             alt="<?= htmlspecialchars($product['nom_producto'] ?? 'Producto') ?>"
             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
        <!-- Overlay al pasar el cursor -->
        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-all duration-300"></div>
    </div>
    
    <!-- Información del Producto -->
    <div class="p-5 flex-1 flex flex-col">
        <!-- Nombre del Producto -->
        <h3 class="font-bold text-lg text-tierra-oscuro mb-2 line-clamp-2 group-hover:text-naranja-artesanal transition-colors">
            <?= htmlspecialchars($product['nom_producto'] ?? 'Sin nombre') ?>
        </h3>
        
        <!-- Información del Stand (Productor) -->
        <div class="flex items-center gap-2 mb-3">
            <div class="w-10 h-10 rounded-full overflow-hidden flex-shrink-0 ring-2 ring-tierra-claro">
                <img src="<?= $stand_logo ?>" 
                     alt="<?= htmlspecialchars($product['nom_stand'] ?? 'Stand') ?>"
                     class="w-full h-full object-cover">
            </div>
            <span class="text-sm text-gray-600 truncate">
                <?= htmlspecialchars($product['nom_stand'] ?? 'Stand artesanal') ?>
            </span>
        </div>
        
        <!-- Espaciador -->
        <div class="flex-1"></div>
        
        <!-- Precio (condicional) -->
        <?php if ($show_price): ?>
            <div class="mt-auto pt-3 border-t border-gray-100">
                <div class="flex items-center justify-between">
                    <span class="text-2xl font-bold text-tierra-oscuro">
                        $<?= number_format($product['precio_producto'] ?? 0, 0, ',', '.') ?>
                    </span>
                    <button class="bg-naranja-artesanal text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-tierra-oscuro transition-colors">
                        <i class="fas fa-shopping-cart mr-1"></i>
                        Comprar
                    </button>
                </div>
            </div>
        <?php else: ?>
            <!-- Botón "Ver más" para el Landing -->
            <div class="mt-auto pt-3">
                <div class="text-center">
                    <span class="inline-flex items-center text-naranja-artesanal font-medium group-hover:text-tierra-oscuro transition-colors">
                        Ver más
                        <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                    </span>
                </div>
            </div>
        <?php endif; ?>
    </div>
</a>
