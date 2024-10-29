<?php
session_start();
include 'conexion.php'; // Archivo donde se configura la conexión a la base de datos

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Preparamos la consulta para buscar el usuario en la tabla clientes
    $stmt = $con->prepare("SELECT id_cliente, correo_electronico, contrasena, rol FROM clientes WHERE correo_electronico = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verificar si se encontró un cliente con ese correo
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Verificar la contraseña ingresada con la encriptada en la base de datos
        if (password_verify($password, $row['contrasena'])) {
            $_SESSION['user'] = $row['correo_electronico'];
            $_SESSION['id_cliente'] = $row['id_cliente'];
            
            // Redireccionar según el rol del usuario
            if ($row['rol'] === 'admin') {
                header("Location: ../administrador/index_admin.php"); // Redirige a la página para admin
            } else {
                header("Location: ../Customers/index.php"); // Redirige a la página para usuario normal
            }
            exit();
        } else {
            echo "Contraseña incorrecta.";
        }
    } else {
        echo "Correo electrónico no encontrado.";
    }

    $stmt->close();
}
?>
