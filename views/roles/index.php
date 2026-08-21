<?php
/**
 * views/roles/index.php
 * Vista del CU-01 — Gestión de Roles.
 * No accedas a este archivo directamente; se carga desde RolesController.php
 */
require_once __DIR__ . '/../../config/rutas.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>SICAWN — Gestión de Roles</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/estilos.css">
</head>
<body>

    <div class="barra-superior">
        <h1>Gestión de Roles</h1>
        <a href="<?= BASE_URL ?>/controllers/LogoutController.php">Cerrar sesión</a>
    </div>

    <div class="contenido">

        <?php if ($mensaje): ?>
            <div class="alerta <?= $tipoMensaje === 'exito' ? 'alerta-exito' : 'alerta-error' ?>">
                <?= htmlspecialchars($mensaje) ?>
            </div>
        <?php endif; ?>

        <?php if ($sinUsuarios): ?>
            <p>No existe ningún usuario registrado en el sistema.</p>
            <a href="<?= BASE_URL ?>/views/contribuyentes/registrar.php" class="btn btn-primario">Registrar Contribuyente</a>

        <?php else: ?>
            <table class="tabla-sicawn">
                <thead>
                    <tr>
                        <th>Nombre completo</th>
                        <th>Usuario</th>
                        <th>Teléfono</th>
                        <th>Rol actual</th>
                        <th>Asignar rol</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usuarios as $u): ?>
                        <tr>
                            <td><?= htmlspecialchars($u['nombre_completo']) ?></td>
                            <td><?= htmlspecialchars($u['nombre_usuario']) ?></td>
                            <td><?= htmlspecialchars($u['telefono']) ?></td>
                            <td><?= $u['rol'] ? htmlspecialchars(ucfirst($u['rol'])) : '— Sin asignar —' ?></td>
                            <td>
                                <form method="POST" action="<?= BASE_URL ?>/controllers/RolesController.php" class="form-inline">
                                    <input type="hidden" name="id_usuario" value="<?= $u['id_usuario'] ?>">
                                    <select name="rol" required>
                                        <option value="">Selecciona...</option>
                                        <option value="presidente" <?= $u['rol'] === 'presidente' ? 'selected' : '' ?>>Presidente</option>
                                        <option value="cobrador" <?= $u['rol'] === 'cobrador' ? 'selected' : '' ?>>Cobrador</option>
                                    </select>
                                    <button type="submit" class="btn btn-primario">Asignar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

    </div>

</body>
</html>