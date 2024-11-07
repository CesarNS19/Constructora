<?php
require '../Login/conexion.php';

$sql_clientes = "SELECT id_cliente, nombre_cliente, apellido_paterno, apellido_materno FROM clientes WHERE rol = 'usuario'";
$result_clientes = $con->query($sql_clientes);
?>

<?php
require '../Administrador/superior_admin.php';?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

<div id="Alert"></div>

<section>
    <button class="btn btn-success" data-toggle="modal" data-target="#addCustomerModal" style="float: right; margin: 10px;">
        Add Customer
    </button><br/>
</section><br/>

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

<!-- Modal para editar cliente -->
<div class="modal fade" id="editCustomerModal" tabindex="-1" role="dialog" aria-labelledby="editCustomerModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCustomerModalLabel">Edit Customer</h5>
            </div>
            <form action="edit_customer.php" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="id_cliente" id="edit_id_cliente">
                    
                    <div class="form-group mb-3">
                        <label for="edit_nombre_cliente">Nombre</label>
                        <input type="text" name="nombre_cliente" id="edit_nombre_cliente" class="form-control" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="edit_apellido_paterno">Apellido Paterno</label>
                        <input type="text" name="apellido_paterno" id="edit_apellido_paterno" class="form-control" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="edit_apellido_materno">Apellido Materno</label>
                        <input type="text" name="apellido_materno" id="edit_apellido_materno" class="form-control" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="edit_genero_cliente">Género</label>
                        <input type="text" name="genero_cliente" id="edit_genero_cliente" class="form-control" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="edit_telefono_personal">Teléfono</label>
                        <input type="text" name="telefono_personal" id="edit_telefono_personal" class="form-control" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="edit_correo_electronico">Correo Electrónico</label>
                        <input type="email" name="correo_electronico" id="edit_correo_electronico" class="form-control" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="edit_edad">Edad</label>
                        <input type="number" name="edad" id="edit_edad" class="form-control" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="edit_rol">Rol</label>
                        <input type="text" name="rol" id="edit_rol" class="form-control" required>
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

<!-- Modal para añadir dirección del cliente -->
<div class="modal fade" id="addCustomerAddressModal" tabindex="-1" role="dialog" aria-labelledby="addCustomerAddressModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCustomerAddressLabel">Add Customer Address</h5>
            </div>
            <form action="add_customer_address.php" method="POST">
                <div class="modal-body">
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
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add Customer Address</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para editar dirección del cliente -->
<div class="modal fade" id="editCustomerAddressModal" tabindex="-1" role="dialog" aria-labelledby="editCustomerAddressModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCustomerAddressLabel">Edit Customer Address</h5>
            </div>
            <form action="edit_customer_address.php" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="id_direccion_cliente" id="edit_id_direccion_cliente">
                    <div class="form-group mb-3">
                        <label for="edit_id_clientee">Seleccione un cliente</label>
                        <select name="id_cliente" id="edit_id_clientee" class="form-control" required>
                            <option value="">Seleccione un cliente</option>
                            <?php
                            $result_clientes->data_seek(0);
                            while ($clientes = $result_clientes->fetch_assoc()) {
                                $nombre_completo = htmlspecialchars($clientes['nombre_cliente'] . ' ' . $clientes['apellido_paterno'] . ' ' . $clientes['apellido_materno']);
                                echo "<option value='" . htmlspecialchars($clientes['id_cliente']) . "'>$nombre_completo</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label for="edit_num_ext">Outside Number</label>
                        <input type="number" name="num_ext" id="edit_num_ext" class="form-control" placeholder="Enter Outside Number" required>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="edit_num_int">Inner Number</label>
                        <input type="number" name="num_int" id="edit_num_int" class="form-control" placeholder="Enter Inner Number" required>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="edit_calle">Street</label>
                        <input type="text" name="calle" id="edit_calle" class="form-control" placeholder="Enter Street" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="edit_ciudad">City</label>
                        <input type="text" name="ciudad" id="edit_ciudad" class="form-control" placeholder="Enter City" required>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="edit_estado">State</label>
                        <input type="text" name="estado" id="edit_estado" class="form-control" placeholder="Enter State" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="edit_codigo_postal">Postal Code</label>
                        <input type="number" name="codigo_postal" id="edit_codigo_postal" class="form-control" placeholder="Enter Postal Code" required>
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
        <h2 class="text-center">Manage Customers</h2><br/>
            <tr>
                <th>Customer ID</th>
                <th>Name</th>
                <th>Last Name</th>
                <th>Mother's Last Name</th>
                <th>Gender</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Age</th>
                <th>Role</th>
                <th>Status</th>
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
                    echo "<td>" . htmlspecialchars($row['id_cliente']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['nombre_cliente']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['apellido_paterno']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['apellido_materno']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['genero_cliente']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['telefono_personal']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['correo_electronico']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['edad']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['rol']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['estatus']) . "</td>";
                    echo "<td>";
                    
                        if ($row['estatus'] === 'activo') {
                            echo "<a href='status_customers.php?id=" . $row['id_cliente'] . "&estatus=inactivo' class='btn btn-warning btn-sm me-2' title='Desactivar cliente'>
                                    <i class='fas fa-ban'></i>
                                </a>";
                        } else {
                            echo "<a href='status_customers.php?id=" . $row['id_cliente'] . "&estatus=activo' class='btn btn-success btn-sm me-2' title='Activar cliente'>
                                    <i class='fas fa-check-circle'></i>
                                </a>";
                        }

                        echo "<button class='btn btn-info btn-sm me-1' onclick='openEditModal(" . json_encode($row) . ")' title='Editar cliente'>
                                <i class='fas fa-edit'></i>
                            </button>
                            <a href='delete_customer.php?id=" . $row['id_cliente'] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"¿Estás seguro de que deseas eliminar a este cliente?\")' title='Eliminar cliente'>
                                <i class='fas fa-trash'></i>
                            </a>
                        </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='11'>No hay empleados registrados.</td></tr>";
                }
            ?>
        </tbody>
    </table>
