<?php
/**
 * views/login/index.php
 * Vista del CU-02 — Inicio de Sesión.
 */
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>SICAWN — Iniciar Sesión</title>
    <link rel="stylesheet" href="/assets/css/estilos.css">
</head>
<body>

    <div style="max-width:400px; margin:80px auto; padding:24px; border:1px solid #ccc; border-radius:8px;">
        <h1>Iniciar Sesión</h1>
        <p>Sistema Gestor del Cobro del Agua Nauak</p>

        <?php if (isset($_GET['sin_rol'])): ?>
            <p style="padding:10px; border-radius:6px; background-color:#fef3c7;">
                Tu cuenta aún no tiene un rol asignado. Contacta al Presidente.
            </p>
        <?php endif; ?>

        <?php if ($error): ?>
            <p style="padding:10px; border-radius:6px; background-color:#fee2e2;">
                <?= htmlspecialchars($error) ?>
            </p>
        <?php endif; ?>

        <form method="POST" action="/controllers/LoginController.php">
            <label for="nombre_usuario">Usuario</label><br>
            <input type="text" id="nombre_usuario" name="nombre_usuario" required autofocus><br><br>

            <label for="contrasena">Contraseña</label><br>
            <input type="password" id="contrasena" name="contrasena" required><br><br>

            <button type="submit" class="btn btn-primario">Ingresar</button>
        </form>
    </div>

</body>
</html>