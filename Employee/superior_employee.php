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
                <li><button class="language-toggle btn btn-primary" id="languageButton" aria-label="Change Language" title="Cambiar idioma">
                    <i class="fas fa-globe"></i>
                </button></li> 
                <li><a href="index_employee.php" title="Inicio"><i class="fas fa-home"></i></a></li>
                <li><a href="perfil.php" title="Perfil"><i class="fas fa-user"></i></a></li>
                <li><a href="servicios.php" title="Servicios"><i class="fas fa-concierge-bell"></i></a></li>
                <li><a href="#contact" title="Contacto"><i class="fas fa-envelope"></i></a></li>
                <li><a href="customers.php" title="Clientes"><i class="fas fa-users"></i></a></li>
                <li><a href="works.php" title="Works"><i class="fas fa-ruler-combined"></i></a></li>
                <li><a href="budget.php" title="Budget"><i class="fas fa-file-invoice-dollar"></i></a></li>
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

        document.getElementById('languageButton').addEventListener('click', () => {
            const newLang = document.documentElement.lang === 'es' ? 'en' : 'es';
            document.cookie = `lang=${newLang}; path=/`;
            location.reload();
        });
    </script>
    
        <script src="../Js/language.js"></script>
</body>
</html>