</section><br/>

<section>
    <button class="btn btn-info" data-toggle="modal" data-target="#addCustomerAddressModal" style="float: right; margin: 10px;">
        Add Customer Address
    </button>
</section><br/>

<section>
    <table class="table">
        <thead class="thead-dark">
        <h2 class="text-center">Manage Customers Address</h2><br/>
            <tr>
                <th>Addres ID</th>
                <th>Customomer</th>
                <th>Outside Number</th>
                <th>Inner Number</th>
                <th>Street</th>
                <th>City</th>
                <th>State</th>
                <th>Postal Code</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php
            $sql = "SELECT d.id_direccion_cliente, c.nombre_cliente, c.apellido_paterno, c.apellido_materno, d.num_ext, d.num_int, d.calle, d.ciudad, d.estado, d.codigo_postal
                    FROM direccion_cliente d
                    JOIN clientes c ON d.id_cliente = c.id_cliente";
            $result = $con->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $nombre_completo = htmlspecialchars($row['nombre_cliente'] . ' ' . $row['apellido_paterno'] . ' ' . $row['apellido_materno']);
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['id_direccion_cliente']) . "</td>";
                    echo "<td>" . $nombre_completo . "</td>";
                    echo "<td>" . htmlspecialchars($row['num_ext']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['num_int']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['calle']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['ciudad']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['estado']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['codigo_postal']) . "</td>";
                    echo "<td>";
                        echo "<button class='btn btn-info btn-sm me-1' onclick='openEditCustomerAddressModal(" . json_encode($row) . ")' title='Edit Customer Addres'>
                                <i class='fas fa-edit'></i>
                            </button>
                            <a href='delete_customer_address.php?id=" . $row['id_direccion_cliente'] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"¿Estás seguro de que deseas eliminar esta direccion de cliente?\")' title='Delete Customer Address'>
                                <i class='fas fa-trash'></i>
                            </a>
                        </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='11'>No hay clientes registrados.</td></tr>";
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

    function openEditCustomerAddressModal(customerData) {
        $('#edit_id_direccion_cliente').val(customerData.id_direccion_cliente);
        $('#hidden_id_cliente').val(customerData.id_cliente);
        $('#edit_id_clientee').val(customerData.id_cliente);
        $('#edit_num_ext').val(customerData.num_ext);
        $('#edit_num_int').val(customerData.num_int);
        $('#edit_calle').val(customerData.calle);
        $('#edit_ciudad').val(customerData.ciudad);
        $('#edit_estado').val(customerData.estado);
        $('#edit_codigo_postal').val(customerData.codigo_postal);

        $('#editCustomerAddressModal').modal('show');
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
    </script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>