<?php
require '../Login/conexion.php';

if (isset($_POST['id_cliente'])) {
    $id_cliente = $_POST['id_cliente'];

    $sql = "SELECT id_direccion, ciudad FROM direcciones WHERE id_cliente = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $id_cliente);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $direccion = $result->fetch_assoc();
        echo json_encode(['id_direccion' => $direccion['id_direccion'], 'ciudad' => $direccion['ciudad']]);
    } else {
        echo json_encode(['id_direccion' => '', 'ciudad' => '']);
    }
}
?>
