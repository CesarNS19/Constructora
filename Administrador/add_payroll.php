<?php
require '../Login/conexion.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_empleado = $_POST['id_empleado'];
    $sueldo_diario = $_POST['sueldo_diario'];
    $dias_trabajados = $_POST['dias_trabajados'];
    $total = $_POST['total'];
    $fecha_actual = date('Y-m-d');

    $sql = "SELECT COUNT(*) as existe
            FROM nomina
            WHERE id_empleado = '$id_empleado' 
            AND fecha BETWEEN DATE_SUB('$fecha_actual', INTERVAL 6 DAY) AND '$fecha_actual'";

    $result = $con->query($sql);
    $row = $result->fetch_assoc();
    
    if ($row['existe'] > 0) {
        $_SESSION['status_message'] = "No se puede agregar la nómina, ya existe una nómina de esta semana.";
        $_SESSION['status_type'] = "error";
    } else {
        $fecha = $fecha_actual;

        $sql_insert = "INSERT INTO nomina (id_empleado, fecha, sueldo_diario, dias_trabajados, total)
                       VALUES ('$id_empleado', '$fecha', '$sueldo_diario', '$dias_trabajados', '$total')";

        if ($con->query($sql_insert) === TRUE) {
            $_SESSION['status_message'] = "Nómina agregada exitosamente.";
            $_SESSION['status_type'] = "success";
        } else {
            $_SESSION['status_message'] = "Error al agregar la Nómina: " . $con->error;
            $_SESSION['status_type'] = "error";
        }
    }

    header("Location: payroll.php");
    exit();
}

$con->close();
?>
