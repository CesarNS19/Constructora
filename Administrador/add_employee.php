<?php
require '../Login/conexion.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $apellido_paterno = $_POST['apellido_paterno'];
    $apellido_materno = $_POST['apellido_materno'];
    $hora_entrada = $_POST['hora_entrada'];
    $hora_salida = $_POST['hora_salida'];
    $salario = $_POST['salario'];
    $telefono_personal = $_POST['telefono_personal'];
    $correo_personal = $_POST['correo_personal'];
    $cargo = $_POST['cargo'];
    $actividades = $_POST['actividades'];
    $id_empresa = $_POST['id_empresa'];
    $estatus  = 'activo';

    $sql = "INSERT INTO empleados (nombre, apellido_paterno, apellido_materno, hora_entrada, hora_salida, salario, telefono_personal, correo_personal, cargo, actividades, id_empresa, estatus)
            VALUES ('$nombre', '$apellido_paterno', '$apellido_materno', '$hora_entrada', '$hora_salida', '$salario', '$telefono_personal', '$correo_personal', '$cargo', '$actividades', '$id_empresa', '$estatus')";

    if ($con->query($sql) === TRUE) {
        $_SESSION['status_message'] = "Empleado agregado exitosamente.";
        $_SESSION['status_type'] = "success";
    } else {
        $_SESSION['status_message'] = "Error al agregar el empleado: " . $con->error;
        $_SESSION['status_type'] = "danger";
    }

    header("Location: employee.php");
    exit();
}

$con->close();
?>