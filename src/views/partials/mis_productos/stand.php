
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
