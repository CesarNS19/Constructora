<?php
require '../Login/conexion.php';

$sql_empresas = "SELECT id_empresa, nombre_empresa FROM empresa";
$result_empresas = $con->query($sql_empresas);

require '../Administrador/superior_admin.php';
?>

<!DOCTYPE html>
<html lang="en">
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

<div class="modal fade" id="addEmployeeModal" tabindex="-1" role="dialog" aria-labelledby="addEmployeeModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addEmployeeModalLabel">Agregar Nuevo Empleado</h5>
            </div>
            <form action="add_employee.php" method="POST">
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <input type="text" name="nombre" class="form-control" placeholder="Nombre" required>
                    </div>
                    <div class="form-group mb-3">
                        <input type="text" name="apellido_paterno" class="form-control" placeholder="Apellido Paterno" required>
                    </div>
                    <div class="form-group mb-3">
                        <input type="text" name="apellido_materno" class="form-control" placeholder="Apellido Materno" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="hora_entrada">Hora de Entrada</label>
                        <input type="time" name="hora_entrada" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="hora_salida">Hora de Salida</label>
                        <input type="time" name="hora_salida" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <input type="text" name="telefono_personal" class="form-control" placeholder="Teléfono Personal" required>
                    </div>
                    <div class="form-group mb-3">
                        <input type="email" name="correo_personal" class="form-control" placeholder="Correo Personal" required>
                    </div>
                    <div class="form-group mb-3">
                        <input type="password" name="contrasena" class="form-control" placeholder="Password" required>
                    </div>
                    <div class="form-group mb-3">
                        <textarea name="actividades" class="form-control" placeholder="Actividades" required></textarea>
                    </div>
                    
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
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Agregar Empleado</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editEmployeeModal" tabindex="-1" role="dialog" aria-labelledby="editEmployeeModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editEmployeeLabel">Editar Empleado</h5>
            </div>
            <form action="edit_employee.php" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="id_empleado" id="edit_id_empleado">
                    
                    <div class="form-group mb-3">
                        <label for="edit_nombre">Nombre</label>
                        <input type="text" name="nombre" id="edit_nombre" class="form-control" placeholder="Ingresa el nombre" required>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="edit_apellido_paterno">Apellido Paterno</label>
                        <input type="text" name="apellido_paterno" id="edit_apellido_paterno" class="form-control" placeholder="Ingresa el apellido paterno" required>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="edit_apellido_materno">Apellido Materno</label>
                        <input type="text" name="apellido_materno" id="edit_apellido_materno" class="form-control" placeholder="Ingresa el apellido materno" required>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="edit_hora_entrada">Hora de Entrada</label>
                        <input type="time" name="hora_entrada" id="edit_hora_entrada" class="form-control" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="edit_hora_salida">Hora de Salida</label>
                        <input type="time" name="hora_salida" id="edit_hora_salida" class="form-control" required>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="edit_telefono_personal">Teléfono Personal</label>
                        <input type="text" name="telefono_personal" id="edit_telefono_personal" class="form-control" placeholder="Ingresa el teléfono personal" required>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="edit_correo_personal">Correo Personal</label>
                        <input type="email" name="correo_personal" id="edit_correo_personal" class="form-control" placeholder="Ingresa el correo personal" required>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="edit_actividades">Actividades</label>
                        <input type="text" name="actividades" id="edit_actividades" class="form-control" placeholder="Ingresa las actividades" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="edit_id_empresa">Selecciona la Empresa</label>
                        <select name="id_empresa" id="edit_id_empresa" class="form-control" required>
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
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para seleccionar días trabajados -->
<div class="modal fade" id="calendarModal" tabindex="-1" aria-labelledby="calendarModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="calendarModalLabel">Seleccionar Días Trabajados</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="calendar"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="saveWorkDays()">Guardar</button>
            </div>
        </div>
    </div>
</div>

