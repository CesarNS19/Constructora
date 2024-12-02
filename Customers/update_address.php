<?php
session_start();
require '../Login/conexion.php';

if (!isset($_SESSION['id_cliente'])) {
    header("Location: ../Login/login.php");
    exit;
}

$user_id = $_SESSION['id_cliente'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir los datos del formulario
    $calle = $_POST['calle'];
    $num_ext = $_POST['num_ext'];
    $num_int = $_POST['num_int'];
    $ciudad = $_POST['ciudad'];
    $estado = $_POST['estado'];
    $codigo_postal = $_POST['codigo_postal'];
    $id_direccion = $_POST['id_direccion'];

    // Actualizar la dirección en la base de datos
    $query = "UPDATE direcciones SET calle = ?, num_ext = ?, num_int = ?, ciudad = ?, estado = ?, codigo_postal = ? WHERE id_direccion = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("ssssssi", $calle, $num_ext, $num_int, $ciudad, $estado, $codigo_postal, $id_direccion);
    
    if ($stmt->execute()) {
        header("Location: perfil.php");  // Redirigir al perfil después de la actualización
        exit;
    } else {
        echo "Error al actualizar la dirección: " . $stmt->error;
    }
}
?>
