<?php
require '../Login/conexion.php';

if (isset($_POST['id_empleado'])) {
    $id = $_POST['id_empleado'];

    // Obtener datos actuales del cliente
    $sql = "SELECT * FROM empleados WHERE id_empleado = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $cliente = $result->fetch_assoc();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Obtener datos actualizados
        $nombre = $_POST['nombre'];
        $apellido_paterno = $_POST['apellido_paterno'];
        $apellido_materno = $_POST['apellido_materno'];
        $hora_entrada = $_POST['hora_entrada'];
        $hora_salida = $_POST['hora_salida'];
        $salario = $_POST['salario'];
        $telefono = $_POST['telefono_personal'];
        $email = $_POST['correo_personal'];
        $cargo = $_POST['cargo'];
        $actividades = $_POST['actividades'];
        $id_empresa = $_POST['id_empresa'];
    
        // Actualizar datos del cliente
        $sql = "UPDATE empleados SET nombre = ?, apellido_paterno = ?, apellido_materno = ?, hora_entrada = ?, hora_salida = ?, salario = ?, telefono_personal = ?, correo_personal = ?, cargo = ?, actividades = ?, id_empresa = ? WHERE id_empleado = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("sssssssssssi", $nombre, $apellido_paterno, $apellido_materno, $hora_entrada, $hora_salida, $salario, $telefono, $email, $cargo, $actividades, $id_empresa, $id);
    
        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                echo "Empleado actualizado exitosamente.";
                header("Location: employee.php");
                exit();
            } else {
                echo "No se realizaron cambios. Verifica los datos.";
            }
        } else {
            echo "Error al actualizar los datos: " . $stmt->error;
        }
    }
}    
?>
