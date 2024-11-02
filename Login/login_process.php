<?php
session_start();
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Preparar la consulta para incluir los nuevos campos
    $stmt = $con->prepare("SELECT id_cliente, correo_electronico, contrasena, rol, nombre_cliente, apellido_paterno, apellido_materno FROM clientes WHERE correo_electronico = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verificar si se encontró un cliente con ese correo
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Verificar la contraseña ingresada con la encriptada en la base de datos
        if (password_verify($password, $row['contrasena'])) {
            // Almacenar los datos en la sesión
            $_SESSION['user'] = $row['correo_electronico'];
            $_SESSION['id_cliente'] = $row['id_cliente'];
            $_SESSION['nombre_cliente'] = $row['nombre_cliente'];
            $_SESSION['apellido_paterno'] = $row['apellido_paterno'];
            $_SESSION['apellido_materno'] = $row['apellido_materno'];

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