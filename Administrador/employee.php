<?php require '../Login/conexion.php'; ?>
<?php require '../Administrador/superior_admin.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>

<?php if (isset($_SESSION['status_message'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php echo $_SESSION['status_message']; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['status_message']); ?>
<?php endif; ?>

<section class="employee-header">
        <button class="btn btn-success" data-toggle="modal" data-target="#addEmployeeModal" style="float: right; margin: 10px;">
            Add Employee
        </button>
    </section>

<div class="modal fade" id="addEmployeeModal" tabindex="-1" role="dialog" aria-labelledby="addEmployeeModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addEmployeeModalLabel">Add New Employee</h5>
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
                        <input type="number" step="0.01" name="salario" class="form-control" placeholder="Salario" required>
                    </div>
                    <div class="form-group mb-3">
                        <input type="text" name="telefono_personal" class="form-control" placeholder="Teléfono Personal" required>
                    </div>
                    <div class="form-group mb-3">
                        <input type="email" name="correo_personal" class="form-control" placeholder="Correo Personal" required>
                    </div>
                    <div class="form-group mb-3">
                        <input type="text" name="cargo" class="form-control" placeholder="Cargo" required>
                    </div>
                    <div class="form-group mb-3">
                        <textarea name="actividades" class="form-control" placeholder="Actividades" required></textarea>
                    </div>
                    <div class="form-group mb-3">
                        <input type="number" name="id_empresa" class="form-control" placeholder="ID Empresa" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add Employee</button>
                </div>
            </form>
        </div>
    </div>
</div>

    <!-- Tabla de Empleados -->
    <section class="employee-table">
        <table class="table">
            <thead class="thead-dark">
                <tr>
                    <th>Name</th>
                    <th>Last Name</th>
                    <th>Mother's Last Name</th>
                    <th>Entry Time</th>
                    <th>Exit Time</th>
                    <th>Salary</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Position</th>
                    <th>Activities</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM empleados";
                $result = $con->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['nombre']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['apellido_paterno']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['apellido_materno']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['hora_entrada']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['hora_salida']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['salario']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['telefono_personal']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['correo_personal']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['cargo']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['actividades']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['estatus']) . "</td>";
                        echo "<td>
                            <a href='edit_employee.php?id=" . $row['id_empleado'] . "' class='btn btn-warning btn-sm'>Edit</a>
                            <a href='delete_employee.php?id=" . $row['id_empleado'] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure you want to delete this employee?\")'>Delete</a>
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

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>