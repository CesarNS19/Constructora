<?php
require '../Login/conexion.php';
require '../Administrador/superior_admin.php';

$sql_empresas = "SELECT id_empresa, nombre_empresa FROM empresa";
$result_empresas = $con->query($sql_empresas);

$sql_clientes = "SELECT id_cliente, nombre_cliente, apellido_paterno, apellido_materno FROM clientes WHERE rol = 'usuario'";
$result_clientes = $con->query($sql_clientes);

$sql_direccion_clientes = "SELECT id_cliente, ciudad FROM direcciones";
$result_direccion_clientes = $con->query($sql_direccion_clientes);

$sql_servicios = "SELECT id_servicio, nombre_servicio, total FROM servicios";
$result_servicios = $con->query($sql_servicios);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

<div id="Alert"></div>

<section class="company-header">
        <a href="../Administrador/budget_address.php" class="btn btn-primary" style="float: right; margin: 10px;">
            View Addresses
        </a>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addBudgetModal" style="float: right; margin: 10px;">
            Add Budget
        </button><br/>
    </section><br/>

<!-- Modal para agregar presupuestos -->
    <div class="modal fade" id="addBudgetModal" tabindex="-1" role="dialog" aria-labelledby="addBudgetModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addBudgetModalLabel">Add New Budget</h5>
            </div>
            <form action="add_budget.php" method="POST">
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
                        <label for="direccion_cliente">Customer Address</label>
                        <input type="text" id="direccion_cliente" class="form-control" readonly>
                        <input type="hidden" name="id_direccion" id="id_direccion">
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
                        <label for="anticipo">Advance Payment</label>
                        <input type="number" name="anticipo" id="anticipo" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label >Total Work</label>
                        <input type="number" name="total" id="total" class="form-control" readonly>
                    </div>
                    <div class="form-group mb-3">
                        <label for="">Observations</label>
                        <textarea name="observaciones" class="form-control" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add New Budget</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para editar presupuestos -->
<div class="modal fade" id="editBudgetModal" tabindex="-1" role="dialog" aria-labelledby="editBudgetModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editBudgetLabel">Edit Budget</h5>
            </div>
            <form action="edit_budget.php" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="folio_presupuesto" id="edit_folio_presupuesto">
                
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
                        <label for="edit_total">Total Work</label>
                        <input type="number" name="total" id="edit_total" class="form-control" readonly>
                    </div>
                             
                    <div class="form-group mb-3">
                        <label for="edit_anticipo">Advance Payment</label>
                        <input type="number" name="anticipo" id="edit_anticipo" class="form-control" required>
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

<!-- Modal para añadir dirección del presupuesto -->
<div class="modal fade" id="addBudgetAddressModal" tabindex="-1" role="dialog" aria-labelledby="addAddressBudgetModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addBudgetAddressLabel">Add Budget Address</h5>
            </div>
            <form action="add_budget_address.php" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="folio_presupuesto" id="folio_presupuesto_modal">
                    <div class="form-group mb-3">
                        <label>Selected Budget</label>
                        <input type="text" id="presupuesto_modal" class="form-control" readonly>
                    </div>
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
                    <button type="submit" class="btn btn-primary">Add Budget Address</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Tabla de Presupuestos -->
<section class="services-table my-2"><br/>
    <div class="table-responsive">
        <table class="table table-bordered table-hover text-center">
            <thead class="thead-dark">
                <tr>
                    <h2 class="text-center">Manage Budgets</h2><br/>
                    <th>Company</th>
                    <th>Customer</th>
                    <th>Customer Address</th>
                    <th>Service</th>
                    <th>Advance Payment</th>
                    <th>Date of Preparation</th>
                    <th>Total</th>
                    <th>Observations</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT o.*, e.nombre_empresa, c.nombre_cliente, c.apellido_paterno, c.apellido_materno, d.ciudad, s.nombre_servicio
                        FROM presupuestos o
                        LEFT JOIN empresa e ON o.id_empresa = e.id_empresa
                        LEFT JOIN clientes c ON o.id_cliente = c.id_cliente
                        LEFT JOIN servicios s ON o.id_servicio = s.id_servicio
                        LEFT JOIN direcciones d ON o.id_direccion = d.id_direccion";

                $result = $con->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $folio_presupuesto = htmlspecialchars($row['folio_presupuesto']);
                        $nombre_cliente = htmlspecialchars($row['nombre_cliente'] . ' ' . $row['apellido_paterno'] . ' ' . $row['apellido_materno']);
                        $servicio = htmlspecialchars($row['nombre_servicio']);
                        $file_path = "../pdf/Proposal_" . $folio_presupuesto . ".pdf";

                        echo "<tr data-folio='$folio_presupuesto'>";
                        echo "<td>" . htmlspecialchars($row['nombre_empresa']) . "</td>";
                        echo "<td>" . $nombre_cliente . "</td>";
                        echo "<td>" . htmlspecialchars($row['ciudad']) . "</td>";
                        echo "<td>" . $servicio . "</td>";
                        echo "<td>" . htmlspecialchars($row['anticipo']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['fecha_elaboracion']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['total']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['observaciones']) . "</td>";
                        echo "<td>
                                <div class='d-flex flex-wrap justify-content-center gap-2'>
                                    <button class='btn btn-info btn-sm edit-button' onclick='openEditModal(" . json_encode($row) . ")' title='Edit Budget'>
                                        <i class='fas fa-edit'></i>
                                    </button>
                                    <a href='delete_budget.php?id=" . $row['folio_presupuesto'] . "' 
                                       class='btn btn-danger btn-sm' 
                                       onclick='return confirm(\"¿Estás seguro de que deseas eliminar este presupuesto?\")' 
                                       title='Delete Budget'>
                                        <i class='fas fa-trash'></i>
                                    </a>
                                    <button class='btn btn-success btn-sm add-btn' onclick='openAddAddressModal(" . json_encode($row) . ")' title='Add Address'>
                                        <i class='fas fa-plus'></i>
                                    </button>
                                    <a href='generate_pdf_proposal.php?folio=$folio_presupuesto' 
                                       class='btn btn-danger btn-sm generate-pdf-button' 
                                       title='Generate PDF'>
                                        <i class='fas fa-file-pdf'></i>
                                    </a>";
                        if (file_exists($file_path)) {
                            echo "<a href='$file_path' 
                                       target='_blank' 
                                       class='btn btn-warning btn-sm' 
                                       title='View PDF'>
                                        <i class='fas fa-eye'></i>
                                  </a>";
                        }
                        echo "<a href='send_pdf_proposal.php?folio=$folio_presupuesto&file=" . urlencode($file_path) . "' 
                                   class='btn btn-primary btn-sm send-button' 
                                   title='Send PDF' 
                                   data-folio='$folio_presupuesto' 
                                   onclick='enviarPDF(this)'>
                                   <i class='fas fa-envelope'></i>
                              </a>";
                        echo "</div></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='8'>No hay presupuestos registrados.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</section>

