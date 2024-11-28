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

// Obtener la dirección asociada al cliente
$sql_direccion = "SELECT d.id_direccion FROM direcciones d WHERE d.id_cliente = ?";
$stmt_direccion = $con->prepare($sql_direccion);
$stmt_direccion->bind_param('i', $id_cliente);
$stmt_direccion->execute();
$stmt_direccion->bind_result($id_direccion);
$stmt_direccion->fetch();
$stmt_direccion->close();

if (!$id_direccion) {
    die("No se encontró una dirección asociada al cliente con ID = $id_cliente.");
}

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $observaciones = htmlspecialchars($_POST['observaciones'] ?? '', ENT_QUOTES, 'UTF-8');
    $folio_presupuesto = $_POST['folio_presupuesto'] ?? ('FP' . strtoupper(uniqid())); // Generar folio si no está presente

    $sql_insert_presupuesto = "
        INSERT INTO presupuestos (id_empresa, id_cliente, id_direccion, id_servicio, observaciones, fecha_elaboracion) 
        VALUES (?, ?, ?, ?, ?, ?)";
    $stmt_insert = $con->prepare($sql_insert_presupuesto);

    $id_empresa = 1; // Empresa fija
    $fecha_elaboracion = date('Y-m-d');

    $stmt_insert->bind_param('iiiiss', $id_empresa, $id_cliente, $id_direccion, $id_servicio, $observaciones, $fecha_elaboracion);

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
        <form action="cotizar.php" id="presupuestoForm" method="POST">
          
            <div class="mb-3">
                <label for="observaciones" class="form-label">Observaciones</label>
                <textarea class="form-control" id="observaciones" name="observaciones" rows="3"></textarea>
            </div>
            <button type="submit" class="btn btn-primary"> Registrar</button>
        </form>
    </div>

    <script>
        $(document).ready(function() {
            // Generar folio automáticamente en el formulario
            $('#folio_presupuesto').val('FP' + Math.random().toString(36).substr(2, 9).toUpperCase());

            $('#presupuestoForm').on('submit', function(e) {
                e.preventDefault();

                // Limpiar mensajes previos
                $('#message').html('');

                // Enviar datos con AJAX
                $.ajax({
                    url: '',  // Envío al mismo archivo PHP
                    type: 'POST',
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function(response) {
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
