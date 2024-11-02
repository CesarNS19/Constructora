<?php require '../Login/conexion.php'; ?>

<?php
require '../Administrador/superior_admin.php';

if (isset($_POST['status_action'])) {
    $accion = $_POST['status_action'];
    if ($accion === 'activate') {
        $_SESSION['status_message'] = 'Empleado activado correctamente';
        $_SESSION['status_type'] = 'success';
    } else {
        $_SESSION['status_message'] = 'Empleado desactivado correctamente';
        $_SESSION['status_type'] = 'warning';
    }
    header("Location: employee.php");
    exit();
}
?>

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
        </button>
    </section>

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

<section>
        <table class="table">
            <thead class="thead-dark">
                <tr>
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
