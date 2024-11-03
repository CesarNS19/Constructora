<?php
session_start();
include 'conexion.php'; // Asegúrate de incluir tu conexión a la base de datos

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $con->prepare("
        SELECT id, correo, contrasena, rol, nombre, apellido_paterno, apellido_materno
        FROM (
            SELECT id_cliente AS id, correo_electronico AS correo, contrasena, rol, nombre_cliente AS nombre, apellido_paterno, apellido_materno 
            FROM clientes
            UNION
            SELECT id_empleado AS id, correo_personal AS correo, contrasena, rol, nombre, apellido_paterno, apellido_materno 
            FROM empleados
        ) AS usuarios
        WHERE correo = ?
    ");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Verifica la contraseña
        if (password_verify($password, $row['contrasena'])) {
            // Almacena información en la sesión
            $_SESSION['user'] = $row['correo'];
            $_SESSION['id'] = $row['id'];
            $_SESSION['nombre'] = $row['nombre'];
            $_SESSION['apellido_paterno'] = $row['apellido_paterno'];
            $_SESSION['apellido_materno'] = $row['apellido_materno'];

            // Redirige según el rol del usuario
            if ($row['rol'] === 'admin') {
                header("Location: ../administrador/index_admin.php");
            } elseif ($row['rol'] === 'empleado') {
                $_SESSION['id_empleado'] = $row['id']; // Almacena el ID del empleado
                header("Location: ../Employee/index_employee.php");
            } else {
                $_SESSION['id_cliente'] = $row['id']; // Almacena el ID del cliente
                header("Location: ../Customers/index.php");
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
$con->close();
?>
