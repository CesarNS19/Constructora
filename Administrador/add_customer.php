<?php
require '../Login/conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre_cliente'];
    $apellido_paterno = $_POST['apellido_paterno'];
    $apellido_materno = $_POST['apellido_materno'];
    $genero = $_POST['genero_cliente'];
    $telefono_personal = $_POST['telefono_personal'];
    $correo = $_POST['correo_electronico'];
    $contrasena = $_POST['contrasena'];
    $confirmar_contrasena = $_POST['confirmar_contrasena'];
    $edad = $_POST['edad'];
    $rol = $_POST['rol'];

    if ($contrasena !== $confirmar_contrasena) {
        die("Las contraseñas no coinciden.");
    }
    
    $hashed_password = password_hash($contrasena, PASSWORD_DEFAULT);

    $sql = "INSERT INTO clientes (nombre_cliente, apellido_paterno, apellido_materno, genero_cliente, telefono_personal, correo_electronico, contrasena, edad, rol)
        VALUES ('$nombre', '$apellido_paterno', '$apellido_materno', '$genero', '$telefono_personal', '$correo', '$hashed_password', '$edad', '$rol')";

    if ($con->query($sql) === TRUE) {
        header("Location: customers.php");
    } else {
        echo "Error: " . $sql . "<br>" . $con->error;
    }
}

$con->close();
?>
