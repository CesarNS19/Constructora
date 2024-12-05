<?php
require '../Login/conexion.php';
require '../Administrador/superior_admin.php';
?>

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

<section class="company-header">
        <a href="../Administrador/company_address.php" class="btn btn-primary" style="float: right; margin: 10px;">
            View Addresses
        </a>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addCompanyModal" style="float: right; margin: 10px;">
            Add Company
        </button><br/>
    </section><br/>

<!-- Modal para añadir empresa -->
<div class="modal fade" id="addCompanyModal" tabindex="-1" role="dialog" aria-labelledby="addCompanyModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCompanyModalLabel">Add New Company</h5>
            </div>
            <form action="add_company.php" method="POST">
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label>Company Name</label>
                        <input type="text" name="nombre_empresa" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label>Phone</label>
                        <input type="text" name="telefono" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label>Web Page</label>
                        <input type="text" name="pagina_web" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label>Email</label>
                        <input type="email" name="correo_empresa" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add Company</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para editar empresa -->
<div class="modal fade" id="editCompanyModal" tabindex="-1" role="dialog" aria-labelledby="editCompanyModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCompanyLabel">Edit Company</h5>
            </div>
            <form action="edit_company.php" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="id_empresa" id="edit_id_empresa">
                    
                    <div class="form-group mb-3">
                        <label for="edit_nombre_empresa">Company Name</label>
                        <input type="text" name="nombre_empresa" id="edit_nombre_empresa" class="form-control" placeholder="Name of Company" required>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="edit_telefono">Phone Number</label>
                        <input type="text" name="telefono" id="edit_telefono" class="form-control" placeholder="Enter phone number" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="edit_pagina_web">Web Page</label>
                        <input type="text" name="pagina_web" id="edit_pagina_web" class="form-control" placeholder="Web page" required>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="edit_correo_empresa">Email</label>
                        <input type="email" name="correo_empresa" id="edit_correo_empresa" class="form-control" placeholder="Enter the company email address" required>
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

<!-- Modal para añadir dirección de la empresa -->
<div class="modal fade" id="addCompanyAddressModal" tabindex="-1" role="dialog" aria-labelledby="addCompanyAddressModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCompanyAddressLabel">Add Company Address</h5>
            </div>
            <form action="add_company_address.php" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="id_empresa" id="id_empresa_modal">
                    <div class="form-group mb-3">
                        <label>Selected Company</label>
                        <input type="text" id="nombre_empresa_modal" class="form-control" readonly>
                    </div>
                    <div class="form-group mb-3">
                        <label for="num_ext">Outside Number</label>
                        <input type="number" name="num_ext" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="num_int">Inner Number</label>
                        <input type="number" name="num_int" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="calle">Street</label>
                        <input type="text" name="calle" class="form-control" required>
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
                    <button type="submit" class="btn btn-primary">Add Company Address</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Tabla de empresas -->
<section class="services-table container my-2"><br/>
<div class="table-responsive">
    <table class="table table-bordered table-hover text-center">
        <thead class="thead-dark">
            <h2 class="text-center">Manage Company</h2><br/>
            <tr>
                <th>Company Name</th>
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
                    echo "<td>" . htmlspecialchars($row['nombre_empresa']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['telefono']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['pagina_web']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['correo_empresa']) . "</td>";
                    echo "<td>";
                    echo "<button class='btn btn-info btn-sm me-1' onclick='openEditModal(" . json_encode($row) . ")' title='Edit Company'>
                            <i class='fas fa-edit'></i>
                        </button>";
                    echo "<a href='delete_company.php?id=" . $row['id_empresa'] . "' class='btn btn-danger btn-sm me-1' onclick='return confirm(\"¿Estás seguro de que deseas eliminar esta empresa?\")' title='Delete Company'>
                            <i class='fas fa-trash'></i>
                        </a>";
                    echo "<button class='btn btn-success btn-sm' onclick='openAddAddressModal(" . json_encode($row) . ")' title='Add Address'>
                            <i class='fas fa-plus'></i>
                        </button>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>There are no company recorded.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>
</section>

<script>
    function openEditModal(customerData) {
    $('#edit_id_empresa').val(customerData.id_empresa);
    $('#edit_nombre_empresa').val(customerData.nombre_empresa);
    $('#edit_telefono').val(customerData.telefono);
    $('#edit_pagina_web').val(customerData.pagina_web);
    $('#edit_correo_empresa').val(customerData.correo_empresa);

    $('#editCompanyModal').modal('show');
}

function openAddAddressModal(empresa) {
    document.getElementById('id_empresa_modal').value = empresa.id_empresa;
    document.getElementById('nombre_empresa_modal').value = empresa.nombre_empresa;
    $('#addCompanyAddressModal').modal('show');
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
