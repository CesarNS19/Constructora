<?php
$servername = "localhost"; // Cambia esto si tu servidor de MySQL es diferente
$username = "root";        // Usuario de la base de datos
$password = "";            // Contraseña de la base de datos
$database = "constructora"; // Nombre de la base de datos

// Establecer la conexión
$con = new mysqli($servername, $username, $password, $database);

// Verificar si la conexión es exitosa
if ($con->connect_error) {
    die("Error de conexión: " . $con->connect_error);
}
$con->set_charset("utf8");

?>
