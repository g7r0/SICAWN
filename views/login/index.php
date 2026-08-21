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

    <h1>Gestión de Roles</h1>
    <a href="<?= BASE_URL ?>/controllers/LogoutController.php" style="float:right;">Cerrar sesión</a>

    <?php if ($mensaje): ?>
        <p style="padding:10px; border-radius:6px; background-color: <?= $tipoMensaje === 'exito' ? '#d1fae5' : '#fee2e2' ?>;">
            <?= htmlspecialchars($mensaje) ?>
        </p>
    <?php endif; ?>

    <?php if ($sinUsuarios): ?>
        <p>No existe ningún usuario registrado en el sistema.</p>
        <a href="<?= BASE_URL ?>/views/contribuyentes/registrar.php" class="btn btn-primario">Registrar Contribuyente</a>

    <?php else: ?>
        <table border="1" cellpadding="8" cellspacing="0" style="width:100%; border-collapse: collapse;">
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
                            <form method="POST" action="<?= BASE_URL ?>/controllers/RolesController.php" style="display:flex; gap:8px;">
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

</body>
</html>