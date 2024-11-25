<?php
require 'conexion.php';

$nombre_cliente = $_POST['nombre_cliente'];
$apellido_paterno = $_POST['apellido_paterno'];
$apellido_materno = $_POST['apellido_materno'] ?? '';
$genero = $_POST['genero_cliente'];
$telefono_personal = $_POST['telefono_personal'];
$correo_electronico = $_POST['correo_electronico'];
$edad = $_POST['edad'];
$contrasena = $_POST['contrasena'];
$confirmar_contrasena = $_POST['confirmar_contrasena'];
$estatus = 'activo';

if ($contrasena !== $confirmar_contrasena) {
    die("Las contraseñas no coinciden.");
}

$sql_check_email = "SELECT id_cliente FROM clientes WHERE correo_electronico = ?";
$stmt_check_email = $con->prepare($sql_check_email);
$stmt_check_email->bind_param("s", $correo_electronico);
$stmt_check_email->execute();
$stmt_check_email->store_result();

if ($stmt_check_email->num_rows > 0) {
    die("El correo electrónico ya está registrado. Por favor, elija otro.");
}

$hashed_password = password_hash($contrasena, PASSWORD_DEFAULT);

$sql = "INSERT INTO clientes (nombre_cliente, apellido_paterno, apellido_materno, genero_cliente, telefono_personal, correo_electronico, contrasena, edad, rol, estatus) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $con->prepare($sql); 
$rol = 'usuario';
$stmt->bind_param("ssssssssss", $nombre_cliente, $apellido_paterno, $apellido_materno, $genero, $telefono_personal, $correo_electronico, $hashed_password, $edad, $rol, $estatus); // Vincula parámetros

if ($stmt->execute()) {
    header("Location: login.php");
    exit();
} else {
    echo "Error al registrar al usuario: " . $stmt->error;
}

$stmt->close();
$con->close();
?>
