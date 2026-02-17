<?php
/**
 * STAND CARD PARTIAL - Reusable Stand Display Component
 * 
 * This partial displays a stand as a modern card with cover image, logo, name, slogan, and description.
 * 
 * ==========================================
 * DATABASE STRUCTURE (tab_stand):
 * ==========================================
 * 
 * The $stand array should come directly from a query to tab_stand table:
 * 
 * SELECT * FROM tab_stand WHERE id_productor = :id;
 * 
 * Expected columns:
 * - id_productor (DECIMAL)          - Producer ID
 * - id_stand (DECIMAL)              - Stand ID
 * - nom_stand (VARCHAR)             - Stand name
 * - slogan_stand (VARCHAR)          - Slogan
 * - descripcion_stand (TEXT)        - Description
 * - img_stand (VARCHAR)             - Logo path (relative to BASE_URL)
 * - portada_stand (VARCHAR)         - Cover image path (relative to BASE_URL)
 * 
 * ==========================================
 * HOW TO USE:
 * ==========================================
 * 
 * OPTION 1 - Single stand:
 * <?php
 * // $stmt = $db->ejecutar('SELECT * FROM tab_stand WHERE id_productor = :id', [':id' => $id]);
 * // $stand = $stmt->fetch(PDO::FETCH_ASSOC);
 * // require_once __DIR__ . '/partials/card_stand.php';
 * ?>
 * 
 * OPTION 2 - Multiple stands in a grid:
 * <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
 * <?php
 * // $stmt = $db->ejecutar('SELECT * FROM tab_stand WHERE is_deleted = FALSE');
 * // while ($stand = $stmt->fetch(PDO::FETCH_ASSOC)) {
 * //     require __DIR__ . '/partials/card_stand.php';
 * // }
 * ?>
 * </div>
 * 
 * ==========================================
 * CUSTOMIZATION:
 * ==========================================
 * 
 * Optional variables you can define before including:
 * - $show_link: Whether to show "Ver Stand" button (default: false)
 * - $stand_url: URL for the stand link (if $show_link is true)
 * 
 */

// Set default values if not provided
$show_link = $show_link ?? false;
$stand_url = $stand_url ?? '#';

// Ensure $stand exists
if (!isset($stand) || empty($stand)) {
    echo '<div class="text-red-500 p-4 border border-red-300 rounded">Error: Stand data not provided</div>';
    return;
}
?>

<!-- Stand Card Component -->
<div class="w-full bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
    <!-- Cover Image (with proper aspect ratio) -->
    <div class="h-40 bg-gradient-to-r from-tierra-claro to-beige-suave relative overflow-hidden">
        <?php if (!empty($stand['portada_stand'])): ?>
            <img src="<?= BASE_URL . $stand['portada_stand'] ?>" 
                 alt="Portada de <?= htmlspecialchars($stand['nom_stand'] ?? 'Stand') ?>"
                 class="w-full h-full object-cover">
        <?php endif; ?>
    </div>
    
    <!-- Stand Content -->
    <div class="relative px-6 pb-6">
        <!-- Logo (overlapping cover) -->
        <div class="flex justify-center -mt-12 mb-4">
            <div class="w-24 h-24 bg-white rounded-full p-1 shadow-lg overflow-hidden">
                <img src="<?= !empty($stand['img_stand']) ? BASE_URL . $stand['img_stand'] : BASE_URL . 'images/default.jpg' ?>" 
                     alt="<?= htmlspecialchars($stand['nom_stand'] ?? 'Stand') ?>"
                     class="w-full h-full rounded-full object-cover">
            </div>
        </div>
        
        <!-- Stand Info -->
        <div class="text-center">
            <h3 class="text-xl font-bold text-tierra-oscuro mb-1">
                <?= htmlspecialchars($stand['nom_stand'] ?? 'Sin nombre') ?>
            </h3>
            
            <?php if (!empty($stand['slogan_stand'])): ?>
                <p class="text-sm text-naranja-artesanal font-medium mb-4">
                    <?= htmlspecialchars($stand['slogan_stand']) ?>
                </p>
            <?php endif; ?>
            
            <?php if ($show_link): ?>
                <a href="<?= htmlspecialchars($stand_url) ?>" 
                   class="inline-block bg-naranja-artesanal text-white px-6 py-2 rounded-full hover:bg-tierra-oscuro transition-colors font-medium text-sm">
                    <i class="fas fa-store mr-2"></i>Ver Stand
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>

