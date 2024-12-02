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

// Consultar servicios
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
                            <button type="button" class="btn btn-secondary" data-bs-toggle="modal" 
                                    data-bs-target="#cotizarModal" 
                                    data-id-servicio="<?php echo $servicio['id_servicio']; ?>" 
                                    data-nombre-servicio="<?php echo htmlspecialchars($servicio['nombre_servicio']); ?>" 
                                    data-total-servicio="<?php echo $servicio['total']; ?>">
                                Cotizar
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No se encontraron servicios disponibles.</p>
        <?php endif; ?>
    </div>

    <!-- Modal de Cotización -->
    <div class="modal fade" id="cotizarModal" tabindex="-1" aria-labelledby="cotizarModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cotizarModalLabel">Solicitar Cotización</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="id_servicio" id="id_servicio">
                        <input type="hidden" name="total_servicio" id="total_servicio">

                        <div class="mb-3">
                            <label for="observaciones" class="form-label">Observaciones</label>
                            <textarea class="form-control" id="observaciones" name="observaciones" rows="3"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="nueva_direccion" class="form-label">¿Desea agregar una nueva dirección?</label>
                            <select class="form-select" id="nueva_direccion" name="nueva_direccion">
                                <option value="no">No</option>
                                <option value="si">Sí</option>
                            </select>
                        </div>

                        <div class="mb-3" id="direccion-form" style="display: none;">
                            <label for="calle" class="form-label">Calle</label>
                            <input type="text" class="form-control" id="calle" name="calle">

                            <label for="ciudad" class="form-label">Ciudad</label>
                            <input type="text" class="form-control" id="ciudad" name="ciudad">

                            <label for="estado" class="form-label">Estado</label>
                            <input type="text" class="form-control" id="estado" name="estado">

                            <label for="codigo_postal" class="form-label">Código Postal</label>
                            <input type="text" class="form-control" id="codigo_postal" name="codigo_postal">
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

</div>

<script>
// Inicializar datos del servicio seleccionado
const cotizarModal = document.getElementById('cotizarModal');
cotizarModal.addEventListener('show.bs.modal', function (event) {
    const button = event.relatedTarget;
    const idServicio = button.getAttribute('data-id-servicio');
    const nombreServicio = button.getAttribute('data-nombre-servicio');
    const totalServicio = button.getAttribute('data-total-servicio');

    document.getElementById('id_servicio').value = idServicio;
    document.getElementById('total_servicio').value = totalServicio;
    document.getElementById('cotizarModalLabel').textContent = `Cotizar ${nombreServicio}`;
});

// Mostrar el formulario de dirección solo si se selecciona "Sí"
document.getElementById('nueva_direccion').addEventListener('change', function () {
    const direccionForm = document.getElementById('direccion-form');
    if (this.value === 'si') {
        direccionForm.style.display = 'block';
    } else {
        direccionForm.style.display = 'none';
    }
});
</script>
</body>
</html>
