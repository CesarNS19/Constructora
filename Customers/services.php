<?php
require '../Login/conexion.php';
require 'superior_customer.php';

// Verificar si el cliente está en sesión
if (!isset($_SESSION['id_cliente'])) {
    die("El cliente no está definido en la sesión.");
}

$id_cliente = $_SESSION['id_cliente'];
$con = new mysqli("localhost", "root", "", "constructora");
$con->set_charset("utf8");

if ($con->connect_error) {
    die("Error de conexión: " . $con->connect_error);
}

// Obtener información del cliente
$sql_cliente = "SELECT nombre_cliente, apellido_paterno, apellido_materno FROM clientes WHERE id_cliente = ?";
$stmt_cliente = $con->prepare($sql_cliente);
$stmt_cliente->bind_param('i', $id_cliente);
$stmt_cliente->execute();
$stmt_cliente->bind_result($nombre_cliente, $apellido_paterno, $apellido_materno);
$stmt_cliente->fetch();
$stmt_cliente->close();

// Obtener dirección asociada al cliente
$sql_direccion = "SELECT id_direccion, calle, ciudad, estado, codigo_postal FROM direcciones WHERE id_cliente = ?";
$stmt_direccion = $con->prepare($sql_direccion);
$stmt_direccion->bind_param('i', $id_cliente);
$stmt_direccion->execute();
$stmt_direccion->bind_result($id_direccion, $calle, $ciudad, $estado, $codigo_postal);
$stmt_direccion->fetch();
$stmt_direccion->close();

// Consultar servicios
$query = "SELECT id_servicio, nombre_servicio, descripcion_servicio, imagen_servicio, total FROM servicios";
$result = $con->query($query);

$servicios = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $servicios[] = $row;
    }
}

// Procesar formulario de presupuesto
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $observaciones = htmlspecialchars($_POST['observaciones'] ?? '', ENT_QUOTES, 'UTF-8');
    $id_servicio = intval($_POST['id_servicio']);
    $folio_presupuesto = 'FP' . strtoupper(uniqid());
    $id_empresa = htmlspecialchars($_POST['id_empresa'] ?? '');
    $fecha_elaboracion = date('Y-m-d');
    $total = floatval($_POST['total_servicio']); // Usamos el total del servicio seleccionado

    // Validar el valor de $total
    if (!isset($_POST['total_servicio']) || empty($_POST['total_servicio'])) {
        echo "<script>alert('El total del servicio es requerido.');</script>";
        exit;
    }

    if ($_POST['nueva_direccion'] === 'si') {
        $sql_check_direccion = "SELECT id_direccion FROM direcciones WHERE id_cliente = ?";
        $stmt_check = $con->prepare($sql_check_direccion);
        $stmt_check->bind_param('i', $id_cliente);
        $stmt_check->execute();
        $stmt_check->store_result();

        if ($stmt_check->num_rows > 0) {
            $sql_update_direccion = "
                UPDATE direcciones
                SET calle = ?, ciudad = ?, estado = ?, num_ext = ?, num_int = ?, codigo_postal = ?
                WHERE id_cliente = ?";
            $stmt_update = $con->prepare($sql_update_direccion);
            $calle = htmlspecialchars($_POST['calle'], ENT_QUOTES, 'UTF-8');
            $ciudad = htmlspecialchars($_POST['ciudad'], ENT_QUOTES, 'UTF-8');
            $estado = htmlspecialchars($_POST['estado'], ENT_QUOTES, 'UTF-8');
            $num_ext = htmlspecialchars($_POST['num_ext'], ENT_QUOTES, 'UTF-8');
            $num_int = htmlspecialchars($_POST['num_int'], ENT_QUOTES, 'UTF-8');
            $codigo_postal = htmlspecialchars($_POST['codigo_postal'], ENT_QUOTES, 'UTF-8');

            $stmt_update->bind_param('ssssssi', $calle, $ciudad, $estado, $num_ext, $num_int, $codigo_postal, $id_cliente);
            $stmt_update->execute();
            $stmt_update->close();
        } else {
            $sql_insert_direccion = "INSERT INTO direcciones (id_cliente, calle, ciudad, estado, num_ext, num_int, codigo_postal) 
                                    VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt_insert_direccion = $con->prepare($sql_insert_direccion);
            $stmt_insert_direccion->bind_param('issssss', $id_cliente, $calle, $ciudad, $estado, $num_ext, $num_int, $codigo_postal);
            if ($stmt_insert_direccion->execute()) {
                $id_direccion = $stmt_insert_direccion->insert_id; 
            }
            $stmt_insert_direccion->close();
        }
        $stmt_check->close();
    } else {
        $id_direccion = $_POST['id_direccion'] ?? null;
    }

    $anticipo = !empty($_POST['anticipo']) ? floatval($_POST['anticipo']) : 0;

    $sql_insert_presupuesto = "
    INSERT INTO presupuestos (id_empresa, id_cliente, id_direccion, id_servicio, anticipo, fecha_elaboracion, total, observaciones) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt_insert = $con->prepare($sql_insert_presupuesto);
    $stmt_insert->bind_param('iiiissss', $id_empresa, $id_cliente, $id_direccion, $id_servicio, $anticipo, $fecha_elaboracion, $total, $observaciones);

    if ($stmt_insert->execute()) {
        echo "<script>alert('Presupuesto registrado correctamente.'); window.location.href = window.location.href;</script>";
    } else {
        echo "<script>alert('Error al registrar el presupuesto: " . $stmt_insert->error . "');</script>";
    }
    $stmt_insert->close();
    $con->close();
    exit;
}

