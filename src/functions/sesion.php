<?php
/**
 * sesion.php
 *
 * Archivo de arranque centralizado para la sesión.
 * Usar require_once de este archivo en cualquier endpoint o controlador
 * que necesite acceder a $_SESSION, garantizando que los parámetros
 * de la cookie sean siempre idénticos a los de index.php (el router principal).
 *
 * Uso:
 *   require_once __DIR__ . '/sesion.php';  // desde src/functions/
 *   require_once __DIR__ . '/../functions/sesion.php'; // desde src/api/
 */

if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'domain'   => '',       // Acepta localhost y 127.0.0.1
        'secure'   => false,    // true en producción con HTTPS
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}
