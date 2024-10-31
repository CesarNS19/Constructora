<?php
require '../Login/conexion.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "DELETE FROM empresa WHERE id_empresa = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: company.php");
        exit();
    } else {
        echo "Error al eliminar la empresa.";
    }
} else {
    echo "ID de la empresa no especificado.";
}
?>