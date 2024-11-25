<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '../../vendor/autoload.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    $conn = new mysqli("localhost", "root", "", "constructora");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $conn->set_charset('utf8');

    $sql = "
        SELECT id_cliente, correo_electronico 
        FROM clientes 
        WHERE correo_electronico = ? 
        UNION 
        SELECT id_empleado, correo_personal 
        FROM empleados 
        WHERE correo_personal = ?
    ";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $verification_code = bin2hex(random_bytes(16));

        $user = $result->fetch_assoc();
        $user_id = $user['id_cliente'] ?? $user['id_empleado'];
        
        if (isset($user['id_cliente'])) {
            $sql = "UPDATE clientes SET codigo_recuperacion = ? WHERE id_cliente = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $verification_code, $user_id);
            $stmt->execute();
        } else {
            $sql = "UPDATE empleados SET codigo_recuperacion = ? WHERE id_empleado = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $verification_code, $user_id);
            $stmt->execute();
        }

        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'cesarneri803@gmail.com';
            $mail->Password = 'kyoi thod ximj mipk';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('cesarneri803@gmail.com', 'Family Drywall');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Recuperación de Contraseña';
            $mail->Body = 'Hola, has solicitado recuperar tu contraseña. Usa el siguiente código para restablecerla: <strong>' . $verification_code . '</strong><br>Además, puedes hacer clic en el siguiente enlace para restablecer tu contraseña: <a href="http://localhost/Constructora/Login/reset_your_password.php?code=' . $verification_code . '">Restablecer Contraseña</a>';

            $mail->send();

            header("Location: forgot_password.php?success=1");
            exit();
        } catch (Exception $e) {
            header("Location: forgot_password.php?error=Error al enviar el correo: {$mail->ErrorInfo}");
            exit();
        }
    } else {
        header("Location: forgot_password.php?error=No se encontró ningún usuario con ese correo electrónico.");
        exit();
    }

    $conn->close();
}
?>
