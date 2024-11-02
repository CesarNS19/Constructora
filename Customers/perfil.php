<?php
session_start();
require '../Login/conexion.php'; // Asegúrate de incluir tu conexión a la base de datos

// Verificar si el usuario está autenticado
if (!isset($_SESSION['id_cliente'])) {
    header("Location: ../Login/login.php"); // Redirigir a la página de inicio de sesión si no está autenticado
    exit;
}

// Supongamos que tienes una variable de sesión para el ID del usuario
$user_id = $_SESSION['id_cliente'];

// Obtener los datos del usuario de la base de datos
$query = "SELECT * FROM clientes WHERE id_cliente = ?";
$stmt = $con->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    echo "No se encontraron datos para este usuario.";
    exit;
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de Usuario</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../Css/style.css">
</head>
<body class="dark-mode">

<header>
    <nav>
        <div class="logo">Mi Aplicación</div>
        <ul class="nav-links">
            <li><a href="index.php">Inicio</a></li>
            <li><a href="perfil.php">Perfil</a></li>
            <li><a href="../Login/logout.php">Cerrar Sesión</a></li>
        </ul>
    </nav>
</header>

<div class="container mt-5">
    <h2 class="text-center">Perfil de Usuario</h2>
    <div class="card mt-4">
        <div class="card-body">
            <h5>Datos Personales</h5>
            <p><strong>Nombre:</strong> <?php echo htmlspecialchars($user['nombre_cliente']); ?></p>
            <p><strong>Apellido:</strong> <?php echo htmlspecialchars($user['apellido_paterno'] . ' ' . $user['apellido_materno']); ?></p>
            <p><strong>Correo:</strong> <?php echo htmlspecialchars($user['correo_electronico']); ?></p>
            <p><strong>Teléfono:</strong> <?php echo htmlspecialchars($user['telefono_personal']); ?></p>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-body">
            <h5>Agregar Dirección</h5>
            <form action="update_profile.php" method="POST">
                <div class="form-group">
                    <label for="direccion">Dirección:</label>
                    <input type="text" class="form-control" id="direccion" name="direccion" required>
                </div>
                <button type="submit" class="btn btn-primary">Agregar Dirección</button>
            </form>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-body">
            <h5>Cambiar Contraseña</h5>
            <form action="update_profile.php" method="POST">
                <div class="form-group">
                    <label for="password">Nueva Contraseña:</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirmar Contraseña:</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                </div>
                <button type="submit" class="btn btn-warning">Cambiar Contraseña</button>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
