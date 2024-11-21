<?php
require '../Login/conexion.php';

if (isset($_GET['id_empleado'])) {
    $id_empleado = intval($_GET['id_empleado']);
    $query = "SELECT dias_trabajados FROM empleados WHERE id_empleado = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $id_empleado);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    echo json_encode($data);
} else {
    echo json_encode(['dias_trabajados' => 0]);
}
