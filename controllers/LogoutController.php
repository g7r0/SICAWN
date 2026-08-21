<?php
session_start();
require_once __DIR__ . '/../config/rutas.php';

session_destroy();
header('Location: ' . BASE_URL . '/controllers/LoginController.php?sesion_cerrada=1');
exit;