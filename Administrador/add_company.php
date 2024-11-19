<?php
require '../Login/conexion.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre_empresa'];
    $telefono = $_POST['telefono'];
    $pagina = $_POST['pagina_web'];
    $correo = $_POST['correo_empresa'];

    $checkEmailSql = "SELECT id_empresa FROM empresa WHERE correo_empresa = ?";
    $checkEmailStmt = $con->prepare($checkEmailSql);
    $checkEmailStmt->bind_param("s", $correo);
    $checkEmailStmt->execute();
    $checkEmailResult = $checkEmailStmt->get_result();

    if ($checkEmailResult->num_rows > 0) {
        $_SESSION['status_message'] = 'El correo electrónico ya está registrado en otra empresa.';
        $_SESSION['status_type'] = 'error';
        $checkEmailStmt->close();
        header("Location: company.php");
        exit();
    }

    $sql = "INSERT INTO empresa (nombre_empresa, telefono, pagina_web, correo_empresa)
            VALUES ('$nombre', '$telefono', '$pagina', '$correo')";

    if ($con->query($sql) === TRUE) {
        $_SESSION['status_message'] = "Empresa agregada exitosamente.";
        $_SESSION['status_type'] = "success";
    } else {
        $_SESSION['status_message'] = "Error al agregar la empresa: " . $con->error;
        $_SESSION['status_type'] = "error";
    }

    header("Location: company.php");
    exit();
}

$con->close();
?>
