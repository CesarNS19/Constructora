<?php
$servername = "localhost"; // Cambia esto si tu servidor de MySQL es diferente
$username = "root";        // Usuario de la base de datos
$password = "";        // Contraseña de la base de datos
$database = "construct"; // Nombre de la base de datos

// Crear conexión
$con = new mysqli($servername, $username, $password, $database);

// Verificar conexión
if ($con->connect_error) {
    die("Error de conexión: " . $con->connect_error);
}
?>
