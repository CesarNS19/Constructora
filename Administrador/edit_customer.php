<?php
require '../Login/conexion.php';

if (isset($_POST['id_cliente'])) {
    $id = $_POST['id_cliente'];

    // Obtener datos actuales del cliente
    $sql = "SELECT * FROM clientes WHERE id_cliente = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $cliente = $result->fetch_assoc();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Obtener datos actualizados
        $nombre = $_POST['nombre_cliente'];
        $apellido_paterno = $_POST['apellido_paterno'];
        $apellido_materno = $_POST['apellido_materno'];
        $genero = $_POST['genero_cliente'];
        $telefono = $_POST['telefono_personal'];
        $email = $_POST['correo_electronico'];
        $edad = $_POST['edad'];
        $rol = $_POST['rol'];
    
        // Actualizar datos del cliente
        $sql = "UPDATE clientes SET nombre_cliente = ?, apellido_paterno = ?, apellido_materno = ?, genero_cliente = ?, telefono_personal = ?, correo_electronico = ?, edad = ?, rol = ? WHERE id_cliente = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("ssssssssi", $nombre, $apellido_paterno, $apellido_materno, $genero, $telefono, $email, $edad, $rol, $id);
    
        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                echo "Cliente actualizado exitosamente.";
                header("Location: customers.php");
                exit();
            } else {
                echo "No se realizaron cambios. Verifica los datos.";
            }
        } else {
            echo "Error al actualizar los datos: " . $stmt->error;
        }
    }
}    
?>
