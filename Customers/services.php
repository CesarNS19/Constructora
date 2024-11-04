<?php 
require '../Customers/superior_customer.php'; 
require '../Login/conexion.php'; 

// Manejar errores de conexión
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Establecer la paginación
$limit = 6; // Número de servicios por página
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Consultar los servicios
$query = "SELECT id_servicio, nombre_servicio, descripcion_servicio, imagen_servicio FROM servicios LIMIT $limit OFFSET $offset";
$result = $con->query($query);

// Contar el total de servicios para la paginación
$totalQuery = "SELECT COUNT(*) as total FROM servicios";
$totalResult = $con->query($totalQuery);
$totalRow = $totalResult->fetch_assoc();
$totalServices = $totalRow['total'];
$totalPages = ceil($totalServices / $limit);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuestros Servicios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<div class="container mt-5">
    <h2>Nuestros Servicios</h2>
    <div class="row">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="col-md-4">
                    <div class="card mb-4">
                        <img src="<?php echo htmlspecialchars($row['imagen_servicio']); ?>" alt="<?php echo htmlspecialchars($row['nombre_servicio']); ?>" class="card-img-top" style="height: 100px; object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($row['nombre_servicio']); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars($row['descripcion_servicio']); ?></p>
                            <a href="https://www.example.com" target="_blank" class="btn btn-primary">Saber Más</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No se encontraron servicios.</p>
        <?php endif; ?>
    </div>

    <!-- Paginación -->
    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?php echo ($i === $page) ? 'active' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$con->close();
?>
