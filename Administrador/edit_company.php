<?php
require '../Login/conexion.php';
session_start();

if (isset($_POST['id_empresa'])) {
    $id = $_POST['id_empresa'];

    $sql = "SELECT * FROM empresa WHERE id_empresa = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $cliente = $result->fetch_assoc();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $nombre = $_POST['nombre_empresa'];
        $telefono = $_POST['telefono'];
        $pagina = $_POST['pagina_web'];
        $email = $_POST['correo_empresa'];
    
        $sql = "UPDATE empresa SET nombre_empresa = ?, telefono = ?, pagina_web = ?, correo_empresa = ? WHERE id_empresa = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("ssssi", $nombre, $telefono, $pagina, $email, $id);
    
        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                $_SESSION['status_message'] = 'Empresa actualizada exitosamente.';
                $_SESSION['status_type'] = 'success';
            } else {
                $_SESSION['status_message'] = 'No se realizaron cambios. Verifica los datos.';
                $_SESSION['status_type'] = 'warning';
            }
        } else {
            $_SESSION['status_message'] = 'Error al actualizar los datos: ' . $stmt->error;
            $_SESSION['status_type'] = 'danger';
        }

        $stmt->close();
        header("Location: company.php");
        exit();
    }
}
?>
