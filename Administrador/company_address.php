<?php
require '../Login/conexion.php';
require '../Administrador/superior_admin.php';?>

<!DOCTYPE html>
<html lang="es">
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

        <a href="../Administrador/company.php" class="btn btn-primary" style="float: right; margin: 20px;">
            Back
        </a><br/>

<!-- Modal para editar dirección de la empresa -->
<div class="modal fade" id="editCompanyAddressModal" tabindex="-1" role="dialog" aria-labelledby="editCompanyAddressModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCompanyAddressLabel">Edit Company Address</h5>
            </div>
            <form action="edit_company_address.php" method="POST">
                <div class="modal-body">
                <input type="hidden" name="id_direccion" id="edit_id_direccion">

                <div class="form-group mb-3">
                        <label for="edit_id_empresa">Company</label>
                        <input type="text" name="edit_id_empresa" id="edit_id_empresa" class="form-control" readonly>
                        <input type="hidden" name="id_empresa" id="id_empresa">
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

<!-- Tabla de direcciones de empresas -->
<section><br/>
    <table class="table table-bordered table-hover text-center">
        <thead class="thead-dark">
        <h2 class="text-center">Manage Company Addresses</h2><br/>
            <tr>
                <th>Company</th>
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
            $sql = "SELECT d.id_direccion, d.id_empresa, e.nombre_empresa, d.num_ext, d.num_int, d.calle, d.ciudad, d.estado, d.codigo_postal
                    FROM direcciones d
                    JOIN empresa e ON d.id_empresa = e.id_empresa";
            $result = $con->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $nombre_empresa = htmlspecialchars($row['nombre_empresa']);
                    echo "<tr>";
                    echo "<td>" . $nombre_empresa . "</td>";
                    echo "<td>" . htmlspecialchars($row['num_ext']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['num_int']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['calle']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['ciudad']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['estado']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['codigo_postal']) . "</td>";
                    echo "<td>";
                        echo "<button class='btn btn-info btn-sm me-1' onclick='openEditCompanyAddressModal(" . json_encode($row) . ")' title='Edit Company Address'>
                                <i class='fas fa-edit'></i>
                            </button>
                            <a href='delete_company_address.php?id=" . $row['id_empresa'] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"¿Estás seguro de que deseas eliminar esta direccion de la empresa?\")' title='Delete Company Address'>
                                <i class='fas fa-trash'></i>
                            </a>
                        </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='11'>There are no company address recorded.</td></tr>";
                }
            ?>
        </tbody>
    </table>
</section>

<script>

    function openEditCompanyAddressModal(customerData) {
        var empresa = customerData.nombre_empresa;

        $('#edit_id_direccion').val(customerData.id_direccion);
        $('#edit_id_empresa').val(empresa);
        $('#edit_num_ext').val(customerData.num_ext);
        $('#edit_num_int').val(customerData.num_int);
        $('#edit_calle').val(customerData.calle);
        $('#edit_ciudad').val(customerData.ciudad);
        $('#edit_estado').val(customerData.estado);
        $('#edit_codigo_postal').val(customerData.codigo_postal);

        $('#editCompanyAddressModal').modal('show');
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