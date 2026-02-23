<?php
// Logout - Cerrar sesión del usuario


// Destruir la sesión
session_unset();
session_destroy();

// Redirigir al inicio
header('Location: ' . BASE_URL);
exit;
?>
