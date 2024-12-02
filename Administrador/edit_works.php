<?php
require '../Login/conexion.php';
session_start();

if (isset($_POST['folio_obra'])) {
    $id = $_POST['folio_obra'];

    $sql = "SELECT * FROM obras WHERE folio_obra = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $cliente = $result->fetch_assoc();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $id_servicio = $_POST['id_servicio'];
        $fecha = $_POST['fecha_inicio'];
        $anticipo = $_POST['anticipo'];
        $adeudo = $_POST['adeudo'];
        $total = $_POST['total_obra'];
        $observaciones = $_POST['observaciones'];
    
        $sql = "UPDATE obras SET id_servicio = ?, fecha_inicio = ?, anticipo = ?, adeudo = ?, total_obra = ?, observaciones = ? WHERE folio_obra = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("isssssi", $id_servicio, $fecha, $anticipo, $adeudo, $total, $observaciones, $id);
    
        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                $_SESSION['status_message'] = 'Obra actualizada exitosamente.';
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
        header("Location: works.php");
        exit();
    }
}
?>
