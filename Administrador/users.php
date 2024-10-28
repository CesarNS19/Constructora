<?php require '../Administrador/conexion.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Family Drywall</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <header>
        <nav>
            <!-- Botón de idioma -->
            <button class="language-toggle" id="languageButton" aria-label="Change Language">
                <i class="fas fa-globe"></i>
            </button>
            <ul class="nav-links">
                <li><a href="index.html"><i class="fas fa-home"></i></a></li>
                <li><a href="perfil.php"><i class="fas fa-user"></i></a></li>
                <li><a href="#services"><i class="fas fa-concierge-bell"></i></a></li>
                <li><a href="#contact"><i class="fas fa-envelope"></i></a></li>
                <li><a href="users.php"><i class="fas fa-users"></i></a></li>
            </ul>
            <button id="themeToggle"><i class="fas fa-adjust"></i></button>
        </nav>
    </header>
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
                $sql = "SELECT * FROM clientes";
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
