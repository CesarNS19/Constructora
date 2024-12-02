<?php
require '../Login/conexion.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $categoria = $_POST['categoria'];
    $altura = $_POST['altura'];

    $sql = "INSERT INTO categorias_servicios (categoria, altura)
            VALUES (?, ?)";
    
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ss", $categoria, $altura);

    if ($stmt->execute()) {
        $_SESSION['status_message'] = "Categoria de servicios agregado exitosamente.";
        $_SESSION['status_type'] = "success";
    } else {
        $_SESSION['status_message'] = "Error al agregar la categoria: " . $stmt->error;
        $_SESSION['status_type'] = "danger";
    }

    $stmt->close();
    header("Location: categorias_servicios.php");
    exit();
}

$con->close();
?>
