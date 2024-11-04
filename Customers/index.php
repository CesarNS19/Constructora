<?php 
require '../Customers/superior_customer.php'; 
require '../Login/conexion.php'; // Asegúrate de incluir tu conexión a la base de datos

// Consulta para obtener los servicios
$query = "SELECT id_servicio, nombre_servicio, descripcion_servicio, imagen_servicio FROM servicios";
$result = $con->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Our Services</title>
    <link rel="stylesheet" href="../path/to/fontawesome/css/all.min.css"> <!-- Asegúrate de incluir FontAwesome si usas iconos -->
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
        <h2>Our Services</h2>
        <div class="plan-cards">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="card fade-in">
                        <img src="<?php echo htmlspecialchars($row['imagen_servicio']); ?>" alt="<?php echo htmlspecialchars($row['nombre_servicio']); ?>" />
                        <h3><?php echo htmlspecialchars($row['nombre_servicio']); ?></h3>
                        <p><?php echo htmlspecialchars($row['descripcion_servicio']); ?></p>
                        <a href="https://www.example.com" target="_blank">
                            <button>Learn More</button>
                        </a>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No services found.</p>
            <?php endif; ?>
        </div>
    </section>
    
    <section class="plans" data-lang="es" style="display:none;">
        <h2>Servicios</h2>
        <div class="plan-cards">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="card fade-in">
                        <img src="<?php echo htmlspecialchars($row['imagen_servicio']); ?>" alt="<?php echo htmlspecialchars($row['nombre_servicio']); ?>" />
                        <h3><?php echo htmlspecialchars($row['nombre_servicio']); ?></h3>
                        <p><?php echo htmlspecialchars($row['descripcion_servicio']); ?></p>
                        <a href="https://www.example.com" target="_blank">
                            <button>Saber Más</button>
                        </a>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No se encontraron servicios.</p>
            <?php endif; ?>
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
$con->close(); // Cierra la conexión a la base de datos
?>
