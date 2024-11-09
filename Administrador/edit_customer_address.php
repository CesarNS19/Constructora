<?php
require '../Login/conexion.php';
session_start();

if (isset($_POST['id_direccion_cliente'])) {
    $id = $_POST['id_direccion_cliente'];

    $num_ext = $_POST['num_ext'];
    $num_int = $_POST['num_int'];
    $calle = $_POST['calle'];
    $ciudad = $_POST['ciudad'];
    $estado = $_POST['estado'];
    $codigo_postal = $_POST['codigo_postal'];

    $sql = "UPDATE direccion_cliente 
            SET num_ext = ?, num_int = ?, calle = ?, ciudad = ?, estado = ?, codigo_postal = ? 
            WHERE id_direccion_cliente = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ssssssi", $num_ext, $num_int, $calle, $ciudad, $estado, $codigo_postal, $id);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            $_SESSION['status_message'] = 'Dirección de cliente actualizada exitosamente.';
            $_SESSION['status_type'] = 'success';
        } else {
            $_SESSION['status_message'] = 'No se realizaron cambios. Verifica los datos.';
            $_SESSION['status_type'] = 'warning';
        }
    } else {
        $_SESSION['status_message'] = 'Error al actualizar los datos: ' . $stmt->error;
        $_SESSION['status_type'] = 'danger';
    }

    $stmt->close();
    header("Location: customer_address.php");
    exit();
}
?>
