<?php 
require '../Customers/superior_customer.php'; 
require '../Login/conexion.php';

$query = "SELECT id_servicio, nombre_servicio, descripcion_servicio, imagen_servicio FROM servicios LIMIT 3";
$result = $con->query($query);

$servicios = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $servicios[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Our Services</title>
    <link rel="stylesheet" href="../path/to/fontawesome/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <section class="hero" data-lang="en">
        <div class="hero-content fade-in">
            <h1>Welcome to Our Website</h1>
            <p>Discover our services and offerings.</p>
            <button id="learnMoreButton" class="button-3d">Learn More</button>
        </div>
    </section>

    <section class="hero" data-lang="es" style="display:none;">
        <div class="hero-content fade-in">
            <h1>Bienvenido a Nuestro Sitio Web</h1>
            <p>Descubre nuestros servicios y ofertas.</p>
            <button id="learnMoreButton" class="button-3d">Aprender Más</button>
        </div>
    </section>

    <section class="plans" data-lang="en">
        <h2 class="text-center my-4">Our Services</h2>
        <div class="container">
            <div class="row">
                <?php if (!empty($servicios)): ?>
                    <?php foreach ($servicios as $servicio): ?>
                        <div class="col-md-4 mb-4 d-flex align-items-stretch">
                            <div class="card text-center h-100 shadow">
                                <img src="<?php echo htmlspecialchars($servicio['imagen_servicio']); ?>" 
                                     class="card-img-top img-fluid" 
                                     alt="<?php echo htmlspecialchars($servicio['nombre_servicio']); ?>" 
                                     style="height: 100px; object-fit: cover;">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo htmlspecialchars($servicio['nombre_servicio']); ?></h5>
                                    <p class="card-text"><?php echo htmlspecialchars($servicio['descripcion_servicio']); ?></p>
                                    <a href="https://www.example.com" target="_blank" class="btn btn-primary">Learn More</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-center">No services found.</p>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <section class="plans" data-lang="es" style="display:none;">
        <h2 class="text-center my-4">Servicios</h2>
        <div class="container">
            <div class="row">
                <?php if (!empty($servicios)): ?>
                    <?php foreach ($servicios as $servicio): ?>
                        <div class="col-md-4 mb-4 d-flex align-items-stretch">
                            <div class="card text-center h-100 shadow">
                                <img src="<?php echo htmlspecialchars($servicio['imagen_servicio']); ?>" 
                                     class="card-img-top img-fluid" 
                                     alt="<?php echo htmlspecialchars($servicio['nombre_servicio']); ?>" 
                                     style="height: 100px; object-fit: cover;">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo htmlspecialchars($servicio['nombre_servicio']); ?></h5>
                                    <p class="card-text"><?php echo htmlspecialchars($servicio['descripcion_servicio']); ?></p>
                                    <a href="https://www.example.com" target="_blank" class="btn btn-primary">Leer más</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-center">No hay servicios.</p>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <footer data-lang="en">
        <p>© Family Drywall. All rights reserved.</p>
    </footer>

    <footer data-lang="es" style="display:none;">
        <p>© Family Drywall. Todos los derechos reservados.</p>
    </footer>    

    <script src="../Js/script.js"></script>
</body>
</html>

<?php
$con->close();
?>
