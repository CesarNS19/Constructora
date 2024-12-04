<?php
require '../Login/conexion.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $apellido_paterno = $_POST['apellido_paterno'];
    $apellido_materno = $_POST['apellido_materno'];
    $hora_entrada = $_POST['hora_entrada'];
    $hora_salida = $_POST['hora_salida'];
    $salario = $_POST['sueldo_diario'];
    $telefono_personal = $_POST['telefono_personal'];
    $correo_personal = $_POST['correo_personal'];
    $contrasena = $_POST['contrasena'];
    $estatus = 'activo';
    $rol = 'empleado';
    $actividades = $_POST['actividades'];
    $id_empresa = $_POST['id_empresa'];

    $checkEmailSql = "SELECT id_empleado FROM empleados WHERE correo_personal = ?";
    $checkEmailStmt = $con->prepare($checkEmailSql);
    $checkEmailStmt->bind_param("s", $correo_personal);
    $checkEmailStmt->execute();
    $checkEmailResult = $checkEmailStmt->get_result();

    if ($checkEmailResult->num_rows > 0) {
        $_SESSION['status_message'] = "El correo electrónico ya está registrado, intente con otro";
        $_SESSION['status_type'] = "error";
    } else {
        $hashed_password = password_hash($contrasena, PASSWORD_DEFAULT);

        $sql = "INSERT INTO empleados (nombre, apellido_paterno, apellido_materno, hora_entrada, sueldo_diario, hora_salida, telefono_personal, correo_personal, contrasena, rol, actividades, id_empresa, estatus) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $con->prepare($sql);
        $stmt->bind_param("sssssssssssss", $nombre, $apellido_paterno, $apellido_materno, $hora_entrada, $hora_salida, $salario, $telefono_personal, $correo_personal, $hashed_password, $rol, $actividades, $id_empresa, $estatus);

        if ($stmt->execute()) {
            $_SESSION['status_message'] = "Empleado agregado exitosamente.";
            $_SESSION['status_type'] = "success";
        } else {
            $_SESSION['status_message'] = "Error al agregar el empleado: " . $stmt->error;
            $_SESSION['status_type'] = "danger";
        }

        $stmt->close();
    }

    $checkEmailStmt->close();
    $con->close();

    header("Location: employee.php");
    exit();
}
?>
