<?php
include '../modelo/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['signature'], $_POST['id_obra'])) {
    $folio_obra = $_POST['id_obra'];
    $file = $_FILES['signature'];

    // Validar el archivo
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($file['type'], $allowed_types)) {
        echo "Formato de archivo no permitido. Usa JPG, PNG o GIF.";
        exit();
    }

    // Definir la ruta de almacenamiento
    $upload_dir = "../Firma_administrador/";
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    // Generar un nombre único para el archivo
    $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $file_name = "Firma_" . $folio_obra . "." . $file_extension;
    $file_path = $upload_dir . $file_name;

    // Mover el archivo cargado
    if (move_uploaded_file($file['tmp_name'], $file_path)) {
        echo "Firma cargada exitosamente.";
        header("Location: ../ruta_a_tu_tabla_principal.php?status=success&message=Firma cargada exitosamente");
        exit();
    } else {
        echo "Error al cargar la firma.";
        header("Location: ../ruta_a_tu_tabla_principal.php?status=error&message=Error al cargar la firma");
        exit();
    }
} else {
    echo "Solicitud inválida.";
    exit();
}
