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
