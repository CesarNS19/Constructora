<?php
// Iniciar sesión
session_start();

// Eliminar todas las variables de sesión
$_SESSION = array();

// Destruir la sesión
session_destroy();

// Redirigir al usuario a la página de inicio de sesión u otra página después de cerrar sesión
header("Location: ../login/login.php");
exit();
?>
