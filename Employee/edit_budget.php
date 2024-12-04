<?php
require '../Login/conexion.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['folio_presupuesto'])) {
    $id = $_POST['folio_presupuesto'];

    // Verificar y cargar el presupuesto existente
    $sql = "SELECT * FROM presupuestos WHERE folio_presupuesto = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $cliente = $result->fetch_assoc();

    if ($cliente) {
        // Obtener datos del formulario
        $id_servicio = $_POST['id_servicio'] ?? null;
        $fecha = $_POST['fecha_elaboracion'] ?? null;
        $total = $_POST['total'] ?? null;
        $observaciones = $_POST['observaciones'] ?? '';

        // Validar que los datos requeridos no estén vacíos
        if ($id_servicio && $fecha && $total !== null) {
            // Actualizar los datos en la base de datos
            $sql = "UPDATE presupuestos 
                    SET id_servicio = ?, fecha_elaboracion = ?, total = ?, observaciones = ? 
                    WHERE folio_presupuesto = ?";
            $stmt = $con->prepare($sql);
            $stmt->bind_param("ssdsi", $id_servicio, $fecha, $total, $observaciones, $id);

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
        } else {
            $_SESSION['status_message'] = 'Faltan datos obligatorios. Por favor, completa el formulario.';
            $_SESSION['status_type'] = 'warning';
        }
    } else {
        $_SESSION['status_message'] = 'El presupuesto no existe.';
        $_SESSION['status_type'] = 'danger';
    }

    // Redirigir a la página de presupuestos
    header("Location: budget.php");
    exit();
}
?>
