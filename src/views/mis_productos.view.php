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
        
        <?php require_once __DIR__ . '/partials/mis_productos/sidebar.php'; ?>

        <!-- Main Content Wrapper -->
        <div class="flex-1 flex flex-col h-screen overflow-hidden relative">
            
            <?php require_once __DIR__ . '/partials/mis_productos/header.php'; ?>
            
            <!-- Main Scrollable Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-6 bg-[linear-gradient(to_bottom,rgba(0,0,0,0.8),transparent),url('<?= BASE_URL ?>images/background_registro_vender.webp')] bg-cover bg-center">   
                <!-- Section: Mis Productos -->
                <section id="productos" class="content-section active">
                   
                   <?php require_once __DIR__ . '/partials/mis_productos/kpi_cards.php'; ?>

                   <?php require_once __DIR__ . '/partials/mis_productos/inventory.php'; ?>   
                </section>
                <!-- Section: Subir Producto -->
                <section id="subir" class="content-section">
                    <?php require_once __DIR__ . '/partials/mis_productos/form_add_product.php'; ?>
                </section>
                <!-- Section: Mi Stand -->
                <section id="stand" class="content-section">
                    <?php require_once __DIR__ . '/partials/mis_productos/stand.php'; ?>
                </section>
                <!-- Section: Estadísticas -->
                <section id="estadisticas" class="content-section">
                    <?php require_once __DIR__ . '/partials/mis_productos/statistics.php'; ?>
                </section>
                <!-- Section: Configuración (Opciones) -->
                <section id="configuracion" class="content-section">
                    <?php require_once __DIR__ . '/partials/mis_productos/configuration.php'; ?>
                </section>
            </main>
        </div>
    </div>

    <div id="toast-container" class="fixed top-5 right-5 z-50 flex flex-col gap-3"></div>
    <script src="<?= BASE_URL ?>src/scripts/toast.js"></script>
    <script src="<?= BASE_URL ?>src/scripts/dash_productos.js"></script>
</body>
</html>
