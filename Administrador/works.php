<?php
require '../Login/conexion.php';

$sql_empresas = "SELECT id_empresa, nombre_empresa FROM empresa";
$result_empresas = $con->query($sql_empresas);

$sql_clientes = "SELECT id_cliente, nombre_cliente, apellido_paterno, apellido_materno FROM clientes WHERE rol = 'usuario'";
$result_clientes = $con->query($sql_clientes);

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
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addWorksModal" style="float: right; margin: 10px;">
            Add Works
        </button><br/>
    </section><br/>

<!-- Modal de Agregar Obra -->
<div class="modal fade" id="addWorksModal" tabindex="-1" role="dialog" aria-labelledby="addWorksModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addWorksModalLabel">Agregar Nueva Obra</h5>
            </div>
            <form action="add_work.php" method="POST">
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="id_empresa">Selecciona la Empresa</label>
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
                        <label for="id_cliente">Seleccione un cliente</label>
                        <select name="id_cliente" id="select_cliente" class="form-control" required>
                            <option value="">Seleccione un cliente</option>
                            <?php
                            if ($result_clientes->num_rows > 0) {
                                while ($clientes = $result_clientes->fetch_assoc()) {
                                    $nombre_completo = htmlspecialchars($clientes['nombre_cliente'] . ' ' . $clientes['apellido_paterno'] . ' ' . $clientes['apellido_materno']);
                                    
                                    $direccion_sql = "SELECT ciudad FROM direccion_cliente WHERE id_cliente = " . (int)$clientes['id_cliente'];
                                    $direccion_result = $con->query($direccion_sql);
                                    $direccion = $direccion_result->fetch_assoc();
                                    
                                    echo "<option value='" . htmlspecialchars($clientes['id_cliente']) . "' data-direccion='" . htmlspecialchars($direccion['ciudad']) . "'>" . $nombre_completo . "</option>";
                                }
                            } else {
                                echo "<option value=''>No hay clientes disponibles</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label for="direccion_cliente">Dirección del cliente</label>
                        <input type="text" id="direccion_cliente" class="form-control" readonly>
                        <input type="hidden" name="id_direccion_cliente" id="id_direccion_cliente">
                    </div>
                    <div class="form-group mb-3">
                        <input type="date" step="0.01" name="fecha_inicio" class="form-control" placeholder="Fecha de Inicio" required>
                    </div>
                    <div class="form-group mb-3">
                        <input type="number" name="anticipo" id="anticipo" class="form-control" placeholder="Anticipo" required>
                    </div>
                    <div class="form-group mb-3">
                        <input type="number" name="adeudo" id="adeudo" class="form-control" placeholder="Adeudo" required>
                    </div>
                    <div class="form-group mb-3">
                        <input type="number" name="total_obra" id="total_obra" class="form-control" placeholder="Total de la Obra" readonly>
                    </div>
                    <div class="form-group mb-3">
                        <textarea name="observaciones" class="form-control" placeholder="Observaciones" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Agregar Obra</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de Editar Obra -->
<div class="modal fade" id="editWorksModal" tabindex="-1" role="dialog" aria-labelledby="editWorksModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editWorksLabel">Editar Obras</h5>
            </div>
            <form action="edit_works.php" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="folio_obra" id="edit_folio_obra">
                    
                    <div class="form-group mb-3">
                        <label for="edit_fecha_inicio">Fecha de Inicio</label>
                        <input type="date" name="fecha_inicio" id="edit_fecha_inicio" class="form-control" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="edit_anticipo">Anticipo</label>
                        <input type="number" name="anticipo" id="edit_anticipo" class="form-control" placeholder="Ingresa el anticipo" required>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="edit_adeudo">Adeudo</label>
                        <input type="number" name="adeudo" id="edit_adeudo" class="form-control" placeholder="Ingresa el adeudo" required>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="edit_total_obra">Total de la Obra</label>
                        <input type="number" name="total_obra" id="edit_total_obra" class="form-control" readonly>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="edit_observaciones">Observaciones</label>
                        <input type="text" name="observaciones" id="edit_observaciones" class="form-control" placeholder="Ingresa las observaciones" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

    <!-- Tabla de Obras -->
    <section class="works-table"><br/>
    <table class="table">
        <thead class="thead-dark">
            <tr>
                <h2 class="text-center">Manage Works</h2><br/>
                <th>Company Name</th>
                <th>Customer Name</th>
                <th>Customer Address</th>
                <th>Start Date</th>
                <th>Advance Payment</th>
                <th>Debit</th>
                <th>Total Work</th>
                <th>Observations</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT o.*, e.nombre_empresa, c.nombre_cliente, c.apellido_paterno, c.apellido_materno, d.ciudad 
                    FROM obras o
                    LEFT JOIN empresa e ON o.id_empresa = e.id_empresa
                    LEFT JOIN clientes c ON o.id_cliente = c.id_cliente
                    LEFT JOIN direccion_cliente d ON o.id_direccion_cliente = d.id_direccion_cliente";
            $result = $con->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $nombre_cliente = htmlspecialchars($row['nombre_cliente'] . ' ' . $row['apellido_paterno'] . ' ' . $row['apellido_materno']);
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['nombre_empresa']) . "</td>";
                    echo "<td>" . $nombre_cliente . "</td>";
                    echo "<td>" . htmlspecialchars($row['ciudad']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['fecha_inicio']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['anticipo']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['adeudo']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['total_obra']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['observaciones']) . "</td>";
                    echo "<td>";
                    echo "<button class='btn btn-info btn-sm me-1' onclick='openEditModal(" . json_encode($row) . ")' title='Editar obra'>
                            <i class='fas fa-edit'></i>
                        </button>
                        <a href='delete_work.php?id=" . $row['folio_obra'] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"¿Estás seguro de que deseas eliminar esta obra?\")' title='Eliminar obra'>
                            <i class='fas fa-trash'></i>
                        </a>
                    </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='9'>No hay obras registradas.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</section>

    <script>

    function openEditModal(obraData) {
        $('#edit_folio_obra').val(obraData.folio_obra);
        $('#edit_fecha_inicio').val(obraData.fecha_inicio);
        $('#edit_anticipo').val(obraData.anticipo);
        $('#edit_adeudo').val(obraData.adeudo);
        $('#edit_total_obra').val(obraData.total_obra);
        $('#edit_observaciones').val(obraData.observaciones);

        calcularTotal();

        $('#editWorksModal').modal('show');
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

        $(document).ready(function () {
            $('select[name="id_cliente"]').on('change', function () {
                var id_cliente = $(this).val();

                if (id_cliente) {
                    $.ajax({
                        url: 'get_direccion_cliente.php',
                        type: 'POST',
                        data: { id_cliente: id_cliente },
                        success: function (data) {
                            var direccion = JSON.parse(data);
                            $('#direccion_cliente').val(direccion.ciudad);
                            $('#id_direccion_cliente').val(direccion.id_direccion_cliente);
                        }
                    });
                } else {
                    $('#direccion_cliente').val('');
                    $('#id_direccion_cliente').val('');
                }
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            function calcularTotalAgregar() {
                var anticipo = parseFloat(document.getElementById('anticipo').value) || 0;
                var adeudo = parseFloat(document.getElementById('adeudo').value) || 0;
                document.getElementById('total_obra').value = (anticipo + adeudo).toFixed(2);
            }

            function calcularTotalEditar() {
                var anticipo = parseFloat(document.getElementById('edit_anticipo').value) || 0;
                var adeudo = parseFloat(document.getElementById('edit_adeudo').value) || 0;
                document.getElementById('edit_total_obra').value = (anticipo + adeudo).toFixed(2);
            }

            if (document.getElementById('anticipo')) {
                document.getElementById('anticipo').addEventListener('input', calcularTotalAgregar);
            }
            if (document.getElementById('adeudo')) {
                document.getElementById('adeudo').addEventListener('input', calcularTotalAgregar);
            }

            if (document.getElementById('edit_anticipo')) {
                document.getElementById('edit_anticipo').addEventListener('input', calcularTotalEditar);
            }
            if (document.getElementById('edit_adeudo')) {
                document.getElementById('edit_adeudo').addEventListener('input', calcularTotalEditar);
            }

            window.openEditModal = function(obraData) {
                document.getElementById('edit_folio_obra').value = obraData.folio_obra;
                document.getElementById('edit_fecha_inicio').value = obraData.fecha_inicio;
                document.getElementById('edit_anticipo').value = obraData.anticipo;
                document.getElementById('edit_adeudo').value = obraData.adeudo;
                document.getElementById('edit_total_obra').value = (parseFloat(obraData.anticipo) + parseFloat(obraData.adeudo)).toFixed(2);
                document.getElementById('edit_observaciones').value = obraData.observaciones;

                $('#editWorksModal').modal('show');
            }
        });

    </script>
</body>
</html>