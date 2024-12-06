<?php
require '../Login/conexion.php';

$sql_empresas = "SELECT id_empresa, nombre_empresa FROM empresa";
$result_empresas = $con->query($sql_empresas);

$sql_clientes = "SELECT id_cliente, nombre_cliente, apellido_paterno, apellido_materno FROM clientes WHERE rol = 'usuario'";
$result_clientes = $con->query($sql_clientes);

$sql_servicios = "SELECT id_servicio, nombre_servicio, total FROM servicios";

require '../Customers/superior_customer.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Work</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

<div id="Alert"></div>

<!-- Tabla de Obras -->
<section class="my-2"><br/>
    <div class="table-responsive">
        <table class="table table-bordered table-hover text-center">
            <thead class="thead-dark">
                <tr>
                    <h2 class="text-center">Works</h2><br/>
                    <th>Company</th>
                    <th>Service</th>
                    <th>Customer</th>
                    <th>Customer Address</th>
                    <th>Start Date</th>
                    <th>Advance Payment</th>
                    <th>Debit</th>
                    <th>Total Work</th>
                    <th>Observations</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT o.*, e.nombre_empresa, c.nombre_cliente, c.apellido_paterno, c.apellido_materno, d.ciudad, s.nombre_servicio
                        FROM obras o
                        LEFT JOIN empresa e ON o.id_empresa = e.id_empresa
                        LEFT JOIN clientes c ON o.id_cliente = c.id_cliente
                        LEFT JOIN servicios s ON o.id_servicio = s.id_servicio
                        LEFT JOIN direcciones d ON o.id_direccion = d.id_direccion";
                $result = $con->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $nombre_cliente = htmlspecialchars($row['nombre_cliente'] . ' ' . $row['apellido_paterno'] . ' ' . $row['apellido_materno']);
                        $folio_obra = htmlspecialchars($row['folio_obra']);
                        $servicio = htmlspecialchars($row['nombre_servicio']);
                        $file_path = "../pdf/Contract_" . $folio_obra . ".pdf";

                        echo "<tr data-folio='$folio_obra'>";
                        echo "<td>" . htmlspecialchars($row['nombre_empresa']) . "</td>";
                        echo "<td>" . $servicio . "</td>";
                        echo "<td>" . $nombre_cliente . "</td>";
                        echo "<td>" . htmlspecialchars($row['ciudad']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['fecha_inicio']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['anticipo']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['adeudo']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['total_obra']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['observaciones']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['estatus']) . "</td>";
                        echo "<td>";
                        echo "<a href='generate_pdf.php?folio=$folio_obra' class='btn btn-danger btn-sm me-2 generate-pdf-button' title='Generate PDF'>
                                <i class='fas fa-file-pdf'></i>
                              </a>";
                        if (file_exists($file_path)) {
                            echo "<a href='$file_path' target='_blank' class='btn btn-warning btn-sm me-2 view-pdf-button' title='View PDF'>
                                    <i class='fas fa-eye'></i>
                                </a>";
                        }
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='9'>There are no works recorded.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</section></br>

<form action="upload_signature.php" method="POST" enctype="multipart/form-data" class="p-4 border rounded shadow-sm">
    <input type="hidden" name="folio_obra" value="<?php echo isset($folio_obra) ? $folio_obra : ''; ?>">
    
    <div class="mb-3">
        <label for="signature" class="form-label fw-bold">Upload Signature:</label>
        <input 
            type="file" 
            name="signature" 
            id="signature" 
            class="form-control" 
            accept="image/*" 
            required
        >
    </div>
    
    <button type="submit" class="btn btn-primary w-100">Upload</button>
</form>

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
                <?php if ($_SESSION["status_type"] === "warning"): ?>
                    mostrarToast("Advertencia", '<?= $_SESSION["status_message"] ?>', '<?= $_SESSION["status_type"] ?>');
                <?php elseif ($_SESSION["status_type"] === "error"): ?>
                    mostrarToast("Error", '<?= $_SESSION["status_message"] ?>', '<?= $_SESSION["status_type"] ?>');
                <?php elseif ($_SESSION["status_type"] === "info"): ?>
                    mostrarToast("Info", '<?= $_SESSION["status_message"] ?>', '<?= $_SESSION["status_type"] ?>');
                <?php else: ?>
                    mostrarToast("Éxito", '<?= $_SESSION["status_message"] ?>', '<?= $_SESSION["status_type"] ?>');
                <?php endif; ?>
                <?php unset($_SESSION['status_message'], $_SESSION['status_type']); ?>
            <?php endif; ?>
        });
</script>
</body>
</html>