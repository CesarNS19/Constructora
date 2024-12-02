<?php
session_start();

// Verifica si el cliente está logueado
if (!isset($_SESSION['id_cliente'])) {
    echo "Error: No se ha definido el ID del cliente en la sesión.";
    exit;
}

// Establecer el ID del cliente desde la sesión
$id_cliente = $_SESSION['id_cliente'];

// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$database = "constructora";

$con = new mysqli($servername, $username, $password, $database);

if ($con->connect_error) {
    die("Conexión fallida: " . $con->connect_error);
}

// Consulta para verificar si el cliente tiene obras asociadas
$sql = "SELECT COUNT(*) AS total_obras FROM obras WHERE id_cliente = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $id_cliente);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$total_obras = $row['total_obras'];
$showNotification = isset($_COOKIE['obra_notificada']) ? false : $total_obras > 0;

$stmt->close();
$con->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Family Drywall</title>
    <link rel="stylesheet" href="../Css/style.css">
    <link rel="stylesheet" href="../Css/style_bg.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <header>
        <nav>
            <ul class="nav-links">
                <li><a href="index.php" title="Home"><i class="fas fa-home"></i></a></li>
                <li><a href="perfil.php" title="Profile"><i class="fas fa-user"></i></a></li>
                <li><a href="services.php" title="Services"><i class="fas fa-concierge-bell"></i></a></li>
                <li><a href="#contact" title="Contact"><i class="fas fa-envelope"></i></a></li>
                <li><a href="../Login/logout.php" title="Cerrar Sesión"><i class="fas fa-sign-out-alt"></i></a></li>
                <?php if ($total_obras > 0): ?>
                    <li>
                        <a href="obra.php" title="Mis Obras" onclick="dismissNotification()">
                            <i class="fas fa-hard-hat"></i>
                            <?php if ($showNotification): ?>
                                <span class="badge bg-danger">!</span>
                            <?php endif; ?>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <script>
        function dismissNotification() {
            document.cookie = "obra_notificada=true; path=/; max-age=86400"; // Notificación válida por 1 día
        }
    </script>
</body>
</html>
