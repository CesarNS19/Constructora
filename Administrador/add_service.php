<?php
require '../Login/conexion.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_categoria = $_POST["id_categoria"];
    $nombre_servicio = $_POST['nombre_servicio'];
    $descripcion_servicio = $_POST['descripcion_servicio'];
    $total = $_POST['total'];

    // Verificar si se ha cargado el archivo sin errores
    if (isset($_FILES['imagen_servicio']) && $_FILES['imagen_servicio']['error'] === UPLOAD_ERR_OK) {
        // Especificar la carpeta de destino para guardar la imagen
        $target_dir = "../Img/"; // Carpeta dentro de tu proyecto
        $target_file = $target_dir . basename($_FILES["imagen_servicio"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Verificar si el archivo es una imagen válida
        $check = getimagesize($_FILES["imagen_servicio"]["tmp_name"]);
        if ($check !== false) {
            if (move_uploaded_file($_FILES["imagen_servicio"]["tmp_name"], $target_file)) {
                // Guardar solo la ruta relativa en la base de datos
                $relative_path = "../Img/" . basename($_FILES["imagen_servicio"]["name"]);
                
                // Insertar los datos en la base de datos
                $sql = "INSERT INTO servicios (id_categoria, nombre_servicio, descripcion_servicio, total, imagen_servicio) 
                        VALUES ('$id_categoria', '$nombre_servicio', '$descripcion_servicio', '$total', '$relative_path')";

                if ($con->query($sql) === TRUE) {
                    $_SESSION['status_message'] = "Servicio agregado exitosamente";
                    $_SESSION['status_type'] = "success";
                } else {
                    $_SESSION['status_message'] = "Error al agregar el servicio en la base de datos";
                    $_SESSION['status_type'] = "error";
                }
            } else {
                $_SESSION['status_message'] = "Error al mover la imagen a la carpeta Img";
                $_SESSION['status_type'] = "error";
            }
        } else {
            $_SESSION['status_message'] = "El archivo seleccionado no es una imagen válida";
            $_SESSION['status_type'] = "warning";
        }
    } else {
        $_SESSION['status_message'] = "Por favor, selecciona una imagen";
        $_SESSION['status_type'] = "warning";
    }

    header("Location: servicios.php");
    exit();
}
