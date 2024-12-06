<?php
require __DIR__ . '../../vendor/autoload.php';
require '../Login/conexion.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();

if (isset($_GET['folio']) && isset($_GET['file'])) {
    $folio_obra = $_GET['folio'];
    $file_path = urldecode($_GET['file']);

    $sql = "SELECT c.correo_electronico FROM obras o
            LEFT JOIN clientes c ON o.id_cliente = c.id_cliente
            WHERE o.folio_obra = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param('i', $folio_obra);
    $stmt->execute();
    $result = $stmt->get_result();
    $obra = $result->fetch_assoc();

    if ($obra && file_exists($file_path)) {
        $correo_cliente = $obra['correo_electronico'];
    } else {
        $_SESSION['status_message'] = "No se encontró el cliente o el archivo PDF.";
        $_SESSION['status_type'] = "error";
        header("Location: works.php");
        exit;
    }
} else {
    $_SESSION['status_message'] = "Parámetros inválidos proporcionados.";
    $_SESSION['status_type'] = "warning";
    header("Location: works.php");
    exit;
}

try {
    // Configuración y envío del correo
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'cesarneri803@gmail.com';
    $mail->Password = 'kyoi thod ximj mipk';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom('cesarneri803@gmail.com', 'Family Drywall');
    $mail->addAddress($correo_cliente);
    $mail->addAttachment($file_path);

    $mail->isHTML(true);
    $mail->Subject = 'Contract Document';
    $mail->Body = 'Please find the attached contract document.';

    $mail->send();

    $update_sql = "UPDATE obras SET estatus = 'iniciada' WHERE folio_obra = ?";
    $update_stmt = $con->prepare($update_sql);
    $update_stmt->bind_param('i', $folio_obra);
    $update_stmt->execute();

    $_SESSION['status_message'] = "Contrato enviado correctamente al cliente por correo electrónico";
    $_SESSION['status_type'] = "success";
} catch (Exception $e) {
    $_SESSION['status_message'] = "Error al enviar el correo: {$mail->ErrorInfo}";
    $_SESSION['status_type'] = "error";
}

header("Location: works.php");
exit();
?>
