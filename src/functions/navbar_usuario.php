<?php
/**
 * src/functions/navbar_usuario.php
 *
 * Helper centralizado para preparar las variables que necesita el navbar (partials/navbar.php).
 *
 * PROBLEMA QUE RESUELVE
 * ---------------------
 * El navbar.php usa las variables $is_logged_in, $nombre_usuario, $foto_usuario,
 * $email_usuario y $es_productor para decidir qué mostrar (avatar del usuario vs
 * botón "Iniciar Sesión"). Sin este helper, cada controlador tenía que repetir el
 * mismo bloque de ~15 líneas para consultar la DB y poblar esas variables.
 *
 * USO
 * ---
 * Llama a esta función justo antes de hacer require de la vista:
 *
 *   require_once __DIR__ . '/../functions/navbar_usuario.php';
 *   cargar_datos_navbar();
 *
 * Después de llamarla, las variables quedan disponibles en el scope del controlador
 * y la vista las hereda normalmente (PHP comparte el scope de include/require).
 *
 * FUNCIONAMIENTO INTERNO
 * ----------------------
 * 1. Lee $_SESSION['id_user'] (seteado al hacer login en auth_controller.php).
 * 2. Si existe, consulta la DB vía el Singleton Database para obtener nom_user,
 *    mail_user, foto_user y si el usuario es productor (validarProductor).
 * 3. Asigna los valores a las variables en el scope del llamador usando referencias
 *    ($GLOBALS) para que sean accesibles desde cualquier punto del controlador.
 * 4. Si no hay sesión o la consulta falla, los valores quedan en sus defaults
 *    seguros (false / cadena vacía / imagen por defecto).
 *
 * NOTA PARA PRODUCCIÓN
 * --------------------
 * Esta función depende de que Database.php ya haya sido incluido. Si el controlador
 * aún no lo incluyó, la función lo importa por su cuenta con require_once.
 */

function cargar_datos_navbar(): void
{
    // Valores por defecto seguros (el navbar los lee aunque no haya sesión)
    $GLOBALS['is_logged_in']   = false;
    $GLOBALS['nombre_usuario'] = '';
    $GLOBALS['email_usuario']  = '';
    $GLOBALS['foto_usuario']   = 'images/default.jpg';
    $GLOBALS['es_productor']   = false;

    $id_user = $_SESSION['id_user'] ?? null;

    if (!$id_user) {
        return; // No hay sesión activa, los defaults son suficientes
    }

    try {
        // Database usa Singleton, así que no importa cuántas veces se llame getInstance()
        require_once __DIR__ . '/Database.php';
        $db = Database::getInstance();

        // Obtener datos del usuario autenticado
        $stmt    = $db->ejecutar('obtenerUsuarioPorId', [':id' => $id_user]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario) {
            $GLOBALS['is_logged_in']   = true;
            $GLOBALS['nombre_usuario'] = $usuario['nom_user']  ?? '';
            $GLOBALS['email_usuario']  = $usuario['mail_user'] ?? '';
            $GLOBALS['foto_usuario']   = !empty($usuario['foto_user'])
                                            ? $usuario['foto_user']
                                            : 'images/default.jpg';
        }

        // Verificar si el usuario es productor (para mostrar "Mis productos" o "Vender")
        $stmtProductor          = $db->ejecutar('validarProductor', [':id_user' => $id_user]);
        $GLOBALS['es_productor'] = (bool) $stmtProductor->fetchColumn();

    } catch (Exception $e) {
        // Error no crítico: el navbar simplemente muestra el estado de visitante.
        // Se registra en el log para trazabilidad.
        error_log('[navbar_usuario] Error cargando datos del usuario: ' . $e->getMessage());
    }
}
