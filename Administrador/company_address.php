<?php
require '../Login/conexion.php';
require '../Administrador/superior_admin.php';?>

<!DOCTYPE html>
<html lang="en">
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
                <input type="hidden" name="id_direccion_empresa" id="edit_id_direccion_empresa">
                <div class="form-group mb-3">
                        <label for="edit_id_empresa">Selecciona la Empresa</label>
                        <select name="id_empresa" id="edit_id_empresa" class="form-control" required>
                            <option value="">Seleccione una empresa</option>
                            <?php
                            $sql_empresas = "SELECT id_empresa, nombre_empresa FROM empresa";
                            $result_empresas = $con->query($sql_empresas);

                            while ($empresa = $result_empresas->fetch_assoc()) {
                                echo "<option value='" . htmlspecialchars($empresa['id_empresa']) . "'>" . htmlspecialchars($empresa['nombre_empresa']) . "</option>";
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

<section><br/>
    <table class="table">
        <thead class="thead-dark">
        <h2 class="text-center">Manage Company Address</h2><br/>
            <tr>
                <th>Address ID</th>
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
            $sql = "SELECT d.id_direccion_empresa, e.nombre_empresa, d.num_ext, d.num_int, d.calle, d.ciudad, d.estado, d.codigo_postal
                    FROM direccion_empresa d
                    JOIN empresa e ON d.id_empresa = e.id_empresa";
            $result = $con->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $nombre_empresa = htmlspecialchars($row['nombre_empresa']);
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['id_direccion_empresa']) . "</td>";
                    echo "<td>" . $nombre_empresa . "</td>";
                    echo "<td>" . htmlspecialchars($row['num_ext']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['num_int']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['calle']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['ciudad']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['estado']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['codigo_postal']) . "</td>";
                    echo "<td>";
                        echo "<button class='btn btn-info btn-sm me-1' onclick='openEditCompanyAddressModal(" . json_encode($row) . ")' title='Edit Company Addres'>
                                <i class='fas fa-edit'></i>
                            </button>
                            <a href='delete_company_address.php?id=" . $row['id_direccion_empresa'] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"¿Estás seguro de que deseas eliminar esta direccion de la empresa?\")' title='Delete Customer Address'>
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
</section>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>

function openEditCompanyAddressModal(customerData) {
    console.log(customerData);
    $('#edit_id_direccion_empresa').val(customerData.id_direccion_empresa);
    $('#edit_num_ext').val(customerData.num_ext);
    $('#edit_num_int').val(customerData.num_int);
    $('#edit_calle').val(customerData.calle);
    $('#edit_ciudad').val(customerData.ciudad);
    $('#edit_estado').val(customerData.estado);
    $('#edit_codigo_postal').val(customerData.codigo_postal);

    $('#edit_id_empresa').val(customerData.id_empresa);

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
