<?php
require '../Login/conexion.php';
session_start();

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $sql = "DELETE FROM direcciones WHERE folio_presupuesto = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $_SESSION['status_message'] = 'Dirección del presupuesto eliminada correctamente.';
        $_SESSION['status_type'] = 'success';
    } else {
        $_SESSION['status_message'] = 'Error al eliminar el derección del presupuesto: ' . $stmt->error;
        $_SESSION['status_type'] = 'danger';
    }

    $stmt->close();
} else {
    $_SESSION['status_message'] = 'ID de presupuesto no especificado.';
    $_SESSION['status_type'] = 'warning';
}

header('Location: budget_address.php');
exit();

$con->close();
?>