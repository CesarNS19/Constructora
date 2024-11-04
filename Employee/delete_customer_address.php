<?php
require '../Login/conexion.php';
session_start();

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $sql = "DELETE FROM direccion_cliente WHERE id_direccion_cliente = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $_SESSION['status_message'] = 'Dirección de cliente eliminada correctamente.';
        $_SESSION['status_type'] = 'success';
    } else {
        $_SESSION['status_message'] = 'Error al eliminar el derección del cliente: ' . $stmt->error;
        $_SESSION['status_type'] = 'danger';
    }

    $stmt->close();
} else {
    $_SESSION['status_message'] = 'ID de cliente no especificado.';
    $_SESSION['status_type'] = 'warning';
}

header('Location: customers.php');
exit();

$con->close();
?>
