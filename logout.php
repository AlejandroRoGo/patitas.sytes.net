<?php
// logout.php - Cerrar sesión de usuario
session_start();
session_unset();
session_destroy();
header('Location: index.php');
exit;
