<?php
require '../Login/conexion.php';
session_start();

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $sql = "DELETE FROM servicios WHERE id_servicio = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $_SESSION['status_message'] = 'Servicio eliminado correctamente.';
        $_SESSION['status_type'] = 'success';
    } else {
        $_SESSION['status_message'] = 'Error al eliminar el servicio: ' . $stmt->error;
        $_SESSION['status_type'] = 'danger';
    }

    $stmt->close();
} else {
    $_SESSION['status_message'] = 'ID de servicio no especificado.';
    $_SESSION['status_type'] = 'warning';
}

header('Location: servicios.php');
exit();

$con->close();
?>
