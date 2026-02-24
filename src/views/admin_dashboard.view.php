<?php
/**
 * src/views/admin_dashboard.view.php
 * Vista del Panel de Administración — VIVA
 * CSS: compilado en src/styles/output.css (via Tailwind input.css)
 * JS:  src/scripts/admin_dashboard.js
 */

$page_title = "Panel Admin — VIVA";
$body_class = "admin-layout overflow-hidden";
$extra_css   = '';

require_once __DIR__ . '/partials/base_head.php';

// ── Definición de menús del sidebar ────────────────────────────────────────
$menus = [
    ['panel_id' => 'overview',   'nom_menu' => 'Resumen General',   'icono_menu' => 'fas fa-home',        'grupo' => 'Resumen',  'color_icon' => 'text-amber-400'],
    ['panel_id' => 'usuarios',   'nom_menu' => 'Usuarios',           'icono_menu' => 'fas fa-users',       'grupo' => 'Gestión',  'color_icon' => 'text-sky-400'],
    ['panel_id' => 'productos',  'nom_menu' => 'Aprobar Productos',  'icono_menu' => 'fas fa-box-open',    'grupo' => 'Gestión',  'color_icon' => 'text-emerald-400'],
    ['panel_id' => 'roles',      'nom_menu' => 'Gestionar Roles',    'icono_menu' => 'fas fa-users-cog',   'grupo' => 'Gestión',  'color_icon' => 'text-rose-400'],
    ['panel_id' => 'crud',       'nom_menu' => 'Gestor de CRUD',     'icono_menu' => 'fas fa-database',    'grupo' => 'Gestión',  'color_icon' => 'text-yellow-400'],
    ['panel_id' => 'reportes',   'nom_menu' => 'Reportes',           'icono_menu' => 'fas fa-chart-line',  'grupo' => 'Gestión',  'color_icon' => 'text-teal-400'],
    ['panel_id' => 'parametros', 'nom_menu' => 'Parámetros DB',      'icono_menu' => 'fas fa-sliders-h',   'grupo' => 'Sistema',  'color_icon' => 'text-slate-400'],
];

$menus_por_grupo = [];
foreach ($menus as $m) {
    $menus_por_grupo[$m['grupo']][] = $m;
}

// ── Configuración de paneles secundarios ────────────────────────────────────
$panel_config = [
    'usuarios'   => ['color' => '#38BDF8', 'icon' => 'fa-users',      'desc' => 'Lista, edita y elimina los usuarios registrados en la plataforma VIVA.'],
    'productos'  => ['color' => '#34D399', 'icon' => 'fa-box-open',   'desc' => 'Gestiona los productos enviados por artesanos. Aprueba, rechaza o solicita cambios.'],
    'roles'      => ['color' => '#BC544B', 'icon' => 'fa-users-cog',  'desc' => 'Asigna y revoca menús y permisos a cada rol de usuario según las necesidades.'],
    'crud'       => ['color' => '#D4AF37', 'icon' => 'fa-database',   'desc' => 'Realiza operaciones CRUD directas sobre las tablas de la base de datos.'],
    'reportes'   => ['color' => '#2D9E73', 'icon' => 'fa-chart-line', 'desc' => 'Visualiza estadísticas, gráficas de ventas y reportes exportables del ecosistema VIVA.'],
    'parametros' => ['color' => '#94A3B8', 'icon' => 'fa-sliders-h',  'desc' => 'Configuración avanzada de parámetros del sistema y variables de entorno.'],
];

// ── Datos de actividad reciente (placeholder) ───────────────────────────────
$actividades = [
    ['color' => '#38BDF8', 'icon' => 'fa-user-plus',           'txt' => 'Nuevo usuario registrado',       'time' => 'Hace 3 min'],
    ['color' => '#D4AF37', 'icon' => 'fa-box-open',            'txt' => 'Producto enviado a revisión',    'time' => 'Hace 12 min'],
    ['color' => '#34D399', 'icon' => 'fa-circle-check',        'txt' => 'Producto aprobado exitosamente', 'time' => 'Hace 28 min'],
    ['color' => '#BC544B', 'icon' => 'fa-triangle-exclamation','txt' => 'Producto rechazado',             'time' => 'Hace 1 hora'],
    ['color' => '#a78bfa', 'icon' => 'fa-shield-halved',       'txt' => 'Rol de artesano asignado',      'time' => 'Hace 2 horas'],
];

