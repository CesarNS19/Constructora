<?php
require __DIR__ . '../../vendor/autoload.php';
require '../Login/conexion.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();

if (isset($_GET['folio']) && isset($_GET['file'])) {
    $folio_obra = $_GET['folio'];
    $file_path = urldecode($_GET['file']);

    // Buscar el correo del cliente asociado a la obra
    $sql_obra = "SELECT c.correo_electronico, c.nombre_cliente, c.apellido_paterno, c.apellido_materno 
                 FROM obras o
                 LEFT JOIN clientes c ON o.id_cliente = c.id_cliente
                 WHERE o.folio_obra = ?";
    $stmt_obra = $con->prepare($sql_obra);
    $stmt_obra->bind_param('i', $folio_obra);
    $stmt_obra->execute();
    $result_obra = $stmt_obra->get_result();
    $obra = $result_obra->fetch_assoc();

    if ($obra && file_exists($file_path)) {
        $correo_cliente = $obra['correo_electronico'];
        $nombre_cliente = htmlspecialchars($obra['nombre_cliente'] . ' ' . $obra['apellido_paterno'] . ' ' . $obra['apellido_materno']);
    } else {
        $_SESSION['status_message'] = "No se encontró el cliente asociado a esta obra o el archivo PDF.";
        $_SESSION['status_type'] = "error";
        header("Location: obra.php");
        exit;
    }

    // Buscar el cliente administrador
    $sql_admin = "SELECT correo_electronico, nombre_cliente, apellido_paterno, apellido_materno 
                  FROM clientes 
                  WHERE rol = 'admin' LIMIT 1";
    $result_admin = $con->query($sql_admin);
    $admin = $result_admin->fetch_assoc();

    if (!$admin) {
        $_SESSION['status_message'] = "No se encontró un cliente administrador en la base de datos.";
        $_SESSION['status_type'] = "error";
        header("Location: obra.php");
        exit;
    }

    $correo_admin = $admin['correo_electronico'];
    $nombre_admin = htmlspecialchars($admin['nombre_cliente'] . ' ' . $admin['apellido_paterno'] . ' ' . $admin['apellido_materno']);
} else {
    $_SESSION['status_message'] = "Parámetros inválidos proporcionados.";
    $_SESSION['status_type'] = "warning";
    header("Location: obra.php");
    exit;
}

try {
    // Configuración de PHPMailer
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'cesarneri803@gmail.com'; // Tu correo autenticador
    $mail->Password = 'kyoi thod ximj mipk'; // Tu contraseña de aplicación
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    // Configurar remitente y destinatario
    $mail->setFrom($correo_cliente, $nombre_cliente); // Correo del cliente como remitente
    $mail->addAddress($correo_admin, $nombre_admin); // Enviar al correo del administrador

    // Adjuntar archivo
    $mail->addAttachment($file_path);

    // Configurar cuerpo del mensaje
    $mail->isHTML(true);
    $mail->Subject = 'Contract Document';
    $mail->Body = "
        <p>Estimado administrador,</p>
        <p>Por favor, encuentre el contrato adjunto a este correo.</p>
        <p>Atentamente,<br>$nombre_cliente
    ";

    // Enviar correo
    $mail->send();

    $_SESSION['status_message'] = "Contrato enviado correctamente al administrador por correo electrónico.";
    $_SESSION['status_type'] = "success";
} catch (Exception $e) {
    $_SESSION['status_message'] = "Error al enviar el correo: {$mail->ErrorInfo}";
    $_SESSION['status_type'] = "error";
}

// Redirigir al usuario
header("Location: obra.php");
exit();
?>
