<?php
require '../Login/conexion.php';
require '../Customers/superior_customer.php';

if (!isset($_SESSION['id_cliente'])) {
    header("Location: ../Login/login.php");
    exit;
}

$user_id = $_SESSION['id_cliente'];

$sql = "SELECT o.*, e.nombre_empresa, c.nombre_cliente, c.apellido_paterno, c.apellido_materno, d.ciudad, s.nombre_servicio
        FROM obras o
        LEFT JOIN empresa e ON o.id_empresa = e.id_empresa
        LEFT JOIN clientes c ON o.id_cliente = c.id_cliente
        LEFT JOIN servicios s ON o.id_servicio = s.id_servicio
        LEFT JOIN direcciones d ON o.id_direccion = d.id_direccion
        WHERE c.id_cliente = ?";

$stmt = $con->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
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
<section class="my-2 container"><br/>
    <div class="table-responsive">
        <table class="table table-bordered table-hover text-center">
            <thead class="thead-dark">
                <tr>
                    <h2 class="text-center">Works</h2><br/>
                    <th>Company</th>
                    <th>Service</th>
                    <th>Customer</th>
                    <th>Address</th>
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
                        echo "<a href='generate_pdf.php?folio=$folio_obra' class='btn btn-danger btn-sm me-2 generate-pdf' title='Generate PDF'>
                                <i class='fas fa-file-pdf'></i>
                              </a>";
                        if (file_exists($file_path)) {
                            echo "<a href='$file_path' target='_blank' class='btn btn-warning btn-sm me-2 view-pdf-button' title='View PDF'>
                                    <i class='fas fa-eye'></i>
                                </a>";
                        }
                        echo "<button class='btn btn-primary btn-sm me-2 signature-btn' data-bs-toggle='modal' data-bs-target='#uploadSignatureModal' 
                                onclick='openSignatureModal(\"$folio_obra\")' title='Add Signature'>
                                <i class='fas fa-pen'></i>
                              </button>";
                        echo "<a href='send_pdf.php?folio=$folio_obra&file=" . urlencode($file_path) . "' 
                              class='btn btn-success env-btn btn-sm me-2' 
                              title='Send PDF' 
                              data-folio='$folio_obra' 
                              onclick='envPDF(this)'>
                              <i class='fas fa-envelope'></i>
                          </a>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='9'>There are no works recorded.</td></tr>";
                    echo "<tr><td colspan='11'>There are no works recorded.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</section></br>

<!-- Modal para subir firma -->
<div class="modal fade" id="uploadSignatureModal" tabindex="-1" aria-labelledby="uploadSignatureModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="upload_signature.php" method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadSignatureModalLabel">Upload Signature</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="folio_obra" id="modalFolioObra">
                    
                    <div class="mb-3">
                        <label for="signature" class="form-label fw-bold">Signature File:</label>
                        <input 
                            type="file" 
                            name="signature" 
                            id="signature" 
                            class="form-control" 
                            accept="image/*" 
                            required
                        >
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>

    function openSignatureModal(folioObra) {
        document.getElementById('modalFolioObra').value = folioObra;
    }

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

        function envPDF(button) {
                const folio = button.getAttribute('data-folio');

                const envPDFs = JSON.parse(localStorage.getItem('envPDFs')) || [];
                if (!envPDFs.includes(folio)) {
                    envPDFs.push(folio);
                    localStorage.setItem('envPDFs', JSON.stringify(envPDFs));
                }

                const row = button.closest('tr');
                row.querySelectorAll('.generate-pdf, .signature-btn, .env-btn').forEach(btn => btn.style.display = 'none');
                if (statusButton) {
                    statusButton.classList.remove('d-none');
                }
            }


        document.addEventListener('DOMContentLoaded', function () {
        const envPDFs = JSON.parse(localStorage.getItem('envPDFs')) || [];

        envPDFs.forEach(folio => {
            const row = document.querySelector(`tr[data-folio="${folio}"]`);
            if (row) {
                row.querySelectorAll('.generate-pdf, .signature-btn, .env-btn').forEach(btn => btn.style.display = 'none');
            }
        });
    });
</script>
</body>
</html>