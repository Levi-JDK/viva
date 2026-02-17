<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test - Stand Cards | VIVA</title>
    <?php require_once __DIR__ . '/partials/tailwind_head.php'; ?>
    <link rel="stylesheet" href="<?= BASE_URL ?>src/styles/web.css">
</head>
<body class="bg-gray-50 font-sans antialiased">
    
    <div class="container mx-auto px-4 py-12">
        <!-- Page Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-tierra-oscuro mb-4">
                <i class="fas fa-store mr-3"></i>Stands Artesanales
            </h1>
            <p class="text-gray-600 text-lg">Galería de prueba del componente card_stand.php</p>
        </div>

        <?php if (empty($stands)): ?>
            <!-- No stands found -->
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-8 text-center">
                <i class="fas fa-exclamation-triangle text-yellow-500 text-4xl mb-4"></i>
                <p class="text-gray-700 text-lg">No hay stands disponibles para mostrar.</p>
                <p class="text-gray-500 text-sm mt-2">Crea un stand desde el panel de vendedor para verlo aquí.</p>
            </div>
        <?php else: ?>
            <!-- Stands Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($stands as $stand): ?>
                    <?php 
                    // Enable link to detail page
                    $show_link = true;
                    $stand_url = BASE_URL . 'stand?id=' . $stand['id_productor'];
                    require __DIR__ . '/partials/card_stand.php'; 
                    ?>
                <?php endforeach; ?>
            </div>

            <!-- Stats -->
            <div class="mt-12 text-center">
                <p class="text-gray-500">
                    Mostrando <span class="font-bold text-naranja-artesanal"><?= count($stands) ?></span> stand<?= count($stands) !== 1 ? 's' : '' ?>
                </p>
            </div>
        <?php endif; ?>

        <!-- Back to home -->
        <div class="mt-12 text-center">
            <a href="<?= BASE_URL ?>" class="inline-block bg-tierra-oscuro text-white px-6 py-3 rounded-lg hover:bg-naranja-artesanal transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Volver al inicio
            </a>
        </div>
    </div>

</body>
</html>
