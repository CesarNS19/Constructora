<?php
require '../Login/conexion.php';
session_start();

if (isset($_POST['id_nomina'])) {
    $id = $_POST['id_nomina'];

    $sql = "SELECT * FROM nomina";
    $stmt = $con->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    $cliente = $result->fetch_assoc();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $sueldo = $_POST['sueldo_diario'];
        $dias = $_POST['dias_trabajados'];
        $total = $_POST['total'];
    
        $sql = "UPDATE nomina SET sueldo_diario = ?, dias_trabajados = ?, total = ? WHERE id_nomina = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("sssi", $sueldo, $dias, $total, $id);
    
        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                $_SESSION['status_message'] = 'Nómina actualizada exitosamente.';
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
        header("Location: payroll.php");
        exit();
    }
}
?>
