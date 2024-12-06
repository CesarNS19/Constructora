<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles de Obra</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
        }
        .container-fluid {
            padding: 0;
        }
    </style>
</head>
<body class="bg-light">
    <!-- Archivo superior -->
    <?php
        require 'superior_customer.php';
    ?>

    <div class="container-fluid">
        <div class="container py-3">
            <?php
            require '../Login/conexion.php';

            // Supongamos que el ID del cliente logueado se guarda en la sesión
            $id_cliente = $_SESSION['id_cliente'];

            // Conexión con la base de datos
            $con = new mysqli($servername, $username, $password, $database);

            if ($con->connect_error) {
                die("<div class='alert alert-danger'>Conexión fallida: " . $con->connect_error . "</div>");
            }

            // Consulta SQL para obtener los datos solicitados
            $sql = "SELECT 
                        e.nombre_empresa, 
                        c.nombre_cliente, 
                        d.num_ext, 
                        d.num_int, 
                        d.calle, 
                        d.ciudad, 
                        d.estado, 
                        d.codigo_postal, 
                        s.nombre_servicio, 
                        p.fecha_inicio, 
                        p.anticipo, 
                        p.adeudo, 
                        p.total_obra, 
                        p.observaciones, 
                        p.estatus
                    FROM 
                        obras p
                    JOIN 
                        empresa e ON p.id_empresa = e.id_empresa
                    JOIN 
                        clientes c ON p.id_cliente = c.id_cliente
                    JOIN 
                        direcciones d ON d.id_cliente = c.id_cliente
                    JOIN 
                        servicios s ON p.id_servicio = s.id_servicio
                    WHERE 
                        p.id_cliente = ?";

            // Preparar la consulta
            $stmt = $con->prepare($sql);
            $stmt->bind_param("i", $id_cliente);
            $stmt->execute();
            $result = $stmt->get_result();

            // Mostrar resultados en una tabla HTML
            if ($result->num_rows > 0) {
                echo "<div class='table-responsive'>";
                echo "<table class='table table-striped table-hover align-middle'>";
                echo "<thead class='table-dark'>
                        <tr>
                            <th>Nombre Empresa</th>
                            <th>Nombre Cliente</th>
                            <th>Dirección</th>
                            <th>Nombre Servicio</th>
                            <th>Fecha Inicio</th>
                            <th>Anticipo</th>
                            <th>Adeudo</th>
                            <th>Total Obra</th>
                            <th>Observaciones</th>
                            <th>Estatus</th>
                        </tr>
                      </thead>";
                echo "<tbody>";
                while ($row = $result->fetch_assoc()) {
                    $direccion = "{$row['calle']} {$row['num_ext']}, Int. {$row['num_int']}, 
                                  {$row['ciudad']}, {$row['estado']}, CP: {$row['codigo_postal']}";
                    echo "<tr>
                            <td>" . htmlspecialchars($row['nombre_empresa']) . "</td>
                            <td>" . htmlspecialchars($row['nombre_cliente']) . "</td>
                            <td>" . htmlspecialchars($direccion) . "</td>
                            <td>" . htmlspecialchars($row['nombre_servicio']) . "</td>
                            <td>" . htmlspecialchars($row['fecha_inicio']) . "</td>
                            <td>" . htmlspecialchars($row['anticipo']) . "</td>
                            <td>" . htmlspecialchars($row['adeudo']) . "</td>
                            <td>" . htmlspecialchars($row['total_obra']) . "</td>
                            <td>" . htmlspecialchars($row['observaciones']) . "</td>
                            <td>" . htmlspecialchars($row['estatus']) . "</td>
                          </tr>";
                }
                echo "</tbody></table></div>";
            } else {
                echo "<div class='alert alert-warning'>No hay resultados para este cliente.</div>";
            }

            // Cerrar la conexión
            $stmt->close();
            $con->close();
            ?>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
