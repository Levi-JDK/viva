/**
 * Registro de Vendedor - Navegación Multi-paso
 * Maneja la lógica de navegación entre pasos del formulario
 */

let currentStep = 1;
const totalSteps = 2;

/**
 * Actualiza los indicadores visuales de progreso y pasos del formulario
 */
function updateStepIndicators() {
    // Actualizar barra de progreso
    const progress = ((currentStep - 1) / (totalSteps - 1)) * 100;
    document.getElementById('progressBar').style.width = progress + '%';

    // Actualizar indicadores de pasos
    document.querySelectorAll('.step-indicator').forEach((indicator, index) => {
        const step = index + 1;
        indicator.classList.remove('active', 'completed');

        if (step < currentStep) {
            indicator.classList.add('completed');
        } else if (step === currentStep) {
            indicator.classList.add('active');
        }
    });

    // Actualizar pasos del formulario
    document.querySelectorAll('.form-step').forEach((step, index) => {
        step.classList.remove('active');
        if (index + 1 === currentStep) {
            step.classList.add('active');
        }
    });

    // Scroll suave al inicio del contenedor
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

/**
 * Avanza al siguiente paso del formulario
 * Valida los campos requeridos antes de avanzar
 */
function nextStep() {
    const currentFormStep = document.querySelector(`.form-step[data-step="${currentStep}"]`);
    const inputs = currentFormStep.querySelectorAll('input[required], select[required]');

    // Validar campos del paso actual
    let isValid = true;
    inputs.forEach(input => {
        if (!input.checkValidity()) {
            input.reportValidity();
            isValid = false;
            return false;
        }
    });

    if (isValid && currentStep < totalSteps) {
        currentStep++;
        updateStepIndicators();
    }
}

/**
 * Retrocede al paso anterior del formulario
 */
function prevStep() {
    if (currentStep > 1) {
        currentStep--;
        updateStepIndicators();
    }
}

/* Maneja el envío del formulario*/
async function handleFormSubmit(event) {
    event.preventDefault();

    const formData = new FormData(event.target);

    // Mostrar estado de carga en el botón
    const submitBtn = event.target.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Procesando...';
    submitBtn.disabled = true;

    // URL del endpoint API que procesará el registro
    // Apunta a src/controllers/api/post_registro_vendedor.php
    const url = (typeof BASE_URL !== 'undefined' ? BASE_URL : '') + 'src/controllers/api/post_registro_vendedor.php';

    try {
        // Enviar datos usando fetch (AJAX) - Método asíncrono moderno
        const response = await fetch(url, {
            method: 'POST',
            body: formData // Contiene todos los campos del formulario automáticamente
        });

        const data = await response.json();

        if (data.success) {
            // Éxito: Mostrar mensaje y redirigir
            alert(data.message + '\n\nDatos guardados: ' + JSON.stringify(data.debug_params, null, 2));
            window.location.href = (typeof BASE_URL !== 'undefined' ? BASE_URL : '') + 'mis_productos';
        } else {
            // Error: Mostrar mensaje y datos debug
            console.error('Debug Params:', data.debug_params);
            alert('Error: ' + data.message + '\n\n[DEBUG] Datos enviados:\n' + JSON.stringify(data.debug_params, null, 2));

            const submitBtn = event.target.querySelector('button[type="submit"]');
            submitBtn.innerHTML = 'Finalizar <i class="fas fa-check ml-2"></i>';
            submitBtn.disabled = false;
        }

    } catch (error) {
        console.error('Error:', error);
        alert('Ocurrió un error de conexión.');
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    }
}

// Inicialización cuando el DOM está listo
document.addEventListener('DOMContentLoaded', function () {
    // Configurar el formulario
    const form = document.getElementById('vendorRegistrationForm');
    if (form) {
        form.addEventListener('submit', handleFormSubmit);
    }

    // Inicializar indicadores de paso
    updateStepIndicators();
});
