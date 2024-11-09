<?php
require '../Login/conexion.php';

$sql_empleados = "SELECT id_empleado, nombre, apellido_paterno, apellido_materno FROM empleados";
$result_empleados = $con->query($sql_empleados);

$sql = "SELECT n.id_nomina, e.nombre, e.apellido_paterno, e.apellido_materno, n.sueldo_diario, n.dias_trabajados, n.total
        FROM nomina n
        JOIN empleados e ON n.id_empleado = e.id_empleado";
$result = $con->query($sql);

require '../Administrador/superior_admin.php';
?>

<!DOCTYPE html>
<html lang="en">
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
                <h5 class="modal-title" id="addPayrollModalLabel">Agregar Nueva Nómina</h5>
            </div>
            <form action="add_payroll.php" method="POST">
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="id_empleado">Seleccione un empleado</label>
                        <select name="id_empleado" class="form-control" required>
                            <option value="">Seleccione un empleado</option>
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
                        <input type="number" id="sueldo_diario" name="sueldo_diario" class="form-control" placeholder="Sueldo diario" required>
                    </div>
                    <div class="form-group mb-3">
                        <input type="number" id="dias_trabajados" name="dias_trabajados" class="form-control" placeholder="Dias trabajados" required>
                    </div>
                    <div class="form-group mb-3">
                        <input type="text" id="total" name="total" class="form-control" placeholder="Total" readonly>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add Payroll</button>
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
                        <label for="edit_id_empleado">Seleccione un empleado</label>
                        <select name="id_empleado" id="edit_id_empleado" class="form-control" required>
                            <option value="">Seleccione un empleado</option>
                            <?php
                            $result_empleados->data_seek(0);
                            while ($empleados = $result_empleados->fetch_assoc()) {
                                $nombre_completo = htmlspecialchars($empleados['nombre'] . ' ' . $empleados['apellido_paterno'] . ' ' . $empleados['apellido_materno']);
                                echo "<option value='" . htmlspecialchars($empleados['id_empleado']) . "'>$nombre_completo</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label for="edit_sueldo_diario">Sueldo diario</label>
                        <input type="number" id="edit_sueldo_diario" name="sueldo_diario" class="form-control" placeholder="Enter sueldo diario" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="edit_dias_trabajados">Días trabajados</label>
                        <input type="number" id="edit_dias_trabajados" name="dias_trabajados" class="form-control" placeholder="Enter Días trabajados" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="edit_total">Total</label>
                        <input type="number" id="edit_total" name="total" class="form-control" placeholder="Enter Total" readonly>
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

<section>
    <table class="table">
        <thead class="thead-dark">
            <h2 class="text-center">Manage Payroll</h2><br/>
            <tr>
                <th>Payroll ID</th>
                <th>Employee</th>
                <th>Sueldo diario</th>
                <th>Días trabajados</th>
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
                    echo "<td>" . htmlspecialchars($row['id_nomina']) . "</td>";
                    echo "<td>" . $nombre_completo . "</td>";
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
</section>

<script>

    function openEditModal(customerData) {
        $('#edit_id_nomina').val(customerData.id_nomina);
        $('#edit_id_empleado').val(customerData.id_empleado);
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
                mostrarToast(
                    '<?= $_SESSION["status_type"] === "warning" ? "Advertencia" : "Éxito" ?>',
                    '<?= $_SESSION["status_message"] ?>',
                    '<?= $_SESSION["status_type"] ?>'
                );
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
</script>
</body>
</html>