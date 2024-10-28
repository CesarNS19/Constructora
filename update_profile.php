<?php
session_start();
include 'db_connection.php'; // Asegúrate de incluir tu conexión a la base de datos

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Agregar dirección
    if (!empty($_POST['direccion'])) {
        $direccion = $_POST['direccion'];
        $query = "INSERT INTO direcciones (id_usuario, direccion) VALUES (?, ?)";
        $stmt = $con->prepare($query);
        $stmt->bind_param("is", $user_id, $direccion);
        $stmt->execute();
        echo "Dirección agregada exitosamente.";
    }

    // Cambiar contraseña
    if (!empty($_POST['password']) && !empty($_POST['confirm_password'])) {
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        if ($password === $confirm_password) {
            // Actualizar contraseña
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $query = "UPDATE personas SET Clave = ? WHERE id_persona = ?";
            $stmt = $con->prepare($query);
            $stmt->bind_param("si", $hashed_password, $user_id);
            $stmt->execute();
            echo "Contraseña cambiada exitosamente.";
        } else {
            echo "Las contraseñas no coinciden.";
        }
    }
}

header("Location: perfil.php");
exit;
?>
