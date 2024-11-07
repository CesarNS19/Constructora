<?php
require '../Login/conexion.php';

if (isset($_POST['id_cliente'])) {
    $id_cliente = $_POST['id_cliente'];

    $sql = "SELECT id_direccion_cliente, ciudad FROM direccion_cliente WHERE id_cliente = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $id_cliente);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $direccion = $result->fetch_assoc();
        echo json_encode($direccion);
    } else {
        echo json_encode(['id_direccion_cliente' => '', 'ciudad' => '']);
    }
}
?>
