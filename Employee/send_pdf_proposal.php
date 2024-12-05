<?php
require __DIR__ . '../../vendor/autoload.php';
require '../Login/conexion.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();

if (isset($_GET['folio']) && isset($_GET['file'])) {
    $folio_presupuesto = $_GET['folio'];
    $file_path = urldecode($_GET['file']);

    $sql = "SELECT c.correo_electronico FROM presupuestos o
            LEFT JOIN clientes c ON o.id_cliente = c.id_cliente
            WHERE o.folio_presupuesto = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param('i', $folio_presupuesto);
    $stmt->execute();
    $result = $stmt->get_result();
    $obra = $result->fetch_assoc();

    if ($obra && file_exists($file_path)) {
        $correo_cliente = $obra['correo_electronico'];
    } else {
        $_SESSION['status_message'] = "No se encontró el cliente o el archivo PDF.";
        $_SESSION['status_type'] = "error";
        header("Location: budget.php");
        exit;
    }
} else {
    $_SESSION['status_message'] = "Parámetros inválidos proporcionados.";
    $_SESSION['status_type'] = "warning";
    header("Location: budget.php");
    exit;
}

try {
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
    $mail->Subject = 'Proposal Document';
    $mail->Body = 'Please find the attached proposal document.';

    $mail->send();
    $_SESSION['status_message'] = "Presupuesto enviado correctamente al cliente";
    $_SESSION['status_type'] = "success";
} catch (Exception $e) {
    $_SESSION['status_message'] = "Error al enviar el correo: {$mail->ErrorInfo}";
    $_SESSION['status_type'] = "error";
}

header("Location: budget.php");
exit();
?>
