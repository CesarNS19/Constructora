<?php
session_start();

// Conexión a la base de datos
require '../Customers/superior_customer.php';
require '../Login/conexion.php';
$con = new mysqli("localhost", "root", "", "constructora");
$con->set_charset("utf8");

if ($con->connect_error) {
    die("Error de conexión: " . $con->connect_error);
}

// Verificar si el cliente está en sesión
if (!isset($_SESSION['id_cliente'])) {
    die("El cliente no está definido en la sesión.");
}

$id_cliente = $_SESSION['id_cliente'];

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

// Consultar servicios y obtener el total
$query = "SELECT id_servicio, nombre_servicio, descripcion_servicio, imagen_servicio, total FROM servicios LIMIT 3";
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
    $id_empresa = 1; // Empresa fija
    $fecha_elaboracion = date('Y-m-d');

    // Obtener el precio del servicio seleccionado
    $sql_precio = "SELECT precio_servicio FROM servicios WHERE id_servicio = ?";
    $stmt_precio = $con->prepare($sql_precio);
    $stmt_precio->bind_param('i', $id_servicio);
    $stmt_precio->execute();
    $stmt_precio->bind_result($precio_servicio);
    $stmt_precio->fetch();
    $stmt_precio->close();

    // Calcular el total (puedes agregar lógica de descuentos, impuestos, etc.)
    $total = $precio_servicio; // Para este ejemplo, solo se usa el precio del servicio

    // Si se agrega nueva dirección
    if ($_POST['nueva_direccion'] === 'si') {
        $calle = htmlspecialchars($_POST['calle'], ENT_QUOTES, 'UTF-8');
        $ciudad = htmlspecialchars($_POST['ciudad'], ENT_QUOTES, 'UTF-8');
        $estado = htmlspecialchars($_POST['estado'], ENT_QUOTES, 'UTF-8');
        $codigo_postal = htmlspecialchars($_POST['codigo_postal'], ENT_QUOTES, 'UTF-8');

        $sql_insert_direccion = "INSERT INTO direcciones (id_cliente, calle, ciudad, estado, codigo_postal) VALUES (?, ?, ?, ?, ?)";
        $stmt_insert_direccion = $con->prepare($sql_insert_direccion);
        $stmt_insert_direccion->bind_param('issss', $id_cliente, $calle, $ciudad, $estado, $codigo_postal);
        if ($stmt_insert_direccion->execute()) {
            $id_direccion = $stmt_insert_direccion->insert_id;
        }
        $stmt_insert_direccion->close();
    }

    // Insertar presupuesto con el total calculado
    $sql_insert_presupuesto = "
        INSERT INTO presupuestos (id_empresa, id_cliente, id_direccion, id_servicio, observaciones, folio_presupuesto, fecha_elaboracion, total) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt_insert = $con->prepare($sql_insert_presupuesto);
    $stmt_insert->bind_param('iiissssi', $id_empresa, $id_cliente, $id_direccion, $id_servicio, $observaciones, $folio_presupuesto, $fecha_elaboracion, $total);

    if ($stmt_insert->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Presupuesto registrado correctamente.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error al registrar el presupuesto: ' . $stmt_insert->error]);
    }
    $stmt_insert->close();
    $con->close();
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Servicios y Cotización</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Nuestros Servicios</h2>
        <div class="row">
            <?php if (!empty($servicios)): ?>
                <?php foreach ($servicios as $servicio): ?>
                    <div class="col-md-4 mb-4 d-flex align-items-stretch">
                        <div class="card text-center h-100 shadow">
                            <img src="<?php echo htmlspecialchars($servicio['imagen_servicio']); ?>" 
                                 class="card-img-top img-fluid" 
                                 alt="<?php echo htmlspecialchars($servicio['nombre_servicio']); ?>" 
                                 style="height: 150px; object-fit: cover;">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($servicio['nombre_servicio']); ?></h5>
                                <p class="card-text"><?php echo htmlspecialchars($servicio['descripcion_servicio']); ?></p>
                                <p class="card-text">Precio: $<?php echo number_format($servicio['total'], 2); ?></p>
                                <button type="button" class="btn btn-secondary" data-bs-toggle="modal" 
                                    data-bs-target="#cotizarModal" 
                                    data-id-servicio="<?php echo $servicio['id_servicio']; ?>"
                                    data-nombre-servicio="<?php echo htmlspecialchars($servicio['nombre_servicio']); ?>">
                                    Cotizar
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-center">No hay servicios disponibles.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Modal para Cotizar -->
    <div class="modal fade" id="cotizarModal" tabindex="-1" aria-labelledby="cotizarModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cotizarModalLabel">Cotizar Servicio</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="presupuestoForm">
                        <input type="hidden" name="id_servicio" id="modalIdServicio">
                        <div class="mb-3">
                            <label>Cliente:</label>
                            <p><?php echo htmlspecialchars("$nombre_cliente $apellido_paterno $apellido_materno"); ?></p>
                        </div>
                        <div class="mb-3">
                            <label>Servicio Seleccionado:</label>
                            <p id="modalNombreServicio"></p>
                        </div>
                        <div class="mb-3">
                            <label>¿Desea agregar una nueva dirección?</label>
                            <select class="form-select" id="nuevaDireccion" name="nueva_direccion">
                                <option value="no" selected>Conservar dirección actual</option>
                                <option value="si">Agregar nueva dirección</option>
                            </select>
                        </div>
                        <div id="nuevaDireccionForm" class="d-none">
                            <div class="mb-3">
                                <label>Calle:</label>
                                <input type="text" class="form-control" name="calle">
                            </div>
                            <div class="mb-3">
                                <label>Ciudad:</label>
                                <input type="text" class="form-control" name="ciudad">
                            </div>
                            <div class="mb-3">
                                <label>Estado:</label>
                                <input type="text" class="form-control" name="estado">
                            </div>
                            <div class="mb-3">
                                <label>Código Postal:</label>
                                <input type="text" class="form-control" name="codigo_postal">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label>Observaciones:</label>
                            <textarea class="form-control" name="observaciones" rows="3"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Generar Presupuesto</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $('#cotizarModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var idServicio = button.data('id-servicio');
            var nombreServicio = button.data('nombre-servicio');
            $('#modalIdServicio').val(idServicio);
            $('#modalNombreServicio').text(nombreServicio);
        });

        $('#nuevaDireccion').change(function () {
            if ($(this).val() === 'si') {
                $('#nuevaDireccionForm').removeClass('d-none');
            } else {
                $('#nuevaDireccionForm').addClass('d-none');
            }
        });

        $('#presupuestoForm').submit(function (event) {
            event.preventDefault();
            $.ajax({
                type: 'POST',
                url: 'cotizar.php', 
                data: $(this).serialize(),
                success: function(response) {
                    var data = JSON.parse(response);
                    alert(data.message);
                    if (data.status === 'success') {
                        $('#cotizarModal').modal('hide');
                    }
                }
            });
        });
    </script>
</body>
</html>
