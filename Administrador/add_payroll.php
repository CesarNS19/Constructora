<?php
require '../Login/conexion.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_empleado = $_POST['id_empleado'];
    $sueldo_diario = $_POST['sueldo_diario'];
    $dias_trabajados = $_POST['dias_trabajados'];
    $total = $_POST['total'];

    $sql = "INSERT INTO nomina (id_empleado, sueldo_diario, dias_trabajados, total)
            VALUES ('$id_empleado', '$sueldo_diario', '$dias_trabajados', '$total')";

if ($con->query($sql) === TRUE) {
    $_SESSION['status_message'] = "Nómina agregada exitosamente.";
    $_SESSION['status_type'] = "success";
} else {
    $_SESSION['status_message'] = "Error al agregar la Nómina: " . $con->error;
    $_SESSION['status_type'] = "danger";
}

header("Location: payroll.php");
exit();
}

$con->close();
?>
