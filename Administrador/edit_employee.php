<?php
require '../Login/conexion.php';
session_start();

if (isset($_POST['id_empleado'])) {
    $id = $_POST['id_empleado'];

    $sql = "SELECT * FROM empleados WHERE id_empleado = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $empleado = $result->fetch_assoc();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $nombre = $_POST['nombre'];
        $apellido_paterno = $_POST['apellido_paterno'];
        $apellido_materno = $_POST['apellido_materno'];
        $hora_entrada = $_POST['hora_entrada'];
        $hora_salida = $_POST['hora_salida'];
        $salario = $_POST['sueldo_diario'];
        $telefono = $_POST['telefono_personal'];
        $email = $_POST['correo_personal'];
        $actividades = $_POST['actividades'];
        $id_empresa = $_POST['id_empresa'];

        $checkEmailSql = "SELECT id_empleado FROM empleados WHERE correo_personal = ? AND id_empleado != ?";
        $checkEmailStmt = $con->prepare($checkEmailSql);
        $checkEmailStmt->bind_param("si", $email, $id);
        $checkEmailStmt->execute();
        $checkEmailResult = $checkEmailStmt->get_result();

        if ($checkEmailResult->num_rows > 0) {
            $_SESSION['status_message'] = 'El correo electrónico ya está registrado en otro empleado.';
            $_SESSION['status_type'] = 'error';
            $checkEmailStmt->close();
            header("Location: employee.php");
            exit();
        }

        $sql = "UPDATE empleados SET nombre = ?, apellido_paterno = ?, apellido_materno = ?, hora_entrada = ?, hora_salida = ?, sueldo_diario = ?, telefono_personal = ?, correo_personal = ?, actividades = ?, id_empresa = ? WHERE id_empleado = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("ssssssssssi", $nombre, $apellido_paterno, $apellido_materno, $hora_entrada, $hora_salida, $salario, $telefono, $email, $actividades, $id_empresa, $id);

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                $_SESSION['status_message'] = 'Empleado actualizado exitosamente.';
                $_SESSION['status_type'] = 'success';
            } else {
                $_SESSION['status_message'] = 'No se realizaron cambios. Verifica los datos.';
                $_SESSION['status_type'] = 'warning';
            }
        } else {
            $_SESSION['status_message'] = 'Error al actualizar los datos: ' . $stmt->error;
            $_SESSION['status_type'] = 'error';
        }

        $stmt->close();
        header("Location: employee.php");
        exit();
    }
}

$con->close();
?>
