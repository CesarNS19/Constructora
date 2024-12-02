<?php
require '../Login/conexion.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_servicio = $_POST['id_servicio'];
    $id_categoria = $_POST['id_categoria'];
    $nombre_servicio = $_POST['nombre_servicio'];
    $descripcion_servicio = $_POST['descripcion_servicio'];
    $total = $_POST['total'];

    // Ruta de destino para las imágenes
    $target_dir = "../Img/";

    // Variable para almacenar la ruta de la imagen
    $imagen_ruta = null;

    // Verifica si se ha subido una nueva imagen
    if (isset($_FILES['imagen_servicio']) && $_FILES['imagen_servicio']['error'] == 0) {
        // Asegúrate de que el directorio de destino exista
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true); // Crea el directorio si no existe
        }

        // Ruta completa del archivo
        $target_file = $target_dir . basename($_FILES["imagen_servicio"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Verificar si es una imagen real
        $check = getimagesize($_FILES["imagen_servicio"]["tmp_name"]);
        if ($check !== false) {
            // Mover el archivo subido a la carpeta de destino
            if (move_uploaded_file($_FILES["imagen_servicio"]["tmp_name"], $target_file)) {
                $imagen_ruta = $target_file; // Actualiza la ruta de la imagen
            } else {
                $_SESSION['status_message'] = "Error al subir la imagen";
                $_SESSION['status_type'] = "error";
                header("Location: servicios.php");
                exit();
            }
        } else {
            $_SESSION['status_message'] = "El archivo no es una imagen válida";
            $_SESSION['status_type'] = "warning";
            header("Location: servicios.php");
            exit();
        }
    }

    // Crear la consulta SQL, incluyendo la imagen solo si hay una nueva
    if ($imagen_ruta) {
        $sql = "UPDATE servicios SET id_categoria='$id_categoria', nombre_servicio='$nombre_servicio', descripcion_servicio='$descripcion_servicio', total='$total' , imagen_servicio='$imagen_ruta' WHERE id_servicio='$id_servicio'";
    } else {
        $sql = "UPDATE servicios SET id_categoria='$id_categoria', nombre_servicio='$nombre_servicio', descripcion_servicio='$descripcion_servicio', total='$total' WHERE id_servicio='$id_servicio'";
    }

    if ($con->query($sql) === TRUE) {
        $_SESSION['status_message'] = "Servicio actualizado exitosamente";
        $_SESSION['status_type'] = "success";
    } else {
        $_SESSION['status_message'] = "Error al actualizar el servicio";
        $_SESSION['status_type'] = "error";
    }

    header("Location: servicios.php");
    exit();
}

?>