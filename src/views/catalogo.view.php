<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogo de Productos | VIVA</title>
    <?php require_once __DIR__ . '/partials/tailwind_head.php'; ?>
    <link rel="stylesheet" href="<?= BASE_URL ?>src/styles/web.css">
</head>
<body class="bg-gray-100 font-sans min-h-screen flex flex-col">
    
    <!-- Reuse Header (Ideally should be a partial, but copying structure for now as per plan) -->
    <!-- Header -->
    <?php require_once __DIR__ . '/partials/navbar.php'; ?>

    <main class="container mx-auto px-6 py-10 flex-1">

        <!-- Encabezado del catálogo -->
        <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-800 capitalize">
                    <?= $search ? htmlspecialchars($search) : 'Todos los productos' ?>
                </h1>
                <p class="text-sm text-gray-400 mt-1"><?= count($productos) ?> resultados encontrados</p>
            </div>
            <?php if ($search || $categoria || $min_precio || $max_precio): ?>
                <a href="<?= BASE_URL ?>catalogo" class="inline-flex items-center gap-2 text-sm text-naranja-artesanal border border-naranja-artesanal px-4 py-2 rounded-full hover:bg-orange-50 transition-colors self-start sm:self-auto">
                    <i class="fas fa-times-circle"></i> Limpiar filtros
                </a>
            <?php endif; ?>
        </div>

        <!-- Layout Grid: Sidebar + Productos -->
        <div class="grid grid-cols-1 lg:grid-cols-[280px_1fr] gap-8 items-start">

            <!-- ── SIDEBAR DE FILTROS ── -->
            <aside class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-8 lg:sticky lg:top-24 self-start">

                <!-- Categorías -->
                <div>
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Categorías</h3>
                    <ul class="space-y-1 text-sm">
                        <li>
                            <a href="<?= BASE_URL ?>catalogo<?= $search ? '?q='.urlencode($search) : '' ?>"
                               class="flex items-center justify-between px-3 py-2 rounded-lg transition-colors <?= !$categoria ? 'bg-orange-50 text-naranja-artesanal font-semibold' : 'text-gray-600 hover:bg-gray-50' ?>">
                                <span>Todas</span>
                            </a>
                        </li>
                        <?php foreach ($categorias_list as $cat): ?>
                            <li>
                                <a href="<?= BASE_URL ?>catalogo?cat=<?= $cat['id_categoria'] ?><?= $search ? '&q='.urlencode($search) : '' ?>"
                                   class="flex items-center justify-between px-3 py-2 rounded-lg transition-colors <?= $categoria == $cat['id_categoria'] ? 'bg-orange-50 text-naranja-artesanal font-semibold' : 'text-gray-600 hover:bg-gray-50' ?>">
                                    <span><?= htmlspecialchars($cat['nom_categoria']) ?></span>
                                    <span class="text-xs text-gray-300 bg-gray-100 rounded-full px-2 py-0.5"><?= $cat['total'] ?></span>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <!-- Divisor -->
                <hr class="border-gray-100">

                <!-- Filtro de Precio -->
                <div>
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Precio</h3>
                    <form action="<?= BASE_URL ?>catalogo" method="GET" class="space-y-3">
                        <?php if ($search): ?><input type="hidden" name="q" value="<?= htmlspecialchars($search) ?>"><?php endif; ?>
                        <?php if ($categoria): ?><input type="hidden" name="cat" value="<?= htmlspecialchars($categoria) ?>"><?php endif; ?>

                        <div class="flex items-center gap-3">
                            <div class="flex-1">
                                <label class="text-xs text-gray-400 mb-1 block">Mínimo</label>
                                <input type="number" name="min_price" placeholder="$ 0" value="<?= $min_precio ?>"
                                       class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-naranja-artesanal/30 focus:border-naranja-artesanal">
                            </div>
                            <div class="flex-1">
                                <label class="text-xs text-gray-400 mb-1 block">Máximo</label>
                                <input type="number" name="max_price" placeholder="$ ∞" value="<?= $max_precio ?>"
                                       class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-naranja-artesanal/30 focus:border-naranja-artesanal">
                            </div>
                        </div>
                        <button type="submit"
                                class="w-full bg-naranja-artesanal hover:bg-tierra-oscuro text-white text-sm font-semibold py-2.5 rounded-lg transition-colors">
                            Aplicar filtro
                        </button>
                    </form>
                </div>

            </aside>

            <!-- ── ÁREA DE PRODUCTOS ── -->
            <div class="flex flex-col gap-6">

                <?php if (empty($productos)): ?>
                    <!-- Estado vacío -->
                    <div class="flex flex-col items-center justify-center bg-white rounded-2xl p-16 shadow-sm border border-gray-100 text-center">
                        <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-5">
                            <i class="fas fa-search text-gray-300 text-3xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-700 mb-2">Sin resultados</h3>
                        <p class="text-gray-400 text-sm max-w-xs">Revisa que la palabra esté bien escrita o intenta con menos filtros.</p>
                        <a href="<?= BASE_URL ?>catalogo" class="mt-6 px-6 py-2 bg-naranja-artesanal text-white rounded-full text-sm font-semibold hover:bg-tierra-oscuro transition-colors">
                            Ver todos los productos
                        </a>
                    </div>

                <?php else: ?>
                    <!-- Grilla de productos -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 xl:grid-cols-3 gap-6">
                        <?php
                        $show_price = true; // Mostrar precio en el catálogo
                        foreach ($productos as $product):
                            require ROOT_PATH . 'src/views/partials/card_producto.php';
                        endforeach;
                        ?>
                    </div>
                <?php endif; ?>

            </div>
            <!-- ── FIN ÁREA DE PRODUCTOS ── -->

        </div>
    </main>
    
    <?php require_once __DIR__ . '/partials/footer.php'; ?>
</body>
</html>
