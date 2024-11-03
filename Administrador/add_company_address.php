<?php
require '../Login/conexion.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_empresa = $_POST['id_empresa'];
    $num_ext = $_POST['num_ext'];
    $num_int = $_POST['num_int'];
    $calle = $_POST['calle'];
    $ciudad = $_POST['ciudad'];
    $estado = $_POST['estado'];
    $codigo_postal = $_POST['codigo_postal'];

    $sql = "INSERT INTO direccion_empresa (id_empresa, num_ext, num_int, calle, ciudad, estado, codigo_postal)
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $con->prepare($sql);
    $stmt->bind_param("issssss", $id_empresa, $num_ext, $num_int, $calle, $ciudad, $estado, $codigo_postal);

    if ($stmt->execute()) {
        $_SESSION['status_message'] = "Dirección de la empresa agregada exitosamente.";
        $_SESSION['status_type'] = "success";
    } else {
        $_SESSION['status_message'] = "Error al agregar la dirección de la empresa: " . $stmt->error;
        $_SESSION['status_type'] = "danger";
    }

    $stmt->close();
    header("Location: company.php");
    exit();
}

$con->close();
?>
