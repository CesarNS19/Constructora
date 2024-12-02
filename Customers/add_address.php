<?php
session_start();
require '../Login/conexion.php';

if (!isset($_SESSION['id_cliente'])) {
    header("Location: ../Login/login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_cliente = $_SESSION['id_cliente'];
    $num_ext = $_POST['num_ext'];
    $num_int = $_POST['num_int'];
    $calle = $_POST['calle'];
    $ciudad = $_POST['ciudad'];
    $estado = $_POST['estado'];
    $codigo_postal = $_POST['codigo_postal'];

    // Inserta la dirección en la tabla direcciones
    $query = "INSERT INTO direcciones (num_ext, num_int, calle, ciudad, estado, codigo_postal, id_cliente) 
              VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $con->prepare($query);
    $stmt->bind_param("ssssssi", $num_ext, $num_int, $calle, $ciudad, $estado, $codigo_postal, $id_cliente);
    
    if ($stmt->execute()) {
        header("Location: perfil.php");
    } else {
        echo "Error al agregar la dirección: " . $stmt->error;
    }
}
?>
