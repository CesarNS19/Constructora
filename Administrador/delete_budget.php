<?php
require '../Login/conexion.php';
session_start();

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql_verificar_direcciones = "SELECT COUNT(*) AS total FROM direcciones WHERE folio_presupuesto = ?";
    $stmt_verificar = $con->prepare($sql_verificar_direcciones);
    $stmt_verificar->bind_param("i", $id);
    $stmt_verificar->execute();
    $result_verificar = $stmt_verificar->get_result();
    $row_verificar = $result_verificar->fetch_assoc();

    if ($row_verificar['total'] > 0) {
        $_SESSION['status_message'] = 'No se puede eliminar el presupuesto porque tiene registros asociados';
        $_SESSION['status_type'] = 'warning';
    } else {
        $sql = "DELETE FROM presupuestos WHERE folio_presupuesto = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            $_SESSION['status_message'] = 'Presupuesto eliminado correctamente.';
            $_SESSION['status_type'] = 'success';
        } else {
            $_SESSION['status_message'] = 'Error al eliminar el presupuesto: ' . $stmt->error;
            $_SESSION['status_type'] = 'error';
        }

        $stmt->close();
    }

    $stmt_verificar->close();
} else {
    $_SESSION['status_message'] = 'ID del presupuesto no especificado.';
    $_SESSION['status_type'] = 'warning';
}

header('Location: budget.php');
exit();
?>
