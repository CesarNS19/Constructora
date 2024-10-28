<?php
require '../Administrador/conexion.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "DELETE FROM clientes WHERE id_cliente = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: users.php");
        exit();
    } else {
        echo "Error al eliminar el cliente.";
    }
} else {
    echo "ID de cliente no especificado.";
}
?>
