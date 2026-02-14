
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

                        <!-- Product Grid -->
                        <div class="grid sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                            <?php if (empty($productos)): ?>
                                <div class="col-span-full text-center py-10 text-gray-500">
                                    <i class="fas fa-box-open text-4xl mb-3 opacity-50"></i>
                                    <p>No tienes productos registrados aún.</p>
                                </div>
                            <?php else: ?>
                                <?php foreach ($productos as $producto): ?>
                                    <div class="border border-gray-200 rounded-xl overflow-hidden hover:shadow-lg transition-all group bg-white relative">
                                        <span class="absolute top-2 right-2 px-2 py-1 rounded text-xs font-bold <?= $producto['activo'] ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' ?>">
                                            <?= $producto['activo'] ? 'Activo' : 'Inactivo' ?>
                                        </span>
                                        <div class="relative h-48 bg-gray-100 flex items-center justify-center overflow-hidden">
                                            <?php if (!empty($producto['imagen'])): ?>
                                                <img src="<?= BASE_URL . $producto['imagen'] ?>" alt="<?= htmlspecialchars($producto['nom_producto']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                            <?php else: ?>
                                                <i class="fas fa-image text-gray-300 text-4xl"></i>
                                            <?php endif; ?>
                                        </div>
                                        <div class="p-4">
                                            <h3 class="font-semibold text-gray-800 mb-1 truncate"><?= htmlspecialchars($producto['nom_producto']) ?></h3>
                                            <p class="text-xs text-gray-500 mb-3"><?= htmlspecialchars($producto['nom_categoria']) ?></p>
                                            <div class="flex items-center justify-between mt-2 pt-3 border-t border-gray-100">
                                                <span class="text-lg font-bold text-tierra-oscuro">$<?= number_format($producto['precio_producto'], 0, ',', '.') ?></span>
                                                <div class="flex space-x-1">
                                                    <button class="text-gray-400 hover:text-blue-600 p-1.5 rounded-full hover:bg-blue-50 transition-colors"><i class="fas fa-edit"></i></button>
                                                    <button class="text-gray-400 hover:text-red-600 p-1.5 rounded-full hover:bg-red-50 transition-colors"><i class="fas fa-trash"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