// ── Accesos rápidos del overview ────────────────────────────────────────────
$accesos = [
    ['panel' => 'usuarios',   'icon' => 'fa-users',      'nom' => 'Usuarios',    'color' => '#38BDF8'],
    ['panel' => 'productos',  'icon' => 'fa-box-open',   'nom' => 'Productos',   'color' => '#34D399'],
    ['panel' => 'reportes',   'icon' => 'fa-chart-line', 'nom' => 'Reportes',    'color' => '#D4AF37'],
    ['panel' => 'crud',       'icon' => 'fa-database',   'nom' => 'Base Datos',  'color' => '#BC544B'],
];
?>

<!-- ── Mobile overlay ────────────────────────────────────────────────────── -->
<div id="mobile-overlay" onclick="closeSidebar()"
     class="hidden fixed inset-0 z-[49] bg-black/60 backdrop-blur-sm"></div>



    <!-- ════════════════════════ SIDEBAR ════════════════════════ -->
    <aside id="admin-sidebar" class="admin-sidebar">

        <!-- Logo -->
        <div class="admin-sidebar__logo">
            <a href="<?= BASE_URL ?>admin_dashboard" class="flex items-center gap-3 no-underline">
                <div class="admin-logo-mark">
                    <img src="<?= BASE_URL ?>images/Logo.png"
                         alt="VIVA"
                         class="admin-logo-img w-full h-full object-cover"
                         onerror="this.style.display='none'; document.getElementById('logo-fallback').style.display='inline'">
                    <span id="logo-fallback" class="admin-logo-wordmark" style="display:none;">V</span>
                </div>
                <div>
                    <p class="admin-logo-wordmark">VIVA</p>
                    <p class="text-[9px] tracking-widest uppercase text-white/20 mt-0.5">Panel de Control</p>
                </div>
            </a>
        </div>

        <!-- Navegación -->
        <nav class="flex-1 px-2 pb-2 overflow-y-auto">
            <?php foreach ($menus_por_grupo as $grupo => $items): ?>
                <p class="admin-nav-group-label"><?= htmlspecialchars($grupo) ?></p>
                <?php foreach ($items as $item):
                    $is_active = ($item['panel_id'] === 'overview') ? 'sidebar-btn--active' : '';
                ?>
                <button
                    onclick="showPanel('<?= htmlspecialchars($item['panel_id']) ?>')"
                    data-panel="<?= htmlspecialchars($item['panel_id']) ?>"
                    data-nom="<?= htmlspecialchars($item['nom_menu']) ?>"
                    class="sidebar-btn <?= $is_active ?>">
                    <i class="<?= htmlspecialchars($item['icono_menu']) ?> admin-btn-icon <?= htmlspecialchars($item['color_icon']) ?>"></i>
                    <?= htmlspecialchars($item['nom_menu']) ?>
                </button>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </nav>

        <!-- Footer de usuario -->
        <div class="admin-sidebar__footer">
            <div class="flex items-center gap-2.5 min-w-0">
                <img src="<?= BASE_URL . htmlspecialchars($foto_usuario ?? 'images/profiles/default.webp') ?>"
                     class="admin-user-avatar"
                     alt="Avatar">
                <div class="min-w-0">
                    <p class="text-xs font-semibold text-white truncate">
                        <?= htmlspecialchars($nombre_usuario ?? 'Administrador') ?>
                    </p>
                    <p class="text-[10px] text-white/25 truncate">
                        <?= htmlspecialchars($email_usuario ?? '') ?>
                    </p>
                </div>
            </div>
            <a href="<?= BASE_URL ?>logout" class="admin-logout-btn">
                <i class="fas fa-arrow-right-from-bracket text-xs"></i>
                Cerrar Sesión
            </a>
        </div>
    </aside>

    <!-- ════════════════════════ MAIN ════════════════════════ -->
    <div class="admin-main-wrapper">

        <!-- Topbar -->
        <header class="admin-topbar">
            <div class="flex items-center gap-3">
                <!-- Mobile menu button -->
                <button id="admin-mobile-menu-btn"
                        onclick="toggleSidebar()"
                        class="admin-topbar-action hidden">
                    <i class="fas fa-bars"></i>
                </button>

                <!-- Breadcrumb -->
                <div class="admin-breadcrumb">
                    <span class="admin-breadcrumb__product">VIVA</span>
                    <span class="admin-breadcrumb__sep">/</span>
                    <span id="panel-title">Resumen General</span>
                </div>
            </div>

            <!-- Cluster derecho -->
            <div class="flex items-center gap-2">
                <div class="admin-status-badge">
                    <div class="admin-status-dot"></div>
                    <span><?= date('d M Y') ?></span>
                </div>
                <a href="<?= BASE_URL ?>" class="admin-topbar-action" title="Ir al sitio">
                    <i class="fas fa-arrow-up-right-from-square"></i>
                </a>
            </div>
        </header>

        <!-- Contenido -->
        <main class="admin-content">

            <!-- ══════ PANEL: OVERVIEW ══════ -->
            <section id="panel-overview" class="admin-panel admin-panel--active">

                <!-- Welcome -->
                <div class="mb-6">
                    <h2 class="admin-section-heading">
                        Bienvenido, <?= htmlspecialchars(explode(' ', $nombre_usuario ?? 'Administrador')[0]) ?> 👋
                    </h2>
                    <p class="admin-section-sub">Aquí tienes una visión global del ecosistema VIVA hoy.</p>
                </div>

                <!-- Métricas -->
                <div class="grid grid-cols-2 xl:grid-cols-4 gap-4 mb-6">

                    <?php
                    $metrics = [
                        ['label' => 'Usuarios',   'icon' => 'fa-users',       'color' => '#38BDF8', 'sub' => 'Registrados en total',
                         'spark' => '0,50 20,38 40,44 60,28 80,32 100,18 120,22'],
                        ['label' => 'Artesanos',  'icon' => 'fa-paint-brush', 'color' => '#D4AF37', 'sub' => 'Activos en plataforma',
                         'spark' => '0,55 20,40 40,48 60,30 80,35 100,20 120,15'],
                        ['label' => 'Ingresos',   'icon' => 'fa-coins',       'color' => '#34D399', 'sub' => 'Mes actual',
                         'spark' => '0,45 20,42 40,35 60,38 80,22 100,28 120,12', 'prefix' => '$'],
                        ['label' => 'Pedidos',    'icon' => 'fa-shopping-bag','color' => '#BC544B', 'sub' => 'Total de órdenes',
                         'spark' => '0,52 20,44 40,46 60,36 80,40 100,24 120,30'],
                    ];
                    foreach ($metrics as $mc): ?>
                        <div class="admin-metric-card">
                            <svg class="admin-metric-sparkline" viewBox="0 0 120 60" fill="none">
                                <polyline points="<?= $mc['spark'] ?>"
                                    stroke="<?= $mc['color'] ?>" stroke-width="1.5" fill="none"/>
                                <polyline points="<?= $mc['spark'] ?> 120,60 0,60"
                                    fill="<?= $mc['color'] ?>" fill-opacity="0.12"/>
                            </svg>
                            <div class="flex items-start justify-between mb-3">
                                <p class="admin-metric-label"><?= $mc['label'] ?></p>
                                <div class="admin-icon-pill" style="background:<?= $mc['color'] ?>18;">
                                    <i class="fas <?= $mc['icon'] ?>" style="color:<?= $mc['color'] ?>;"></i>
                                </div>
                            </div>
                            <p class="admin-metric-num text-white"><?= ($mc['prefix'] ?? '') ?>—</p>
                            <p class="admin-metric-sub"><?= $mc['sub'] ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Fila inferior: actividad + accesos rápidos -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    <!-- Actividad reciente -->
                    <div class="admin-glass-card p-5">
                        <div class="mb-4">
                            <p class="admin-section-heading text-sm">Actividad Reciente</p>
                            <p class="admin-section-sub">Últimos eventos del sistema</p>
                        </div>
                        <?php foreach ($actividades as $act): ?>
                            <div class="admin-activity-row">
                                <div class="admin-activity-dot" style="background:<?= $act['color'] ?>;box-shadow:0 0 5px <?= $act['color'] ?>55;"></div>
                                <i class="fas <?= $act['icon'] ?> w-3.5 text-center text-xs flex-shrink-0"
                                   style="color:<?= $act['color'] ?>;"></i>
                                <p class="text-xs text-white/60 flex-1"><?= htmlspecialchars($act['txt']) ?></p>
                                <span class="text-[11px] text-white/20 whitespace-nowrap"><?= $act['time'] ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Accesos rápidos -->
                    <div class="admin-glass-card p-5">
                        <div class="mb-4">
                            <p class="admin-section-heading text-sm">Acceso Rápido</p>
                            <p class="admin-section-sub">Módulos más utilizados</p>
                        </div>
                        <div class="grid grid-cols-2 gap-2.5">
                            <?php foreach ($accesos as $ac): ?>
                                <button onclick="showPanel('<?= $ac['panel'] ?>')"
                                    class="flex flex-col items-start gap-2 p-3.5 rounded-xl bg-white/[0.03] border border-white/[0.05] hover:bg-white/[0.06] cursor-pointer transition-all duration-200 text-left group"
                                    style="--c: <?= $ac['color'] ?>">
                                    <i class="fas <?= $ac['icon'] ?> text-sm" style="color:<?= $ac['color'] ?>;"></i>
                                    <span class="text-[11px] font-semibold text-white/65 group-hover:text-white/90 tracking-wide">
                                        <?= htmlspecialchars($ac['nom']) ?>
                                    </span>
                                </button>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

            </section>

            <!-- ══════ PANELES SECUNDARIOS ══════ -->
            <?php foreach ($panel_config as $pid => $pcfg):
                $nom = '';
                foreach ($menus as $m) { if ($m['panel_id'] === $pid) { $nom = $m['nom_menu']; break; } }
            ?>
            <section id="panel-<?= $pid ?>" class="admin-panel">
                <div class="mb-5">
                    <h2 class="admin-section-heading"><?= htmlspecialchars($nom) ?></h2>
                    <p class="admin-section-sub">Módulo activo · Listo para implementar</p>
                </div>
                <div class="admin-module-card">
                    <div class="admin-module-icon" style="background:<?= $pcfg['color'] ?>18;">
                        <i class="fas <?= $pcfg['icon'] ?>" style="color:<?= $pcfg['color'] ?>;"></i>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-white mb-1.5"><?= htmlspecialchars($nom) ?></p>
                        <p class="text-[13px] text-white/40 max-w-lg leading-relaxed"><?= $pcfg['desc'] ?></p>
                    </div>
                    <div class="flex items-center gap-2 px-3 py-1.5 rounded-full w-fit"
                         style="background:<?= $pcfg['color'] ?>12; border:1px solid <?= $pcfg['color'] ?>22;">
                        <div class="w-1.5 h-1.5 rounded-full"
                             style="background:<?= $pcfg['color'] ?>;box-shadow:0 0 5px <?= $pcfg['color'] ?>88;"></div>
                        <span class="text-[10px] font-bold tracking-widest uppercase"
                              style="color:<?= $pcfg['color'] ?>;">En desarrollo</span>
                    </div>
                </div>
            </section>
            <?php endforeach; ?>

        </main>
    </div><!-- /.admin-main-wrapper -->


<!-- JS externo del admin dashboard -->
<script src="<?= BASE_URL ?>src/scripts/admin_dashboard.js"></script>

</body>
</html>