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
        <div class="logo"></div>
        <ul class="nav-links">
        <li><a href="index.php"><i class="fas fa-home"></i></a></li>
        <li><a href="perfil.php" title="Perfil"><i class="fas fa-user"></i></a></li>

          <li><a href="../Login/logout.php"><i class="fas fa-sign-out-alt"></i></a></li>

        </ul>
    </nav>
</header>

<div id="Alert"></div>

<div class="container mt-5 d-flex justify-content-center">
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
</div>

<!-- Modal para editar datos -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Editar Datos</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="update_profile.php" method="post">
                    <div class="form-group">
                        <label for="nombre_cliente">Nombre</label>
                        <input type="text" class="form-control" name="nombre_cliente" id="nombre_cliente" value="<?php echo htmlspecialchars($user['nombre_cliente'] ?? ''); ?>">
                    </div>
                    <div class="form-group">
                        <label for="apellido_paterno">Apellido Paterno</label>
                        <input type="text" class="form-control" name="apellido_paterno" id="apellido_paterno" value="<?php echo htmlspecialchars($user['apellido_paterno'] ?? ''); ?>">
                    </div>
                    <div class="form-group">
                        <label for="apellido_materno">Apellido Materno</label>
                        <input type="text" class="form-control" name="apellido_materno" id="apellido_materno" value="<?php echo htmlspecialchars($user['apellido_materno'] ?? ''); ?>">
                    </div>
                    <div class="form-group">
                        <label for="correo_electronico">Correo Electrónico</label>
                        <input type="email" class="form-control" name="correo_electronico" id="correo_electronico" value="<?php echo htmlspecialchars($user['correo_electronico'] ?? ''); ?>">
                    </div>
                    <div class="form-group">
                        <label for="telefono_personal">Teléfono</label>
                        <input type="text" class="form-control" name="telefono_personal" id="telefono_personal" value="<?php echo htmlspecialchars($user['telefono_personal'] ?? ''); ?>">
                    </div>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
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
                mostrarToast(
                    '<?= $_SESSION["status_type"] === "warning" ? "Advertencia" : "Éxito" ?>',
                    '<?= $_SESSION["status_message"] ?>',
                    '<?= $_SESSION["status_type"] ?>'
                );
                <?php unset($_SESSION['status_message'], $_SESSION['status_type']); ?>
            <?php endif; ?>
        });
        
</script>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
