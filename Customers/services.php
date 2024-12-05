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

// Variables de paginación
$limit = 6; // Número de servicios por página
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Consultar categorías
$sql_categorias = "SELECT id_categoria, categoria FROM categorias_servicios ORDER BY categoria";
$result_categorias = $con->query($sql_categorias);

// Consultar servicios por categoría con paginación
$sql_servicios = "
    SELECT s.id_servicio, s.nombre_servicio, s.descripcion_servicio, s.imagen_servicio, s.total, c.categoria
    FROM servicios s
    JOIN categorias_servicios c ON s.id_categoria = c.id_categoria
    ORDER BY categoria, s.nombre_servicio
    LIMIT ? OFFSET ?";
$stmt_servicios = $con->prepare($sql_servicios);
$stmt_servicios->bind_param('ii', $limit, $offset);
$stmt_servicios->execute();
$result_servicios = $stmt_servicios->get_result();

$servicios = [];
if ($result_servicios->num_rows > 0) {
    while ($row = $result_servicios->fetch_assoc()) {
        $servicios[] = $row;
    }
}

// Consultar total de servicios para la paginación
$sql_total_servicios = "SELECT COUNT(*) AS total FROM servicios";
$result_total = $con->query($sql_total_servicios);
$row_total = $result_total->fetch_assoc();
$total_servicios = $row_total['total'];
$total_pages = ceil($total_servicios / $limit);

