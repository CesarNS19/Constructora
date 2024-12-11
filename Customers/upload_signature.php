<?php
require '../Login/conexion.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['signature'], $_POST['folio_obra'])) {
    $folio_obra = $_POST['folio_obra'];
    $file = $_FILES['signature'];

    // Validar el archivo
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($file['type'], $allowed_types)) {
        $_SESSION['status_message'] = "Formato de archivo no permitido. Usa JPG, PNG o GIF.";
        $_SESSION['status_type'] = "danger";
        header("Location: obra.php");
        exit();
    }

    // Definir la ruta de almacenamiento
    $upload_dir = "../signatures_client/";
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    $file_name = "signature_." . pathinfo($file['name'], PATHINFO_EXTENSION);
    $file_path = $upload_dir . $file_name;

    // Mover el archivo cargado
    if (move_uploaded_file($file['tmp_name'], $file_path)) {
        $_SESSION['status_message'] = "Firma cargada exitosamente.";
        $_SESSION['status_type'] = "success";
    } else {
        $_SESSION['status_message'] = "Error al cargar la firma.";
        $_SESSION['status_type'] = "danger";
    }

    header("Location: obra.php");
    exit();
}
?>