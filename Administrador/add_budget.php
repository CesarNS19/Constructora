<?php
require '../Login/conexion.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_empresa = $_POST['id_empresa'];
    $id_cliente = $_POST['id_cliente'];
    $id_direccion_cliente = $_POST['id_direccion'];
    $id_servicio = $_POST['id_servicio'];
    $fecha = date('Y-m-d H:i:s');
    $anticipo = $_POST['anticipo'];
    $total = $_POST['total'];
    $observaciones = $_POST['observaciones'];

    $sql = "INSERT INTO presupuestos (id_empresa, id_cliente, id_direccion, id_servicio, fecha_elaboracion, anticipo, total, observaciones)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ssssssss", $id_empresa, $id_cliente, $id_direccion_cliente, $id_servicio, $fecha, $anticipo, $total, $observaciones);

    if ($stmt->execute()) {
        $_SESSION['status_message'] = "Presupuesto agregado exitosamente.";
        $_SESSION['status_type'] = "success";
    } else {
        $_SESSION['status_message'] = "Error al agregar el presupuesto: " . $stmt->error;
        $_SESSION['status_type'] = "error";
    }

    $stmt->close();
    header("Location: budget.php");
    exit();
}

$con->close();

?>
