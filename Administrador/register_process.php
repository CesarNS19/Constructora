<?php
require '../Administrador/conexion.php';

$nombre_cliente = $_POST['nombre_cliente'];
$apellido_paterno = $_POST['apellido_paterno'];
$apellido_materno = $_POST['apellido_materno'] ?? '';
$telefono_personal = $_POST['telefono_personal'];
$correo_electronico = $_POST['correo_electronico'];
$contrasena = $_POST['contrasena'];
$confirmar_contrasena = $_POST['confirmar_contrasena'];

// Verificar que las contraseñas coincidan
if ($contrasena !== $confirmar_contrasena) {
    die("Las contraseñas no coinciden.");
}

// Hashear la contraseña
$hashed_password = password_hash($contrasena, PASSWORD_DEFAULT);

// Procede a guardar en la base de datos
$sql = "INSERT INTO clientes (nombre_cliente, apellido_paterno, apellido_materno, telefono_personal, correo_electronico, contrasena, rol) 
        VALUES (?, ?, ?, ?, ?, ?, ?)";

$stmt = $con->prepare($sql); // Prepara la declaración
$rol = 'admin'; // Valor por defecto para el rol
$stmt->bind_param("sssssss", $nombre_cliente, $apellido_paterno, $apellido_materno, $telefono_personal, $correo_electronico, $hashed_password, $rol); // Vincula parámetros

if ($stmt->execute()) {
    // Redirige a index.html si la inserción fue exitosa
    header("Location: login.php");
    exit();
} else {
    // Maneja el error si la inserción falla
    echo "Error al registrar al usuario: " . $stmt->error;
}

// Cierra la declaración y la conexión
$stmt->close();
$con->close();
?>
