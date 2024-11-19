<?php
require '../Login/conexion.php';
session_start();

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $checkEmpleadosSql = "SELECT id_empleado FROM empleados WHERE id_empresa = ?";
    $checkEmpleadosStmt = $con->prepare($checkEmpleadosSql);
    $checkEmpleadosStmt->bind_param("i", $id);
    $checkEmpleadosStmt->execute();
    $checkEmpleadosResult = $checkEmpleadosStmt->get_result();

    $checkDireccionesSql = "SELECT id_direccion FROM direcciones WHERE id_empresa = ?";
    $checkDireccionesStmt = $con->prepare($checkDireccionesSql);
    $checkDireccionesStmt->bind_param("i", $id);
    $checkDireccionesStmt->execute();
    $checkDireccionesResult = $checkDireccionesStmt->get_result();

    $checkObrasSql = "SELECT folio_obra FROM obras WHERE id_empresa = ?";
    $checkObrasStmt = $con->prepare($checkObrasSql);
    $checkObrasStmt->bind_param("i", $id);
    $checkObrasStmt->execute();
    $checkObrasResult = $checkObrasStmt->get_result();

    $checkPresupuestosSql = "SELECT folio_presupuesto FROM presupuestos WHERE id_empresa = ?";
    $checkPresupuestosStmt = $con->prepare($checkPresupuestosSql);
    $checkPresupuestosStmt->bind_param("i", $id);
    $checkPresupuestosStmt->execute();
    $checkPresupuestosResult = $checkPresupuestosStmt->get_result();

    if ($checkEmpleadosResult->num_rows > 0 || $checkDireccionesResult->num_rows > 0 || $checkObrasResult->num_rows > 0 || $checkPresupuestosResult->num_rows > 0) {
        $_SESSION['status_message'] = 'No se puede eliminar la empresa, tiene registros asociados';
        $_SESSION['status_type'] = 'warning';
    } else {
        $sql = "DELETE FROM empresa WHERE id_empresa = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            $_SESSION['status_message'] = 'Empresa eliminada correctamente.';
            $_SESSION['status_type'] = 'success';
        } else {
            $_SESSION['status_message'] = 'Error al eliminar la empresa: ' . $stmt->error;
            $_SESSION['status_type'] = 'danger';
        }

        $stmt->close();
    }

    $checkEmpleadosStmt->close();
    $checkDireccionesStmt->close();
    $checkObrasStmt->close();
    $checkPresupuestosStmt->close();

} else {
    $_SESSION['status_message'] = 'ID de empresa no especificado.';
    $_SESSION['status_type'] = 'warning';
}

header('Location: company.php');
exit();

$con->close();
?>
