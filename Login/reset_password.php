<?php
require 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['email'])) {
    $email = $_POST['email'];
    $newPassword = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    if ($newPassword === $confirmPassword) {
        // Encriptar la nueva contraseña
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        // Actualizar la contraseña en la base de datos
        $stmt = $con->prepare("UPDATE clientes SET contrasena = ? WHERE correo_electronico = ?");
        $stmt->bind_param("ss", $hashedPassword, $email);

        if ($stmt->execute()) {
            // Redirigir al usuario al inicio de sesión
            header("Location: login.php");
            exit();
        } else {
            echo "Hubo un problema al restablecer la contraseña.";
        }
    } else {
        echo "Las contraseñas no coinciden.";
    }
}

$con->close();
?>
