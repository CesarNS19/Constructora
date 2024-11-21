<?php
require '../Login/conexion.php';

$sql_empresas = "SELECT id_empresa, nombre_empresa FROM empresa";
$result_empresas = $con->query($sql_empresas);

$sql_clientes = "SELECT id_cliente, nombre_cliente, apellido_paterno, apellido_materno FROM clientes WHERE rol = 'usuario'";
$result_clientes = $con->query($sql_clientes);

$sql_direccion_clientes = "SELECT id_cliente, ciudad FROM direcciones";
$result_direccion_clientes = $con->query($sql_direccion_clientes);

require '../Administrador/superior_admin.php';
?>

<!DOCTYPE html>
<html lang="en">
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
                <h5 class="modal-title" id="addBudgetModalLabel">Agregar Nuevo Presupuesto</h5>
            </div>
            <form action="add_budget.php" method="POST">
                <div class="modal-body">
                <div class="form-group mb-3">
                        <label for="id_empresa">Selecciona la Empresa</label>
                        <select name="id_empresa" class="form-control" required>
                            <option value="">Seleccione una empresa</option>
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
                        <label for="id_cliente">Seleccione un cliente</label>
                        <select name="id_cliente" id="select_cliente" class="form-control" required>
                            <option value="">Seleccione un cliente</option>
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
                        <label for="direccion_cliente">Dirección del cliente</label>
                        <input type="text" id="direccion_cliente" class="form-control" readonly>
                        <input type="hidden" name="id_direccion" id="id_direccion">
                    </div>
                    <div class="form-group mb-3">
                        <label for="date">Start Date</label>
                        <input type="date" step="0.01" name="fecha_elaboracion" class="form-control" placeholder="Start Date" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="">Observations</label>
                        <textarea name="observaciones" class="form-control" placeholder="Observations" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Agregar Presupuesto</button>
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
                <h5 class="modal-title" id="editBudgetLabel">Editar Presupuesto</h5>
            </div>
            <form action="edit_budget.php" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="folio_presupuesto" id="edit_folio_presupuesto">
                    
                    <div class="form-group mb-3">
                        <label for="edit_fecha_elaboracion">Start Date</label>
                        <input type="date" name="fecha_elaboracion" id="edit_fecha_elaboracion" class="form-control" required>
                    </div>
                                        
                    <div class="form-group mb-3">
                        <label for="edit_observaciones">Observations</label>
                        <input type="text" name="observaciones" id="edit_observaciones" class="form-control" placeholder="Ingresa las observaciones" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
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
                <h5 class="modal-title" id="addBudgetAddressLabel">Add Company Address</h5>
            </div>
            <form action="add_budget_address.php" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="folio_presupuesto" id="folio_presupuesto_modal">
                    <div class="form-group mb-3">
                        <label>Presupuesto seleccionado</label>
                        <input type="text" id="presupuesto_modal" class="form-control" readonly>
                    </div>
                    <div class="form-group mb-3">
                        <input type="number" name="num_ext" class="form-control" placeholder="Outside number" required>
                    </div>
                    <div class="form-group mb-3">
                        <input type="number" name="num_int" class="form-control" placeholder="Inner number" required>
                    </div>
                    <div class="form-group mb-3">
                        <input type="text" name="calle" class="form-control" placeholder="Street" required>
                    </div>
                    <div class="form-group mb-3">
                        <input type="text" name="ciudad" class="form-control" placeholder="City" required>
                    </div>
                    <div class="form-group mb-3">
                        <input type="text" name="estado" class="form-control" placeholder="State" required>
                    </div>
                    <div class="form-group mb-3">
                        <input type="number" name="codigo_postal" class="form-control" placeholder="Postal code" required>
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
<section class="works-table"><br/>
    <table class="table table-bordered table-hover text-center">
        <thead class="thead-dark">
            <tr>
                <h2 class="text-center">Manage Budgets</h2><br/>
                <th>Company Name</th>
                <th>Customer Name</th>
                <th>Customer Address</th>
                <th>Date of Preparation</th>
                <th>Observations</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT p.*, e.nombre_empresa, 
                           CONCAT(c.nombre_cliente, ' ', c.apellido_paterno, ' ', c.apellido_materno) AS nombre_cliente,
                           d.ciudad AS direccion_cliente
                    FROM presupuestos p
                    LEFT JOIN empresa e ON p.id_empresa = e.id_empresa
                    LEFT JOIN clientes c ON p.id_cliente = c.id_cliente
                    LEFT JOIN direcciones d ON p.id_cliente = d.id_cliente";
                    
            $result = $con->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $nombre_cliente = htmlspecialchars($row['nombre_cliente']);
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['nombre_empresa']) . "</td>";
                    echo "<td>" . $nombre_cliente . "</td>";
                    echo "<td>" . htmlspecialchars($row['direccion_cliente']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['fecha_elaboracion']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['observaciones']) . "</td>";
                    echo "<td>";
                    echo "<button class='btn btn-info btn-sm me-1' onclick='openEditModal(" . json_encode($row) . ")' title='Editar presupuesto'>
                            <i class='fas fa-edit'></i>
                        </button>
                        <a href='delete_budget.php?id=" . $row['folio_presupuesto'] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"¿Estás seguro de que deseas eliminar este presupuesto?\")' title='Eliminar presupuesto'>
                            <i class='fas fa-trash'></i>
                        </a>
                        <button class='btn btn-success btn-sm' onclick='openAddAddressModal(" . json_encode($row) . ")' title='Agregar Dirección'>
                            <i class='fas fa-plus'></i>
                        </button>
                    </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7'>No hay presupuestos registrados.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</section>

    <script>

    function openEditModal(customerData) {
        $('#edit_folio_presupuesto').val(customerData.folio_presupuesto);
        $('#edit_fecha_elaboracion').val(customerData.fecha_elaboracion);
        $('#edit_total_obra').val(customerData.total_obra);
        $('#edit_observaciones').val(customerData.observaciones);
        
        $('#editBudgetModal').modal('show');
    }

    function openAddAddressModal(presupuesto) {
        document.getElementById('folio_presupuesto_modal').value = presupuesto.folio_presupuesto;
        document.getElementById('presupuesto_modal').value = presupuesto.folio_presupuesto;
        $('#addBudgetAddressModal').modal('show');
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
    </script>
</body>
</html>