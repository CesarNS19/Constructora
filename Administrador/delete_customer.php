<?php
require '../Login/conexion.php';
session_start();

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $checkAddressSql = "SELECT COUNT(*) FROM direcciones WHERE id_cliente = ?";
    $checkAddressStmt = $con->prepare($checkAddressSql);
    $checkAddressStmt->bind_param("i", $id);
    $checkAddressStmt->execute();
    $checkAddressStmt->bind_result($addressCount);
    $checkAddressStmt->fetch();
    $checkAddressStmt->close();

    $checkWorksSql = "SELECT COUNT(*) FROM obras WHERE id_cliente = ?";
    $checkWorksStmt = $con->prepare($checkWorksSql);
    $checkWorksStmt->bind_param("i", $id);
    $checkWorksStmt->execute();
    $checkWorksStmt->bind_result($worksCount);
    $checkWorksStmt->fetch();
    $checkWorksStmt->close();

    $checkBudgetSql = "SELECT COUNT(*) FROM presupuestos WHERE id_cliente = ?";
    $checkBudgetStmt = $con->prepare($checkBudgetSql);
    $checkBudgetStmt->bind_param("i", $id);
    $checkBudgetStmt->execute();
    $checkBudgetStmt->bind_result($budgetCount);
    $checkBudgetStmt->fetch();
    $checkBudgetStmt->close();

    if ($addressCount > 0 || $worksCount > 0 || $budgetCount > 0) {
        $_SESSION['status_message'] = 'No se puede eliminar el cliente porque tiene registros asociados.';
        $_SESSION['status_type'] = 'warning';
    } else {
        $deleteSql = "DELETE FROM clientes WHERE id_cliente = ?";
        $deleteStmt = $con->prepare($deleteSql);
        $deleteStmt->bind_param("i", $id);

        if ($deleteStmt->execute()) {
            $_SESSION['status_message'] = 'Cliente eliminado correctamente.';
            $_SESSION['status_type'] = 'success';
        } else {
            $_SESSION['status_message'] = 'Error al eliminar el cliente: ' . $deleteStmt->error;
            $_SESSION['status_type'] = 'error';
        }

        $deleteStmt->close();
    }
} else {
    $_SESSION['status_message'] = 'ID de cliente no especificado.';
    $_SESSION['status_type'] = 'warning';
}

header('Location: customers.php');
exit();

$con->close();
?>
