<?php
require '../Login/conexion.php';
require '../Administrador/superior_admin.php';
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
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addServicesModal" style="float: right; margin: 10px;">
            Add Service
        </button><br/>
    </section><br/>

    <div class="modal fade" id="addServicesModal" tabindex="-1" role="dialog" aria-labelledby="addServicesModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addServicesModalLabel">Agregar Nuevo Servicio</h5>
            </div>
            <form action="add_service.php" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <input type="text" name="nombre_servicio" class="form-control" placeholder="Name of service" required>
                    </div>
                    <div class="form-group mb-3">
                        <textarea name="descripcion_servicio" class="form-control" placeholder="Description of the service" required></textarea>
                    </div>
                    <div class="form-group mb-3">
                        <input type="file" name="imagen_servicio" class="form-control" placeholder="Service Image">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Agregar Servicio</button>
                </div>
            </form>
        </div>
    </div>
</div>

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
                        <label for="edit_nombre_servicio">Name of Service</label>
                        <input type="text" name="nombre_servicio" id="edit_nombre_servicio" class="form-control" placeholder="Enter Name of Service" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="edit_descripcion_servicio">Description of the Service</label>
                        <textarea name="descripcion_servicio" class="form-control" id="edit_descripcion_servicio" placeholder="Description of the service" required></textarea>
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
    <section class="services-table">
        <table class="table">
            <thead class="thead-dark">
                <tr>
                    <h2 class="text-center">Manage Services</h2><br/>
                    <th>Service ID</th>
                    <th>Name of Service</th>
                    <th>Description of the Service</th>
                    <th>Service Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM servicios";
                $result = $con->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['id_servicio']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['nombre_servicio']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['descripcion_servicio']) . "</td>";
                        echo "<td><img src='../Img/" . htmlspecialchars($row['imagen_servicio']) . "' width='100px' height='60px' alt='Service Image'></td>";
                        echo "<td>";
                        echo "<button class='btn btn-info btn-sm me-1' onclick='openEditModal(" . json_encode($row) . ")' title='Editar servicio'>
                                <i class='fas fa-edit'></i>
                            </button>
                            <a href='delete_service.php?id=" . $row['id_servicio'] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"¿Estás seguro de que deseas eliminar este servicio?\")' title='Eliminar Servicio'>
                                <i class='fas fa-trash'></i>
                            </a>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No hay servicios registrados.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </section>

    <script>

    function openEditModal(serviceData) {
        $('#edit_id_servicio').val(serviceData.id_servicio);
        $('#edit_nombre_servicio').val(serviceData.nombre_servicio);
        $('#edit_descripcion_servicio').val(serviceData.descripcion_servicio);
        $('#current_image').attr('src', '../Img/' + serviceData.imagen_servicio);
        
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