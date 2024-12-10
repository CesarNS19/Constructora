<?php
session_start();
require '../Login/conexion.php';

if (!isset($_SESSION['id_cliente'])) {
    header("Location: ../Login/login.php");
    exit;
}

$user_id = $_SESSION['id_cliente'];

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
    <title>Perfil de Administrador</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="../Css/style.css">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<header>
    <nav>
        <ul class="nav-links">
        <li><button class="language-toggle btn btn-primary" id="languageButton" aria-label="Change Language" title="Change Language">
                    <i class="fas fa-globe"></i>
        </button></li> 
        <li><a href="index_admin.php" title="Inicio"><i class="fas fa-home"></i></a></li>
        <li><a href="perfil.php" title="Perfil"><i class="fas fa-user"></i></a></li>
        <li><a href="servicios.php" title="Servicios"><i class="fas fa-concierge-bell"></i></a></li>
        <li><a href="#contact" title="Contacto"><i class="fas fa-envelope"></i></a></li>
        <li><a href="customers.php" title="Clientes"><i class="fas fa-users"></i></a></li>
        <li><a href="employee.php" title="Empleados"><i class="fas fa-id-badge"></i></a></li>
        <li><a href="company.php" title="Empresa"><i class="fas fa-building"></i></a></li>
        <li><a href="works.php" title="Obras"><i class="fas fa-ruler-combined"></i></a></li>
        <li><a href="budget.php" title="Presupuesto"><i class="fas fa-file-invoice-dollar"></i></a></li>
        <li><a href="payroll.php" title="Nómina Empleado"><i class="fas fa-money-check-alt"></i></a></li>

        </ul>
        <div class="user-controls" style="display: flex; align-items: center; margin-left: auto;">
        <li class="nav-links"><a href="../Login/logout.php" title="Cerrar sesión"><i class="fas fa-sign-out-alt text-dark"></i></a></li>
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

<div id="Alert"></div>

<div class="container mt-5 d-flex justify-content-center">
    <div class="card text-center" style="width: 24rem;">
        <div class="card-body">
            <h5 class="card-title">Personal Data</h5>
            <p><strong>Name</strong><strong>: </strong><?php echo htmlspecialchars($user['nombre_cliente'] ?? 'No disponible'); ?></p>
            <p><strong>Last Name</strong><strong>: </strong><?php echo htmlspecialchars(($user['apellido_paterno'] ?? '') . ' ' . ($user['apellido_materno'] ?? '')); ?></p>
            <p><strong>Email</strong><strong>: </strong> <?php echo htmlspecialchars($user['correo_electronico'] ?? 'No disponible'); ?></p>
            <p><strong>Phone</strong><strong>: </strong> <?php echo htmlspecialchars($user['telefono_personal'] ?? 'No disponible'); ?></p>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editModal">Edit</button>
        </div>
    </div>
</div>

<!-- Modal para editar datos -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content shadow-lg">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="update_profile.php" method="post">
                    <div class="mb-3">
                        <label for="nombre_cliente" class="form-label">Name</label>
                        <input type="text" class="form-control" name="nombre_cliente" id="nombre_cliente" value="<?php echo htmlspecialchars($user['nombre_cliente'] ?? ''); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="apellido_paterno" class="form-label">Paternal Surname</label>
                        <input type="text" class="form-control" name="apellido_paterno" id="apellido_paterno" value="<?php echo htmlspecialchars($user['apellido_paterno'] ?? ''); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="apellido_materno" class="form-label">Maternal Surname</label>
                        <input type="text" class="form-control" name="apellido_materno" id="apellido_materno" value="<?php echo htmlspecialchars($user['apellido_materno'] ?? ''); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="correo_electronico" class="form-label">Email</label>
                        <input type="email" class="form-control" name="correo_electronico" id="correo_electronico" value="<?php echo htmlspecialchars($user['correo_electronico'] ?? ''); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="telefono_personal" class="form-label">Phone</label>
                        <input type="text" class="form-control" name="telefono_personal" id="telefono_personal" value="<?php echo htmlspecialchars($user['telefono_personal'] ?? ''); ?>" required>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary w-100">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<script>

function mostrarToast(titulo, mensaje, tipo) {
            let icon = '';
            let alertClass = '';

            switch (tipo) {
                case 'success':
                    icon = '<span class="fas fa-check-circle text-white fs-6"></span>';
                    alertClass = 'alert-success';
                    break;
                case 'error':
                    icon = '<span class="fas fa-times-circle text-white fs-6"></span>';
                    alertClass = 'alert-danger';
                    break;
                case 'warning':
                    icon = '<span class="fas fa-exclamation-circle text-white fs-6"></span>';
                    alertClass = 'alert-warning';
                    break;
                case 'info':
                    icon = '<span class="fas fa-info-circle text-white fs-6"></span>';
                    alertClass = 'alert-info';
                    break;
                default:
                    icon = '<span class="fas fa-info-circle text-white fs-6"></span>';
                    alertClass = 'alert-info';
                    break;
            }

            const alert = `
            <div class="alert ${alertClass} d-flex align-items-center alert-dismissible fade show" role="alert">
                <div class="me-2">${icon}</div>
                <div>${titulo}: ${mensaje}</div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>`;

            $("#Alert").html(alert);

            setTimeout(() => {
                $(".alert").alert('close');
            }, 4000);
        }

        document.addEventListener('DOMContentLoaded', function() {
            <?php if (isset($_SESSION['status_message']) && isset($_SESSION['status_type'])): ?>
                <?php if ($_SESSION["status_type"] === "warning"): ?>
                    mostrarToast("Advertencia", '<?= $_SESSION["status_message"] ?>', '<?= $_SESSION["status_type"] ?>');
                <?php elseif ($_SESSION["status_type"] === "error"): ?>
                    mostrarToast("Error", '<?= $_SESSION["status_message"] ?>', '<?= $_SESSION["status_type"] ?>');
                <?php elseif ($_SESSION["status_type"] === "info"): ?>
                    mostrarToast("Info", '<?= $_SESSION["status_message"] ?>', '<?= $_SESSION["status_type"] ?>');
                <?php else: ?>
                    mostrarToast("Éxito", '<?= $_SESSION["status_message"] ?>', '<?= $_SESSION["status_type"] ?>');
                <?php endif; ?>
                <?php unset($_SESSION['status_message'], $_SESSION['status_type']); ?>
            <?php endif; ?>
        });
        
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
