<?php
require '../Login/conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre_empresa'];
    $telefono = $_POST['telefono'];
    $pagina = $_POST['pagina_web'];
    $correo = $_POST['correo_empresa'];

    $sql = "INSERT INTO empresa (nombre_empresa, telefono, pagina_web, correo_empresa)
            VALUES ('$nombre', '$telefono', '$pagina', '$correo')";

    if ($con->query($sql) === TRUE) {
        header("Location: company.php");
    } else {
        echo "Error: " . $sql . "<br>" . $con->error;
    }
}

$con->close();
?>
