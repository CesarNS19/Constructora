<?php require '../Login/conexion.php'; ?>
<?php require '../Administrador/superior_admin.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
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

<section>
        <table class="table">
            <thead class="thead-dark">
                <tr>
                    <th>Name of company</th>
                    <th>Phone</th>
                    <th>Page web</th>
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
                        echo "<td>
                            <a href='edit_company.php?id=" . $row['id_empresa'] . "' class='btn btn-warning btn-sm'>Edit</a>
                            <a href='delete_company.php?id=" . $row['id_empresa'] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure you want to delete this company?\")'>Delete</a>
                        </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='11'>No hay empresas registrados.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </section>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>