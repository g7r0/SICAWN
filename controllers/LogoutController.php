<?php
session_start();
session_destroy();
header('Location: /controllers/LoginController.php');
exit;