<?php
/**
 * views/login/index.php
 * Vista del CU-02 — Inicio de Sesión.
 */
require_once __DIR__ . '/../../config/rutas.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>SICAWN — Iniciar Sesión</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/estilos.css">
</head>
<body>

    <div class="tarjeta-login">
        <h1>Iniciar Sesión</h1>
        <p class="subtitulo">Sistema Gestor del Cobro del Agua Nauak</p>

        <?php if (isset($_GET['sesion_cerrada'])): ?>
            <div class="alerta alerta-exito">Sesión cerrada correctamente.</div>
        <?php endif; ?>

        <?php if (isset($_GET['sin_rol'])): ?>
            <div class="alerta alerta-aviso">Tu cuenta aún no tiene un rol asignado. Contacta al Presidente.</div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alerta alerta-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="<?= BASE_URL ?>/controllers/LoginController.php">
            <div class="campo-formulario">
                <label for="nombre_usuario">Usuario</label>
                <input type="text" id="nombre_usuario" name="nombre_usuario" required autofocus>
            </div>

            <div class="campo-formulario">
                <label for="contrasena">Contraseña</label>
                <input type="password" id="contrasena" name="contrasena" required>
            </div>

            <button type="submit" class="btn btn-primario" style="width:100%;">Ingresar</button>
        </form>
    </div>

</body>
</html>