<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_empleado']) && isset($_POST['dias_trabajados'])) {
    // Sanitizar y obtener el ID del empleado
    $idEmpleado = intval($_POST['id_empleado']);
    
    // Obtener el total de días trabajados
    $diasTrabajados = intval($_POST['dias_trabajados']); // Obtenemos el total de días seleccionados como un número entero
    
    // Verificamos que el número de días trabajados sea válido (no negativo)
    if ($diasTrabajados < 0 || $diasTrabajados > 7) {
        $_SESSION['status_message'] = 'El número de días trabajados no es válido';
        $_SESSION['status_type'] = 'error';
        header('Location: employee.php');
        exit();
    }

    // Actualizar los días trabajados en la base de datos
    $sql = "UPDATE empleados SET dias_trabajados = $diasTrabajados WHERE id_empleado = $idEmpleado";

    if ($con->query($sql) === TRUE) {
        // Si la actualización es exitosa, redirigir con un mensaje de éxito
        $_SESSION['status_message'] = 'Días trabajados actualizados correctamente';
        $_SESSION['status_type'] = 'success';
    } else {
        // Si ocurre un error, redirigir con un mensaje de error
        $_SESSION['status_message'] = 'Hubo un problema al actualizar los días trabajados';
        $_SESSION['status_type'] = 'error';
    }

    // Redirigir de vuelta a la página de empleados para mostrar la alerta
    header('Location: employee.php');
    exit();
}
?>
