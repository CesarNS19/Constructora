<?php
session_start();
require '../Login/conexion.php';

if (!isset($_SESSION['id_cliente'])) {
    header("Location: ../Login/login.php");
    exit;
}

$user_id = $_SESSION['id_cliente'];

// Obtener los datos del cliente
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

// Obtener la dirección del cliente
$query_dir = "SELECT * FROM direcciones WHERE id_cliente = ?";
$stmt_dir = $con->prepare($query_dir);
$stmt_dir->bind_param("i", $user_id);
$stmt_dir->execute();
$result_dir = $stmt_dir->get_result();
$direccion = $result_dir->fetch_assoc();

if (!$direccion) {
    $direccion = null; // Indica que no hay dirección registrada
}

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
    <title>Perfil de Usuario</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="../Css/style.css">
</head>
<body style="background-color: #ffffff;">

<header>
    <nav>
        <ul class="nav-links">
        <li><button class="language-toggle btn btn-primary" id="languageButton" aria-label="Change Language" title="Change Language">
                    <i class="fas fa-globe"></i>
        </button></li>
            <li><a href="index.php" title="Inicio"><i class="fas fa-home"></i></a></li>
            <li><a href="perfil.php" title="Perfil"><i class="fas fa-user"></i></a></li>
            <li><a href="services.php" title="Servicios"><i class="fas fa-concierge-bell"></i></a></li>
            <li><a href="#contact" title="Contacto"><i class="fas fa-envelope"></i></a></li>
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

