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
                <li><a href="index_admin.php" title="Home"><i class="fas fa-home"></i></a></li>
                <li><a href="perfil.php" title="Profile"><i class="fas fa-user"></i></a></li>
                <li><a href="servicios.php" title="Services"><i class="fas fa-concierge-bell"></i></a></li>
                <li><a href="#contact" title="Contact"><i class="fas fa-envelope"></i></a></li>
                <li><a href="customers.php" title="Customers"><i class="fas fa-users"></i></a></li>
                <li><a href="employee.php" title="Employee"><i class="fas fa-id-badge"></i></a></li>
                <li><a href="company.php" title="Company"><i class="fas fa-building"></i></a></li>
                <li><a href="works.php" title="Works"><i class="fas fa-ruler-combined"></i></a></li>
                <li><a href="budget.php" title="Budget"><i class="fas fa-file-invoice-dollar"></i></a></li>
                <li><a href="payroll.php" title="Employee Payroll"><i class="fas fa-money-check-alt"></i></a></li>
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