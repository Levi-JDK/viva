tailwind.config = {
    theme: {
        extend: {
            colors: {
                principal: '#b15b0a',
                secundario: '#a04e07',
                claro: '#F5E9D3',
                oscuro: '#4A3B2B',
                'fondo-claro': '#fff',
                'fondo-oscuro': '#eee',
                'tierra-oscuro': '#8B4513',
                'tierra-medio': '#CD853F',
                'tierra-claro': '#DEB887',
                'verde-artesanal': '#6B8E23',
                'naranja-artesanal': '#D2691E',
                'beige-suave': '#F5F5DC',
            },
            fontFamily: {
                sans: ['Outfit', 'sans-serif'],
            }
        }
    }
}

let isTransitioning = false;
const pageTitle = document.getElementById('pageTitle');
const sidebar = document.getElementById('sidebar');
const sidebarOverlay = document.getElementById('sidebarOverlay');

function showSection(sectionId) {
    if (isTransitioning) return;

    // Find active section
    const currentSection = document.querySelector('.content-section.active');
    const targetSection = document.getElementById(sectionId);

    // If the section is already active, do nothing
    if (currentSection && currentSection.id === sectionId) return;

    // Update Menu Active State
    document.querySelectorAll('.menu-item').forEach(item => {
        item.classList.remove('active-item');
        // Check exact match or containing string appropriately
        if (item.getAttribute('onclick')?.includes(`'${sectionId}'`)) {
            item.classList.add('active-item');
            // Update Title Header based on active menu text
            const menuText = item.querySelector('span').innerText;
            if (pageTitle) pageTitle.innerText = menuText;
        }
    });

    // Handle Mobile Sidebar Close
    if (window.innerWidth < 1024) {
        sidebar.classList.remove('translate-x-0');
        sidebar.classList.add('-translate-x-full');
        sidebarOverlay.classList.add('hidden');
    }

    // Section Transition
    if (currentSection) {
        isTransitioning = true;
        currentSection.classList.add('closing');
        currentSection.classList.remove('active');

        setTimeout(() => {
            currentSection.classList.remove('closing');
            currentSection.style.display = 'none';

            if (targetSection) {
                targetSection.style.display = 'block';
                requestAnimationFrame(() => {
                    targetSection.classList.add('active');
                    isTransitioning = false;
                });
            } else {
                isTransitioning = false;
            }
        }, 300);
    } else {
        if (targetSection) {
            targetSection.style.display = 'block';
            requestAnimationFrame(() => {
                targetSection.classList.add('active');
            });
        }
    }
}

function toggleSidebar() {
    console.log("Toggle sidebar called");
    if (!sidebar || !sidebarOverlay) {
        console.error("Sidebar elements not found");
        return;
    }

    const isClosed = sidebar.classList.contains('-translate-x-full');
    if (isClosed) {
        sidebar.classList.remove('-translate-x-full');
        sidebar.classList.add('translate-x-0');
        sidebarOverlay.classList.remove('hidden');
    } else {
        sidebar.classList.remove('translate-x-0');
        sidebar.classList.add('-translate-x-full');
        sidebarOverlay.classList.add('hidden');
    }
}

// Make functions globally available
window.showSection = showSection;
window.toggleSidebar = toggleSidebar;

