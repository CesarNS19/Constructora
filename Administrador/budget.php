<?php
require '../Login/conexion.php';

$sql_empresas = "SELECT id_empresa, nombre_empresa FROM empresa";
$result_empresas = $con->query($sql_empresas);

$sql_clientes = "SELECT id_cliente, nombre_cliente, apellido_paterno, apellido_materno FROM clientes WHERE rol = 'usuario'";
$result_clientes = $con->query($sql_clientes);

$sql_direccion_clientes = "SELECT id_direccion_cliente, ciudad FROM direccion_cliente";
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
                        <select name="id_cliente" class="form-control" required>
                            <option value="">Seleccione un cliente</option>
                            <?php
                            if ($result_clientes->num_rows > 0) {
                                while ($clientes = $result_clientes->fetch_assoc()) {
                                    $nombre_completo = htmlspecialchars($clientes['nombre_cliente'] . ' ' . $clientes['apellido_paterno'] . ' ' . $clientes['apellido_materno']);
                                    echo "<option value='" . htmlspecialchars($clientes['id_cliente']) . "'>" . $nombre_completo . "</option>";
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
                        <input type="hidden" name="id_direccion_cliente" id="id_direccion_cliente">
                    </div>
                    <div class="form-group mb-3">
                        <input type="date" step="0.01" name="fecha_elaboracion" class="form-control" placeholder="Start Date" required>
                    </div>
                    <div class="form-group mb-3">
                        <input type="number" name="total_obra" class="form-control" placeholder="Total Work" required>
                    </div>
                    <div class="form-group mb-3">
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
                        <label for="edit_total_obra">Total Work</label>
                        <input type="number" name="total_obra" id="edit_total_obra" class="form-control" placeholder="Ingresa el total de la obra" required>
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

    <!-- Tabla de Presupuestos -->
    <section class="works-table">
        <table class="table">
            <thead class="thead-dark">
                <tr>
                    <h2 class="text-center">Manage Budgets</h2><br/>
                    <th>Company ID</th>
                    <th>Customer ID</th>
                    <th>Customer Address ID</th>
                    <th>Start Date</th>
                    <th>Total work</th>
                    <th>Observations</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM presupuestos";
                $result = $con->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['id_empresa']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['id_cliente']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['id_direccion_cliente']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['fecha_elaboracion']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['total_obra']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['observaciones']) . "</td>";
                        echo "<td>";
                        echo "<button class='btn btn-info btn-sm me-1' onclick='openEditModal(" . json_encode($row) . ")' title='Editar presupuesto'>
                                <i class='fas fa-edit'></i>
                            </button>
                            <a href='delete_budget.php?id=" . $row['folio_presupuesto'] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"¿Estás seguro de que deseas eliminar este presupuesto?\")' title='Eliminar presupuesto'>
                                <i class='fas fa-trash'></i>
                            </a>
                        </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='11'>No hay obras registradas.</td></tr>";
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
                mostrarToast(
                    '<?= $_SESSION["status_type"] === "warning" ? "Advertencia" : "Éxito" ?>',
                    '<?= $_SESSION["status_message"] ?>',
                    '<?= $_SESSION["status_type"] ?>'
                );
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
                            $('#id_direccion_cliente').val(direccion.id_direccion_cliente);
                        }
                    });
                } else {
                    $('#direccion_cliente').val('');
                    $('#id_direccion_cliente').val('');
                }
            });
        });
    </script>

</body>
</html>