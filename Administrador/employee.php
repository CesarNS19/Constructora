<?php
require '../Login/conexion.php';

$sql_empresas = "SELECT id_empresa, nombre_empresa FROM empresa";
$result_empresas = $con->query($sql_empresas);

require '../Administrador/superior_admin.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

<div id="Alert"></div>

<section class="employee-header">
    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addEmployeeModal" style="float: right; margin: 10px;">
        Add Employee
    </button><br/>
</section><br/>

<!-- Modal para añadir empleado -->
<div class="modal fade" id="addEmployeeModal" tabindex="-1" role="dialog" aria-labelledby="addEmployeeModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addEmployeeModalLabel">Add Employee</h5>
            </div>
            <form action="add_employee.php" method="POST">
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label>Employee Name</label>
                        <input type="text" name="nombre" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label>Paternal Surname</label>
                        <input type="text" name="apellido_paterno" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label>Maternal Surname</label>
                        <input type="text" name="apellido_materno" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="hora_entrada">Entry Time</label>
                        <input type="time" name="hora_entrada" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="hora_salida">Exit Time</label>
                        <input type="time" name="hora_salida" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label>Daily Salary</label>
                        <input type="number" name="sueldo_diario" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label>Phone</label>
                        <input type="text" name="telefono_personal" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label>Email</label>
                        <input type="email" name="correo_personal" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label>Password</label>
                        <input type="password" name="contrasena" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label>Activities</label>
                        <textarea name="actividades" class="form-control" required></textarea>
                    </div>
                    
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
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Add Employee</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para editar empleado -->
<div class="modal fade" id="editEmployeeModal" tabindex="-1" role="dialog" aria-labelledby="editEmployeeModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editEmployeeLabel">Edit Employee</h5>
            </div>
            <form action="edit_employee.php" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="id_empleado" id="edit_id_empleado">
                    
                    <div class="form-group mb-3">
                        <label for="edit_nombre">Employee Name</label>
                        <input type="text" name="nombre" id="edit_nombre" class="form-control" required>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="edit_apellido_paterno">Paternal Surname</label>
                        <input type="text" name="apellido_paterno" id="edit_apellido_paterno" class="form-control" required>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="edit_apellido_materno">Maternal Surname</label>
                        <input type="text" name="apellido_materno" id="edit_apellido_materno" class="form-control" required>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="edit_hora_entrada">Entry Time</label>
                        <input type="time" name="hora_entrada" id="edit_hora_entrada" class="form-control" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="edit_hora_salida">Exit Time</label>
                        <input type="time" name="hora_salida" id="edit_hora_salida" class="form-control" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="edit_sueldo_diario">Daily Salary</label>
                        <input type="number" name="sueldo_diario" id="edit_sueldo_diario" class="form-control" required>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="edit_telefono_personal">Phone</label>
                        <input type="text" name="telefono_personal" id="edit_telefono_personal" class="form-control" required>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="edit_correo_personal">Email</label>
                        <input type="email" name="correo_personal" id="edit_correo_personal" class="form-control" required>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="edit_actividades">Activities</label>
                        <input type="text" name="actividades" id="edit_actividades" class="form-control" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="edit_id_empresa">Selected a Company</label>
                        <select name="id_empresa" id="edit_id_empresa" class="form-control" required>
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
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para agregar días trabajados -->
<div id="calendarModal" class="modal fade" tabindex="-1" aria-labelledby="calendarModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="calendarModalLabel">Selected Days Worked</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="modal_id_empleado" name="id_empleado">

                <div id="workDaysRow1" class="d-flex justify-content-between mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="day1" value="Lunes">
                        <label class="form-check-label" for="day1">Monday</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="day2" value="Martes">
                        <label class="form-check-label" for="day2">Tuesday</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="day3" value="Miércoles">
                        <label class="form-check-label" for="day3">Wednesday</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="day4" value="Jueves">
                        <label class="form-check-label" for="day4">Thursday</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="day5" value="Viernes">
                        <label class="form-check-label" for="day5">Friday</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="day6" value="Sábado">
                        <label class="form-check-label" for="day6">Saturday</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="day7" value="Domingo">
                        <label class="form-check-label" for="day7">Sunday</label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveWorkDays">Save Changes</button>
            </div>
        </div>
    </div>
</div>

