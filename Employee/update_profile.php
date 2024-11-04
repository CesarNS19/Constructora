<?php
require '../Login/conexion.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_SESSION['id_empleado'])) {
        header("Location: ../Login/login.php");
        exit;
    }

    $user_id = $_SESSION['id_empleado'];
    
    $nombre = $_POST['nombre'] ?? '';
    $apellido_paterno = $_POST['apellido_paterno'] ?? '';
    $apellido_materno = $_POST['apellido_materno'] ?? '';
    $correo = $_POST['correo_personal'] ?? '';
    $telefono = $_POST['telefono_personal'] ?? '';
    
    $sql = "UPDATE empleados SET nombre = ?, apellido_paterno = ?, apellido_materno = ?, correo_personal = ?, telefono_personal = ? WHERE id_empleado = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("sssssi", $nombre, $apellido_paterno, $apellido_materno, $correo, $telefono, $user_id);
    
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            $_SESSION['status_message'] = 'Perfil actualizado exitosamente.';
            $_SESSION['status_type'] = 'success';
        } else {
            $_SESSION['status_message'] = 'No se realizaron cambios. Verifica los datos.';
            $_SESSION['status_type'] = 'warning';
        }
    } else {
        $_SESSION['status_message'] = 'Error al actualizar los datos: ' . $stmt->error;
        $_SESSION['status_type'] = 'danger';
    }
    
    $stmt->close();
    header("Location: perfil.php");
    exit();
}
?>