// ==========================================
// Product Image Upload Logic (Multiple)
// ==========================================
document.addEventListener('DOMContentLoaded', () => {
    const productInput = document.getElementById('product-images-input');
    const uploadForm = document.getElementById('product-upload-form');
    const gridContainer = document.getElementById('image-preview-grid');

    // 5. Validacion de inputs numericos (Sin negativos)
    const numericInputs = document.querySelectorAll('input[type="number"]');
    numericInputs.forEach(input => {
        input.addEventListener('keydown', function (e) {
            // Prevenir caracteres inválidos: -, +, e
            if (['-', '+', 'e', 'E'].includes(e.key)) {
                e.preventDefault();
            }
        });

        input.addEventListener('input', function () {
            // Asegurar que no sea menor a 1 si tiene valor
            if (this.value !== '' && parseFloat(this.value) < 0) {
                this.value = Math.abs(parseFloat(this.value));
            }
        });
    });

    // Almacenar archivos seleccionados (Array de File objects)
    let selectedImages = [];
    const MAX_IMAGES = 4;

    if (productInput && uploadForm && gridContainer) {

        // 1. Manejar selección de archivos
        productInput.addEventListener('change', function (e) {
            const files = Array.from(this.files);

            // Validar cantidad
            if (selectedImages.length + files.length > MAX_IMAGES) {
                showToast(`Máximo ${MAX_IMAGES} imágenes permitidas.`, 'error');
                this.value = ''; // Reset input
                return;
            }

            // Procesar cada archivo
            files.forEach(file => {
                // Validar tipo
                const allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
                const fileExtension = file.name.split('.').pop().toLowerCase();

                if (!allowedExtensions.includes(fileExtension)) {
                    showToast(`Formato no permitido: ${file.name}`, 'error');
                    return;
                }

                // Validar tamaño (5MB)
                if (file.size > 5 * 1024 * 1024) {
                    showToast(`Archivo muy pesado: ${file.name}`, 'error');
                    return;
                }

                // Agregar a la lista
                selectedImages.push(file);
            });

            // Limpiar input para permitir seleccionar los mismos archivos de nuevo si se borran
            this.value = '';

            renderPreviews();
        });

        // 2. Renderizar previsualizaciones
        function renderPreviews() {
            gridContainer.innerHTML = ''; // Limpiar todo el grid

            // Renderizar Imágenes Seleccionadas
            selectedImages.forEach((file, index) => {
                const reader = new FileReader();

                // Crear contenedor temporal
                const slot = document.createElement('div');
                slot.className = 'bg-gray-100 rounded-lg aspect-square flex items-center justify-center relative overflow-hidden group border border-gray-200';

                // Placeholder de carga
                slot.innerHTML = '<i class="fas fa-spinner fa-spin text-gray-400"></i>';
                gridContainer.appendChild(slot);

                reader.onload = function (e) {
                    slot.innerHTML = `
                        <img src="${e.target.result}" class="w-full h-full object-cover">
                        <button type="button" class="absolute top-1 right-1 bg-white text-red-500 rounded-full p-1 shadow-md opacity-0 group-hover:opacity-100 transition-opacity hover:bg-red-50 z-10" onclick="removeImage(${index})">
                            <i class="fas fa-times text-xs w-4 h-4 flex items-center justify-center"></i>
                        </button>
                        ${index === 0 ? '<span class="absolute bottom-0 left-0 right-0 bg-black/50 text-white text-[10px] text-center py-1">Principal</span>' : ''}
                    `;
                }
                reader.readAsDataURL(file);
            });

            // Renderizar Botón de Agregar (si no se ha llegado al límite)
            if (selectedImages.length < MAX_IMAGES) {
                const addBtn = document.createElement('div');
                addBtn.onclick = () => document.getElementById('product-images-input').click();
                addBtn.className = 'border-2 border-dashed border-naranja-artesanal/30 rounded-lg aspect-square flex flex-col items-center justify-center text-center hover:bg-orange-50 transition-colors cursor-pointer bg-orange-50/30 relative overflow-hidden group';
                addBtn.innerHTML = `
                    <i class="fas fa-plus text-2xl text-naranja-artesanal mb-2 group-hover:scale-110 transition-transform"></i>
                    <span class="text-xs text-naranja-artesanal font-medium">Agregar</span>
                `;
                gridContainer.appendChild(addBtn);
            }

            // Rellenar con placeholders vacíos si faltan para completar la grilla
            // Si queremos mantener siempre 4 espacios visibles en la grilla final
            // El botón de agregar cuenta como 1 espacio si está visible
            const itemsVisibles = selectedImages.length + (selectedImages.length < MAX_IMAGES ? 1 : 0);
            const slotsRestantes = MAX_IMAGES - itemsVisibles;

            for (let i = 0; i < slotsRestantes; i++) {
                const placeholder = document.createElement('div');
                placeholder.className = 'border-2 border-dashed border-gray-200 rounded-lg aspect-square flex items-center justify-center bg-gray-50 opacity-50';
                placeholder.innerHTML = '<i class="fas fa-image text-gray-300"></i>';
                gridContainer.appendChild(placeholder);
            }
        }

        // 3. Función global para remover imagen
        window.removeImage = function (index) {
            selectedImages.splice(index, 1);
            renderPreviews();
        };

        // 4. Manejar envío del formulario
        uploadForm.addEventListener('submit', function (e) {
            e.preventDefault();

            if (selectedImages.length === 0) {
                showToast('Debes agregar al menos una imagen principal.', 'error');
                return;
            }

            const formData = new FormData(this);

            // Reemplazar los archivos del input original con nuestro array gestionado
            // Nota: formData.delete('imagen_producto[]') no es necesario porque el input se limpió
            // Pero nos aseguramos de agregar nuestros archivos

            selectedImages.forEach((file, index) => {
                formData.append('imagen_producto[]', file);
            });

            showToast('Publicando producto...', 'info');

            fetch('src/functions/upload_product.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast('Producto publicado exitosamente.', 'success');
                        // Resetear todo
                        selectedImages = [];
                        renderPreviews();
                        this.reset();
                        // Opcional: Redirigir o actualizar lista
                    } else {
                        showToast(data.message || 'Error al publicar producto.', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Error de conexión.', 'error');
                });
        });
    }
});



