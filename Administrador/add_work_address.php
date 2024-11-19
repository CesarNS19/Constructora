<?php
require '../Login/conexion.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['folio_obra'];
    $num_ext = $_POST['num_ext'];
    $num_int = $_POST['num_int'];
    $calle = $_POST['calle'];
    $ciudad = $_POST['ciudad'];
    $estado = $_POST['estado'];
    $codigo_postal = $_POST['codigo_postal'];

    $sql = "INSERT INTO direcciones(folio_obra, num_ext, num_int, calle, ciudad, estado, codigo_postal)
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $con->prepare($sql);
    $stmt->bind_param("issssss", $id, $num_ext, $num_int, $calle, $ciudad, $estado, $codigo_postal);

    if ($stmt->execute()) {
        $_SESSION['status_message'] = "Dirección de la obra agregada exitosamente.";
        $_SESSION['status_type'] = "success";
    } else {
        $_SESSION['status_message'] = "Error al agregar la dirección de la obra: " . $stmt->error;
        $_SESSION['status_type'] = "danger";
    }

    $stmt->close();
    header("Location: works.php");
    exit();
}

$con->close();
?>
