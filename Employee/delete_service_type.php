<?php
require '../Login/conexion.php';
session_start();

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $sql = "DELETE FROM categorias_servicios WHERE id_categoria = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $_SESSION['status_message'] = 'Categoria de servicio eliminada correctamente.';
        $_SESSION['status_type'] = 'success';
    } else {
        $_SESSION['status_message'] = 'Error al eliminar la categoria: ' . $stmt->error;
        $_SESSION['status_type'] = 'error';
    }

    $stmt->close();
} else {
    $_SESSION['status_message'] = 'ID de categoria no especificado.';
    $_SESSION['status_type'] = 'warning';
}

header('Location: categorias_servicios.php');
exit();

$con->close();
?>