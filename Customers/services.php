<?php 
require '../Customers/superior_customer.php'; 
require '../Login/conexion.php';

$query = "SELECT id_servicio, nombre_servicio, descripcion_servicio, imagen_servicio FROM servicios";
$result = $con->query($query);

$servicios = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $servicios[] = $row;
    }
}
?>

<div class="container mt-4">
    <div class="row">
        <?php foreach ($servicios as $servicio): ?>
            <div class="col-md-4">
                <div class="card mb-4 shadow-sm">
                    <img src="<?php echo $servicio['imagen_servicio']; ?>" class="card-img-top" alt="<?php echo $servicio['nombre_servicio']; ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $servicio['nombre_servicio']; ?></h5>
                        <p class="card-text"><?php echo $servicio['descripcion_servicio']; ?></p>
                        <a href="detalle_servicio.php?id=<?php echo $servicio['id_servicio']; ?>" class="btn btn-primary">Ver más</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
