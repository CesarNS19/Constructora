<?php
require '../Login/conexion.php';
require '../Administrador/superior_admin.php';

$sql_clientes = "SELECT id_cliente, nombre_cliente, apellido_paterno, apellido_materno FROM clientes WHERE rol = 'usuario'";
$result_clientes = $con->query($sql_clientes);

if (!$result_clientes) {
    die("Error al cargar clientes: " . $con->error);
}

$sql = "SELECT d.id_direccion_cliente, c.nombre_cliente, c.apellido_paterno, c.apellido_materno, d.num_ext, d.num_int, d.calle, d.ciudad, d.estado, d.codigo_postal
        FROM direccion_cliente d
        JOIN clientes c ON d.id_cliente = c.id_cliente";
$result = $con->query($sql);

if (!$result) {
    die("Error en la consulta de direcciones: " . $con->error);
}
?>

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

<a href="../Administrador/customers.php" class="btn btn-primary" style="float: right; margin: 20px;">
    Back
</a><br/>

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
                        <label for="edit_id_cliente">Cliente</label>
                        <input type="text" name="edit_id_cliente" id="edit_id_cliente" class="form-control" readonly>
                        <input type="hidden" name="id_cliente" id="id_cliente">
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
            <h2 class="text-center">Manage Customers Address</h2><br/>
            <tr>
                <th>Address ID</th>
                <th>Customer</th>
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
                    echo "<td>
                            <button class='btn btn-info btn-sm me-1' onclick='openEditCustomerAddressModal(" . json_encode($row) . ")' title='Edit Customer Address'>
                                <i class='fas fa-edit'></i>
                            </button>
                            <a href='delete_customer_address.php?id=" . $row['id_direccion_cliente'] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"¿Estás seguro de que deseas eliminar esta dirección de cliente?\")' title='Delete Customer Address'>
                                <i class='fas fa-trash'></i>
                            </a>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='9'>No hay clientes registrados.</td></tr>";
            }
        ?>
        </tbody>
    </table>
</section>

<script>

    function openEditCustomerAddressModal(customerData) {
        $('#edit_id_direccion_cliente').val(customerData.id_direccion_cliente);
        
        var nombreCompleto = customerData.nombre_cliente + ' ' + customerData.apellido_paterno + ' ' + customerData.apellido_materno;
        $('#edit_id_cliente').val(nombreCompleto);
        $('#id_cliente').val(customerData.id_cliente);
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
</body>
</html>