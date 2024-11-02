<?php
require '../Login/conexion.php';

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
        header("Location: employee.php");
    } else {
        echo "Error: " . $sql . "<br>" . $con->error;
    }
}

$con->close();
?>
