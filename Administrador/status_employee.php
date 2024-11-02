<?php
require '../Login/conexion.php';
session_start();

if (isset($_GET['id']) && isset($_GET['estatus'])) {
    $id_cliente = intval($_GET['id']);
    $nuevo_estatus = $_GET['estatus'];

    if ($nuevo_estatus === 'activo' || $nuevo_estatus === 'inactivo') {
        $sql = "UPDATE empleados SET estatus = ? WHERE id_empleado = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param('si', $nuevo_estatus, $id_cliente);

        if ($stmt->execute()) {
            $_SESSION['status_message'] = $nuevo_estatus === 'activo' ? 'Empleado activado correctamente.' : 'Empleado desactivado correctamente.';
        } else {
            $_SESSION['status_message'] = 'Error al actualizar el estado del empleado: ' . $stmt->error;
        }

        $stmt->close();
    } else {
        $_SESSION['status_message'] = 'Estatus inválido.';
    }
} else {
    $_SESSION['status_message'] = 'ID o estatus no especificado.';
}

header('Location: ' . $_SERVER['HTTP_REFERER']);
exit();

$con->close();
?>
