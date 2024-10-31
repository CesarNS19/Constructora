<?php
require '../Login/conexion.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "DELETE FROM empleados WHERE id_empleado = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: employee.php");
        exit();
    } else {
        echo "Error al eliminar el empleado.";
    }
} else {
    echo "ID de empleado no especificado.";
}
?>