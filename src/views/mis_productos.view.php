<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Vendedor | VIVA - Artesanías Colombianas</title>
    <?php require_once __DIR__ . '/partials/tailwind_head.php'; ?>
    <link rel="stylesheet" href="<?= BASE_URL ?>src/styles/web.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>src/styles/dash_productos.css">
</head>
<body class="bg-gray-100 font-sans antialiased">
    
    <div class="flex h-screen overflow-hidden">
        
        <?php require_once __DIR__ . '/mis_productos/sidebar.view.php'; ?>
        <!-- Main Content Wrapper -->
        <div class="flex-1 flex flex-col h-screen overflow-hidden relative">
            
            <?php require_once __DIR__ . '/mis_productos/header.view.php'; ?>
            
            <!-- Main Scrollable Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-6 bg-[linear-gradient(to_bottom,rgba(0,0,0,0.8),transparent),url('<?= BASE_URL ?>images/background_registro_vender.webp')] bg-cover bg-center">   
                <?php 
                    if (isset($current_controller) && file_exists($current_controller)) {
                        require_once $current_controller;
                    } else {
                        echo '<div class="p-6 bg-white rounded-xl shadow-lg text-center"><p class="text-red-500">Error: Vista no encontrada.</p></div>';
                    }
                ?>
            </main>
        </div>
    </div>

    <div id="toast-container" class="fixed top-5 right-5 z-50 flex flex-col gap-3"></div>
    <script src="<?= BASE_URL ?>src/scripts/toast.js"></script>
    <script src="<?= BASE_URL ?>src/scripts/dash_productos.js"></script>
</body>
</html>