// Procesar formulario de presupuesto
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $observaciones = htmlspecialchars($_POST['observaciones'] ?? '', ENT_QUOTES, 'UTF-8');
    $id_servicio = intval($_POST['id_servicio']);
    $folio_presupuesto = 'FP' . strtoupper(uniqid());
    $id_empresa = 4; // Empresa fija
    $fecha_elaboracion = date('Y-m-d');
    $total = floatval($_POST['total_servicio']); // Usamos el total del servicio seleccionado

    // Validar el valor de $total
    if (!isset($_POST['total_servicio']) || empty($_POST['total_servicio'])) {
        echo "<script>alert('El total del servicio es requerido.');</script>";
        exit;
    }

    // Si se agrega nueva dirección
    if ($_POST['nueva_direccion'] === 'si') {
        $calle = htmlspecialchars($_POST['calle'], ENT_QUOTES, 'UTF-8');
        $ciudad = htmlspecialchars($_POST['ciudad'], ENT_QUOTES, 'UTF-8');
        $estado = htmlspecialchars($_POST['estado'], ENT_QUOTES, 'UTF-8');
        $codigo_postal = htmlspecialchars($_POST['codigo_postal'], ENT_QUOTES, 'UTF-8');

        $sql_insert_direccion = "INSERT INTO direcciones (id_cliente, calle, ciudad, estado, codigo_postal) 
                                VALUES (?, ?, ?, ?, ?)";
        $stmt_insert_direccion = $con->prepare($sql_insert_direccion);
        $stmt_insert_direccion->bind_param('issss', $id_cliente, $calle, $ciudad, $estado, $codigo_postal);
        if ($stmt_insert_direccion->execute()) {
            $id_direccion = $stmt_insert_direccion->insert_id; // Obtener el id de la nueva dirección
        }
        $stmt_insert_direccion->close();
    }

    // Insertar presupuesto en la base de datos
    $sql_insert_presupuesto = "
    INSERT INTO presupuestos (id_empresa, id_cliente, id_direccion, id_servicio, fecha_elaboracion, total, observaciones) 
    VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt_insert = $con->prepare($sql_insert_presupuesto);
    $stmt_insert->bind_param('iiiisis', $id_empresa, $id_cliente, $id_direccion, $id_servicio, $fecha_elaboracion, $total, $observaciones);

    if ($stmt_insert->execute()) {
        echo "<script>alert('Presupuesto registrado correctamente.'); window.location.href = window.location.href;</script>";
    } else {
        echo "<script>alert('Error al registrar el presupuesto: " . $stmt_insert->error . "');</script>";
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
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Nuestros Servicios</h2>

        <?php while ($categoria = $result_categorias->fetch_assoc()): ?>
            <h4><?php echo htmlspecialchars($categoria['categoria']); ?></h4>
            <div class="row">
                <?php foreach ($servicios as $servicio): ?>
                    <?php if ($servicio['categoria'] === $categoria['categoria']): ?>
                        <div class="col-md-4 mb-4 d-flex align-items-stretch">
                            <div class="card text-center h-100 shadow">
                                <img src="<?php echo htmlspecialchars($servicio['imagen_servicio']); ?>" 
                                    class="card-img-top img-fluid" 
                                    alt="<?php echo htmlspecialchars($servicio['nombre_servicio']); ?>" 
                                    style="height: 150px; object-fit: cover;">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo htmlspecialchars($servicio['nombre_servicio']); ?></h5>
                                    <p class="card-text"><?php echo htmlspecialchars($servicio['descripcion_servicio']); ?></p>
                                    <button type="button" class="btn btn-secondary" data-bs-toggle="modal" 
                                        data-bs-target="#cotizarModal" 
                                        data-id-servicio="<?php echo $servicio['id_servicio']; ?>"
                                        data-nombre-servicio="<?php echo htmlspecialchars($servicio['nombre_servicio']); ?>"
                                        data-total-servicio="<?php echo htmlspecialchars($servicio['total']); ?>">
                                        Cotizar
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        <?php endwhile; ?>

        <!-- Modal de Cotización -->
        <div class="modal fade" id="cotizarModal" tabindex="-1" aria-labelledby="cotizarModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="cotizarModalLabel">Solicitar Cotización</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="POST">
                        <div class="modal-body">
                            <input type="hidden" name="id_servicio" id="id_servicio">
                            <div class="mb-3">
                                <label for="nombre_servicio" class="form-label">Servicio</label>
                                <input type="text" class="form-control" id="nombre_servicio" name="nombre_servicio" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="total_servicio" class="form-label">Total</label>
                                <input type="text" class="form-control" id="total_servicio" name="total_servicio" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="observaciones" class="form-label">Observaciones</label>
                                <textarea class="form-control" id="observaciones" name="observaciones" rows="3"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="nueva_direccion" class="form-label">¿Agregar nueva dirección?</label>
                                <select class="form-select" id="nueva_direccion" name="nueva_direccion">
                                    <option value="no">No</option>
                                    <option value="si">Sí</option>
                                </select>
                            </div>
                            <div id="direccion_fields" style="display: none;">
                                <div class="mb-3">
                                    <label for="calle" class="form-label">Calle</label>
                                    <input type="text" class="form-control" id="calle" name="calle">
                                </div>
                                <div class="mb-3">
                                    <label for="ciudad" class="form-label">Ciudad</label>
                                    <input type="text" class="form-control" id="ciudad" name="ciudad">
                                </div>
                                <div class="mb-3">
                                    <label for="estado" class="form-label">Estado</label>
                                    <input type="text" class="form-control" id="estado" name="estado">
                                </div>
                                <div class="mb-3">
                                    <label for="codigo_postal" class="form-label">Código Postal</label>
                                    <input type="text" class="form-control" id="codigo_postal" name="codigo_postal">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Solicitar Cotización</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Paginación -->
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $page - 1; ?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                    </li>
                <?php endfor; ?>
                <li class="page-item <?php echo $page >= $total_pages ? 'disabled' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $page + 1; ?>" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>

    <script>
        // Función para llenar el modal con los datos del servicio
        var cotizarModal = document.getElementById('cotizarModal');
        cotizarModal.addEventListener('show.bs.modal', function(event) {
            var button = event.relatedTarget;
            var idServicio = button.getAttribute('data-id-servicio');
            var nombreServicio = button.getAttribute('data-nombre-servicio');
            var totalServicio = button.getAttribute('data-total-servicio');

            document.getElementById('id_servicio').value = idServicio;
            document.getElementById('nombre_servicio').value = nombreServicio;
            document.getElementById('total_servicio').value = totalServicio;
        });

        // Mostrar los campos de dirección si se selecciona "Sí"
        document.getElementById('nueva_direccion').addEventListener('change', function() {
            var direccionFields = document.getElementById('direccion_fields');
            if (this.value === 'si') {
                direccionFields.style.display = 'block';
            } else {
                direccionFields.style.display = 'none';
            }
        });
    </script>
</body>
</html>
        