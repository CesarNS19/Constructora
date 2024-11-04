<?php
require '../Login/conexion.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_cliente = $_POST['id_cliente'];
    $num_ext = $_POST['num_ext'];
    $num_int = $_POST['num_int'];
    $calle = $_POST['calle'];
    $ciudad = $_POST['ciudad'];
    $estado = $_POST['estado'];
    $codigo_postal = $_POST['codigo_postal'];

    $sql = "INSERT INTO direccion_cliente (id_cliente, num_ext, num_int, calle, ciudad, estado, codigo_postal)
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $con->prepare($sql);
    $stmt->bind_param("issssss", $id_cliente, $num_ext, $num_int, $calle, $ciudad, $estado, $codigo_postal);

    if ($stmt->execute()) {
        $_SESSION['status_message'] = "Dirección del cliente agregada exitosamente.";
        $_SESSION['status_type'] = "success";
    } else {
        $_SESSION['status_message'] = "Error al agregar la dirección del cliente: " . $stmt->error;
        $_SESSION['status_type'] = "danger";
    }

    $stmt->close();
    header("Location: customers.php");
    exit();
}

$con->close();
?>
