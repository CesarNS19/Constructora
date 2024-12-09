<?php
require '../Login/conexion.php';
require '../Employee/superior_employee.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Work</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

<div id="Alert"></div>

<section>
    <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px;">
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addServicesModal">
            Add Service Category
        </button>
        <a href="../Employee/servicios.php" class="btn btn-primary">
            Back
        </a>
    </div>
</section>


<!-- Modal para agregar tipos de servicios -->
<div class="modal fade" id="addServicesModal" tabindex="-1" role="dialog" aria-labelledby="addServicesModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addServicesModalLabel">Add Service Category</h5>
            </div>
            <form action="add_service_type.php" method="POST">
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="nombre_servicio">Name of Category</label>
                        <input type="text" name="categoria" id="nombre_servicio" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="altura">Height</label>
                        <input type="number" name="altura" id="altura" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add Service Category</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para editar tipos de servicios -->
<div class="modal fade" id="editServicesModal" tabindex="-1" role="dialog" aria-labelledby="editServicesLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editServicesLabel">Edit Service Category</h5>
            </div>
            <form action="edit_services_type.php" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="id_categoria" id="edit_id_categoria">

                    <div class="form-group mb-3">
                        <label for="edit_categoria">Name of Category</label>
                        <input type="text" name="categoria" id="edit_categoria" class="form-control" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="edit_altura">Height</label>
                        <input type="number" name="altura" id="edit_altura" class="form-control" required>
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

 <!-- Tabla de Servicios -->
 <section class="container my-4">
    <h2 class="text-center mb-4">Manage Services Category</h2>
    <div class="table-responsive">
        <table class="table table-bordered table-hover text-center">
            <thead class="thead-dark">
                <tr>
                    <th>Name of Category</th>
                    <th>Height</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM categorias_servicios";
                $result = $con->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['categoria']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['altura']) . "</td>";
                        echo "<td>";
                        echo "<button class='btn btn-info btn-sm me-1' onclick='openEditModal(" . json_encode($row) . ")' title='Edit Service Category'>
                                <i class='fas fa-edit'></i>
                              </button>
                              <a href='delete_service_type.php?id=" . $row['id_categoria'] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"¿Estás seguro de que deseas eliminar este servicio?\")' title='Delete Service Category'>
                                  <i class='fas fa-trash'></i>
                              </a>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>There are no service categories recorded.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</section>

<script>

function openEditModal(serviceData) {
    $('#edit_id_categoria').val(serviceData.id_categoria);
    $('#edit_categoria').val(serviceData.categoria);
    $('#edit_altura').val(serviceData.altura);    
    $('#editServicesModal').modal('show');
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