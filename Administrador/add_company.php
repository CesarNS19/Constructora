<?php
require '../Login/conexion.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre_empresa'];
    $telefono = $_POST['telefono'];
    $pagina = $_POST['pagina_web'];
    $correo = $_POST['correo_empresa'];

    $sql = "INSERT INTO empresa (nombre_empresa, telefono, pagina_web, correo_empresa)
            VALUES ('$nombre', '$telefono', '$pagina', '$correo')";

if ($con->query($sql) === TRUE) {
    $_SESSION['status_message'] = "Empresa agregada exitosamente.";
    $_SESSION['status_type'] = "success";
} else {
    $_SESSION['status_message'] = "Error al agregar la empresa: " . $con->error;
    $_SESSION['status_type'] = "danger";
}

header("Location: company.php");
exit();
}

$con->close();
?>
