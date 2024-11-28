<?php
session_start();

if (!isset($_SESSION['id_cliente'])) {
    die("El cliente no está definido en la sesión.");
}

$id_cliente = $_SESSION['id_cliente'];
$con = new mysqli("localhost", "root", "", "constructora");
$con->set_charset("utf8");

if ($con->connect_error) {
    die("Error de conexión: " . $con->connect_error);
}

// Consulta para obtener la dirección asociada al cliente usando INNER JOIN
$sql_cliente = "
    SELECT c.id_direccion
    FROM clientes c
    INNER JOIN direcciones d ON c.id_direccion = d.id_direccion
    WHERE c.id_cliente = ?";
$stmt_cliente = $con->prepare($sql_cliente);
$stmt_cliente->bind_param('i', $id_cliente);
$stmt_cliente->execute();
$stmt_cliente->bind_result($id_direccion);
$stmt_cliente->fetch();
$stmt_cliente->close();

echo "ID Direccion del cliente: " . $id_direccion . "<br>";

if (!$id_direccion) {
    die("No se encontró la dirección asociada para el cliente con id_cliente = $id_cliente.");
}

// Obtener servicios disponibles
$sql_servicios = "SELECT id_servicio, nombre_servicio FROM servicios";
$result_servicios = $con->query($sql_servicios);

// Depuración: Ver los servicios disponibles
if ($result_servicios->num_rows > 0) {
    while ($row = $result_servicios->fetch_assoc()) {
        echo "Servicio: " . $row['id_servicio'] . " - " . $row['nombre_servicio'] . "<br>";
    }
} else {
    echo "No hay servicios disponibles.<br>";
}

// Procesamiento del formulario con AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // La fecha se toma automáticamente con la función date()
    $fecha_elaboracion = $_POST['fecha_elaboracion'] ?? date('Y-m-d');  // Toma la fecha actual por defecto
    $observaciones = htmlspecialchars($_POST['observaciones'] ?? '', ENT_QUOTES, 'UTF-8');
    $id_servicio = $_POST['id_servicio'] ?? null;

    echo "Fecha de Elaboración: " . $fecha_elaboracion . "<br>";
    echo "Observaciones: " . $observaciones . "<br>";
    echo "ID Servicio: " . $id_servicio . "<br>";

    // Validación del servicio seleccionado
    if (!is_numeric($id_servicio)) {
        echo json_encode(['status' => 'error', 'message' => 'ID de servicio inválido.']);
        exit;
    } else {
        $query_servicio = "SELECT id_servicio FROM servicios WHERE id_servicio = ?";
        $stmt_servicio = $con->prepare($query_servicio);
        $stmt_servicio->bind_param('i', $id_servicio);
        $stmt_servicio->execute();
        $stmt_servicio->store_result();

        if ($stmt_servicio->num_rows == 0) {
            echo json_encode(['status' => 'error', 'message' => 'El servicio seleccionado no existe.']);
            exit;
        } else {
            echo "Servicio válido encontrado. Procediendo con la inserción.<br>";

            $aux_id_empresa = 1;  // Asignar el valor del id_empresa, aquí he colocado 1 como ejemplo
            $aux_id_cliente = $id_cliente;
            $aux_fecha_elaboracion = $fecha_elaboracion;
            $aux_observaciones = $observaciones;
            $aux_id_direccion = $id_direccion;
            $aux_id_servicio = $id_servicio;

            // Depuración: Ver los datos a insertar
            echo "Datos a insertar:<br>";
            var_dump($aux_id_empresa, $aux_id_cliente, $aux_fecha_elaboracion, $aux_observaciones, $aux_id_direccion, $aux_id_servicio);

            // Insertar presupuesto
            $sql_insert_presupuesto = "INSERT INTO presupuestos (id_empresa, id_cliente, fecha_elaboracion, observaciones, id_direccion, id_servicio) 
                                       VALUES (?, ?, ?, ?, ?, ?)";
            $stmt_insert = $con->prepare($sql_insert_presupuesto);
            $stmt_insert->bind_param('iissii', $aux_id_empresa, $aux_id_cliente, $aux_fecha_elaboracion, $aux_observaciones, $aux_id_direccion, $aux_id_servicio);

            if ($stmt_insert->execute()) {
                echo json_encode(['status' => 'success', 'message' => 'Presupuesto guardado correctamente.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Error al registrar el presupuesto.']);
            }
            $stmt_insert->close();
        }
        $stmt_servicio->close();
    }
    $con->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Presupuesto</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h2>Registrar Presupuesto</h2>

        <!-- Mensajes de éxito o error -->
        <div id="message"></div>

        <!-- Formulario para registrar presupuesto -->
        <form id="presupuestoForm">
            <div class="mb-3">
                <label for="fecha_elaboracion" class="form-label">Fecha de Elaboración</label>
                <input type="date" class="form-control" id="fecha_elaboracion" name="fecha_elaboracion" 
                       value="<?php echo date('Y-m-d'); ?>" required disabled>
                <!-- La fecha se toma automáticamente y el campo está deshabilitado -->
            </div>
            <div class="mb-3">
                <label for="observaciones" class="form-label">Observaciones</label>
                <textarea class="form-control" id="observaciones" name="observaciones" rows="3"></textarea>
            </div>
            <div class="mb-3">
                <label for="id_servicio" class="form-label">Servicio</label>
                <select name="id_servicio" id="id_servicio" class="form-select" required>
                    <option value="">Seleccione un servicio</option>
                    <?php
                    // Este bloque PHP carga los servicios disponibles en el select
                    if ($result_servicios->num_rows > 0) {
                        while ($row = $result_servicios->fetch_assoc()) {
                            echo "<option value='" . $row['id_servicio'] . "'>" . $row['nombre_servicio'] . "</option>";
                        }
                    }
                    ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Registrar</button>
        </form>
    </div>

    <script>
        $(document).ready(function() {
            $('#presupuestoForm').on('submit', function(e) {
                e.preventDefault();

                // Limpiar mensajes previos
                $('#message').html('');

                // Crear el objeto FormData
                var formData = $(this).serialize();

                // Depuración: Ver los datos antes de enviarlos
                console.log("Datos a enviar: ", formData);

                // Enviar los datos con AJAX
                $.ajax({
                    url: '',  // Aquí se usa el mismo archivo PHP para procesar la solicitud
                    type: 'POST',
                    data: formData,
                    dataType: 'json',
                    success: function(response) {
                        console.log("Respuesta: ", response);
                        if (response.status === 'success') {
                            $('#message').html('<div class="alert alert-success">' + response.message + '</div>');
                        } else {
                            $('#message').html('<div class="alert alert-danger">' + response.message + '</div>');
                        }
                    },
                    error: function() {
                        $('#message').html('<div class="alert alert-danger">Ocurrió un error al procesar la solicitud.</div>');
                    }
                });
            });
        });
    </script>
</body>
</html>
