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
<html lang="es">
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
            <p>Discover our services and offerings</p>
            <button id="learnMoreButton" class="button-3d">Learn More</button>
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
                                    <a href="https://www.example.com" target="_blank" class="btn btn-primary mb-2">Learn More</a>
                                    <!-- Modal trigger for Cotizar -->
                                    <button type="button" class="btn btn-secondary" data-bs-toggle="modal" 
                                        data-bs-target="#cotizarModal" 
                                        data-id-servicio="<?php echo $servicio['id_servicio']; ?>">
                                        Cotizar
                                    </button>
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

    <!-- Modal for Cotizar -->
    <div class="modal fade" id="cotizarModal" tabindex="-1" aria-labelledby="cotizarModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cotizarModalLabel">Cotizar Servicio</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="cotizarModalContent">
                    <!-- Content will be loaded here dynamically -->
                    Cargando...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <footer data-lang="en">
        <p>© Family Drywall. All rights reserved</p>
    </footer>

    <script src="../Js/script.js"></script>
    <script>
        // JavaScript to load content dynamically into the modal
        var cotizarModal = document.getElementById('cotizarModal');
        cotizarModal.addEventListener('show.bs.modal', function (event) {
            // Extract service ID from data-bs-* attribute
            var button = event.relatedTarget;
            var servicioId = button.getAttribute('data-id-servicio');
            
            var modalContent = document.getElementById('cotizarModalContent');
            modalContent.innerHTML = 'Cargando...'; // Show loading message

            // Fetch the cotizar.php content via AJAX
            fetch('cotizar.php?id_servicio=' + servicioId)
                .then(response => response.text())
                .then(data => {
                    modalContent.innerHTML = data; // Replace loading message with cotizar content
                })
                .catch(error => {
                    modalContent.innerHTML = 'Error al cargar el contenido.';
                });
        });
    </script>
</body>
</html>

<?php
$con->close();
?>
