<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'cobrador') {
    header('Location: /controllers/LoginController.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>SICAWN — Panel Cobrador</title>
    <link rel="stylesheet" href="/assets/css/estilos.css">
</head>
<body>
    <h1>Bienvenido, <?= htmlspecialchars($_SESSION['nombre_completo']) ?></h1>
    <p>Panel del Cobrador (pendiente de construir — próximos CU).</p>
    <a href="/controllers/LogoutController.php">Cerrar sesión</a>
</body>
</html>