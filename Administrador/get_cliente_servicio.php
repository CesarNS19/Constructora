<?php
require '../Login/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_cliente'])) {
    $id_cliente = intval($_POST['id_cliente']);

    $query = "SELECT servicio, total_servicio 
              FROM presupuestos 
              WHERE id_cliente = ? 
              LIMIT 1"; // Usamos LIMIT 1 para devolver solo un registro

    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("i", $id_cliente);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            echo json_encode([
                'servicio' => $row['servicio'],
                'total_servicio' => $row['total_servicio']
            ]);
        } else {
            // No se encontró un registro
            echo json_encode(['error' => 'No se encontraron servicios para este cliente.']);
        }

        $stmt->close();
    } else {
        // Error en la preparación de la consulta
        echo json_encode(['error' => 'Error en la consulta.']);
    }
} else {
    // Petición no válida
    echo json_encode(['error' => 'Petición no válida.']);
}
?>

