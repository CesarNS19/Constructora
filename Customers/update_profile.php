<?php
require '../Login/conexion.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_SESSION['id_cliente'])) {
        header("Location: ../Login/login.php");
        exit;
    }

    $user_id = $_SESSION['id_cliente'];
    
    $nombre = $_POST['nombre_cliente'] ?? '';
    $apellido_paterno = $_POST['apellido_paterno'] ?? '';
    $apellido_materno = $_POST['apellido_materno'] ?? '';
    $correo = $_POST['correo_electronico'] ?? '';
    $telefono = $_POST['telefono_personal'] ?? '';
    
    $sql = "UPDATE clientes SET nombre_cliente = ?, apellido_paterno = ?, apellido_materno = ?, correo_electronico = ?, telefono_personal = ? WHERE id_cliente = ?";
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
