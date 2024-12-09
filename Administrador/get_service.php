<?php
require '../Login/conexion.php';

if (isset($_POST['id_cliente'])) {
    $id_cliente = (int)$_POST['id_cliente'];
    $response = [];

    // Obtener dirección
    $sql_direccion = "SELECT id_direccion, ciudad FROM direcciones WHERE id_cliente = $id_cliente LIMIT 1";
    $result_direccion = $con->query($sql_direccion);

    if ($result_direccion->num_rows > 0) {
        $response['direccion'] = $result_direccion->fetch_assoc();
    } else {
        $response['direccion'] = ['id_direccion' => '', 'ciudad' => 'No address'];
    }

    // Obtener presupuesto (incluyendo id_empresa)
    $sql_presupuesto = "SELECT e.nombre_empresa, p.anticipo, p.id_empresa, p.observaciones
                        FROM presupuestos p
                        INNER JOIN empresa e ON e.id_empresa = p.id_empresa
                        WHERE p.id_cliente = $id_cliente LIMIT 1";
    $result_presupuesto = $con->query($sql_presupuesto);

    if ($result_presupuesto->num_rows > 0) {
        $response['presupuesto'] = $result_presupuesto->fetch_assoc();
    } else {
        $response['presupuesto'] = ['nombre_empresa' => 'No company', 'anticipo' => '0', 'id_empresa' => ''];
    }

    // Enviar la respuesta como JSON
    echo json_encode($response);
}
?>
