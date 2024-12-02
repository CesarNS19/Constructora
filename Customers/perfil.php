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
            <li><a href="index.php" title="Inicio"><i class="fas fa-home"></i></a></li>
            <li><a href="perfil.php" title="Perfil"><i class="fas fa-user"></i></a></li>
            <li><a href="services.php" title="Servicios"><i class="fas fa-concierge-bell"></i></a></li>
            <li><a href="#contact" title="Contacto"><i class="fas fa-envelope"></i></a></li>
            <li><a href="../Login/logout.php" title="Cerrar Sesión"><i class="fas fa-sign-out-alt"></i></a></li>
        </ul>
    </nav>
</header>

<div id="Alert"></div>

<div class="container mt-5 d-flex justify-content-center">
    <!-- Card for Personal Information -->
    <div class="card text-center" style="width: 24rem;">
        <div class="card-body">
            <h5 class="card-title">Datos Personales</h5>
            <p><strong>Nombre:</strong> <?php echo htmlspecialchars($user['nombre_cliente'] ?? 'No disponible'); ?></p>
            <p><strong>Apellidos:</strong> <?php echo htmlspecialchars(($user['apellido_paterno'] ?? '') . ' ' . ($user['apellido_materno'] ?? '')); ?></p>
            <p><strong>Correo:</strong> <?php echo htmlspecialchars($user['correo_electronico'] ?? 'No disponible'); ?></p>
            <p><strong>Teléfono:</strong> <?php echo htmlspecialchars($user['telefono_personal'] ?? 'No disponible'); ?></p>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#editModal">Editar Datos</button>
        </div>
    </div>

    <!-- Card for Address Information -->
    <div class="card text-center mt-4" style="width: 24rem;">
        <div class="card-body">
            <h5 class="card-title">Dirección</h5>
            <?php if ($direccion) { ?>
                <p><strong>Calle:</strong> <?php echo htmlspecialchars($direccion['calle'] ?? 'No disponible'); ?></p>
                <p><strong>Número Exterior:</strong> <?php echo htmlspecialchars($direccion['num_ext'] ?? 'No disponible'); ?></p>
                <p><strong>Número Interior:</strong> <?php echo htmlspecialchars($direccion['num_int'] ?? 'No disponible'); ?></p>
                <p><strong>Ciudad:</strong> <?php echo htmlspecialchars($direccion['ciudad'] ?? 'No disponible'); ?></p>
                <p><strong>Estado:</strong> <?php echo htmlspecialchars($direccion['estado'] ?? 'No disponible'); ?></p>
                <p><strong>Código Postal:</strong> <?php echo htmlspecialchars($direccion['codigo_postal'] ?? 'No disponible'); ?></p>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#editAddressModal">Editar Dirección</button>
            <?php } else { ?>
                <p>No se encontraron datos de dirección.</p>
                <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#addAddressModal">Agregar Dirección</button>
            <?php } ?>
        </div>
    </div>
</div>

<!-- Modal para editar datos personales -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Editar Datos Personales</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="update_profile.php" method="post">
                    <div class="form-group">
                        <label for="nombre_cliente">Nombre</label>
                        <input type="text" class="form-control" name="nombre_cliente" id="nombre_cliente" value="<?php echo htmlspecialchars($user['nombre_cliente']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="apellido_paterno">Apellido Paterno</label>
                        <input type="text" class="form-control" name="apellido_paterno" id="apellido_paterno" value="<?php echo htmlspecialchars($user['apellido_paterno']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="apellido_materno">Apellido Materno</label>
                        <input type="text" class="form-control" name="apellido_materno" id="apellido_materno" value="<?php echo htmlspecialchars($user['apellido_materno']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="correo_electronico">Correo Electrónico</label>
                        <input type="email" class="form-control" name="correo_electronico" id="correo_electronico" value="<?php echo htmlspecialchars($user['correo_electronico']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="telefono_personal">Teléfono</label>
                        <input type="text" class="form-control" name="telefono_personal" id="telefono_personal" value="<?php echo htmlspecialchars($user['telefono_personal']); ?>" required>
                    </div>
                    <input type="hidden" name="id_cliente" value="<?php echo htmlspecialchars($user['id_cliente']); ?>">
                    <button type="submit" class="btn btn-primary">Actualizar Perfil</button>
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
                <h5 class="modal-title" id="addAddressModalLabel">Agregar Dirección</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="add_address.php" method="post">
                    <div class="form-group">
                        <label for="calle">Calle</label>
                        <input type="text" class="form-control" name="calle" id="calle" placeholder="Calle" required>
                    </div>
                    <div class="form-group">
                        <label for="num_ext">Número Exterior</label>
                        <input type="text" class="form-control" name="num_ext" id="num_ext" placeholder="Número Exterior" required>
                    </div>
                    <div class="form-group">
                        <label for="num_int">Número Interior</label>
                        <input type="text" class="form-control" name="num_int" id="num_int" placeholder="Número Interior">
                    </div>
                    <div class="form-group">
                        <label for="ciudad">Ciudad</label>
                        <input type="text" class="form-control" name="ciudad" id="ciudad" placeholder="Ciudad" required>
                    </div>
                    <div class="form-group">
                        <label for="estado">Estado</label>
                        <input type="text" class="form-control" name="estado" id="estado" placeholder="Estado" required>
                    </div>
                    <div class="form-group">
                        <label for="codigo_postal">Código Postal</label>
                        <input type="text" class="form-control" name="codigo_postal" id="codigo_postal" placeholder="Código Postal" required>
                    </div>
                    <input type="hidden" name="id_cliente" value="<?php echo htmlspecialchars($user['id_cliente']); ?>">
                    <button type="submit" class="btn btn-primary">Agregar Dirección</button>
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
                <h5 class="modal-title" id="editAddressModalLabel">Editar Dirección</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="update_address.php" method="post">
                    <div class="form-group">
                        <label for="calle">Calle</label>
                        <input type="text" class="form-control" name="calle" id="calle" value="<?php echo htmlspecialchars($direccion['calle']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="num_ext">Número Exterior</label>
                        <input type="text" class="form-control" name="num_ext" id="num_ext" value="<?php echo htmlspecialchars($direccion['num_ext']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="num_int">Número Interior</label>
                        <input type="text" class="form-control" name="num_int" id="num_int" value="<?php echo htmlspecialchars($direccion['num_int']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="ciudad">Ciudad</label>
                        <input type="text" class="form-control" name="ciudad" id="ciudad" value="<?php echo htmlspecialchars($direccion['ciudad']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="estado">Estado</label>
                        <input type="text" class="form-control" name="estado" id="estado" value="<?php echo htmlspecialchars($direccion['estado']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="codigo_postal">Código Postal</label>
                        <input type="text" class="form-control" name="codigo_postal" id="codigo_postal" value="<?php echo htmlspecialchars($direccion['codigo_postal']); ?>" required>
                    </div>
                    <input type="hidden" name="id_cliente" value="<?php echo htmlspecialchars($user['id_cliente']); ?>">
                    <input type="hidden" name="id_direccion" value="<?php echo htmlspecialchars($direccion['id_direccion']); ?>">
                    <button type="submit" class="btn btn-primary">Actualizar Dirección</button>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>
