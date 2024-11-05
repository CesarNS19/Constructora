<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Family Drywall</title>
    <link rel="stylesheet" href="../Css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <header>
        <nav>
            <button class="language-toggle" id="languageButton" aria-label="Change Language" title="Cambiar Idioma">
                <i class="fas fa-globe"></i>
            </button>
            <ul class="nav-links">
                <li><a href="index.php" title="Iniico"><i class="fas fa-home"></i></a></li>
                <li><a href="perfil.php" title="Perfil"><i class="fas fa-user"></i></a></li>
                <li><a href="services.php" title="Servicios"><i class="fas fa-concierge-bell"></i></a></li>
                <li><a href="#contact" title="Contacto"><i class="fas fa-envelope"></i></a></li>
            </ul>
            <div class="user-controls" style="display: flex; align-items: center; margin-left: auto;">
            <?php
            session_start();

            if (isset($_SESSION['nombre'], $_SESSION['apellido_paterno'], $_SESSION['apellido_materno'])) {
                $fullName = $_SESSION['nombre'] . ' ' . $_SESSION['apellido_paterno'] . ' ' . $_SESSION['apellido_materno'];
                
                if (isset($_COOKIE['timezone'])) {
                    date_default_timezone_set($_COOKIE['timezone']);
                }

                $hour = date('H');
                
                if ($hour >= 5 && $hour < 12) {
                    $greeting = "Good Morning";
                } elseif ($hour >= 12 && $hour < 19) {
                    $greeting = "Good Afternoon";
                } else {
                    $greeting = "Good night";
                }

                echo "
                <div class='nav-item' style='display: flex; align-items: center;'>
                    <a class='nav-link' href='perfil.php' style='color: black; font-size: 18px; text-decoration: none; font-weight: 600;'>
                        $greeting $fullName
                    </a>
                    <div class='vr' style='height: 24px; width: 1px; background-color: #ffffff; margin: 0 10px;'></div>
                    <button id='themeToggle' style='background: none; border: none; color: black; font-size: 20px; cursor: pointer;'>
                        <i class='fas fa-adjust'></i>
                    </button>
                </div>";
            } else {
                echo "<a class='nav-link' href='../Login/login.php' style='color: black; font-size: 18px; text-decoration: none; font-weight: 600;'>Iniciar sesión</a>";
            }
            ?>
        </div>
        </nav>

    </header>
    <script>
    document.cookie = "timezone=" + Intl.DateTimeFormat().resolvedOptions().timeZone;
</script>

</body>
</html>