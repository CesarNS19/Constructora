<?php
require 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['password'], $_POST['confirm_password'], $_POST['code'])) {
    $newPassword = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];
    $verificationCode = $_POST['code'];

    if ($newPassword === $confirmPassword) {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        $stmt = $con->prepare("
            SELECT correo_electronico FROM clientes WHERE codigo_recuperacion = ? 
            UNION
            SELECT correo_personal FROM empleados WHERE codigo_recuperacion = ?
        ");
        $stmt->bind_param("ss", $verificationCode, $verificationCode);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $stmt1 = $con->prepare("UPDATE clientes SET contrasena = ?, codigo_recuperacion = NULL WHERE codigo_recuperacion = ?");
            $stmt1->bind_param("ss", $hashedPassword, $verificationCode);
            $stmt1->execute();

            $stmt2 = $con->prepare("UPDATE empleados SET contrasena = ?, codigo_recuperacion = NULL WHERE codigo_recuperacion = ?");
            $stmt2->bind_param("ss", $hashedPassword, $verificationCode);
            $stmt2->execute();

            if ($stmt1->affected_rows > 0 || $stmt2->affected_rows > 0) {
                header("Location: login.php?success=1");
                exit();
            } else {
                echo "Hubo un problema al restablecer la contraseña.";
            }

            $stmt1->close();
            $stmt2->close();
        } else {
            echo "Código de verificación inválido o expirado.";
        }

        $stmt->close();
    } else {
        echo "Las contraseñas no coinciden.";
    }
}

$con->close();
?>
