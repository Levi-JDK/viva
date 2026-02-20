<?php
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params(0, '/');
    session_start();
}


try {
    require_once(__DIR__ . '/Database.php');
} catch (Error $e) {
    header('Content-Type: application/json');
    echo json_encode([
        "mensaje" => "Error crítico de carga: " . $e->getMessage(),
        "clase" => "mensaje-error"
    ]);
    exit;
}
header('Content-Type: application/json');

try {
    // Usar Singleton pattern
    $db = Database::getInstance();
} catch (Exception $e) {
    echo json_encode([
        "mensaje" => "Error al inicializar la base de datos: " . $e->getMessage(),
        "clase" => "mensaje-error"
    ]);
    exit;
}


// Manejar solicitud GET para verificar sesión
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    session_start();
    
    if (isset($_SESSION['email']) && isset($_SESSION['nombre'])) {
        echo json_encode([
            'loggedIn' => true,
            'nombre' => $_SESSION['nombre'],
            'email' => $_SESSION['email']
        ]);
    } else {
        echo json_encode([
            'loggedIn' => false
        ]);
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';

    if ($accion === 'registro') {
        // Registro
        $nombre = $_POST['nombre'] ?? '';
        $apellido = $_POST['apellido'] ?? '';
        $email = $_POST['email'] ?? '';
        $contrasena = $_POST['contrasena'] ?? '';

        if (empty($nombre) || empty($apellido) || empty($email) || empty($contrasena)) {
            echo json_encode([
                "mensaje" => "Todos los campos son obligatorios.",
                "clase" => "mensaje-error"
            ]);
            exit;
        }

        // Validar caracteres en nombre (# * - ' ")
        if (preg_match('/[#*\-\'"]/', $nombre)) {
            echo json_encode([
                "mensaje" => "El nombre no puede contener los caracteres: # * - ' \"",
                "clase" => "mensaje-error"
            ]);
            exit;
        }

        // Validar caracteres en apellido (' ")
        if (preg_match('/[\'"]/', $apellido)) {
            echo json_encode([
                "mensaje" => "El apellido no puede contener comillas (' \")",
                "clase" => "mensaje-error"
            ]);
            exit;
        }

        // Validar formato de email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode([
                "mensaje" => "El correo electrónico no es válido.",
                "clase" => "mensaje-error"
            ]);
            exit;
        }

        $hash = password_hash($contrasena, PASSWORD_ARGON2ID);

        try {
            // Validar email usando consulta preparada
            $stmtcheck = $db->ejecutar('validarEmail', [':email' => $email]);
            $result = $stmtcheck->fetchColumn();

            if (!$result) {
                echo json_encode([
                    "mensaje" => "El correo ya está registrado.",
                    "clase" => "mensaje-error"
                ]);
                exit;
            }

            // Crear usuario usando consulta preparada
            $stmt = $db->ejecutar('crearUsuario', [
                ':email' => $email,
                ':contrasena' => $hash,
                ':nombre' => $nombre,
                ':apellido' => $apellido
            ]);

            echo json_encode([
                "mensaje" => "Usuario registrado correctamente.",
                "clase" => "mensaje-exito"
            ]);
        } catch (PDOException $e) {
            echo json_encode([
                "mensaje" => "Error en el registro: " . $e->getMessage(),
                "clase" => "mensaje-error"
            ]);
        }

    } elseif ($accion === 'login') {
        // Login
        $email = $_POST['email'] ?? '';
        $contrasena = $_POST['contrasena'] ?? '';

        if (empty($email) || empty($contrasena)) {
            echo json_encode([
                "mensaje" => "Todos los campos son obligatorios.",
                "clase" => "mensaje-error"
            ]);
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode([
                "mensaje" => "El correo electrónico no es válido.",
                "clase" => "mensaje-error"
            ]);
            exit;
        }

        try {
            // Obtener hash de contraseña usando consulta preparada
            $stmt = $db->ejecutar('obtenerHashLogin', [':email' => $email]);
            $hash = $stmt->fetchColumn();

            if ($hash && password_verify($contrasena, $hash)) {
                // Iniciar sesión
                // Iniciar sesión (ya iniciada arriba)
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
                $_SESSION['email'] = $email;

                // Obtener datos del usuario usando consulta preparada
                $stmtUsuario = $db->ejecutar('obtenerUsuarioPorEmail', [':email' => $email]);
                $usuario = $stmtUsuario->fetch(PDO::FETCH_ASSOC);

                if ($usuario) {
                    $_SESSION['id_user'] = $usuario['id_user'];  
                    $_SESSION['nombre'] = $usuario['nom_user'];
                } else {
                    $_SESSION['nombre'] = $email;
                }

                echo json_encode([
                    "mensaje" => "Inicio de sesión exitoso",
                    "clase" => "mensaje-exito"
                ]);
            } else {
                echo json_encode([
                    "mensaje" => "❌ Correo o contraseña incorrectos",
                    "clase" => "mensaje-error"
                ]);
            }
        } catch (PDOException $e) {
            echo json_encode([
                "mensaje" => "Error en la base de datos: " . $e->getMessage(),
                "clase" => "mensaje-error"
            ]);
        }

    } else {
        echo json_encode([
            "mensaje" => "Acción no válida.",
            "clase" => "mensaje-error"
        ]);
    }
}
?>