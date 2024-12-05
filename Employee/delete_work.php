<?php
require '../Login/conexion.php';
session_start();

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql_direcciones = "SELECT COUNT(*) AS total FROM direcciones WHERE folio_obra = ?";
    $stmt_direcciones = $con->prepare($sql_direcciones);
    $stmt_direcciones->bind_param("i", $id);
    $stmt_direcciones->execute();
    $result_direcciones = $stmt_direcciones->get_result();
    $row_direcciones = $result_direcciones->fetch_assoc();

    if ($row_direcciones['total'] > 0) {
        $_SESSION['status_message'] = 'No se puede eliminar la obra porque tiene registros asociados';
        $_SESSION['status_type'] = 'warning';
    } else {
        $sql = "DELETE FROM obras WHERE folio_obra = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            $_SESSION['status_message'] = 'Obra eliminada correctamente.';
            $_SESSION['status_type'] = 'success';
        } else {
            $_SESSION['status_message'] = 'Error al eliminar la obra: ' . $stmt->error;
            $_SESSION['status_type'] = 'error';
        }

        $stmt->close();
    }

    $stmt_direcciones->close();
} else {
    $_SESSION['status_message'] = 'ID de la obra no especificado.';
    $_SESSION['status_type'] = 'warning';
}

header('Location: works.php');
exit();
?>
