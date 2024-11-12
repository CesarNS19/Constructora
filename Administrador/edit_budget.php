<?php
require '../Login/conexion.php';
session_start();

if (isset($_POST['folio_presupuesto'])) {
    $id = $_POST['folio_presupuesto'];

    $sql = "SELECT * FROM presupuestos WHERE folio_presupuesto = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $cliente = $result->fetch_assoc();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $fecha = $_POST['fecha_elaboracion'];
        $observaciones = $_POST['observaciones'];
    
        $sql = "UPDATE presupuestos SET fecha_elaboracion = ?, observaciones = ? WHERE folio_presupuesto = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("ssi", $fecha, $observaciones, $id);
    
        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                $_SESSION['status_message'] = 'Presupuesto actualizado exitosamente.';
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
        header("Location: budget.php");
        exit();
    }
}
?>