$sql_empresas = "SELECT id_empresa, nombre_empresa FROM empresa";
$result_empresas = $con->query($sql_empresas);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Servicios y Cotización</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center">Our Services</h2></br></br>
    <div class="row justify-content-center">
        <?php if (!empty($servicios)): ?>
            <?php foreach ($servicios as $servicio): ?>
                <div class="col-lg-3 col-md-6 mb-4 d-flex align-items-stretch">
                    <div class="card text-center h-100">
                        <img src="<?php echo htmlspecialchars($servicio['imagen_servicio']); ?>" 
                            class="card-img-top img-fluid" 
                            alt="<?php echo htmlspecialchars($servicio['nombre_servicio']); ?>" 
                            style="height: 200px; object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($servicio['nombre_servicio']); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars($servicio['descripcion_servicio']); ?></p>
                            <button type="button" class="btn btn-secondary" data-bs-toggle="modal" 
                                data-bs-target="#cotizarModal" 
                                data-id-servicio="<?php echo $servicio['id_servicio']; ?>"
                                data-nombre-servicio="<?php echo htmlspecialchars($servicio['nombre_servicio']); ?>"
                                data-total-servicio="<?php echo htmlspecialchars($servicio['total']); ?>">
                                Qoute
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-center">There are no services recorded.</p>
        <?php endif; ?>
    </div>
