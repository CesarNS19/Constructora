<?php
require '../Login/conexion.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $folio_obra = $_POST['folio_obra'];
    $new_status = $_POST['estatus'];

    // Primer actualización: cambiar el estado de la obra
    $updateSql = "UPDATE obras SET estatus = ? WHERE folio_obra = ?";
    $stmt = $con->prepare($updateSql);
    $stmt->bind_param('ss', $new_status, $folio_obra);

    if ($stmt->execute()) {
        // Si el estatus es "completa", actualizamos adeudo y anticipo a 0
        if ($new_status == 'Completa') {
            $updateSql2 = "UPDATE obras SET adeudo = 0, anticipo = 0 WHERE folio_obra = ?";
            $stmt2 = $con->prepare($updateSql2);
            $stmt2->bind_param('s', $folio_obra);

            if ($stmt2->execute()) {
                $_SESSION['status_message'] = "Estado de la obra actualizado exitosamente.";
            } else {
                $_SESSION['status_message'] = "Error al actualizar adeudo y anticipo: " . $stmt2->error;
            }

            $stmt2->close();
        } else {
            $_SESSION['status_message'] = "Estado de la obra actualizado exitosamente.";
        }
        
        $_SESSION['status_type'] = "success";
    } else {
        $_SESSION['status_message'] = "Error al actualizar el estado de la obra: " . $stmt->error;
        $_SESSION['status_type'] = "error";
    }

    $stmt->close();
    $con->close();

    header("Location: works.php");
    exit();
}
?>
