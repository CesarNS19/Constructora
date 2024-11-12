<?php
require '../Login/conexion.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_empresa = $_POST['id_empresa'];
    $id_cliente = $_POST['id_cliente'];
    $id_direccion_cliente = $_POST['id_direccion_cliente'];
    $fecha = $_POST['fecha_elaboracion'];
    $observaciones = $_POST['observaciones'];

    $sql = "INSERT INTO presupuestos (id_empresa, id_cliente, id_direccion_cliente, fecha_elaboracion, observaciones)
            VALUES (?, ?, ?, ?, ?)";
    
    $stmt = $con->prepare($sql);
    $stmt->bind_param("sssss", $id_empresa, $id_cliente, $id_direccion_cliente, $fecha, $observaciones);

    if ($stmt->execute()) {
        $_SESSION['status_message'] = "Presupuesto agregado exitosamente.";
        $_SESSION['status_type'] = "success";
    } else {
        $_SESSION['status_message'] = "Error al agregar el presupuesto: " . $stmt->error;
        $_SESSION['status_type'] = "danger";
    }

    $stmt->close();
    header("Location: budget.php");
    exit();
}

$con->close();
?>
