<?php
require '../Login/conexion.php';
session_start();

if (isset($_POST['id_categoria'])) {
    $id = $_POST['id_categoria'];

    $sql = "SELECT * FROM categorias_servicios";
    $stmt = $con->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    $cliente = $result->fetch_assoc();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $categoria = $_POST['categoria'];
        $altura = $_POST['altura'];
    
        $sql = "UPDATE categorias_servicios SET categoria = ?, altura = ? WHERE id_categoria = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("ssi", $categoria, $altura, $id);
    
        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                $_SESSION['status_message'] = 'Categoria de servicios actualizada exitosamente.';
                $_SESSION['status_type'] = 'success';
            } else {
                $_SESSION['status_message'] = 'No se realizaron cambios. Verifica los datos.';
                $_SESSION['status_type'] = 'warning';
            }
        } else {
            $_SESSION['status_message'] = 'Error al actualizar los datos: ' . $stmt->error;
            $_SESSION['status_type'] = 'error';
        }

        $stmt->close();
        header("Location: categorias_servicios.php");
        exit();
    }
}
?>
