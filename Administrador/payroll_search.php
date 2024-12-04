<?php
require '../Login/conexion.php';
require '../Administrador/superior_admin.php';

$sql_empleados = "SELECT id_empleado, nombre, apellido_paterno, apellido_materno FROM empleados";
$result_empleados = $con->query($sql_empleados);

$sql = "SELECT n.id_nomina, e.nombre, e.apellido_paterno, e.apellido_materno, n.fecha, n.sueldo_diario, n.dias_trabajados, n.total
        FROM nomina n
        JOIN empleados e ON n.id_empleado = e.id_empleado";

if (isset($_POST['buscar'])) {
    $id_empleado = $_POST['id_empleado'];
    $fecha = $_POST['fecha'];
    $busqueda_tipo = $_POST['busqueda_tipo'];

    $conditions = [];

    if ($id_empleado != "") {
        $conditions[] = "n.id_empleado = '$id_empleado'";
    }

    if ($busqueda_tipo == "mes" && !empty($fecha)) {
        $mes = date('m', strtotime($fecha));
        $year = date('Y', strtotime($fecha));
        $conditions[] = "MONTH(n.fecha) = '$mes' AND YEAR(n.fecha) = '$year'";
    } elseif ($busqueda_tipo == "semana" && !empty($fecha)) {
        $year = date('Y', strtotime($fecha));
        $mes = date('m', strtotime($fecha));
        $semana = intval($_POST['semana']);
        $primer_dia_mes = new DateTime("$year-$mes-01");
        $primer_dia_mes_semana = $primer_dia_mes->format('N');
        $ajuste_inicio = (7 - $primer_dia_mes_semana + 1) % 7;
        $inicio_semana = clone $primer_dia_mes;
        $inicio_semana->modify("+".($ajuste_inicio + ($semana - 1) * 7)." days");
        $fin_semana = clone $inicio_semana;
        $fin_semana->modify("+6 days");
        $conditions[] = "n.fecha BETWEEN '" . $inicio_semana->format('Y-m-d') . "' AND '" . $fin_semana->format('Y-m-d') . "'";
    }

    if (count($conditions) > 0) {
        $sql .= " WHERE " . implode(" AND ", $conditions);
    }
}

$result = $con->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payroll</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

<div id="Alert"></div>
<a href="../Administrador/payroll.php" class="btn btn-primary" style="float: right; margin: 10px;">Back</a><br>

<!-- Formulario para búsqueda de nóminas -->
<form action="" method="POST" class="container mt-5">
    <h3>Search Payroll</h3>
    <div class="row">
        <div class="col-md-4">
            <label for="search_id_empleado">Select Employee</label>
            <select name="id_empleado" id="search_id_empleado" class="form-control">
                <option value="">Select Employee</option>
                <?php
                if ($result_empleados->num_rows > 0) {
                    while ($empleados = $result_empleados->fetch_assoc()) {
                        $selected = (isset($_POST['id_empleado']) && $_POST['id_empleado'] == $empleados['id_empleado']) ? "selected" : "";
                        $nombre_completo = htmlspecialchars($empleados['nombre'] . ' ' . $empleados['apellido_paterno'] . ' ' . $empleados['apellido_materno']);
                        echo "<option value='" . htmlspecialchars($empleados['id_empleado']) . "' $selected>" . $nombre_completo . "</option>";
                    }
                } else {
                    echo "<option value=''>No employees available</option>";
                }
                ?>
            </select>
        </div>
        <div class="col-md-4">
            <label for="busqueda_tipo">Search by:</label>
            <select name="busqueda_tipo" id="busqueda_tipo" class="form-control" onchange="toggleDateField()" required>
                <option value="empleado" <?= isset($_POST['busqueda_tipo']) && $_POST['busqueda_tipo'] == "empleado" ? "selected" : "" ?>>Search by:</option>
                <option value="mes" <?= isset($_POST['busqueda_tipo']) && $_POST['busqueda_tipo'] == "mes" ? "selected" : "" ?>>Month</option>
                <option value="semana" <?= isset($_POST['busqueda_tipo']) && $_POST['busqueda_tipo'] == "semana" ? "selected" : "" ?>>Week</option>
            </select>
        </div>
        <div class="col-md-4">
            <label for="fecha">Select Date:</label>
            <input type="month" id="fecha" name="fecha" class="form-control" value="<?= isset($_POST['fecha']) ? htmlspecialchars($_POST['fecha']) : date('Y-m') ?>">
        </div>
        <div class="col-md-4" id="semana_field" style="display:none;">
            <label for="semana">Select Week:</label>
            <select name="semana" id="semana" class="form-control">
                <?php for ($i = 1; $i <= 4; $i++): ?>
                    <option value="<?= $i ?>" <?= isset($_POST['semana']) && $_POST['semana'] == $i ? "selected" : "" ?>>Week <?= $i ?></option>
                <?php endfor; ?>
            </select>
        </div>
        <div class="col-md-4">
            <button type="submit" name="buscar" class="btn btn-primary mt-4">Search</button>
        </div>
    </div>
</form><br>

<!-- Tabla con los resultados de la búsqueda -->
<section class="services-table container my-2"><br>
    <div class="table-responsive">
        <table class="table table-bordered table-hover text-center">
            <thead class="thead-dark">
                <h2 class="text-center">Payrolls</h2><br>
                <tr>
                    <th>Employee Name</th>
                    <th>Date</th>
                    <th>Daily Salary</th>
                    <th>Days Worked</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (isset($_POST['buscar']) && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $nombre_completo = htmlspecialchars($row['nombre'] . ' ' . $row['apellido_paterno'] . ' ' . $row['apellido_materno']);
                        echo "<tr>";
                        echo "<td>" . $nombre_completo . "</td>";
                        echo "<td>" . htmlspecialchars($row['fecha']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['sueldo_diario']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['dias_trabajados']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['total']) . "</td>";
                        echo "</tr>";
                    }
                } elseif (!isset($_POST['buscar'])) {
                    echo "<tr><td colspan='5'>Please perform a search to see payroll results.</td></tr>";
                } else {
                    echo "<tr><td colspan='5'>No payrolls found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</section>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        toggleDateField();
    });

    function toggleDateField() {
        const busquedaTipo = document.getElementById('busqueda_tipo').value;
        const semanaField = document.getElementById('semana_field');
        
        if (busquedaTipo === 'semana') {
            semanaField.style.display = 'block';
        } else {
            semanaField.style.display = 'none';
        }
    }
</script>

</body>
</html>
