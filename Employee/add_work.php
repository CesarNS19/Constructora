<?php
require '../Login/conexion.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_empresa = $_POST['id_empresa'];
    $id_cliente = $_POST['id_cliente'];
    $id_direccion_cliente = $_POST['id_direccion'];
    $id_servicio = $_POST['id_servicio'];
    $fecha = $_POST['fecha_inicio'];
    $anticipo = $_POST['anticipo'];
    $adeudo = $_POST['adeudo'];
    $total = $_POST['total_obra'];
    $observaciones = $_POST['observaciones'];

    $sql = "INSERT INTO obras (id_empresa, id_cliente, id_direccion, id_servicio, fecha_inicio, anticipo, adeudo, total_obra, observaciones)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $con->prepare($sql);
    $stmt->bind_param("iiiisssss", $id_empresa, $id_cliente, $id_direccion_cliente, $id_servicio, $fecha, $anticipo, $adeudo, $total, $observaciones);

    if ($stmt->execute()) {
        $_SESSION['status_message'] = "Obra agregada exitosamente.";
        $_SESSION['status_type'] = "success";
    } else {
        $_SESSION['status_message'] = "Error al agregar la obra: " . $stmt->error;
        $_SESSION['status_type'] = "danger";
    }

    $stmt->close();
    header("Location: works.php");
    exit();
}

$con->close();
?>
