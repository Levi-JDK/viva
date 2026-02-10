<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Vendedor | VIVA - Artesanías Colombianas</title>
    <script>const BASE_URL = '<?= BASE_URL ?>';</script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>src/styles/web.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>src/styles/dash_productos.css">
</head>
<body class="bg-gray-100 font-sans antialiased">
    
    <div class="flex h-screen overflow-hidden">
        
        <!-- Sidebar Navigation -->
        <aside id="sidebar" class="w-64 flex-shrink-0 bg-gradient-to-b from-tierra-oscuro to-secundario text-white flex flex-col transition-all duration-300 transform lg:translate-x-0 -translate-x-full fixed lg:static z-30 h-full shadow-2xl">
            <!-- Logo Section -->
            <div class="h-20 flex items-center justify-start border-b border-white/10 pl-8">
                <a href="<?= BASE_URL ?>" class="flex items-center space-x-2 group">
                    <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center bg-opacity-20 backdrop-blur-sm group-hover:bg-opacity-30 transition-all">
                        <img src="<?= BASE_URL ?>images/Logo.png" alt="VIVA" class="w-8 h-8 object-contain rounded-lg">
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold tracking-wider">VIVA</h1>
                        <p class="text-[10px] uppercase tracking-widest opacity-80">Vendedor</p>
                    </div>
                </a>
            </div>

            <!-- Navigation Links -->
            <nav class="flex-1 overflow-y-auto py-6 space-y-1">
                <button onclick="showSection('productos')" class="menu-item active-item w-full flex items-center space-x-4 px-6 py-3.5 text-left">
                    <i class="fas fa-box w-5 text-center"></i>
                    <span class="font-semibold">Mis Productos</span>
                </button>
                <button onclick="showSection('subir')" class="menu-item w-full flex items-center space-x-4 px-6 py-3.5 text-left">
                    <i class="fas fa-plus-circle w-5 text-center"></i>
                    <span class="font-semibold">Subir Producto</span>
                </button>
                <button onclick="showSection('stand')" class="menu-item w-full flex items-center space-x-4 px-6 py-3.5 text-left">
                    <i class="fas fa-store w-5 text-center"></i>
                    <span class="font-semibold">Mi Stand</span>
                </button>
                <button onclick="showSection('estadisticas')" class="menu-item w-full flex items-center space-x-4 px-6 py-3.5 text-left">
                    <i class="fas fa-chart-line w-5 text-center"></i>
                    <span class="font-semibold">Estadísticas</span>
                </button>
                <div class="pt-4 pb-2 px-6">
                    <p class="text-xs uppercase text-white/50 font-bold tracking-wider">Configuración</p>
                </div>
                <button onclick="showSection('configuracion')" class="menu-item w-full flex items-center space-x-4 px-6 py-3.5 text-left">
                    <i class="fas fa-cog w-5 text-center"></i>
                    <span class="font-semibold">Opciones</span>
                </button>
                <a href="<?= BASE_URL ?>dashboard" class="menu-item w-full flex items-center space-x-4 px-6 py-3.5 text-left text-white/80 hover:text-white">
                    <i class="fas fa-user-circle w-5 text-center"></i>
                    <span class="font-semibold">Mi Perfil (Comprador)</span>
                </a>
            </nav>

            <!-- User Footer -->
            <div class="p-4 border-t border-white/10 bg-black/10">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center overflow-hidden">
                         <img src="<?= BASE_URL . $foto_usuario ?>?v=<?= time() ?>" alt="User" class="w-full h-full object-cover">
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium truncate"><?= htmlspecialchars($nombre_usuario) ?></p>
                        <p class="text-xs text-white/60 truncate">Vendedor</p>
                    </div>
                    <a href="<?= BASE_URL ?>logout" class="text-white/60 hover:text-white p-2" title="Cerrar Sesión">
                        <i class="fas fa-sign-out-alt"></i>
                    </a>
                </div>
            </div>
        </aside>

        <!-- Overlay for mobile sidebar -->
        <div id="sidebarOverlay" class="fixed inset-0 bg-black/50 z-20 hidden lg:hidden" onclick="toggleSidebar()"></div>

        <!-- Main Content Wrapper -->
        <div class="flex-1 flex flex-col h-screen overflow-hidden relative">
            
            <!-- Top Header -->
            <header class="h-20 bg-white shadow-sm flex items-center justify-between px-6 z-10">
                <div class="flex items-center">
                    <button class="lg:hidden text-gray-500 hover:text-gray-700 mr-4" onclick="toggleSidebar()">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <h2 id="pageTitle" class="text-xl font-bold text-gray-800">Panel de Control</h2>
                </div>
                
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <button class="p-2 text-gray-400 hover:text-naranja-artesanal transition-colors relative">
                            <i class="fas fa-bell text-xl"></i>
                            <span class="absolute top-1 right-1 w-2.5 h-2.5 bg-red-500 rounded-full border-2 border-white"></span>
                        </button>
                    </div>
                     <div class="h-8 w-px bg-gray-200 mx-2"></div>
                     <a href="<?= BASE_URL ?>" class="text-sm font-medium text-gray-600 hover:text-naranja-artesanal">
                        Ir al sitio <i class="fas fa-external-link-alt ml-1"></i>
                     </a>
                </div>
            </header>

            <!-- Main Scrollable Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-6">
                
                <!-- Section: Mis Productos -->
                <section id="productos" class="content-section active">
                   <!-- KPI Small Cards for Products -->
                   <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div class="bg-white p-6 rounded-xl shadow-sm border border-orange-100 flex items-center">
                            <div class="p-3 bg-orange-100 text-naranja-artesanal rounded-full mr-4">
                                <i class="fas fa-box text-xl"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 font-medium">Total Productos</p>
                                <h3 class="text-2xl font-bold text-gray-800">12</h3>
                            </div>
                        </div>
                        <div class="bg-white p-6 rounded-xl shadow-sm border border-green-100 flex items-center">
                            <div class="p-3 bg-green-100 text-verde-artesanal rounded-full mr-4">
                                <i class="fas fa-check-circle text-xl"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 font-medium">Activos</p>
                                <h3 class="text-2xl font-bold text-gray-800">10</h3>
                            </div>
                        </div>
                         <div class="bg-white p-6 rounded-xl shadow-sm border border-blue-100 flex items-center">
                            <div class="p-3 bg-blue-100 text-blue-600 rounded-full mr-4">
                                <i class="fas fa-eye text-xl"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 font-medium">Vistas Totales</p>
                                <h3 class="text-2xl font-bold text-gray-800">1.2k</h3>
                            </div>
                        </div>
                   </div>

                    <div class="bg-white rounded-xl shadow-lg p-6 md:p-8">
                        <div class="flex flex-col sm:flex-row items-center justify-between mb-6 gap-4">
                            <h2 class="text-xl font-bold text-tierra-oscuro">Inventario</h2>
                            <div class="flex space-x-3 w-full sm:w-auto">
                                <div class="relative flex-1 sm:flex-none">
                                    <input type="text" placeholder="Buscar..." class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-naranja-artesanal w-full">
                                    <i class="fas fa-search absolute left-3 top-2.5 text-gray-400 text-sm"></i>
                                </div>
                                <button onclick="showSection('subir')" class="btn-primary text-white px-4 py-2 rounded-lg text-sm hover:shadow-lg transition-all flex items-center">
                                    <i class="fas fa-plus mr-2"></i>Nuevo
                                </button>
                            </div>
                        </div>

                        <!-- Product Grid (Placeholder) -->
                        <div class="grid sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                            <!-- Placeholder Product 1 -->
                            <div class="border border-gray-200 rounded-xl overflow-hidden hover:shadow-lg transition-all group bg-white relative">
                                <span class="absolute top-2 right-2 bg-green-100 text-green-700 text-xs font-bold px-2 py-1 rounded">Activo</span>
                                <div class="relative h-48 bg-gray-100 flex items-center justify-center">
                                    <i class="fas fa-image text-gray-300 text-4xl"></i>
                                </div>
                                <div class="p-4">
                                    <h3 class="font-semibold text-gray-800 mb-1 truncate">Mochila uwu...</h3>
                                    <p class="text-xs text-gray-500 mb-3">Tejidos</p>
                                    <div class="flex items-center justify-between mt-2 pt-3 border-t border-gray-100">
                                        <span class="text-lg font-bold text-tierra-oscuro">$120.000</span>
                                        <div class="flex space-x-1">
                                            <button class="text-gray-400 hover:text-blue-600 p-1.5 rounded-full hover:bg-blue-50 transition-colors"><i class="fas fa-edit"></i></button>
                                            <button class="text-gray-400 hover:text-red-600 p-1.5 rounded-full hover:bg-red-50 transition-colors"><i class="fas fa-trash"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Placeholder Product 2 -->
                            <div class="border border-gray-200 rounded-xl overflow-hidden hover:shadow-lg transition-all group bg-white relative">
                                 <span class="absolute top-2 right-2 bg-gray-100 text-gray-600 text-xs font-bold px-2 py-1 rounded">Borrador</span>
                                <div class="relative h-48 bg-gray-100 flex items-center justify-center">
                                    <i class="fas fa-image text-gray-300 text-4xl"></i>
                                </div>
                                <div class="p-4">
                                    <h3 class="font-semibold text-gray-800 mb-1 truncate">Jarrón de Barro</h3>
                                    <p class="text-xs text-gray-500 mb-3">Cerámica</p>
                                    <div class="flex items-center justify-between mt-2 pt-3 border-t border-gray-100">
                                        <span class="text-lg font-bold text-tierra-oscuro">$85.000</span>
                                        <div class="flex space-x-1">
                                            <button class="text-gray-400 hover:text-blue-600 p-1.5 rounded-full hover:bg-blue-50 transition-colors"><i class="fas fa-edit"></i></button>
                                            <button class="text-gray-400 hover:text-red-600 p-1.5 rounded-full hover:bg-red-50 transition-colors"><i class="fas fa-trash"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Section: Subir Producto -->
                <section id="subir" class="content-section">
                    <div class="max-w-4xl mx-auto">
                        <div class="mb-6">
                             <a href="#" onclick="showSection('productos')" class="text-gray-500 hover:text-naranja-artesanal text-sm mb-2 inline-block"><i class="fas fa-arrow-left mr-1"></i> Volver a productos</a>
                             <h2 class="text-2xl font-bold text-tierra-oscuro">Subir Nuevo Producto</h2>
                        </div>
                   
                        <div class="bg-white rounded-xl shadow-lg p-6 md:p-8">
                            <form class="space-y-8">
                                <!-- Image Upload Area -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-3">Imágenes del Producto</label>
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                        <div class="border-2 border-dashed border-naranja-artesanal/30 rounded-lg aspect-square flex flex-col items-center justify-center text-center hover:bg-orange-50 transition-colors cursor-pointer bg-orange-50/30">
                                            <i class="fas fa-plus text-2xl text-naranja-artesanal mb-2"></i>
                                            <span class="text-xs text-naranja-artesanal font-medium">Principal</span>
                                        </div>
                                         <div class="border-2 border-dashed border-gray-200 rounded-lg aspect-square flex items-center justify-center hover:border-gray-300 transition-colors cursor-pointer">
                                            <i class="fas fa-image text-gray-300"></i>
                                        </div>
                                        <div class="border-2 border-dashed border-gray-200 rounded-lg aspect-square flex items-center justify-center hover:border-gray-300 transition-colors cursor-pointer">
                                            <i class="fas fa-image text-gray-300"></i>
                                        </div>
                                        <div class="border-2 border-dashed border-gray-200 rounded-lg aspect-square flex items-center justify-center hover:border-gray-300 transition-colors cursor-pointer">
                                            <i class="fas fa-image text-gray-300"></i>
                                        </div>
                                    </div>
                                    <p class="text-xs text-gray-400 mt-2">Formatos permitidos: JPG, PNG. Máx 5MB.</p>
                                </div>

                                <div class="grid md:grid-cols-2 gap-6">
                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-sm font-semibold text-gray-700 mb-2">Nombre del Producto</label>
                                            <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-naranja-artesanal focus:ring-1 focus:ring-naranja-artesanal" placeholder="Ej: Mochila Arhuaca">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-semibold text-gray-700 mb-2">Precio (COP)</label>
                                            <div class="relative">
                                                <span class="absolute left-3 top-2 text-gray-500">$</span>
                                                <input type="number" class="w-full pl-8 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-naranja-artesanal focus:ring-1 focus:ring-naranja-artesanal" placeholder="0">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-sm font-semibold text-gray-700 mb-2">Categoría</label>
                                            <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-naranja-artesanal focus:ring-1 focus:ring-naranja-artesanal bg-white">
                                                <option>Seleccionar categoría...</option>
                                                <option>Tejidos</option>
                                                <option>Cerámica</option>
                                                <option>Joyería</option>
                                                <option>Madera</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-semibold text-gray-700 mb-2">Stock Disponible</label>
                                            <input type="number" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-naranja-artesanal focus:ring-1 focus:ring-naranja-artesanal" placeholder="1">
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Descripción Detallada</label>
                                    <textarea rows="5" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-naranja-artesanal focus:ring-1 focus:ring-naranja-artesanal" placeholder="Describe los materiales, técnica, historia..."></textarea>
                                </div>

                                <div class="flex justify-end space-x-3 pt-6 border-t border-gray-100">
                                    <button type="button" onclick="showSection('productos')" class="px-6 py-2.5 border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50 font-medium transition-colors">Cancelar</button>
                                    <button type="submit" class="btn-primary text-white px-8 py-2.5 rounded-lg hover:shadow-lg font-medium transition-all">Publicar Producto</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </section>

                <!-- Section: Mi Stand -->
                <section id="stand" class="content-section">
                    <div class="bg-white rounded-xl shadow-lg p-6 md:p-8">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
                            <div>
                                <h2 class="text-2xl font-bold text-tierra-oscuro">Mi Stand Virtual</h2>
                                <p class="text-gray-500 text-sm">Personaliza cómo los clientes ven tu marca.</p>
                            </div>
                            <button class="text-naranja-artesanal hover:text-tierra-oscuro font-medium text-sm flex items-center border border-naranja-artesanal rounded-full px-4 py-1.5 hover:bg-orange-50 transition-colors">
                                <i class="fas fa-external-link-alt mr-2"></i>Ver página pública
                            </button>
                        </div>
                        
                        <!-- Banner Placeholder -->
                        <div class="h-56 bg-gradient-to-r from-tierra-claro to-beige-suave rounded-xl mb-12 relative group cursor-pointer overflow-hidden border-2 border-dashed border-transparent hover:border-naranja-artesanal transition-all">
                            <div class="absolute inset-0 flex items-center justify-center bg-black/0 group-hover:bg-black/10 transition-all">
                                <span class="opacity-0 group-hover:opacity-100 bg-white text-gray-800 px-4 py-2 rounded-full shadow-lg font-medium text-sm transition-all transform scale-90 group-hover:scale-100">
                                    <i class="fas fa-camera mr-2"></i>Editar Portada
                                </span>
                            </div>
                        </div>

                        <form class="space-y-8 relative -mt-20 px-4">
                            <div class="flex flex-col md:flex-row gap-8 items-start">
                                <!-- Logo Upload -->
                                <div class="relative group">
                                    <div class="w-32 h-32 bg-white rounded-full p-1 shadow-lg ring-4 ring-white relative z-10 flex-shrink-0">
                                        <div class="w-full h-full bg-gray-200 rounded-full overflow-hidden relative cursor-pointer group-inner">
                                            <div class="absolute inset-0 flex items-center justify-center bg-black/0 group-hover:bg-black/20 transition-all z-20"> 
                                                <i class="fas fa-camera text-white opacity-0 group-hover:opacity-100 text-2xl drop-shadow-md"></i>
                                            </div>
                                            <img src="<?= BASE_URL ?>images/default.jpg" class="w-full h-full object-cover">
                                        </div>
                                    </div>
                                </div>

                                <div class="flex-1 w-full pt-10 md:pt-12 space-y-6">
                                    <div class="grid md:grid-cols-2 gap-6">
                                        <div>
                                            <label class="block text-sm font-semibold text-gray-700 mb-2">Nombre del Emprendimiento</label>
                                            <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-naranja-artesanal" value="Artesanías Example">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-semibold text-gray-700 mb-2">Slogan Corto</label>
                                            <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-naranja-artesanal" placeholder="El arte de nuestras manos">
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Historia / Descripción</label>
                                        <textarea rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-naranja-artesanal" placeholder="Cuenta la historia de tu emprendimiento..."></textarea>
                                    </div>

                                    <div class="grid md:grid-cols-2 gap-6">
                                        <div>
                                            <label class="block text-sm font-semibold text-gray-700 mb-2">Ubicación</label>
                                            <div class="relative">
                                                <i class="fas fa-map-marker-alt absolute left-3 top-2.5 text-gray-400"></i>
                                                <input type="text" class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-naranja-artesanal" placeholder="Ciudad, Departamento">
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-semibold text-gray-700 mb-2">WhatsApp / Contacto</label>
                                            <div class="relative">
                                                <i class="fab fa-whatsapp absolute left-3 top-2.5 text-green-500"></i>
                                                <input type="text" class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-naranja-artesanal" placeholder="+57 300...">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex justify-end pt-4 border-t border-gray-100">
                                        <button type="submit" class="btn-primary text-white px-8 py-2.5 rounded-lg hover:shadow-lg font-medium">Guardar Cambios</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </section>

                <!-- Section: Estadísticas -->
                <section id="estadisticas" class="content-section">
                    <h2 class="text-2xl font-bold text-tierra-oscuro mb-6">Resumen de Estadísticas</h2>
                    
                    <!-- KPI Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                        <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-naranja-artesanal flex justify-between items-start">
                             <div>
                                <p class="text-sm text-gray-500 font-medium mb-1">Ventas Totales</p>
                                <h3 class="text-2xl font-bold text-gray-800">$154,430</h3>
                                <p class="text-xs text-green-500 mt-1"><i class="fas fa-arrow-up mr-1"></i> 13% vs semana pasada</p>
                             </div>
                             <div class="p-2 bg-orange-50 rounded-lg text-naranja-artesanal">
                                 <i class="fas fa-wallet text-xl"></i>
                             </div>
                        </div>
                        <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-blue-500 flex justify-between items-start">
                             <div>
                                <p class="text-sm text-gray-500 font-medium mb-1">Visitas Perfil</p>
                                <h3 class="text-2xl font-bold text-gray-800">6,480</h3>
                                <p class="text-xs text-green-500 mt-1"><i class="fas fa-arrow-up mr-1"></i> 10% vs semana pasada</p>
                             </div>
                             <div class="p-2 bg-blue-50 rounded-lg text-blue-500">
                                 <i class="fas fa-users text-xl"></i>
                             </div>
                        </div>
                        <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-purple-500 flex justify-between items-start">
                             <div>
                                <p class="text-sm text-gray-500 font-medium mb-1">Pedidos</p>
                                <h3 class="text-2xl font-bold text-gray-800">125</h3>
                                <p class="text-xs text-gray-400 mt-1">Total acumulado</p>
                             </div>
                             <div class="p-2 bg-purple-50 rounded-lg text-purple-500">
                                 <i class="fas fa-shopping-bag text-xl"></i>
                             </div>
                        </div>
                         <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-green-500 flex justify-between items-start">
                             <div>
                                <p class="text-sm text-gray-500 font-medium mb-1">Valoración</p>
                                <h3 class="text-2xl font-bold text-gray-800">4.8</h3>
                                <p class="text-xs text-yellow-500 mt-1"><i class="fas fa-star mr-1"></i> Promedio</p>
                             </div>
                             <div class="p-2 bg-green-50 rounded-lg text-green-500">
                                 <i class="fas fa-smile text-xl"></i>
                             </div>
                        </div>
                    </div>

                    <div class="grid lg:grid-cols-3 gap-6">
                        <!-- Chart Area -->
                        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm p-6">
                             <h3 class="text-lg font-bold text-gray-800 mb-6">Tendencia de Ventas</h3>
                             <!-- Placeholder for Chart -->
                             <div class="h-64 bg-gray-50 rounded-lg flex items-center justify-center border border-dashed border-gray-300 relative overflow-hidden">
                                  <div class="absolute inset-x-0 bottom-0 h-40 bg-gradient-to-t from-blue-100 to-transparent opacity-50"></div>
                                  <svg class="w-full h-full absolute inset-0 text-blue-400 opacity-30" viewBox="0 0 100 50" preserveAspectRatio="none">
                                      <path d="M0,50 L0,30 Q10,20 20,35 T40,15 T60,25 T80,10 L100,5 L100,50 Z" fill="currentColor" />
                                  </svg>
                                  <span class="text-gray-400 font-medium z-10"><i class="fas fa-chart-area mr-2"></i>Gráfico Demostrativo</span>
                             </div>
                        </div>

                        <!-- Top Products -->
                        <div class="bg-white rounded-xl shadow-sm p-6">
                             <h3 class="text-lg font-bold text-gray-800 mb-6">Más Vendidos</h3>
                             <ul class="space-y-4">
                                 <li class="flex items-center justify-between pb-3 border-b border-gray-100 last:border-0 last:pb-0">
                                     <div class="flex items-center space-x-3">
                                         <div class="w-10 h-10 bg-gray-100 rounded-lg flex-shrink-0"></div>
                                         <div>
                                             <p class="text-sm font-semibold text-gray-800">Mochila Wayuu</p>
                                             <p class="text-xs text-gray-500">12 ventas</p>
                                         </div>
                                     </div>
                                     <span class="text-sm font-bold text-tierra-oscuro">$1.2M</span>
                                 </li>
                                 <li class="flex items-center justify-between pb-3 border-b border-gray-100 last:border-0 last:pb-0">
                                     <div class="flex items-center space-x-3">
                                         <div class="w-10 h-10 bg-gray-100 rounded-lg flex-shrink-0"></div>
                                         <div>
                                             <p class="text-sm font-semibold text-gray-800">Sombrero Vueltiao</p>
                                             <p class="text-xs text-gray-500">8 ventas</p>
                                         </div>
                                     </div>
                                     <span class="text-sm font-bold text-tierra-oscuro">$980k</span>
                                 </li>
                                 <li class="flex items-center justify-between pb-3 border-b border-gray-100 last:border-0 last:pb-0">
                                     <div class="flex items-center space-x-3">
                                         <div class="w-10 h-10 bg-gray-100 rounded-lg flex-shrink-0"></div>
                                         <div>
                                             <p class="text-sm font-semibold text-gray-800">Aretes Filigrana</p>
                                             <p class="text-xs text-gray-500">24 ventas</p>
                                         </div>
                                     </div>
                                     <span class="text-sm font-bold text-tierra-oscuro">$1.5M</span>
                                 </li>
                             </ul>
                        </div>
                    </div>
                </section>

                <!-- Section: Configuración (Opciones) -->
                <section id="configuracion" class="content-section">
                    <div class="max-w-2xl mx-auto">
                        <h2 class="text-2xl font-bold text-tierra-oscuro mb-6">Opciones de Vendedor</h2>
                        <div class="bg-white rounded-xl shadow-lg p-6 md:p-8 space-y-8">
                            
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-100">Visibilidad de la Tienda</h3>
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="font-medium text-gray-800">Tienda Pública</p>
                                        <p class="text-sm text-gray-500">Tus productos aparecen en el catálogo general</p>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" value="" class="sr-only peer" checked>
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-orange-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-naranja-artesanal"></div>
                                    </label>
                                </div>
                            </div>

                            <div>
                                <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-100">Métodos de Envío</h3>
                                <div class="space-y-4">
                                    <label class="flex items-center p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                        <input type="checkbox" checked class="w-5 h-5 text-naranja-artesanal rounded focus:ring-naranja-artesanal">
                                        <div class="ml-3">
                                             <span class="block text-gray-800 font-medium">Envío Nacional Estándar</span>
                                             <span class="block text-xs text-gray-500">Coordinado por transportadora aliada</span>
                                        </div>
                                    </label>
                                    <label class="flex items-center p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                        <input type="checkbox" class="w-5 h-5 text-naranja-artesanal rounded focus:ring-naranja-artesanal">
                                        <div class="ml-3">
                                             <span class="block text-gray-800 font-medium">Recogida en taller</span>
                                             <span class="block text-xs text-gray-500">El cliente recoge el producto en tu dirección</span>
                                        </div>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="pt-6 border-t border-gray-100">
                                <h3 class="text-lg font-semibold text-red-600 mb-4">Zona de Peligro</h3>
                                <div class="flex items-center justify-between p-4 bg-red-50 rounded-lg border border-red-100">
                                    <div>
                                         <p class="font-medium text-red-800">Desactivar cuenta de vendedor</p>
                                         <p class="text-xs text-red-600">Esto ocultará todos tus productos y perfil.</p>
                                    </div>
                                    <button class="border border-red-200 text-red-600 bg-white hover:bg-red-50 px-4 py-2 rounded-lg text-sm font-medium transition-colors shadow-sm">
                                        Desactivar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

            </main>
        </div>
    </div>

    <script src="<?= BASE_URL ?>src/scripts/dash_productos.js"></script>
</body>
</html>
