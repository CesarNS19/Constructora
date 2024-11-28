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

    $query = "INSERT INTO direcciones (num_ext, num_int, calle, ciudad, estado, codigo_postal) 
              VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $con->prepare($query);
    $stmt->bind_param("ssssss", $num_ext, $num_int, $calle, $ciudad, $estado, $codigo_postal);
    
    if ($stmt->execute()) {
        $direccion_id = $stmt->insert_id;
        
        // Actualizar la dirección del cliente en la tabla de clientes
        $update_cliente_query = "UPDATE direcciones SET id_direccion = ? WHERE id_cliente = ?";
        $stmt_cliente = $con->prepare($update_cliente_query);
        $stmt_cliente->bind_param("ii", $direccion_id, $id_cliente);
        
        if ($stmt_cliente->execute()) {
            header("Location: perfil.php");
        } else {
            echo "Error al actualizar la dirección del cliente.";
        }
    } else {
        echo "Error al agregar la dirección.";
    }
}
?>
