<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '../../vendor/autoload.php';

// Verifica si se recibió una solicitud POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener la dirección de correo electrónico del formulario
    $email = $_POST['email'];

    // Realizar la conexión a la base de datos
    $conn = new mysqli("localhost", "root", "", "constructora");

    // Verificar la conexión
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Configurar codificación UTF-8 para la conexión
    $conn->set_charset('utf8');

    // Consultar la contraseña del usuario basada en el correo electrónico
    $sql = "SELECT contrasena FROM clientes WHERE correo_electronico = '$email'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        // Obtener la contraseña del resultado de la consulta
        $row = $result->fetch_assoc();
        $userPassword = $row['contrasena'];

        // Configura PHPMailer para enviar correo a través de Gmail
        $mail = new PHPMailer(true);

        try {
            // Configuración del servidor SMTP
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'cesarneri803@gmail.com'; // Tu dirección de correo de Gmail
            $mail->Password = 'kyoi thod ximj mipk'; // Tu contraseña de Gmail
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            // Configurar remitente y destinatario
            $mail->setFrom('cesarneri803@gmail.com', 'Family Drywall');
            $mail->addAddress($email);

            // Contenido del correo
            $mail->isHTML(true);
            $mail->Subject = 'Recuperación de Contraseña';
            $mail->Body = 'Hola, has solicitado recuperar tu contraseña. Haz clic en el siguiente enlace para restablecerla: ' .
                '<a href="http://localhost/Constructora/Login/reset_your_password.php">Restablecer Contraseña</a>';
            
            // Enviar correo
            $mail->send();

            // Redirigir a forgot_password.php con éxito
            header("Location: forgot_password.php?success=1");
            exit();
        } catch (Exception $e) {
            header("Location: forgot_password.php?error=Error al enviar el correo: {$mail->ErrorInfo}");
            exit();
        }
    } else {
        // Redirigir a forgot_password.php con un mensaje de error
        header("Location: forgot_password.php?error=No se encontró ningún usuario con ese correo electrónico.");
        exit();
    }

    // Cerrar conexión a la base de datos
    $conn->close();
}
?>
