<?php require '../Login/conexion.php'; ?>
<?php require '../Administrador/superior_admin.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<section class="employee-header">
    <button class="btn btn-success" data-toggle="modal" data-target="#addCustomerModal" style="float: right; margin: 10px;">
        Add Customer
    </button>
</section>

<!-- Modal para añadir cliente -->
<div class="modal fade" id="addCustomerModal" tabindex="-1" role="dialog" aria-labelledby="addCustomerModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCustomerLabel">Add New Customer</h5>
            </div>
            <form action="add_customer.php" method="POST">
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <input type="text" name="nombre_cliente" class="form-control" placeholder="Nombre" required>
                    </div>
                    <div class="form-group mb-3">
                        <input type="text" name="apellido_paterno" class="form-control" placeholder="Apellido Paterno" required>
                    </div>
                    <div class="form-group mb-3">
                        <input type="text" name="apellido_materno" class="form-control" placeholder="Apellido Materno" required>
                    </div>
                    <div class="form-group mb-3">
                        <input type="text" name="genero_cliente" class="form-control" placeholder="Género" required>
                    </div>
                    <div class="form-group mb-3">
                        <input type="text" name="telefono_personal" class="form-control" placeholder="Teléfono Personal" required>
                    </div>
                    <div class="form-group mb-3">
                        <input type="email" name="correo_electronico" class="form-control" placeholder="Correo Electrónico" required>
                    </div>
                    <div class="form-group mb-3">
                        <input type="password" name="contrasena" class="form-control" placeholder="Contraseña" required>
                    </div>
                    <div class="form-group mb-3">
                        <input type="password" name="confirmar_contrasena" class="form-control" placeholder="Confirmar Contraseña" required>
                    </div>
                    <div class="form-group mb-3">
                        <input type="number" name="edad" class="form-control" placeholder="Edad" required>
                    </div>
                    <div class="form-group mb-3">
                        <input type="text" name="rol" class="form-control" placeholder="Rol" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add Customer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editCustomerModal" tabindex="-1" role="dialog" aria-labelledby="editCustomerModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCustomerLabel">Edit Customer</h5>
            </div>
            <form action="edit_customer.php" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="id_cliente" id="edit_id_cliente">
                    
                    <div class="form-group mb-3">
                        <label for="edit_nombre_cliente">First Name</label>
                        <input type="text" name="nombre_cliente" id="edit_nombre_cliente" class="form-control" placeholder="Enter first name" required>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="edit_apellido_paterno">Last Name</label>
                        <input type="text" name="apellido_paterno" id="edit_apellido_paterno" class="form-control" placeholder="Enter last name" required>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="edit_apellido_materno">Mother's Last Name</label>
                        <input type="text" name="apellido_materno" id="edit_apellido_materno" class="form-control" placeholder="Enter mother's last name" required>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="edit_genero_cliente">Gender</label>
                        <input type="text" name="genero_cliente" id="edit_genero_cliente" class="form-control" placeholder="Enter gender" required>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="edit_telefono_personal">Phone Number</label>
                        <input type="text" name="telefono_personal" id="edit_telefono_personal" class="form-control" placeholder="Enter phone number" required>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="edit_correo_electronico">Email</label>
                        <input type="email" name="correo_electronico" id="edit_correo_electronico" class="form-control" placeholder="Enter email" required>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="edit_contrasena">Password</label>
                        <input type="password" name="contrasena" id="edit_contrasena" class="form-control" placeholder="Enter password" required>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="edit_edad">Age</label>
                        <input type="number" name="edad" id="edit_edad" class="form-control" placeholder="Enter age" required>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="edit_rol">Role</label>
                        <input type="text" name="rol" id="edit_rol" class="form-control" placeholder="Enter role" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<section>
    <table class="table">
        <thead class="thead-dark">
            <tr>
                <th>Name</th>
                <th>Last Name</th>
                <th>Mother's Last Name</th>
                <th>Gender</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Password</th>
                <th>Age</th>
                <th>Rol</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT * FROM clientes WHERE rol = 'usuario'";
            $result = $con->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['nombre_cliente']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['apellido_paterno']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['apellido_materno']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['genero_cliente']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['telefono_personal']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['correo_electronico']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['contrasena']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['edad']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['rol']) . "</td>";
                    echo "<td>
                        <button class='btn btn-warning btn-sm' onclick='openEditModal(" . json_encode($row) . ")'>Edit</button>
                        <a href='delete_customer.php?id=" . $row['id_cliente'] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure you want to delete this customer?\")'>Delete</a>
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

<script>
function openEditModal(customerData) {
    $('#edit_id_cliente').val(customerData.id_cliente);
    $('#edit_nombre_cliente').val(customerData.nombre_cliente);
    $('#edit_apellido_paterno').val(customerData.apellido_paterno);
    $('#edit_apellido_materno').val(customerData.apellido_materno);
    $('#edit_genero_cliente').val(customerData.genero_cliente);
    $('#edit_telefono_personal').val(customerData.telefono_personal);
    $('#edit_correo_electronico').val(customerData.correo_electronico);
    $('#edit_contrasena').val(customerData.contrasena);
    $('#edit_edad').val(customerData.edad);
    $('#edit_rol').val(customerData.rol);

    $('#editCustomerModal').modal('show');
}
</script>
</body>
</html>
