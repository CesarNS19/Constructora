<?php
require '../Login/conexion.php';

$sql_empleados = "SELECT id_empleado, nombre, apellido_paterno, apellido_materno FROM empleados";
$result_empleados = $con->query($sql_empleados);

$sql = "SELECT n.id_nomina, e.nombre, e.apellido_paterno, e.apellido_materno, n.fecha, n.sueldo_diario, n.dias_trabajados, n.total
        FROM nomina n
        JOIN empleados e ON n.id_empleado = e.id_empleado";
$result = $con->query($sql);

require '../Administrador/superior_admin.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payroll</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

<div id="Alert"></div>

<button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addPayrollModal" style="float: right; margin: 10px;">
    Add Payroll
</button><br/>

<div class="modal fade" id="addPayrollModal" tabindex="-1" aria-labelledby="addPayrollModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addPayrollModalLabel">Add New Payroll</h5>
            </div>
            <form action="add_payroll.php" method="POST">
                <div class="modal-body">
                <div class="form-group mb-3">
                        <label for="id_empleado">Selected a Employee</label>
                        <select name="id_empleado" id="id_empleado" class="form-control" required>
                            <option value="">Selected a Employee</option>
                            <?php
                            if ($result_empleados->num_rows > 0) {
                                while ($empleados = $result_empleados->fetch_assoc()) {
                                    $nombre_completo = htmlspecialchars($empleados['nombre'] . ' ' . $empleados['apellido_paterno'] . ' ' . $empleados['apellido_materno']);
                                    echo "<option value='" . htmlspecialchars($empleados['id_empleado']) . "'>" . $nombre_completo . "</option>";
                                }
                            } else {
                                echo "<option value=''>No hay empleados disponibles</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label for="dias_trabajados">Days Worked</label>
                        <input type="number" id="dias_trabajados" name="dias_trabajados" class="form-control" readonly>
                    </div>
                    <div class="form-group mb-3">
                        <label for="sueldo_diario">Daily Salary</label>
                        <input type="number" id="sueldo_diario" name="sueldo_diario" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="total">Total</label>
                        <input type="text" id="total" name="total" class="form-control" readonly>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add New Payroll</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para editar la nómina -->
<div class="modal fade" id="editPayrollModal" tabindex="-1" role="dialog" aria-labelledby="editPayrollModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editPayrollLabel">Edit Payroll</h5>
            </div>
            <form action="edit_payroll.php" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="id_nomina" id="edit_id_nomina">
                    <div class="form-group mb-3">
                        <label for="edit_id_empleado">Employee Name</label>
                        <input type="text" name="edit_id_empleado" id="edit_id_empleado" class="form-control" readonly>
                        <input type="hidden" name="id_empleado" id="id_empleado">
                    </div>

                    <div class="form-group mb-3">
                        <label for="edit_dias_trabajados">Days Worked</label>
                        <input type="number" id="edit_dias_trabajados" name="dias_trabajados" class="form-control" readonly>
                    </div>

                    <div class="form-group mb-3">
                        <label for="edit_sueldo_diario">Daily Salary</label>
                        <input type="number" id="edit_sueldo_diario" name="sueldo_diario" class="form-control" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="edit_total">Total</label>
                        <input type="number" id="edit_total" name="total" class="form-control" readonly>
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

<section class="services-table container my-2"><br/>
<div class="table-responsive">
    <table class="table table-bordered table-hover text-center">
        <thead class="thead-dark">
            <h2 class="text-center">Manage Payrolls</h2><br/>
            <tr>
                <th>Employee Name</th>
                <th>Date</th>
                <th>Daily Salary</th>
                <th>Days Worked</th>
                <th>Total</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $nombre_completo = htmlspecialchars($row['nombre'] . ' ' . $row['apellido_paterno'] . ' ' . $row['apellido_materno']);
                    echo "<tr>";
                    echo "<td>" . $nombre_completo . "</td>";
                    echo "<td>" . htmlspecialchars($row['fecha']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['sueldo_diario']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['dias_trabajados']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['total']) . "</td>";
                    echo "<td>";
                    echo "<button class='btn btn-info btn-sm me-1' onclick='openEditModal(" . json_encode($row) . ")' title='Edit Payroll'>
                            <i class='fas fa-edit'></i>
                          </button>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No hay nóminas registradas.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>
</section>

<script>

    function openEditModal(customerData) {
        var nombreCompleto = customerData.nombre + ' ' + customerData.apellido_paterno + ' ' + customerData.apellido_materno;

        $('#edit_id_nomina').val(customerData.id_nomina);
        $('#edit_id_empleado').val(nombreCompleto);
        $('#edit_sueldo_diario').val(customerData.sueldo_diario);
        $('#edit_dias_trabajados').val(customerData.dias_trabajados);
        $('#edit_total').val(customerData.total);

        $('#editPayrollModal').modal('show');
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

        document.getElementById('sueldo_diario').addEventListener('input', calcularTotal);
        document.getElementById('dias_trabajados').addEventListener('input', calcularTotal);

        function calcularTotal() {
            const sueldoDiario = parseFloat(document.getElementById('sueldo_diario').value) || 0;
            const diasTrabajados = parseFloat(document.getElementById('dias_trabajados').value) || 0;
            const total = sueldoDiario * diasTrabajados;
            document.getElementById('total').value = total.toFixed(2);
        }

        document.getElementById('edit_sueldo_diario').addEventListener('input', calcularTotalEdit);
        document.getElementById('edit_dias_trabajados').addEventListener('input', calcularTotalEdit);

        function calcularTotalEdit() {
            const sueldoDiario = parseFloat(document.getElementById('edit_sueldo_diario').value) || 0;
            const diasTrabajados = parseFloat(document.getElementById('edit_dias_trabajados').value) || 0;
            const total = sueldoDiario * diasTrabajados;
            document.getElementById('edit_total').value = total.toFixed(2);
        }

        document.getElementById('id_empleado').addEventListener('change', function () {
            const idEmpleado = this.value;

            if (idEmpleado) {
                fetch(`get_work_days.php?id_empleado=${idEmpleado}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.dias_trabajados !== undefined) {
                            document.getElementById('dias_trabajados').value = data.dias_trabajados;
                        } else {
                            document.getElementById('dias_trabajados').value = '';
                        }
                    })
                    .catch(error => console.error('Error al obtener días trabajados:', error));
            } else {
                document.getElementById('dias_trabajados').value = '';
            }
        });

</script>
</body>
</html>