<?php
require '../Login/conexion.php';
session_start();

if (isset($_GET['folio'])) {
    $folio_presupuesto = $_GET['folio'];
    $file_path = "../pdf/Proposal_" . $folio_presupuesto . ".pdf";

    // Verificar si el archivo existe
    if (file_exists($file_path)) {
        // Definir los encabezados para mostrar el PDF en línea
        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="' . basename($file_path) . '"');
        
        // Leer el contenido del archivo y enviarlo al navegador
        readfile($file_path);
    } else {
        // Si el archivo no existe, mostrar una alerta de error
        $_SESSION['status_message'] = "El PDF no existe.";
        $_SESSION['status_type'] = "error";
        header("Location: budget.php"); // Redirige a la página anterior o a donde quieras
        exit();
    }
} else {
    // Si no se proporciona el folio, mostrar una alerta de error
    $_SESSION['status_message'] = "Folio no proporcionado.";
    $_SESSION['status_type'] = "error";
    header("Location: budget.php"); // Redirige a la página anterior o a donde quieras
    exit();
}

?>