<div class="container mt-5">
    <div class="row justify-content-center g-4">
        <!-- Card for Personal Information -->
        <div class="col-lg-4 col-md-6">
            <div class="card text-center h-100">
                <div class="card-body">
                    <h5 class="card-title">Personal Data</h5>
                    <p><strong>Name</strong><strong>: </strong> <?php echo htmlspecialchars($user['nombre_cliente'] ?? 'No disponible'); ?></p>
                    <p><strong>Last Name</strong><strong>: </strong> <?php echo htmlspecialchars(($user['apellido_paterno'] ?? '') . ' ' . ($user['apellido_materno'] ?? '')); ?></p>
                    <p><strong>Email</strong><strong>: </strong> <?php echo htmlspecialchars($user['correo_electronico'] ?? 'No disponible'); ?></p>
                    <p><strong>Phone</strong><strong>: </strong> <?php echo htmlspecialchars($user['telefono_personal'] ?? 'No disponible'); ?></p>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#editModal">Edit</button>
                </div>
            </div>
        </div>

        <!-- Card for Address Information -->
        <div class="col-lg-4 col-md-6">
            <div class="card text-center h-100">
                <div class="card-body">
                    <h5 class="card-title">Address</h5>
                    <?php if ($direccion) { ?>
                        <p><strong>Outside Number</strong><strong>: </strong> <?php echo htmlspecialchars($direccion['num_ext'] ?? 'No disponible'); ?></p>
                        <p><strong>Inner Number</strong><strong>: </strong> <?php echo htmlspecialchars($direccion['num_int'] ?? 'No disponible'); ?></p>
                        <p><strong>Street</strong><strong>: </strong> <?php echo htmlspecialchars($direccion['calle'] ?? 'No disponible'); ?></p>
                        <p><strong>City</strong><strong>: </strong> <?php echo htmlspecialchars($direccion['ciudad'] ?? 'No disponible'); ?></p>
                        <p><strong>State</strong><strong>: </strong> <?php echo htmlspecialchars($direccion['estado'] ?? 'No disponible'); ?></p>
                        <p><strong>Postal Code</strong><strong>: </strong> <?php echo htmlspecialchars($direccion['codigo_postal'] ?? 'No disponible'); ?></p>
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#editAddressModal">Edit</button>
                    <?php } else { ?>
                        <p>The address data was not found.</p>
                        <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#addAddressModal">Add Address</button>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Modal para editar datos personales -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="update_profile.php" method="post">
                    <div class="form-group">
                        <label for="nombre_cliente">Name</label>
                        <input type="text" class="form-control" name="nombre_cliente" id="nombre_cliente" value="<?php echo htmlspecialchars($user['nombre_cliente']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="apellido_paterno">Paternal Surname</label>
                        <input type="text" class="form-control" name="apellido_paterno" id="apellido_paterno" value="<?php echo htmlspecialchars($user['apellido_paterno']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="apellido_materno">Maternal Surname</label>
                        <input type="text" class="form-control" name="apellido_materno" id="apellido_materno" value="<?php echo htmlspecialchars($user['apellido_materno']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="correo_electronico">Email</label>
                        <input type="email" class="form-control" name="correo_electronico" id="correo_electronico" value="<?php echo htmlspecialchars($user['correo_electronico']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="telefono_personal">Phone</label>
                        <input type="text" class="form-control" name="telefono_personal" id="telefono_personal" value="<?php echo htmlspecialchars($user['telefono_personal']); ?>" required>
                    </div>
                    <input type="hidden" name="id_cliente" value="<?php echo htmlspecialchars($user['id_cliente']); ?>">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal para agregar dirección -->
<div class="modal fade" id="addAddressModal" tabindex="-1" role="dialog" aria-labelledby="addAddressModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addAddressModalLabel">Add Address</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="add_address.php" method="post">
                    <div class="form-group">
                        <label for="calle">Street</label>
                        <input type="text" class="form-control" name="calle" id="calle"  required>
                    </div>
                    <div class="form-group">
                        <label for="num_ext">Ouside Number</label>
                        <input type="text" class="form-control" name="num_ext" id="num_ext"  required>
                    </div>
                    <div class="form-group">
                        <label for="num_int">Inner Number</label>
                        <input type="text" class="form-control" name="num_int" id="num_int">
                    </div>
                    <div class="form-group">
                        <label for="ciudad">City</label>
                        <input type="text" class="form-control" name="ciudad" id="ciudad" required>
                    </div>
                    <div class="form-group">
                        <label for="estado">Status</label>
                        <input type="text" class="form-control" name="estado" id="estado"  required>
                    </div>
                    <div class="form-group">
                        <label for="codigo_postal">Postal Code</label>
                        <input type="text" class="form-control" name="codigo_postal" id="codigo_postal" required>
                    </div>
                    <input type="hidden" name="id_cliente" value="<?php echo htmlspecialchars($user['id_cliente']); ?>">
                    <button type="submit" class="btn btn-primary">Add Address</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal para editar dirección -->
<div class="modal fade" id="editAddressModal" tabindex="-1" role="dialog" aria-labelledby="editAddressModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editAddressModalLabel">Edit</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="update_address.php" method="post">
                    <div class="form-group">
                        <label for="calle">Street</label>
                        <input type="text" class="form-control" name="calle" id="calle" value="<?php echo htmlspecialchars($direccion['calle']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="num_ext">Outside Number</label>
                        <input type="text" class="form-control" name="num_ext" id="num_ext" value="<?php echo htmlspecialchars($direccion['num_ext']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="num_int">Inner Number</label>
                        <input type="text" class="form-control" name="num_int" id="num_int" value="<?php echo htmlspecialchars($direccion['num_int']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="ciudad">City</label>
                        <input type="text" class="form-control" name="ciudad" id="ciudad" value="<?php echo htmlspecialchars($direccion['ciudad']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="estado">State</label>
                        <input type="text" class="form-control" name="estado" id="estado" value="<?php echo htmlspecialchars($direccion['estado']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="codigo_postal">Postal Code</label>
                        <input type="text" class="form-control" name="codigo_postal" id="codigo_postal" value="<?php echo htmlspecialchars($direccion['codigo_postal']); ?>" required>
                    </div>
                    <input type="hidden" name="id_cliente" value="<?php echo htmlspecialchars($user['id_cliente']); ?>">
                    <input type="hidden" name="id_direccion" value="<?php echo htmlspecialchars($direccion['id_direccion']); ?>">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>
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