const inputSencillo = document.getElementById('miInput');

inputSencillo?.addEventListener('keypress', function (e) {
    // Bloquea el signo menos (código 45) y el punto/coma si solo quieres enteros
    if (e.key === '-' || e.key === '.' || e.key === ',') {
        e.preventDefault();
    }
});


// ==========================================
// Stand View Logic
// ==========================================

// Image Preview Helper
window.previewImage = function (input, imgId) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function (e) {
            document.getElementById(imgId).src = e.target.result;
        }
        reader.readAsDataURL(input.files[0]);
    }
}

// Background Image Preview Helper (for Banner)
window.previewBackground = function (input, elementId) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function (e) {
            const element = document.getElementById(elementId);
            if (element) {
                element.style.backgroundImage = `url('${e.target.result}')`;
                element.style.backgroundSize = 'cover';
                element.style.backgroundPosition = 'center';
            }
        }
        reader.readAsDataURL(input.files[0]);
    }
}

document.addEventListener('DOMContentLoaded', () => {
    // Stand Form Submission
    const standForm = document.getElementById('stand-form');
    if (standForm) {
        standForm.addEventListener('submit', async function (e) {
            e.preventDefault();
            const formData = new FormData(this);

            showToast('Guardando cambios...', 'info');

            try {
                // Use the main controller route which handles POST requests for stand updates
                const response = await fetch('mis_productos?view=stand', {
                    method: 'POST',
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    showToast(data.message, 'success');
                } else {
                    showToast(data.message, 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('Error de conexión', 'error');
            }
        });
    }

    // Banner Click Trigger - Removed as it is now handled inline in the HTML
    // const bannerPlaceholder = document.getElementById('banner-placeholder'); ...
});

// ==========================================
// Global Preview Functions (called from inline HTML attributes)
// ==========================================

// Preview image in an img element (used for logo)
window.previewImage = function (input, imgId) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function (e) {
            const img = document.getElementById(imgId);
            if (img) {
                img.src = e.target.result;
            }
        }
        reader.readAsDataURL(input.files[0]);
    }
}

// Preview image as background (used for banner)
window.previewBackground = function (input, elementId) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function (e) {
            const element = document.getElementById(elementId);
            if (element) {
                element.style.backgroundImage = `url('${e.target.result}')`;
                element.style.backgroundSize = 'cover';
                element.style.backgroundPosition = 'center';
            }
        }
        reader.readAsDataURL(input.files[0]);
    }
}
