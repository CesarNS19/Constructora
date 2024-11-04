<?php
require '../Login/conexion.php';
session_start();

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "DELETE FROM obras WHERE folio_obra = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $_SESSION['status_message'] = 'Obra eliminada correctamente.';
        $_SESSION['status_type'] = 'success';
    } else {
        $_SESSION['status_message'] = 'Error al eliminar la obra: ' . $stmt->error;
        $_SESSION['status_type'] = 'danger';
    }

    $stmt->close();
} else {
    $_SESSION['status_message'] = 'ID de la obra no especificado.';
    $_SESSION['status_type'] = 'warning';
}

header('Location: works.php');
exit();
?>