<script>

    function openEditModal(customerData) {
        $('#edit_folio_presupuesto').val(customerData.folio_presupuesto);
        $('#edit_total_obra').val(customerData.total_obra);
        $('#edit_total').val(customerData.total);
        $('#edit_anticipo').val(customerData.anticipo);
        $('#edit_observaciones').val(customerData.observaciones);
        
        $('#edit_id_servicio').val(customerData.id_servicio);

        $('#editBudgetModal').modal('show');
    }
        $(document).ready(function () {
        $('#select_servicio').change(function () {
            const selectedOption = $(this).find('option:selected');
            const total = selectedOption.data('total') || 0;
            $('#total').val(total);
        });

        $('#edit_id_servicio').change(function () {
            const selectedOption = $(this).find('option:selected');
            const total = selectedOption.data('total') || 0;
            $('#edit_total').val(total);
        });
    });

    function openAddAddressModal(presupuesto) {
        document.getElementById('folio_presupuesto_modal').value = presupuesto.folio_presupuesto;
        document.getElementById('presupuesto_modal').value = presupuesto.folio_presupuesto;
        $('#addBudgetAddressModal').modal('show');
    }

    document.addEventListener('DOMContentLoaded', function () {
        const anticipoField = document.getElementById('anticipo');
        const totalField = document.getElementById('total');
        const editAnticipoField = document.getElementById('edit_anticipo');
        const editTotalField = document.getElementById('edit_total');

        anticipoField.addEventListener('input', function () {
            const anticipo = parseFloat(anticipoField.value);
            const total = parseFloat(totalField.value);

            if (!isNaN(anticipo) && !isNaN(total) && anticipo > total) {
                alert(`The advance payment cannot exceed the total amount of $${total}.`);
                anticipoField.value = 0;
            }
        });

        editAnticipoField.addEventListener('input', function () {
            const anticipo = parseFloat(editAnticipoField.value);
            const total = parseFloat(editTotalField.value);

            if (!isNaN(anticipo) && !isNaN(total) && anticipo > total) {
                alert(`The advance payment cannot exceed the total amount of $${total}.`);
                editAnticipoField.value = 0;
            }
        });
    });

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

        function enviarPDF(button) {
            const folio_presupuesto = button.getAttribute('data-folio');

            const enviarPDF = JSON.parse(localStorage.getItem('enviarPDF')) || [];

            if (!enviarPDF.includes(folio_presupuesto)) {
                enviarPDF.push(folio_presupuesto);
                localStorage.setItem('enviarPDF', JSON.stringify(enviarPDF));
            }

            const row = button.closest('tr');
            row.querySelectorAll('.edit-button, .generate-pdf-button, .send-button, .add-btn').forEach(btn => btn.style.display = 'none');
            
            const statusButton = row.querySelector('.btn-status');
            if (statusButton) {
                statusButton.classList.remove('d-none');
            }
        }


        document.addEventListener('DOMContentLoaded', function () {
            const enviarPDF = JSON.parse(localStorage.getItem('enviarPDF')) || [];

            enviarPDF.forEach(folio => {
                const row = document.querySelector(`tr[data-folio="${folio}"]`);
                if (row) {
                    row.querySelectorAll('.edit-button, .generate-pdf-button, .send-button, .add-btn').forEach(btn => btn.style.display = 'none');
                }
            });
        });

    </script>
</body>
</html>