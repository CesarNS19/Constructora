<?php
require 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['email'])) {
    $email = $_POST['email'];
    $newPassword = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    if ($newPassword === $confirmPassword) {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        $stmt1 = $con->prepare("UPDATE clientes SET contrasena = ? WHERE correo_electronico = ?");
        $stmt1->bind_param("ss", $hashedPassword, $email);
        
        $stmt2 = $con->prepare("UPDATE empleados SET contrasena = ? WHERE correo_personal = ?");
        $stmt2->bind_param("ss", $hashedPassword, $email);

        $updatedCliente = $stmt1->execute();
        $updatedEmpleado = $stmt2->execute();

        if ($updatedCliente || $updatedEmpleado) {
            header("Location: login.php");
            exit();
        } else {
            echo "Hubo un problema al restablecer la contraseña.";
        }
        
        $stmt1->close();
        $stmt2->close();
    } else {
        echo "Las contraseñas no coinciden.";
    }
}

$con->close();
?>
