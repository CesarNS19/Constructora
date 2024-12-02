<?php
// Inicia la sesión y conecta a la base de datos
session_start();

// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$database = "constructora";

$con = new mysqli($servername, $username, $password, $database);

if ($con->connect_error) {
    die("Error de conexión: " . $con->connect_error);
}

// Procesar inserción de datos cuando el formulario sea enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['submit'])) {
        $id_empresa = intval($_POST['id_empresa']);
        $id_cliente = intval($_POST['id_cliente']);
        $id_direccion = intval($_POST['id_direccion']);
        $id_servicio = intval($_POST['id_servicio']);
        $observaciones = htmlspecialchars($_POST['observaciones'], ENT_QUOTES, 'UTF-8');
        $folio_presupuesto = 'FP' . strtoupper(uniqid());
        $fecha_elaboracion = date('Y-m-d');

        $sql_insert = "INSERT INTO presupuestos (id_empresa, id_cliente, id_direccion, id_servicio, observaciones, folio_presupuesto, fecha_elaboracion) 
                       VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $con->prepare($sql_insert);
        $stmt->bind_param('iiiisss', $id_empresa, $id_cliente, $id_direccion, $id_servicio, $observaciones, $folio_presupuesto, $fecha_elaboracion);

        if ($stmt->execute()) {
            $mensaje = "Presupuesto registrado correctamente.";
        } else {
            $mensaje = "Error al registrar el presupuesto: " . $stmt->error;
        }
        $stmt->close();
    }

    // Actualizar o agregar dirección
    if (isset($_POST['actualizar_direccion'])) {
        $id_cliente = intval($_POST['id_cliente']);
        $calle = htmlspecialchars($_POST['calle'], ENT_QUOTES, 'UTF-8');
        $ciudad = htmlspecialchars($_POST['ciudad'], ENT_QUOTES, 'UTF-8');
        $estado = htmlspecialchars($_POST['estado'], ENT_QUOTES, 'UTF-8');
        $codigo_postal = htmlspecialchars($_POST['codigo_postal'], ENT_QUOTES, 'UTF-8');

        $sql_update = "INSERT INTO direcciones (id_cliente, calle, ciudad, estado, codigo_postal) 
                       VALUES (?, ?, ?, ?, ?) 
                       ON DUPLICATE KEY UPDATE calle = VALUES(calle), ciudad = VALUES(ciudad), estado = VALUES(estado), codigo_postal = VALUES(codigo_postal)";
        $stmt = $con->prepare($sql_update);
        $stmt->bind_param('issss', $id_cliente, $calle, $ciudad, $estado, $codigo_postal);
        if ($stmt->execute()) {
            $mensaje = "Dirección actualizada correctamente.";
        } else {
            $mensaje = "Error al actualizar la dirección: " . $stmt->error;
        }
        $stmt->close();
    }
}

// Obtener listas para desplegar en el formulario
$sql_clientes = "SELECT id_cliente, CONCAT(nombre_cliente, ' ', apellido_paterno, ' ', apellido_materno) AS nombre_completo FROM clientes";
$result_clientes = $con->query($sql_clientes);

$sql_servicios = "SELECT id_servicio, nombre_servicio FROM servicios";
$result_servicios = $con->query($sql_servicios);

$sql_empresas = "SELECT id_empresa, nombre_empresa FROM empresa";
$result_empresas = $con->query($sql_empresas);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Presupuesto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h1 class="text-center">Registrar Presupuesto</h1>
        <?php if (isset($mensaje)): ?>
            <div class="alert alert-info"><?= $mensaje; ?></div>
        <?php endif; ?>
        <!-- Botón para abrir el modal -->
        <div class="text-center mb-4">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#presupuestoModal">Agregar Presupuesto</button>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="presupuestoModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalLabel">Registrar Presupuesto</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="POST">
                        <div class="modal-body">
                            <!-- Cliente -->
                            <div class="mb-3">
                                <label for="id_cliente" class="form-label">Cliente</label>
                                <select name="id_cliente" id="id_cliente" class="form-select" required>
                                    <option value="">Seleccione un cliente</option>
                                    <?php while ($cliente = $result_clientes->fetch_assoc()): ?>
                                        <option value="<?= $cliente['id_cliente']; ?>"><?= $cliente['nombre_completo']; ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>

                            <!-- Dirección -->
                            <div class="mb-3">
                                <label for="direccion" class="form-label">Dirección</label>
                                <select name="id_direccion" id="id_direccion" class="form-select" required>
                                    <option value="">Seleccione una dirección</option>
                                    <!-- Opciones cargadas dinámicamente -->
                                </select>
                                <button type="button" id="btnNuevaDireccion" class="btn btn-link">Agregar Nueva Dirección</button>
                            </div>

                            <!-- Nueva Dirección -->
                            <div id="nuevaDireccion" style="display: none;">
                                <div class="mb-3">
                                    <label for="calle" class="form-label">Calle</label>
                                    <input type="text" name="calle" id="calle" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label for="ciudad" class="form-label">Ciudad</label>
                                    <input type="text" name="ciudad" id="ciudad" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label for="estado" class="form-label">Estado</label>
                                    <input type="text" name="estado" id="estado" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label for="codigo_postal" class="form-label">Código Postal</label>
                                    <input type="text" name="codigo_postal" id="codigo_postal" class="form-control">
                                </div>
                            </div>

                            <!-- Servicio -->
                            <div class="mb-3">
                                <label for="id_servicio" class="form-label">Servicio</label>
                                <select name="id_servicio" id="id_servicio" class="form-select" required>
                                    <option value="">Seleccione un servicio</option>
                                    <?php while ($servicio = $result_servicios->fetch_assoc()): ?>
                                        <option value="<?= $servicio['id_servicio']; ?>"><?= $servicio['nombre_servicio']; ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>

                            <!-- Empresa -->
                            <div class="mb-3">
                                <label for="id_empresa" class="form-label">Empresa</label>
                                <select name="id_empresa" id="id_empresa" class="form-select" required>
                                    <option value="">Seleccione una empresa</option>
                                    <?php while ($empresa = $result_empresas->fetch_assoc()): ?>
                                        <option value="<?= $empresa['id_empresa']; ?>"><?= $empresa['nombre_empresa']; ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>

                            <!-- Observaciones -->
                            <div class="mb-3">
                                <label for="observaciones" class="form-label">Observaciones</label>
                                <textarea name="observaciones" id="observaciones" rows="4" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" name="submit" class="btn btn-primary">Guardar Presupuesto</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            // Mostrar campos de nueva dirección
            $('#btnNuevaDireccion').on('click', function () {
                $('#nuevaDireccion').toggle();
            });

            // Cargar direcciones dinámicamente
            $('#id_cliente').on('change', function () {
                const clienteId = $(this).val();
                if (clienteId) {
                    $.ajax({
                        url: 'cargar_direcciones.php',
                        method: 'POST',
                        data: { id_cliente: clienteId },
                        success: function (data) {
                            $('#id_direccion').html(data);
                        }
                    });
                } else {
                    $('#id_direccion').html('<option value="">Seleccione una dirección</option>');
                }
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
