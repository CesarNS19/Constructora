<?php
require '../Login/conexion.php';
session_start();

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
    $rol = 'usuario';
    $estatus = 'activo';

    if ($contrasena !== $confirmar_contrasena) {
        die("Las contraseñas no coinciden.");
    }

    $checkEmailSql = "SELECT id_cliente FROM clientes WHERE correo_electronico = ?";
    $checkEmailStmt = $con->prepare($checkEmailSql);
    $checkEmailStmt->bind_param("s", $correo);
    $checkEmailStmt->execute();
    $checkEmailResult = $checkEmailStmt->get_result();

    if ($checkEmailResult->num_rows > 0) {
        $_SESSION['status_message'] = "El correo electrónico ya está registrado.";
        $_SESSION['status_type'] = "error";
    } else {
        $hashed_password = password_hash($contrasena, PASSWORD_DEFAULT);

        $sql = "INSERT INTO clientes (nombre_cliente, apellido_paterno, apellido_materno, genero_cliente, telefono_personal, correo_electronico, contrasena, edad, rol, estatus)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $con->prepare($sql);
        $stmt->bind_param("ssssssssss", $nombre, $apellido_paterno, $apellido_materno, $genero, $telefono_personal, $correo, $hashed_password, $edad, $rol, $estatus);

        if ($stmt->execute()) {
            $_SESSION['status_message'] = "Cliente agregado exitosamente.";
            $_SESSION['status_type'] = "success";
        } else {
            $_SESSION['status_message'] = "Error al agregar el cliente: " . $stmt->error;
            $_SESSION['status_type'] = "error";
        }

        $stmt->close();
    }

    $checkEmailStmt->close();
    $con->close();

    header("Location: customers.php");
    exit();
}
?>
