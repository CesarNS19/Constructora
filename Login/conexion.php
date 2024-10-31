<?php
$servername = "localhost"; // Cambia esto si tu servidor de MySQL es diferente
$username = "root";        // Usuario de la base de datos
$password = "";        // Contraseña de la base de datos
$database = "constructora"; // Nombre de la base de datos

$con = new mysqli($servername, $username, $password, $database);

if ($con->connect_error) {
    die("Error de conexión: " . $con->connect_error);
}
?>