</div>

    <!-- Modal para Cotizar -->
    <div class="modal fade" id="cotizarModal" tabindex="-1" aria-labelledby="cotizarModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cotizarModalLabel">Qoute</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="presupuestoForm" method="POST">
                        <input type="hidden" name="id_servicio" id="modalIdServicio">
                        <div class="mb-3">
                            <label>Customer</label>
                            <p><?php echo htmlspecialchars("$nombre_cliente $apellido_paterno $apellido_materno"); ?></p>
                        </div>
                        <div class="mb-3">
                            <label>Service</label>
                            <p id="modalNombreServicio"></p>
                        </div>
                        <div class="mb-3">
                            <label>Total</label>
                            <p id="modalTotalServicio"></p>
                            <input type="hidden" name="total_servicio" id="modalTotal">
                            <div class="mb-3">
                                <label>Advance Payment</label>
                                <input type="number" class="form-control" name="anticipo">
                            </div>
                        <div class="form-group mb-3">
                        <label for="id_empresa">Selected a Company</label>
                        <select name="id_empresa" class="form-control" required>
                            <option value="">Selected a Company</option>
                            <?php
                            if ($result_empresas->num_rows > 0) {
                                while ($empresa = $result_empresas->fetch_assoc()) {
                                    echo "<option value='" . htmlspecialchars($empresa['id_empresa']) . "'>" . htmlspecialchars($empresa['nombre_empresa']) . "</option>";
                                }
                            } else {
                                echo "<option value=''>No hay empresas disponibles</option>";
                            }
                            ?>
                        </select>
                    </div>
                        <?php if (empty($id_direccion)): ?>
                            <div class="alert alert-warning" role="alert">
                            You don't have a registered address. Please enter an address to proceed with the quotation.
                            </div>
                            <div class="mb-3">
                                <label>Do you want to add a new address?</label>
                                <select class="form-select" id="nuevaDireccion" name="nueva_direccion">
                                    <option value="no" selected>Keep current address</option>
                                    <option value="si">Add New Address</option>
                                </select>
                            </div>
                        <?php else: ?>
                            <div class="mb-3">
                                <label>Current address:</label>
                                <p><?php echo htmlspecialchars("$calle, $ciudad, $estado, $codigo_postal"); ?></p>
                            </div>
                            <div class="mb-3">
                                <label>Do you want to keep this address or add a new one?</label>
                                <select class="form-select" id="nuevaDireccion" name="nueva_direccion">
                                    <option value="no" selected>Keep current address</option>
                                    <option value="si">Add New Address</option>
                                </select>
                            </div>
                        <?php endif; ?>
                        <div id="nuevaDireccionForm" class="d-none">
                        <div class="mb-3">
                                <label>Outside Number</label>
                                <input type="number" class="form-control" name="num_ext">
                            </div>
                            <div class="mb-3">
                                <label>Inner Number</label>
                                <input type="number" class="form-control" name="num_int">
                            </div>
                            <div class="mb-3">
                                <label>Street</label>
                                <input type="text" class="form-control" name="calle">
                            </div>
                            <div class="mb-3">
                                <label>City</label>
                                <input type="text" class="form-control" name="ciudad">
                            </div>
                            <div class="mb-3">
                                <label>State</label>
                                <input type="text" class="form-control" name="estado">
                            </div>
                            <div class="mb-3">
                                <label>Postal Code</label>
                                <input type="text" class="form-control" name="codigo_postal">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label>Observations</label>
                            <textarea class="form-control" name="observaciones" rows="3"></textarea>
                        </div>
                        <button type="button" class="btn btn-primary" onclick="confirmarCotizacion()">Qoute</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Llenar el modal con la información del servicio
        var cotizarModal = document.getElementById('cotizarModal');
        cotizarModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var idServicio = button.getAttribute('data-id-servicio');
            var nombreServicio = button.getAttribute('data-nombre-servicio');
            var totalServicio = button.getAttribute('data-total-servicio');

            document.getElementById('modalIdServicio').value = idServicio;
            document.getElementById('modalNombreServicio').textContent = nombreServicio;
            document.getElementById('modalTotalServicio').textContent = totalServicio;
            document.getElementById('modalTotal').value = totalServicio;
        });

        // Mostrar u ocultar el formulario de nueva dirección
        document.getElementById('nuevaDireccion').addEventListener('change', function () {
            var nuevaDireccionForm = document.getElementById('nuevaDireccionForm');
            if (this.value === 'si') {
                nuevaDireccionForm.classList.remove('d-none');
            } else {
                nuevaDireccionForm.classList.add('d-none');
            }
        });

        // Confirmación antes de enviar la cotización
        function confirmarCotizacion() {
            if (confirm("¿Está seguro de que desea enviar la cotización?")) {
                document.getElementById('presupuestoForm').submit();
            }
        }
    </script>
</body>
</html>