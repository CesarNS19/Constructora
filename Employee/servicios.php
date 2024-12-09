<?php
require '../Login/conexion.php';
require '../Employee/superior_employee.php';
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
<a href="../Employee/categorias_servicios.php" class="btn btn-primary" style="float: right; margin: 10px;">
            Service Category
        </a>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addServicesModal" style="float: right; margin: 10px;">
            Add Service
        </button><br/>
    </section><br/>

<!-- Modal para añadir servicio -->
<div class="modal fade" id="addServicesModal" tabindex="-1" role="dialog" aria-labelledby="addServicesModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addServicesModalLabel">Add New Service</h5>
            </div>
            <form action="add_service.php" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                <div class="form-group mb-3">
                    <label for="categoria_servicio">Service Category</label>
                    <select name="id_categoria" class="form-control" required>
                        <?php
                        $categoria_sql = "SELECT * FROM categorias_servicios";
                        $categoria_result = $con->query($categoria_sql);

                        while ($categoria = $categoria_result->fetch_assoc()) {
                            echo "<option value='" . $categoria['id_categoria'] . "'>" . htmlspecialchars($categoria['categoria']) . "</option>";
                        }
                        ?>
                    </select>
                </div>
                    <div class="form-group mb-3">
                        <label for="">Name of Service</label>
                        <input type="text" name="nombre_servicio" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="">Description of the Service</label>
                        <textarea name="descripcion_servicio" class="form-control" required></textarea>
                    </div>
                    <div class="form-group mb-3">
                        <label for="">Total Service</label>
                        <input type="number" name="total" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <input type="file" name="imagen_servicio" class="form-control" placeholder="Service Image">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add Service</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para editar servicio -->
<div class="modal fade" id="editServicesModal" tabindex="-1" role="dialog" aria-labelledby="editServicesLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editServicesLabel">Edit Service</h5>
            </div>
            <form action="edit_services.php" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="id_servicio" id="edit_id_servicio">
                    
                    <div class="form-group mb-3">
                        <label for="edit_categoria_servicio">Service Category</label>
                        <select name="id_categoria" id="edit_categoria_servicio" class="form-control" required>
                            <?php
                            $categoria_sql = "SELECT * FROM categorias_servicios";
                            $categoria_result = $con->query($categoria_sql);

                            while ($categoria = $categoria_result->fetch_assoc()) {
                                echo "<option value='" . $categoria['id_categoria'] . "'>" . htmlspecialchars($categoria['categoria']) . "</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label for="edit_nombre_servicio">Name of Service</label>
                        <input type="text" name="nombre_servicio" id="edit_nombre_servicio" class="form-control" placeholder="Enter Name of Service" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="edit_descripcion_servicio">Description of the Service</label>
                        <textarea name="descripcion_servicio" class="form-control" id="edit_descripcion_servicio" placeholder="Description of the service" required></textarea>
                    </div>

                    <div class="form-group mb-3">
                        <label for="edit_total">Total Service</label>
                        <input type="number" name="total" id="edit_total" class="form-control" placeholder="Enter Total Service" required>
                    </div>

                    <div class="form-group mb-3">
                        <label>Current Image</label>
                        <div>
                            <img id="current_image" src="" width="100" alt="Service Image">
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="edit_imagen_servicio">Update Service Image</label>
                        <input type="file" name="imagen_servicio" id="edit_imagen_servicio" class="form-control">
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
<section class="services-table container my-4">
    <h2 class="text-center mb-4">Manage Services</h2>
    <div class="table-responsive">
        <table class="table table-bordered table-hover text-center">
            <thead class="thead-dark">
                <tr>
                    <th>Name of Service</th>
                    <th>Service Category</th>
                    <th>Description of the Service</th>
                    <th>Total Service</th>
                    <th>Service Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT servicios.*, categorias_servicios.categoria FROM servicios 
                JOIN categorias_servicios ON servicios.id_categoria = categorias_servicios.id_categoria";
                    $result = $con->query($sql);
                    
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['nombre_servicio']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['categoria']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['descripcion_servicio']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['total']) . "</td>";
                            echo "<td><img src='../Img/" . htmlspecialchars($row['imagen_servicio']) . "' width='100px' height='60px' alt='Service Image'></td>";
                            echo "<td>";
                            echo "<button class='btn btn-info btn-sm me-1' onclick='openEditModal(" . json_encode($row) . ")' title='Edit Service'>
                                    <i class='fas fa-edit'></i>
                                </button>
                                <a href='delete_service.php?id=" . $row['id_servicio'] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"¿Estás seguro de que deseas eliminar este servicio?\")' title='Delete Service'>
                                    <i class='fas fa-trash'></i>
                                </a>";
                            echo "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6'>There are no services recorded.</td></tr>";
                    }
                ?>
            </tbody>
        </table>
    </div>
</section>

<script>

function openEditModal(serviceData) {
    $('#edit_id_servicio').val(serviceData.id_servicio);
    $('#edit_nombre_servicio').val(serviceData.nombre_servicio);
    $('#edit_descripcion_servicio').val(serviceData.descripcion_servicio);
    $('#edit_total').val(serviceData.total);
    $('#current_image').attr('src', '../Img/' + serviceData.imagen_servicio);

    $('#edit_categoria_servicio').val(serviceData.id_categoria);

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