/**
 * src/scripts/admin_dashboard.js
 * Lógica del Panel de Administración VIVA
 */

// ── Panel switcher ──────────────────────────────────────────────────────────
function showPanel(id) {
    // Ocultar todos
    document.querySelectorAll('.admin-panel').forEach(p => {
        p.classList.remove('admin-panel--active');
    });

    // Mostrar panel objetivo
    const target = document.getElementById('panel-' + id);
    if (target) {
        // Forzar reflow para disparar animación CSS
        void target.offsetWidth;
        target.classList.add('admin-panel--active');
    }

    // Actualizar título del breadcrumb
    const btn = document.querySelector(`[data-panel="${id}"]`);
    const nom = btn ? (btn.dataset.nom || id) : id;
    const titleEl = document.getElementById('panel-title');
    if (titleEl) titleEl.textContent = nom;

    // Actualizar estado activo en sidebar
    document.querySelectorAll('.sidebar-btn').forEach(b => b.classList.remove('sidebar-btn--active'));
    if (btn) btn.classList.add('sidebar-btn--active');

    // Cerrar sidebar en mobile
    if (window.innerWidth <= 768) closeSidebar();
}

// ── Mobile sidebar ──────────────────────────────────────────────────────────
function toggleSidebar() {
    const sidebar = document.getElementById('admin-sidebar');
    const overlay = document.getElementById('mobile-overlay');
    const isOpen = sidebar.classList.toggle('admin-sidebar--open');
    overlay.style.display = isOpen ? 'block' : 'none';
}

function closeSidebar() {
    document.getElementById('admin-sidebar').classList.remove('admin-sidebar--open');
    const overlay = document.getElementById('mobile-overlay');
    if (overlay) overlay.style.display = 'none';
}

// ── Logo image fallback ─────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    // Inicializar: asegurarse de que solo el primer panel esté visible
    document.querySelectorAll('.admin-panel:not(.admin-panel--active)').forEach(p => {
        p.style.display = 'none';
    });

    // Logo fallback si la imagen no carga
    const logoImg = document.querySelector('.admin-logo-img');
    if (logoImg) {
        logoImg.addEventListener('error', () => {
            logoImg.style.display = 'none';
            const fallback = document.getElementById('logo-fallback');
            if (fallback) fallback.style.display = 'inline';
        });
    }
});