<!-- Tabla de Empleados -->
<section class="my-4"><br/>
<div class="table-responsive">
    <table class="table table-bordered table-hover text-center">
        <thead class="thead-dark">
            <tr>
                <h2 class="text-center">Manage Employee</h2><br/>
                <th>Employee</th>
                <th>Entry Time</th>
                <th>Exit Time</th>
                <th>Days Worked</th>
                <th>Daily Salary</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Position</th>
                <th>Company</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT e.*, emp.nombre_empresa FROM empleados e
                    LEFT JOIN empresa emp ON e.id_empresa = emp.id_empresa";
            $result = $con->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $nombre_completo = htmlspecialchars($row['nombre'] . ' ' . $row['apellido_paterno'] . ' ' . $row['apellido_materno']);
                    echo "<tr>";
                    echo "<td>" . $nombre_completo . "</td>";
                    echo "<td>" . htmlspecialchars($row['hora_entrada']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['hora_salida']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['dias_trabajados']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['sueldo_diario']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['telefono_personal']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['correo_personal']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['rol']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['nombre_empresa']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['estatus']) . "</td>";
                    echo "<td>";
                
                    if ($row['estatus'] === 'activo') {
                        echo "<a href='status_employee.php?id=" . $row['id_empleado'] . "&estatus=inactivo' class='btn btn-warning btn-sm me-2' title='Deactivate Employee'>
                                <i class='fas fa-ban'></i>
                            </a>";
                    } else {
                        echo "<a href='status_employee.php?id=" . $row['id_empleado'] . "&estatus=activo' class='btn btn-success btn-sm me-2' title='Activate Employee'>
                                <i class='fas fa-check-circle'></i>
                            </a>";
                    }

                    echo "<button class='btn btn-info btn-sm me-1' onclick='openEditModal(" . json_encode($row) . ")' title='Edit Employee'>
                            <i class='fas fa-edit'></i>
                        </button>
                        <a href='delete_employee.php?id=" . $row['id_empleado'] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"¿Estás seguro de que deseas eliminar a este empleado?\")' title='Delete Employee'>
                            <i class='fas fa-trash'></i>
                        </a>
                        <button class='btn btn-primary btn-sm me-1' 
                                onclick='openCalendarModal(" . $row['id_empleado'] . ", " . json_encode(explode(",", $row['dias_trabajados'])) . ")' 
                                title='Selected Days Worked'>
                            <i class='fas fa-calendar-alt'></i>
                        </button>
                    </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='11'>There are no employees recorded.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>
</section>

<script>

    function openEditModal(customerData) {
        $('#edit_id_empleado').val(customerData.id_empleado);
        $('#edit_nombre').val(customerData.nombre);
        $('#edit_apellido_paterno').val(customerData.apellido_paterno);
        $('#edit_apellido_materno').val(customerData.apellido_materno);
        $('#edit_hora_entrada').val(customerData.hora_entrada);
        $('#edit_hora_salida').val(customerData.hora_salida);
        $('#edit_sueldo_diario').val(customerData.sueldo_diario);
        $('#edit_telefono_personal').val(customerData.telefono_personal);
        $('#edit_correo_personal').val(customerData.correo_personal);
        $('#edit_actividades').val(customerData.actividades);

        $('#edit_id_empresa').empty().append('<option value="">Seleccione una empresa</option>');
        <?php
        $result_empresas->data_seek(0);
        while ($empresa = $result_empresas->fetch_assoc()) {
            ?>
            $('#edit_id_empresa').append('<option value="<?php echo $empresa['id_empresa']; ?>" ' +
                (customerData.id_empresa == <?php echo $empresa['id_empresa']; ?> ? 'selected' : '') + 
                '> <?php echo addslashes($empresa['nombre_empresa']); ?></option>');
            <?php
        }
        ?>

        $('#editEmployeeModal').modal('show');
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

        function openCalendarModal(idEmpleado, diasTrabajados) {
            $('#modal_id_empleado').val(idEmpleado);
            if (diasTrabajados) {
                diasTrabajados.forEach(dia => {
                    $('#workDaysForm .form-check-input[value="' + dia + '"]').prop('checked', true);
                });
            }

            $('#calendarModal').modal('show');
        }

        $('#saveWorkDays').on('click', function() {
            const idEmpleado = $('#modal_id_empleado').val();
            const diasSeleccionadosArriba = $('#workDaysRow1 .form-check-input:checked').length;
            const diasSeleccionados = diasSeleccionadosArriba;

            $.ajax({
                url: 'save_work_days.php',
                type: 'POST',
                data: { id_empleado: idEmpleado, dias_trabajados: diasSeleccionados },
                success: function(response) {
                    const res = JSON.parse(response);
                    if (res.status === 'success') {
                        mostrarToast("Éxito", res.message, "success");
                        $('#calendarModal').modal('hide');
                        location.reload();
                    } else {
                        mostrarToast("Error", res.message, "error");
                    }
                },
                error: function() {
                    mostrarToast("Error", "Error al guardar los datos.", "error");
                }
            });
        });
</script>
</body>
</html>