<?php require '../Login/conexion.php'; ?>
<?php require '../Administrador/superior_admin.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Family Drywall</title>
    <link rel="stylesheet" href="../Css/style_edit.css">
</head>
<body>
    <button>
        
    </button>
    <section class="client-table">
        <h2>Customers</h2>
        <table>
        <thead>
        <tr>
            <th>Name</th>
            <th>Last Name</th>
            <th>Mother's Last Name</th>
            <th>Phone</th>
            <th>Email</th>
            <th>Actions</th>
        </tr>
        </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM clientes WHERE rol = 'usuario'";
                $result = $con->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['nombre_cliente']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['apellido_paterno']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['apellido_materno']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['telefono_personal']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['correo_electronico']) . "</td>";
                        echo "<td>
                <a href='edit.php?id=" . $row['id_cliente'] . "' class='edit-btn' aria-label='Edit'>
                    <i class='fas fa-edit'></i>
                </a>
                <a href='delete.php?id=" . $row['id_cliente'] . "' class='delete-btn' onclick='return confirm(\"Are you sure you want to delete this client?\")' aria-label='Delete'>
                    <i class='fas fa-trash'></i>
                </a>
              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>No hay clientes registrados.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </section>
</body>
</html>