<!-- Tabla de Empleados -->
<section class="employee-table"><br/>
    <table class="table">
        <thead class="thead-dark">
            <tr>
                <h2 class="text-center">Manage Employees</h2><br/>
                <th>Name</th>
                <th>Last Name</th>
                <th>Mother's Last Name</th>
                <th>Entry Time</th>
                <th>Exit Time</th>
                <th>Days Worked</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Position</th>
                <th>Activities</th>
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
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['nombre']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['apellido_paterno']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['apellido_materno']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['hora_entrada']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['hora_salida']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['dias_trabajados']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['telefono_personal']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['correo_personal']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['rol']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['actividades']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['nombre_empresa']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['estatus']) . "</td>";
                    echo "<td>";
                
                    if ($row['estatus'] === 'activo') {
                        echo "<a href='status_employee.php?id=" . $row['id_empleado'] . "&estatus=inactivo' class='btn btn-warning btn-sm me-2' title='Desactivar empleado'>
                                <i class='fas fa-ban'></i>
                            </a>";
                    } else {
                        echo "<a href='status_employee.php?id=" . $row['id_empleado'] . "&estatus=activo' class='btn btn-success btn-sm me-2' title='Activar empleado'>
                                <i class='fas fa-check-circle'></i>
                            </a>";
                    }

                    echo "<button class='btn btn-info btn-sm me-1' onclick='openEditModal(" . json_encode($row) . ")' title='Editar empleado'>
                            <i class='fas fa-edit'></i>
                        </button>
                        <a href='delete_employee.php?id=" . $row['id_empleado'] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"¿Estás seguro de que deseas eliminar a este empleado?\")' title='Eliminar empleado'>
                            <i class='fas fa-trash'></i>
                        </a>
                        <button class='btn btn-primary btn-sm me-1' 
                                onclick='openCalendarModal(" . $row['id_empleado'] . ", " . json_encode(explode(",", $row['dias_trabajados'])) . ")' 
                                title='Seleccionar días trabajados'>
                            <i class='fas fa-calendar-alt'></i>
                        </button>
                    </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='11'>No hay empleados registrados.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</section>

<script>

    function openEditModal(customerData) {
        $('#edit_id_empleado').val(customerData.id_empleado);
        $('#edit_nombre').val(customerData.nombre);
        $('#edit_apellido_paterno').val(customerData.apellido_paterno);
        $('#edit_apellido_materno').val(customerData.apellido_materno);
        $('#edit_hora_entrada').val(customerData.hora_entrada);
        $('#edit_hora_salida').val(customerData.hora_salida);
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
            // Si diasTrabajados no es un array, lo convertimos a un array (por seguridad)
            if (!Array.isArray(diasTrabajados)) {
                diasTrabajados = diasTrabajados.split(',').map(Number);
            }

            // Pasamos el idEmpleado al modal
            $('#calendarModal').data('idEmpleado', idEmpleado);
            
            // Generamos el calendario y lo pasamos al modal
            generateCalendar(diasTrabajados);
            
            // Mostramos el modal
            $('#calendarModal').modal('show');
        }

function generateCalendar(diasTrabajados) {
    const calendar = document.getElementById('calendar');
    calendar.innerHTML = ''; // Limpiamos el calendario antes de generarlo

    const days = ['L', 'M', 'X', 'J', 'V', 'S', 'D']; // Días de la semana
    const row1 = document.createElement('div');
    const row2 = document.createElement('div');
    row1.classList.add('d-flex', 'justify-content-center', 'mb-2');
    row2.classList.add('d-flex', 'justify-content-center');

    // Generamos los botones de los días (del 1 al 7, representando los días de la semana)
    for (let i = 1; i <= 7; i++) {
        const dayBtn = document.createElement('button');
        dayBtn.className = 'day-btn btn btn-outline-primary m-1';
        dayBtn.textContent = days[i - 1]; // Asignamos la letra del día
        dayBtn.dataset.day = i; // Guardamos el número de día (1 a 7)

        // Si el día está seleccionado, lo marcamos con 'active'
        if (diasTrabajados.includes(i)) {
            dayBtn.classList.add('active');
        }

        // Cambiamos el estado del botón (seleccionado/desmarcado)
        dayBtn.onclick = () => {
            dayBtn.classList.toggle('active');
        };
        
        // Distribuimos los días en dos filas
        if (i <= 4) {
            row1.appendChild(dayBtn);
        } else {
            row2.appendChild(dayBtn);
        }
    }

    // Añadimos las filas al calendario
    calendar.appendChild(row1);
    calendar.appendChild(row2);
}


        function saveWorkDays() {
            const idEmpleado = $('#calendarModal').data('idEmpleado');
            const selectedDays = Array.from(document.querySelectorAll('.day-btn.active')).map(btn => btn.dataset.day);

            const totalDays = selectedDays.length;

            $.ajax({
                url: 'update_work_days.php',
                type: 'POST',
                data: { 
                    id_empleado: idEmpleado, 
                    dias_trabajados: totalDays
                },
                success: function(response) {
                    mostrarToast('Éxito', 'Días trabajados actualizados correctamente', 'success');
                    $('#calendarModal').modal('hide');
                    location.reload();
                },
                error: function() {
                    mostrarToast('Error', 'Hubo un problema al actualizar los días trabajados', 'error');
                }
            });
        }

</script>

</body>
</html>