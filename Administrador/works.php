<?php
require '../Login/conexion.php';

$sql_empresas = "SELECT id_empresa, nombre_empresa FROM empresa";
$result_empresas = $con->query($sql_empresas);

$sql_clientes = "SELECT id_cliente, nombre_cliente, apellido_paterno, apellido_materno FROM clientes WHERE rol = 'usuario'";
$result_clientes = $con->query($sql_clientes);

$sql_servicios = "SELECT id_servicio, nombre_servicio, total FROM servicios";

require '../Administrador/superior_admin.php';
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

<section>
        <a href="../Administrador/works_address.php" class="btn btn-primary" style="float: right; margin: 10px;">
            View Addresses
        </a>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addWorksModal" style="float: right; margin: 10px;">
            Add Work
        </button><br/>
    </section><br/>

<!-- Modal de Agregar Obra -->
<div class="modal fade" id="addWorksModal" tabindex="-1" role="dialog" aria-labelledby="addWorksModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addWorksModalLabel">Add New Work</h5>
            </div>
            <form action="add_work.php" method="POST">
                <div class="modal-body">
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
                    <div class="form-group mb-3">
                        <label for="id_cliente">Selected a Customer</label>
                        <select name="id_cliente" id="select_cliente" class="form-control" required>
                            <option value="">Selected a Customer</option>
                            <?php
                            if ($result_clientes->num_rows > 0) {
                                while ($clientes = $result_clientes->fetch_assoc()) {
                                    $nombre_completo = htmlspecialchars($clientes['nombre_cliente'] . ' ' . $clientes['apellido_paterno'] . ' ' . $clientes['apellido_materno']);

                                    $direccion_sql = "SELECT id_direccion, ciudad FROM direcciones WHERE id_cliente = " . (int)$clientes['id_cliente'];
                                    $direccion_result = $con->query($direccion_sql);
                                    $direccion = $direccion_result->fetch_assoc();

                                    echo "<option value='" . htmlspecialchars($clientes['id_cliente']) . "' data-direccion='" . htmlspecialchars($direccion['ciudad']) . "' data-id-direccion='" . htmlspecialchars($direccion['id_direccion']) . "'>" . $nombre_completo . "</option>";
                                }
                            } else {
                                echo "<option value=''>No hay clientes disponibles</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label for="id_servicio">Select a Service</label>
                        <select name="id_servicio" id="select_servicio" class="form-control" required>
                            <option value="">Select a Service</option>
                            <?php
                            $result_servicios = $con->query($sql_servicios);
                            if ($result_servicios->num_rows > 0) {
                                while ($servicio = $result_servicios->fetch_assoc()) {
                                    echo "<option value='" . htmlspecialchars($servicio['id_servicio']) . "' data-total='" . htmlspecialchars($servicio['total']) . "'>" . htmlspecialchars($servicio['nombre_servicio']) . "</option>";
                                }
                            } else {
                                echo "<option value=''>No services available</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label for="direccion_cliente">Customer Address</label>
                        <input type="text" id="direccion_cliente" class="form-control" readonly>
                        <input type="hidden" name="id_direccion" id="id_direccion">
                    </div>
                    <div class="form-group mb-3">
                        <label >Start Date</label>
                        <input type="date" name="fecha_inicio" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label >Advance Payment</label>
                        <input type="number" name="anticipo" id="anticipo" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label >Debit</label>
                        <input type="number" name="adeudo" id="adeudo" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label >Total Work</label>
                        <input type="number" name="total_obra" id="total_obra" class="form-control" readonly>
                    </div>
                    <div class="form-group mb-3">
                        <label >Observations</label>
                        <textarea name="observaciones" class="form-control"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add New Work</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de Editar Obra -->
<div class="modal fade" id="editWorksModal" tabindex="-1" role="dialog" aria-labelledby="editWorksModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editWorksLabel">Edit Work</h5>
            </div>
            <form action="edit_works.php" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="folio_obra" id="edit_folio_obra">

                    <div class="form-group mb-3">
                    <label for="edit_id_servicio">Select a Service</label>
                    <select name="id_servicio" id="edit_id_servicio" class="form-control" required>
                        <option value="">Select a Service</option>
                        <?php
                        $result_servicios = $con->query($sql_servicios);
                        if ($result_servicios->num_rows > 0) {
                            while ($servicio = $result_servicios->fetch_assoc()) {
                                echo "<option value='" . htmlspecialchars($servicio['id_servicio']) . "' data-total='" . htmlspecialchars($servicio['total']) . "'>" . htmlspecialchars($servicio['nombre_servicio']) . "</option>";
                            }
                        } else {
                            echo "<option value=''>No services available</option>";
                        }
                        ?>
                    </select>
                    </div>

                    <div class="form-group mb-3">
                        <label for="edit_fecha_inicio">Start Date</label>
                        <input type="date" name="fecha_inicio" id="edit_fecha_inicio" class="form-control" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="edit_anticipo">Advance Payment</label>
                        <input type="number" name="anticipo" id="edit_anticipo" class="form-control" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="edit_adeudo">Debit</label>
                        <input type="number" name="adeudo" id="edit_adeudo" class="form-control" readonly>
                    </div>

                    <div class="form-group mb-3">
                        <label for="edit_total_obra">Total Work</label>
                        <input type="number" name="total_obra" id="edit_total_obra" class="form-control" readonly>
                    </div>

                    <div class="form-group mb-3">
                        <label for="edit_observaciones">Observations</label>
                        <input type="text" name="observaciones" id="edit_observaciones" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para añadir dirección de la obra -->
<div class="modal fade" id="addWorkAddressModal" tabindex="-1" role="dialog" aria-labelledby="addWorkAddressModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addWorkAddressLabel">Add Work Address</h5>
            </div>
            <form action="add_work_address.php" method="POST">
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="id_obra_modal_display">Work ID</label>
                        <input type="number" id="id_obra_modal_display" class="form-control" readonly>
                    </div>
                    <input type="hidden" name="folio_obra" id="folio_obra_hidden">
                    <div class="form-group mb-3">
                        <label for="num_ext">Outside Number</label>
                        <input type="number" name="num_ext" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="num_int">Inner Number</label>
                        <input type="number" name="num_int" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="calle">Street</label>
                        <input type="text" name="calle" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="ciudad">City</label>
                        <input type="text" name="ciudad" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="estado">State</label>
                        <input type="text" name="estado" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="codigo_postal">Postal Code</label>
                        <input type="number" name="codigo_postal" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add Work Address</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para editar el estado de la obra -->
<div class="modal fade" id="workStatusModal" tabindex="-1" aria-labelledby="workStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="workStatusModalLabel">Change Work Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="status_work.php" method="POST">
                <div class="modal-body">
                    <input type="hidden" id="folioObra" name="folio_obra">
                    <div class="mb-3">
                        <select class="form-select" id="newStatus" name="estatus">
                            <option value="Iniciada">Initial</option>
                            <option value="En Progreso">Medium</option>
                            <option value="Completa">Complete</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Tabla de Obras -->
<section class="my-2"><br/>
    <div class="table-responsive">
        <table class="table table-bordered table-hover text-center">
            <thead class="thead-dark">
                <tr>
                    <h2 class="text-center">Manage Works</h2><br/>
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
                        echo "<button class='btn btn-info btn-sm me-1 edit-button' onclick='openEditModal(" . json_encode($row) . ")' title='Edit Work'>
                            <i class='fas fa-edit'></i>
                        </button>";
                        echo "<a href='delete_work.php?id=$folio_obra' class='btn btn-danger btn-sm me-2 delete-button' onclick='return confirm(\"Are you sure you want to delete this work?\")' title='Delete Work'>
                                <i class='fas fa-trash'></i>
                              </a>";
                        echo "<button class='btn btn-success add-btn btn-sm me-2' onclick='openAddAddressModal(" . json_encode($row) . ")' title='Add Address'>
                            <i class='fas fa-plus'></i>
                        </button>";
                        echo "<a href='generate_pdf.php?folio=$folio_obra' class='btn btn-danger btn-sm me-2 generate-pdf-button' title='Generate PDF'>
                                <i class='fas fa-file-pdf'></i>
                              </a>";
                        if (file_exists($file_path)) {
                            echo "<a href='$file_path' target='_blank' class='btn btn-warning btn-sm me-2 view-pdf-button' title='View PDF'>
                                    <i class='fas fa-eye'></i>
                                </a>";
                        }
                        echo "<a href='send_pdf.php?folio=$folio_obra&file=" . urlencode($file_path) . "' 
                                class='btn btn-primary send-button btn-sm me-2' 
                                title='Send PDF' 
                                data-folio='$folio_obra' 
                                onclick='sendPDF(this)'>
                                <i class='fas fa-envelope'></i>
                            </a>";
                        if (strtolower($row['estatus']) !== 'completa') { 
                            echo "<button class='btn btn-info btn-sm me-2 btn-status' onclick='openStatusModal(" . json_encode($row) . ")' title='Work Status'>
                                <i class='fas fa-spinner fa-spin'></i>
                            </button>";
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
        <label for="signature" class="form-label fw-bold">Upload Signature</label>
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

    function openEditModal(obraData) {
        $('#edit_folio_obra').val(obraData.folio_obra);
        $('#edit_fecha_inicio').val(obraData.fecha_inicio);
        $('#edit_anticipo').val(obraData.anticipo);
        $('#edit_adeudo').val(obraData.adeudo);
        $('#edit_total_obra').val(obraData.total_obra);
        $('#edit_observaciones').val(obraData.observaciones);

        $('#edit_id_servicio').val(obraData.id_servicio);

        let selectedServiceTotal = $('#edit_id_servicio option:selected').data('total');
        $('#edit_total_obra').val(selectedServiceTotal);

        $('#edit_id_servicio').off('change').on('change', function() {
            let selectedServiceTotal = $('#edit_id_servicio option:selected').data('total');
            $('#edit_total_obra').val(selectedServiceTotal);

            $('#edit_anticipo').val('');
            $('#edit_adeudo').val('');
        });

        $('#editWorksModal').modal('show');
    }

    $('#select_servicio').on('change', function () {
        var total = parseFloat($(this).find(':selected').data('total')) || 0;
        $('#total_obra').val(total.toFixed(2));
        $('#anticipo').val('');
        $('#adeudo').val('');
    });


    function openAddAddressModal(obra) {
        document.getElementById('id_obra_modal_display').value = obra.folio_obra;
        document.getElementById('folio_obra_hidden').value = obra.folio_obra;

        $('#addWorkAddressModal').modal('show');
    }

    document.getElementById('newStatus').addEventListener('change', function() {
        localStorage.setItem('selectedStatus', this.value);
    });


    function openStatusModal(data) {
        document.getElementById('folioObra').value = data.folio_obra;
        const storedStatus = localStorage.getItem('selectedStatus');
        if (storedStatus) {
            document.getElementById('newStatus').value = storedStatus;
        }

        const statusModal = new bootstrap.Modal(document.getElementById('workStatusModal'));
        statusModal.show();
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

        $(document).ready(function () {
            $('select[name="id_cliente"]').on('change', function () {
                var id_cliente = $(this).val();

                if (id_cliente) {
                    $.ajax({
                        url: 'get_direccion_cliente.php',
                        type: 'POST',
                        data: { id_cliente: id_cliente },
                        success: function (data) {
                            var direccion = JSON.parse(data);
                            $('#direccion_cliente').val(direccion.ciudad);
                            $('#id_direccion').val(direccion.id_direccion);
                        }
                    });
                } else {
                    $('#direccion_cliente').val('');
                    $('#id_direccion').val('');
                }
            });
        });

        function validarYCalcularAgregar() {
            var total = parseFloat(document.getElementById('total_obra').value) || 0;
            var anticipo = parseFloat(document.getElementById('anticipo').value) || 0;

            if (anticipo > total) {
                alert("El anticipo no puede exceder el total.");
                document.getElementById('anticipo').value = total.toFixed(2);
                anticipo = total;
            }

            var adeudo = total - anticipo;
            document.getElementById('adeudo').value = adeudo.toFixed(2);
        }

    function validarYCalcularEditar() {
        var total = parseFloat(document.getElementById('edit_total_obra').value) || 0;
        var anticipo = parseFloat(document.getElementById('edit_anticipo').value) || 0;

        if (anticipo > total) {
                alert("El anticipo no puede exceder el total.");
                document.getElementById('edit_anticipo').value = total.toFixed(2);
                anticipo = total;
            }

                var adeudo = total - anticipo;
                document.getElementById('edit_adeudo').value = adeudo.toFixed(2);
            }

            if (document.getElementById('anticipo')) {
                document.getElementById('anticipo').addEventListener('input', validarYCalcularAgregar);
            }

            if (document.getElementById('edit_anticipo')) {
                document.getElementById('edit_anticipo').addEventListener('input', validarYCalcularEditar);
            }

            function sendPDF(button) {
                const folio = button.getAttribute('data-folio');

                const sentPDFs = JSON.parse(localStorage.getItem('sentPDFs')) || [];
                if (!sentPDFs.includes(folio)) {
                    sentPDFs.push(folio);
                    localStorage.setItem('sentPDFs', JSON.stringify(sentPDFs));
                }

                const row = button.closest('tr');
                row.querySelectorAll('.edit-button, .generate-pdf-button, .send-button, .add-btn, .delete-button').forEach(btn => btn.style.display = 'none');
                
                const statusButton = row.querySelector('.btn-status');
                if (statusButton) {
                    statusButton.classList.remove('d-none');
                }
            }


        document.addEventListener('DOMContentLoaded', function () {
        const sentPDFs = JSON.parse(localStorage.getItem('sentPDFs')) || [];

        sentPDFs.forEach(folio => {
            const row = document.querySelector(`tr[data-folio="${folio}"]`);
            if (row) {
                row.querySelectorAll('.edit-button, .generate-pdf-button, .send-button, .add-btn, .delete-button').forEach(btn => btn.style.display = 'none');
            }
        });
    });
    </script>
</body>