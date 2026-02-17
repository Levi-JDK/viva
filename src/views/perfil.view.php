<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Cuenta | VIVA - Artesanías Colombianas</title>
    <?php require_once __DIR__ . '/partials/tailwind_head.php'; ?>
    <link rel="stylesheet" href="<?= BASE_URL ?>src/styles/web.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>src/styles/perfil.css">
</head>
<body class="bg-gray-50 font-sans antialiased">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar Navigation -->
        <aside id="sidebar" class="w-64 bg-white shadow-xl h-full flex-shrink-0 flex flex-col z-20 hidden lg:flex border-r border-gray-100">
            <div class="p-6 border-b border-gray-100">
                <a href="<?= BASE_URL ?>" class="flex items-center space-x-3 group">
                     <div class="w-10 h-10 bg-gradient-to-br from-tierra-oscuro to-verde-artesanal rounded-xl flex items-center justify-center shadow-md group-hover:scale-105 transition-all">
                        <img src="<?= BASE_URL ?>images/Logo.png" alt="VIVA" class="w-full h-full object-contain rounded-xl">
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-tierra-oscuro">VIVA</h2>
                        <p class="text-xs text-gray-500 uppercase tracking-widest">Mi Cuenta</p>
                    </div>
                </a>
            </div>
            
            <nav class="flex-1 overflow-y-auto py-6 space-y-1">
                <button onclick="showSection('profile')" class="menu-item active-item w-full flex items-center space-x-4 px-6 py-3.5 text-left text-gray-600 hover:bg-gray-50 transition-colors">
                    <i class="fas fa-user w-5 text-center text-tierra-medio"></i>
                    <span class="font-semibold">Mi Perfil</span>
                </button>
                <button onclick="showSection('orders')" class="menu-item w-full flex items-center space-x-4 px-6 py-3.5 text-left text-gray-600 hover:bg-gray-50 transition-colors">
                    <i class="fas fa-shopping-bag w-5 text-center text-tierra-medio"></i>
                    <span class="font-semibold">Mis Pedidos</span>
                </button>
                <button onclick="showSection('favorites')" class="menu-item w-full flex items-center space-x-4 px-6 py-3.5 text-left text-gray-600 hover:bg-gray-50 transition-colors">
                    <i class="fas fa-heart w-5 text-center text-tierra-medio"></i>
                    <span class="font-semibold">Favoritos</span>
                </button>
                
                <?php if (isset($es_productor) && $es_productor): ?>
                <a href="<?= BASE_URL ?>mis_productos" class="menu-item w-full flex items-center space-x-4 px-6 py-3.5 text-left text-gray-600 hover:bg-gray-50 transition-colors">
                    <i class="fas fa-box-open w-5 text-center text-tierra-medio"></i>
                    <span class="font-semibold">Mis Productos</span>
                </a>
                <?php endif; ?>

                <button onclick="showSection('settings')" class="menu-item w-full flex items-center space-x-4 px-6 py-3.5 text-left text-gray-600 hover:bg-gray-50 transition-colors">
                    <i class="fas fa-cog w-5 text-center text-tierra-medio"></i>
                    <span class="font-semibold">Configuración</span>
                </button>
                
            </nav>

            <div class="p-4 border-t border-gray-100 bg-gray-50/50">
                <a href="<?= BASE_URL ?>" class="flex items-center space-x-3 text-gray-500 hover:text-naranja-artesanal transition-colors mb-4 pl-2">
                    <i class="fas fa-arrow-left w-5 text-center"></i>
                    <span class="font-medium text-sm">Volver al Inicio</span>
                </a>
                <button onclick="logout()" class="flex items-center space-x-3 text-red-500 hover:text-red-700 transition-colors w-full pl-2">
                    <i class="fas fa-sign-out-alt w-5 text-center"></i>
                    <span class="font-medium text-sm">Cerrar Sesión</span>
                </button>
            </div>
        </aside>
        
        <!-- Main Content Wrapper -->
        <div class="flex-1 flex flex-col h-screen overflow-hidden relative bg-gray-50">
            <!-- Header for Mobile / Top Bar -->
             <header class="bg-white shadow-sm flex items-center justify-between px-6 py-4 z-10 lg:hidden">
                <div class="flex items-center">
                    <button class="text-gray-500 hover:text-gray-700 mr-4" onclick="document.getElementById('sidebar').classList.toggle('hidden'); document.getElementById('sidebar').classList.toggle('fixed'); document.getElementById('sidebar').classList.toggle('inset-0');">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <h2 class="text-xl font-bold text-gray-800">Mi Cuenta</h2>
                </div>
            </header>

            <!-- Main Content Area -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]">
                <div class="min-h-full flex flex-col bg-gradient-to-br from-orange-50/50 via-white/80 to-amber-50/50">
                    <div class="flex-1 p-6 md:p-8 max-w-7xl mx-auto w-full space-y-8">
                    <!-- Profile Section -->
                    <section id="profile" class="content-section active">
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                            <div class="flex items-center justify-between mb-8">
                                <div>
                                    <h2 class="text-2xl font-bold text-tierra-oscuro">Mi Perfil</h2>
                                    <p class="text-sm text-gray-500 mt-1">Gestiona tu información personal</p>
                                </div>
                                <div id="edit-buttons">
                                    <button onclick="toggleEdit()" id="btn-editar" class="btn-primary text-white px-5 py-2.5 rounded-lg text-sm hover:shadow-lg transition-all flex items-center">
                                        <i class="fas fa-edit mr-2"></i>Editar
                                    </button>
                                    <div id="save-cancel-buttons" class="hidden space-x-3">
                                        <button onclick="saveProfile()" class="bg-verde-artesanal text-white px-5 py-2.5 rounded-lg text-sm hover:shadow-lg transition-all flex items-center">
                                            <i class="fas fa-save mr-2"></i>Guardar
                                        </button>
                                        <button onclick="cancelEdit()" class="bg-gray-100 text-gray-600 hover:bg-gray-200 px-5 py-2.5 rounded-lg text-sm transition-all flex items-center">
                                            <i class="fas fa-times mr-2"></i>Cancelar
                                        </button>
                                    </div>
                                </div>
                            </div>

                        <!-- Profile Info -->
                        <div class="space-y-6">
                            <!-- Avatar with Pencil Icon Overlay -->
                            <!-- Avatar with Edit Overlay -->
                            <div class="flex items-center space-x-6">
                                <div id="avatar-container" class="avatar-container relative group w-24 h-24 cursor-pointer">
                                    <!-- 
                                        Avatar del usuario - Siempre muestra la imagen desde foto_user
                                        - Por defecto: images/default.jpg
                                        - Cache-busting: Se agrega timestamp para forzar recarga después de uploads
                                    -->
                                    <div class="w-full h-full rounded-full overflow-hidden">
                                        <img id="avatar-image" 
                                             src="<?= BASE_URL . $foto_usuario ?>?v=<?= time() ?>" 
                                             alt="<?= htmlspecialchars($nombre_completo) ?>" 
                                             class="w-full h-full object-cover">
                                    </div>
                                    
                                    <!-- Hover overlay con ícono de edición -->
                                    <div class="avatar-hover-overlay absolute inset-0 bg-black bg-opacity-40 rounded-full flex items-center justify-center text-white transition-all">
                                        <i class="fas fa-pencil-alt text-xl"></i>
                                    </div>
                                    
                                    <!-- Formulario oculto para upload automático -->
                                    <form id="profile-upload-form" action="<?= BASE_URL ?>src/functions/upload.php" method="POST" enctype="multipart/form-data" class="hidden">
                                        <input type="file" id="profile-image-input" name="imagen_perfil" accept="image/*">
                                    </form>
                                </div>
                                <div>
                                    <h3 class="text-xl font-semibold text-gray-800"><?= htmlspecialchars($nombre_completo) ?></h3>
                                    <p class="text-sm text-gray-500">Miembro desde <?= $fecha_formateada ?></p>
                                </div>
                            </div>

                            <!-- Info Grid -->
                            <form id="profile-form" class="grid md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nombre </label>
                                    <input type="text" id="input-nombre" value="<?= htmlspecialchars($nombre_usuario) ?>" class="profile-input w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-100 text-gray-500 cursor-not-allowed focus:border-tierra-medio focus:outline-none transition-all" disabled>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Apellido</label>
                                    <input type="text" id="input-apellido" value="<?= htmlspecialchars($apellido_usuario) ?>" class="profile-input w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-100 text-gray-500 cursor-not-allowed focus:border-tierra-medio focus:outline-none transition-all" disabled>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Correo Electrónico</label>
                                    <input type="email" id="input-email" value="<?= htmlspecialchars($email_usuario) ?>" class="profile-input w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-100 text-gray-500 cursor-not-allowed focus:border-tierra-medio focus:outline-none transition-all" disabled>
                                </div>
                                <!-- <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Teléfono</label>
                                    <input type="tel" id="input-telefono" value="+57 300 123 4567" class="profile-input w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-100 text-gray-500 cursor-not-allowed focus:border-tierra-medio focus:outline-none transition-all" disabled>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Ciudad</label>
                                    <input type="text" id="input-ciudad" value="Bogotá, Colombia" class="profile-input w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-100 text-gray-500 cursor-not-allowed focus:border-tierra-medio focus:outline-none transition-all" disabled>
                                </div> -->
                            </form>    
                    </div>
                </section>

                <!-- Orders Section -->
                <section id="orders" class="content-section">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                        <div class="flex items-center justify-between mb-8">
                            <div>
                                <h2 class="text-2xl font-bold text-tierra-oscuro">Mis Pedidos</h2>
                                <p class="text-sm text-gray-500 mt-1">Historial y estado de tus compras</p>
                            </div>
                        </div>
                        
                        <!-- Orders List -->
                        <div class="space-y-4">
                            <!-- Order Item -->
                            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-all">
                                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                                    <div class="flex items-start space-x-4">
                                        <div class="w-20 h-20 bg-gray-100 rounded-lg flex-shrink-0">
                                            <img src="<?= BASE_URL ?>images/wayuu.jpg" alt="Producto" class="w-full h-full object-cover rounded-lg">
                                        </div>
                                        <div>
                                            <h3 class="font-semibold text-gray-800">Pedido #12345</h3>
                                            <p class="text-sm text-gray-500">3 productos • 28 Enero 2025</p>
                                            <p class="text-sm font-semibold text-tierra-oscuro mt-1">$350.000</p>
                                        </div>
                                    </div>
                                    <div class="flex flex-col md:items-end space-y-2">
                                        <span class="inline-block px-3 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full">
                                            En Camino
                                        </span>
                                        <button class="text-sm text-naranja-artesanal hover:underline">Ver detalles</button>
                                    </div>
                                </div>
                            </div>

                            <!-- Another Order -->
                            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-all">
                                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                                    <div class="flex items-start space-x-4">
                                        <div class="w-20 h-20 bg-gray-100 rounded-lg flex-shrink-0">
                                            <img src="<?= BASE_URL ?>images/mochila-arhuaca.jpg" alt="Producto" class="w-full h-full object-cover rounded-lg">
                                        </div>
                                        <div>
                                            <h3 class="font-semibold text-gray-800">Pedido #12344</h3>
                                            <p class="text-sm text-gray-500">1 producto • 15 Enero 2025</p>
                                            <p class="text-sm font-semibold text-tierra-oscuro mt-1">$280.000</p>
                                        </div>
                                    </div>
                                    <div class="flex flex-col md:items-end space-y-2">
                                        <span class="inline-block px-3 py-1 bg-blue-100 text-blue-700 text-xs font-semibold rounded-full">
                                            Entregado
                                        </span>
                                        <button class="text-sm text-naranja-artesanal hover:underline">Ver detalles</button>
                                    </div>
                                </div>
                            </div>

                          
                        </div>
                    </div>
                </section>

                <!-- Favorites Section -->
                <section id="favorites" class="content-section">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                        <div class="flex items-center justify-between mb-8">
                            <div>
                                <h2 class="text-2xl font-bold text-tierra-oscuro">Mis Favoritos</h2>
                                <p class="text-sm text-gray-500 mt-1">Productos que te encantan</p>
                            </div>
                        </div>
                        
                        <!-- Favorites Grid -->
                        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            <!-- Favorite Item -->
                            <div class="border border-gray-200 rounded-lg overflow-hidden hover:shadow-lg transition-all group">
                                <div class="relative">
                                    <img src="<?= BASE_URL ?>images/wayuu.jpg" alt="Producto" class="w-full h-48 object-cover">
                                    <button class="absolute top-3 right-3 w-10 h-10 bg-white rounded-full flex items-center justify-center hover:bg-red-50 transition-all">
                                        <i class="fas fa-heart text-red-500"></i>
                                    </button>
                                </div>
                                <div class="p-4">
                                    <h3 class="font-semibold text-gray-800 mb-1">Canasto Wayuu Tradicional</h3>
                                    <p class="text-sm text-gray-500 mb-2">Comunidad Wayuu</p>
                                    <div class="flex items-center justify-between">
                                        <span class="text-lg font-bold text-tierra-oscuro">$120.000</span>
                                        <button class="btn-primary text-white px-4 py-2 rounded-lg text-sm">Agregar</button>
                                    </div>
                                </div>
                            </div>

                            <!-- Another Favorite -->
                            <div class="border border-gray-200 rounded-lg overflow-hidden hover:shadow-lg transition-all group">
                                <div class="relative">
                                    <img src="<?= BASE_URL ?>images/mochila-arhuaca.jpg" alt="Producto" class="w-full h-48 object-cover">
                                    <button class="absolute top-3 right-3 w-10 h-10 bg-white rounded-full flex items-center justify-center hover:bg-red-50 transition-all">
                                        <i class="fas fa-heart text-red-500"></i>
                                    </button>
                                </div>
                                <div class="p-4">
                                    <h3 class="font-semibold text-gray-800 mb-1">Mochila Arhuaca Original</h3>
                                    <p class="text-sm text-gray-500 mb-2">Comunidad Arhuaca</p>
                                    <div class="flex items-center justify-between">
                                        <span class="text-lg font-bold text-tierra-oscuro">$280.000</span>
                                        <button class="btn-primary text-white px-4 py-2 rounded-lg text-sm">Agregar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Settings Section -->
                <section id="settings" class="content-section">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                        <div class="flex items-center justify-between mb-8">
                            <div>
                                <h2 class="text-2xl font-bold text-tierra-oscuro">Configuración</h2>
                                <p class="text-sm text-gray-500 mt-1">Ajustes de tu cuenta y preferencias</p>
                            </div>
                        </div>
                        
                        <div class="space-y-6">
                            <!-- Notifications -->
                            <div class="border-b border-gray-200 pb-6">
                                <h3 class="text-lg font-semibold text-gray-800 mb-4">Notificaciones</h3>
                                <div class="space-y-3">
                                    <label class="flex items-center justify-between cursor-pointer">
                                        <span class="text-gray-700">Notificaciones por email</span>
                                        <input type="checkbox" checked class="w-5 h-5 text-tierra-medio rounded">
                                    </label>
                                    <label class="flex items-center justify-between cursor-pointer">
                                        <span class="text-gray-700">Ofertas y promociones</span>
                                        <input type="checkbox" checked class="w-5 h-5 text-tierra-medio rounded">
                                    </label>
                                    <label class="flex items-center justify-between cursor-pointer">
                                        <span class="text-gray-700">Actualizaciones de pedidos</span>
                                        <input type="checkbox" checked class="w-5 h-5 text-tierra-medio rounded">
                                    </label>
                                </div>
                            </div>

                            <!-- Privacy -->
                            <div class="border-b border-gray-200 pb-6">
                                <h3 class="text-lg font-semibold text-gray-800 mb-4">Privacidad</h3>
                                <div class="space-y-3">
                                    <label class="flex items-center justify-between cursor-pointer">
                                        <span class="text-gray-700">Perfil público</span>
                                        <input type="checkbox" class="w-5 h-5 text-tierra-medio rounded">
                                    </label>
                                    <label class="flex items-center justify-between cursor-pointer">
                                        <span class="text-gray-700">Mostrar historial de compras</span>
                                        <input type="checkbox" class="w-5 h-5 text-tierra-medio rounded">
                                    </label>
                                </div>
                            </div>

                            <!-- Security -->
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800 mb-4">Seguridad</h3>
                                <button class="w-full md:w-auto btn-primary text-white px-6 py-3 rounded-lg">
                                    <i class="fas fa-key mr-2"></i>Cambiar Contraseña
                                </button>
                            </div>
                        </div>
                    </div>
                </section>
                
                </div>
            </div>
            </main>
        </div>
    </div>


    <div id="toast-container" class="fixed top-5 right-5 z-50 flex flex-col gap-3"></div>
    <script src="<?= BASE_URL ?>src/scripts/toast.js"></script>
    <script src="<?= BASE_URL ?>src/scripts/perfil.js"></script>
</body>
</html>
