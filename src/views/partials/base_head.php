<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title ?? 'VIVA | Artesanías Colombianas') ?></title>
    
    <script>const BASE_URL = '<?= defined('BASE_URL') ? BASE_URL : '/' ?>';</script>
    <link rel="stylesheet" href="<?= defined('BASE_URL') ? BASE_URL : '/' ?>src/styles/output.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <!-- CSS Extra dinámico (solo para páginas que lo necesiten) -->
    <?= $extra_css ?? '' ?>
    <!-- Toast notifications global -->
    <script src="<?= defined('BASE_URL') ? BASE_URL : '/' ?>src/scripts/toast.js"></script>
    <script src="<?= defined('BASE_URL') ? BASE_URL : '/' ?>src/scripts/favoritos.js"></script>
</head>
<body class="<?= htmlspecialchars($body_class ?? 'bg-fondo-claro font-sans text-oscuro flex flex-col min-h-screen') ?>">
