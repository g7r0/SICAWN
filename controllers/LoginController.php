<?php
/**
 * controllers/LoginController.php
 * Controlador del CU-02 — Iniciar Sesión.
 */

session_start();
require_once __DIR__ . '/../models/Usuario.php';

$error = null;

// Si ya hay sesión activa, redirige directo a su panel
if (isset($_SESSION['rol'])) {
    redirigirSegunRol($_SESSION['rol']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombreUsuario = trim($_POST['nombre_usuario'] ?? '');
    $contrasena = $_POST['contrasena'] ?? '';

    // --- Alternativa 2.A: campos vacíos ---
    if ($nombreUsuario === '' || $contrasena === '') {
        $error = 'Ambos campos son obligatorios.';
    } else {
        $usuario = Usuario::obtenerPorNombreUsuario($nombreUsuario);

        if ($usuario === null) {
            // --- Alternativa 3.A: no revelar cuál dato es incorrecto ---
            $error = 'Usuario o contraseña incorrectos.';
        } else {
            // --- Verificar si está bloqueado (RF_03) ---
            $minutosRestantes = Usuario::minutosDeBloqueoRestantes($usuario);

            if ($minutosRestantes > 0) {
                $error = "Cuenta bloqueada temporalmente. Intenta de nuevo en {$minutosRestantes} minuto(s).";
            } elseif (!password_verify($contrasena, $usuario['contrasena'])) {
                // --- Credenciales incorrectas: registrar intento fallido (RF_03) ---
                Usuario::registrarIntentoFallido($usuario['id_usuario']);

                $intentosPrevios = $usuario['intentos_fallidos'] + 1;
                if ($intentosPrevios >= 5) {
                    $error = 'Has alcanzado el límite de intentos. Tu cuenta quedó bloqueada por 30 minutos.';
                } else {
                    $error = 'Usuario o contraseña incorrectos.';
                }
            } else {
                // --- Login exitoso ---
                Usuario::reiniciarIntentos($usuario['id_usuario']);

                $_SESSION['id_usuario'] = $usuario['id_usuario'];
                $_SESSION['nombre_completo'] = $usuario['nombre_completo'];
                $_SESSION['rol'] = $usuario['rol'];

                redirigirSegunRol($usuario['rol']);
                exit;
            }
        }
    }
}

function redirigirSegunRol(?string $rol): void
{
    if ($rol === 'presidente') {
        header('Location: /SICAWN/controllers/RolesController.php');
    } elseif ($rol === 'cobrador') {
        header('Location: /views/panel/cobrador.php');
    } else {
        // Usuario sin rol asignado todavía (caso de borde, CU-01 pendiente)
        session_destroy();
        header('Location: //controllers/LoginController.php?sin_rol=1');
    }
}

require __DIR__ . '/SICAWN/views/login/index.php';