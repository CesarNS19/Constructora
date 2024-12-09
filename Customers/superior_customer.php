<?php
session_start();

$lang = 'en';
if (isset($_COOKIE['lang'])) {
    $lang = $_COOKIE['lang'];
}

$translations = [
    'en' => [
        'morning' => 'Good Morning',
        'afternoon' => 'Good Afternoon',
        'night' => 'Good Night',
        'log_in' => 'Log In'
    ],
    'es' => [
        'morning' => 'Buenos Días',
        'afternoon' => 'Buenas Tardes',
        'night' => 'Buenas Noches',
        'log_in' => 'Iniciar Sesión'
    ]
];

if (isset($_COOKIE['timezone'])) {
    date_default_timezone_set($_COOKIE['timezone']);
}

$hour = date('H');
if ($hour >= 5 && $hour < 12) {
    $greeting = $translations[$lang]['morning'];
} elseif ($hour >= 12 && $hour < 19) {
    $greeting = $translations[$lang]['afternoon'];
} else {
    $greeting = $translations[$lang]['night'];
}
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
            <li><button class="language-toggle btn btn-primary" id="languageButton" aria-label="Change Language" title="Change Language">
                    <i class="fas fa-globe"></i>
                </button></li>
                <li><a href="index.php" title="Home"><i class="fas fa-home"></i></a></li>
                <li><a href="perfil.php" title="Profile"><i class="fas fa-user"></i></a></li>
                <li><a href="services.php" title="Services"><i class="fas fa-concierge-bell"></i></a></li>
                <?php if ($total_obras > 0): ?>
                    <li>
                        <a href="obra.php" title="Works" onclick="dismissNotification()">
                            <i class="fas fa-hard-hat"></i>
                            <?php if ($showNotification): ?>
                                <span class="badge bg-danger">!</span>
                            <?php endif; ?>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
            <div class="user-controls" style="display: flex; align-items: center; margin-left: auto;">
            <?php
                if (isset($_SESSION['nombre'], $_SESSION['apellido_paterno'], $_SESSION['apellido_materno'])) {
                    $fullName = $_SESSION['nombre'] . ' ' . $_SESSION['apellido_paterno'] . ' ' . $_SESSION['apellido_materno'];

                    echo "
                    <div class='nav-item' style='display: flex; align-items: center;'>
                        <a class='nav-link' href='perfil.php' style='color: black; font-size: 18px; text-decoration: none; font-weight: 600;'>
                            $greeting $fullName
                        </a>
                    </div>";
                } else {
                    $logInText = $translations[$lang]['log_in'];
                    echo "<a class='nav-link' href='../Login/login.php' style='color: black; font-size: 18px; text-decoration: none; font-weight: 600;'>$logInText</a>";
                }
                ?>
        </div>
        </nav>
    </header>

    <script>
        function dismissNotification() {
            document.cookie = "obra_notificada=true; path=/; max-age=86400";
        }

        document.cookie = "timezone=" + Intl.DateTimeFormat().resolvedOptions().timeZone;

        document.getElementById('languageButton').addEventListener('click', () => {
            const newLang = document.documentElement.lang === 'es' ? 'en' : 'es';
            document.cookie = `lang=${newLang}; path=/`;
            location.reload();
        });
    </script>
    
        <script src="../Js/language.js"></script>
</body>
</html>
