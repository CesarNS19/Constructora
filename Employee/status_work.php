<?php
require '../Login/conexion.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $folio_obra = $_POST['folio_obra'];
    $new_status = $_POST['estatus'];

    $updateSql = "UPDATE obras SET estatus = ? WHERE folio_obra = ?";
    $stmt = $con->prepare($updateSql);
    $stmt->bind_param('ss', $new_status, $folio_obra);

    if ($stmt->execute()) {
        $_SESSION['status_message'] = "Estado de la obra actualizado exitosamente.";
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
