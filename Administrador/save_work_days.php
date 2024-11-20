<?php
require '../Login/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_empleado = $_POST['id_empleado'] ?? null;
    $dias_trabajados = $_POST['dias_trabajados'] ?? null;

    if ($id_empleado && is_numeric($dias_trabajados)) {
        $stmt = $con->prepare("UPDATE empleados SET dias_trabajados = ? WHERE id_empleado = ?");
        $stmt->bind_param("ii", $dias_trabajados, $id_empleado);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Días trabajados actualizados correctamente.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error al actualizar los días trabajados.']);
        }
        $stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Datos inválidos.']);
    }
}
?>
