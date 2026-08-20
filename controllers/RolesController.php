<?php
/**
 * controllers/RolesController.php
 * Controlador del CU-01 — Gestionar Roles.
 */

session_start();
require_once __DIR__ . '/../models/Usuario.php';

// --- Candado de sesión (CU-02 aún no está listo, ver nota de pruebas abajo) ---
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'presidente') {
    http_response_code(403);
    die('Acceso denegado. Solo el Presidente puede gestionar roles. (Simula tu sesión — ver instrucciones de prueba)');
}

$mensaje = null;
$tipoMensaje = null; // 'exito' | 'error'

// --- Procesar asignación de rol (POST) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_usuario'], $_POST['rol'])) {
    $idUsuario = (int) $_POST['id_usuario'];
    $rolSeleccionado = $_POST['rol'];

    $exito = Usuario::asignarRol($idUsuario, $rolSeleccionado);

    if ($exito) {
        $mensaje = 'Rol asignado correctamente.';
        $tipoMensaje = 'exito';
    } else {
        $mensaje = 'Ocurrió un error al guardar la asignación. Inténtalo de nuevo.';
        $tipoMensaje = 'error';
    }
}

// --- Obtener listado actualizado para mostrar en la vista ---
$usuarios = Usuario::obtenerTodos();

// --- Caso 1.A de la ERS: no hay usuarios registrados ---
$sinUsuarios = empty($usuarios);

require __DIR__ . '/../views/roles/index.php';