<?php
require '../Login/conexion.php';

if (isset($_GET['id_empleado'])) {
    $id_empleado = intval($_GET['id_empleado']);

    $sql = "SELECT dias_trabajados, sueldo_diario FROM empleados WHERE id_empleado = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $id_empleado);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
        echo json_encode($data);
    } else {
        echo json_encode(['dias_trabajados' => null, 'sueldo_diario' => null]);
    }

    $stmt->close();
} else {
    echo json_encode(['error' => 'Invalid request']);
}
?>
