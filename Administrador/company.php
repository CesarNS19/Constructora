<?php
require '../Login/conexion.php';

$sql_empresas = "SELECT id_empresa, nombre_empresa FROM empresa";
$result_empresas = $con->query($sql_empresas);
?>

<?php
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

<section class="company-header">
        <button class="btn btn-success" data-toggle="modal" data-target="#addCompanyModal" style="float: right; margin: 10px;">
            Add Company
        </button><br/>
    </section><br/>

    <div class="modal fade" id="addCompanyModal" tabindex="-1" role="dialog" aria-labelledby="addCompanyModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCompanyModalLabel">Add New Company</h5>
            </div>
            <form action="add_company.php" method="POST">
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <input type="text" name="nombre_empresa" class="form-control" placeholder="Nombre de la empresa" required>
                    </div>
                    <div class="form-group mb-3">
                        <input type="text" name="telefono" class="form-control" placeholder="Teléfono" required>
                    </div>
                    <div class="form-group mb-3">
                        <input type="text" name="pagina_web" class="form-control" placeholder="Página web" required>
                    </div>
                    <div class="form-group mb-3">
                        <input type="email" name="correo_empresa" class="form-control" placeholder="Correo de la empresa" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add Company</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editCompanyModal" tabindex="-1" role="dialog" aria-labelledby="editCompanyModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCompanyLabel">Edit Customer</h5>
            </div>
            <form action="edit_company.php" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="id_empresa" id="edit_id_empresa">
                    
                    <div class="form-group mb-3">
                        <label for="edit_nombre_empresa">First Name</label>
                        <input type="text" name="nombre_empresa" id="edit_nombre_empresa" class="form-control" placeholder="Name of Company" required>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="edit_telefono">Phone Number</label>
                        <input type="text" name="telefono" id="edit_telefono" class="form-control" placeholder="Enter phone number" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="edit_pagina_web">Page web</label>
                        <input type="text" name="pagina_web" id="edit_pagina_web" class="form-control" placeholder="Web page" required>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="edit_correo_empresa">Email</label>
                        <input type="email" name="correo_empresa" id="edit_correo_empresa" class="form-control" placeholder="Enter the company email address" required>
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

<!-- Modal para añadir dirección de la empresa -->
<div class="modal fade" id="addCompanyAddressModal" tabindex="-1" role="dialog" aria-labelledby="addCompanyAddressModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCompanyAddressLabel">Add Company Address</h5>
            </div>
            <form action="add_company_address.php" method="POST">
                <div class="modal-body">
                <div class="form-group mb-3">
                        <label for="id_empresa">Seleccione una empresa</label>
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
                    <button type="submit" class="btn btn-primary">Add Company Address</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para editar dirección de la empresa -->
<div class="modal fade" id="editCompanyAddressModal" tabindex="-1" role="dialog" aria-labelledby="editCompanyAddressModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCompanyAddressLabel">Edit Customer Address</h5>
            </div>
            <form action="edit_company_address.php" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="id_direccion_empresa" id="edit_id_direccion_empresa">
                    <div class="form-group mb-3">
                        <label for="edit_id_empresaa">Seleccione una empresa</label>
                        <select name="id_empresa" id="edit_id_empresaa" class="form-control" required>
                            <option value="">Seleccione una empresa</option>
                            <?php
                            
                            if ($result_empresas->num_rows > 0) {
                                while ($empresa = $result_empresas->fetch_assoc()) {
                                    echo "<option value='" . htmlspecialchars($empresa['id_empresa']) . "'";
                                    echo " id='option_" . htmlspecialchars($empresa['id_empresa']) . "'>";
                                    echo htmlspecialchars($empresa['nombre_empresa']);
                                    echo "</option>";
                                }
                            } else {
                                echo "<option value=''>No hay empresas disponibles</option>";
                            }
                            ?>
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
                <h2 class="text-center">Manage Company</h2><br/>
                <tr>
                    <th>Company ID</th>
                    <th>Name of company</th>
                    <th>Phone</th>
                    <th>Web Page</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM empresa";
                $result = $con->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['id_empresa']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['nombre_empresa']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['telefono']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['pagina_web']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['correo_empresa']) . "</td>";
                        echo "<td>";
                        echo "<button class='btn btn-info btn-sm me-1' onclick='openEditModal(" . json_encode($row) . ")' title='Editar Empresa'>
                                <i class='fas fa-edit'></i>
                            </button>";
                        echo "<a href='delete_company.php?id=" . $row['id_empresa'] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"¿Estás seguro de que deseas eliminar esta empresa?\")' title='Eliminar Empresa'>
                                <i class='fas fa-trash'></i>
                            </a>";
                        echo "</td>";

                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No hay empresas registradas.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </section><br/>

    <section>
    <button class="btn btn-info" data-toggle="modal" data-target="#addCompanyAddressModal" style="float: right; margin: 10px;">
        Add Company Address
    </button>
</section><br/>

    <section>
    <table class="table">
        <thead class="thead-dark">
        <h2 class="text-center">Manage Company Address</h2><br/>
            <tr>
                <th>Company ID</th>
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
            $sql = "SELECT * FROM direccion_empresa";
            $result = $con->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['id_empresa']) . "</td>";
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
    function openEditModal(customerData) {
    $('#edit_id_empresa').val(customerData.id_empresa);
    $('#edit_nombre_empresa').val(customerData.nombre_empresa);
    $('#edit_telefono').val(customerData.telefono);
    $('#edit_pagina_web').val(customerData.pagina_web);
    $('#edit_correo_empresa').val(customerData.correo_empresa);

    $('#editCompanyModal').modal('show');
}

function openEditCustomerAddressModal(customerData) {
        $('#edit_id_direccion_cliente').val(customerData.id_direccion_empresa);
        $('#hidden_id_empresa').val(customerData.id_cliente);
        $('#edit_id_empresaa').val(customerData.id_empresaa);
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
