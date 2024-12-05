<?php
require '../Login/conexion.php';

$sql_clientes = "SELECT id_cliente, nombre_cliente, apellido_paterno, apellido_materno FROM clientes WHERE rol = 'usuario'";
$result_clientes = $con->query($sql_clientes);

require '../Administrador/superior_admin.php';
?>

<!DOCTYPE html>
<html lang="es">
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
        <a href="../Administrador/customer_address.php" class="btn btn-primary" style="float: right; margin: 10px;">
            View Addresses
        </a>
    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addCustomerModal" style="float: right; margin: 10px;">
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
                        <label>Customer Name</label>
                        <input type="text" name="nombre_cliente" class="form-control" required>
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
                        <label>Gender</label>
                        <input type="text" name="genero_cliente" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label>Phone</label>
                        <input type="text" name="telefono_personal" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label>Email</label>
                        <input type="email" name="correo_electronico" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label>Password</label>
                        <input type="password" name="contrasena" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label>Confirm Password</label>
                        <input type="password" name="confirmar_contrasena" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label>Age</label>
                        <input type="number" name="edad" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
                        <label for="edit_nombre_cliente">Customer Name</label>
                        <input type="text" name="nombre_cliente" id="edit_nombre_cliente" class="form-control" required>
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
                        <label for="edit_genero_cliente">Gender</label>
                        <input type="text" name="genero_cliente" id="edit_genero_cliente" class="form-control" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="edit_telefono_personal">Phone</label>
                        <input type="text" name="telefono_personal" id="edit_telefono_personal" class="form-control" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="edit_correo_electronico">Email</label>
                        <input type="email" name="correo_electronico" id="edit_correo_electronico" class="form-control" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="edit_edad">Age</label>
                        <input type="number" name="edad" id="edit_edad" class="form-control" required>
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
                        <label for="nombre_cliente_modal">Customer Name</label>
                        <input type="text" id="nombre_cliente_modal" class="form-control" readonly>
                    </div>
                    <input type="hidden" name="id_cliente" id="id_cliente_modal">
                    <div class="form-group mb-3">
                        <label for="num_ext">Outside Number</label>
                        <input type="number" name="num_ext" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="num_int">Inner Number</label>
                        <input type="number" name="num_int" class="form-control"  required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="calle">Street</label>
                        <input type="text" name="calle" class="form-control"  required>
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
                    <button type="submit" class="btn btn-primary">Add Customer Address</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Tabla de clientes -->
<section class="services-table container my-2"><br/>
<div class="table-responsive">
    <table class="table table-bordered table-hover text-center">
        <thead class="thead-dark">
        <h2 class="text-center">Manage Customers</h2><br/>
            <tr>
                <th>Customer Name</th>
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
                    $nombre_completo = htmlspecialchars($row['nombre_cliente'] . ' ' . $row['apellido_paterno'] . ' ' . $row['apellido_materno']);
                    echo "<tr>";
                    echo "<td>" . $nombre_completo . "</td>";
                    echo "<td>" . htmlspecialchars($row['genero_cliente']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['telefono_personal']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['correo_electronico']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['edad']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['rol']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['estatus']) . "</td>";
                    echo "<td>";
                    
                    if ($row['estatus'] === 'activo') {
                        echo "<a href='status_customers.php?id=" . $row['id_cliente'] . "&estatus=inactivo' class='btn btn-warning btn-sm me-2' title='Deactivate Customer'>
                                <i class='fas fa-ban'></i>
                            </a>";
                    } else {
                        echo "<a href='status_customers.php?id=" . $row['id_cliente'] . "&estatus=activo' class='btn btn-success btn-sm me-2' title='Activate Customer'>
                                <i class='fas fa-check-circle'></i>
                            </a>";
                    }
                    echo "<button class='btn btn-info btn-sm me-1' onclick='openEditModal(" . json_encode($row) . ")' title='Edit Customer'>
                            <i class='fas fa-edit'></i>
                        </button>
                        <a href='delete_customer.php?id=" . $row['id_cliente'] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure you want to delete this customer?\")' title='Delete Customer'>
                            <i class='fas fa-trash'></i>
                        </a>
                        <button class='btn btn-success btn-sm' onclick='openAddAddressModal(" . json_encode($row) . ")' title='Add Address'>
                            <i class='fas fa-plus'></i>
                        </button>
                        </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='11'>There are no customers recorded.</td></tr>";
                }
            ?>
        </tbody>
    </table>
</div>
</section>

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

        $('#editCustomerModal').modal('show');
    }

    function openAddAddressModal(cliente) {
        document.getElementById('id_cliente_modal').value = cliente.id_cliente;
        
        document.getElementById('nombre_cliente_modal').value = cliente.nombre_cliente + ' ' + cliente.apellido_paterno + ' ' + cliente.apellido_materno;
    
        $('#addCustomerAddressModal').modal('show');
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

    </script>
</body>
</html>