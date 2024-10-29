<?php require '../Administrador/superior_admin.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Family Drywall</title>
    <link rel="stylesheet" href="../Css/style1.css">
</head>
<body>

<?php
require '../Login/conexion.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Obtener datos del cliente
    $sql = "SELECT * FROM clientes WHERE id_cliente = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $cliente = $result->fetch_assoc();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Obtener datos actualizados
        $nombre = $_POST['nombre_cliente'];
        $apellido_paterno = $_POST['apellido_paterno'];
        $apellido_materno = $_POST['apellido_materno'];
        $telefono = $_POST['telefono_personal'];
        $email = $_POST['correo_electronico'];
        $rol = $_POST['rol'];

        // Actualizar datos del cliente
        $sql = "UPDATE clientes SET nombre_cliente = ?, apellido_paterno = ?, apellido_materno = ?, telefono_personal = ?, correo_electronico = ?, rol = ? WHERE id_cliente = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("ssssssi", $nombre, $apellido_paterno, $apellido_materno, $telefono, $email, $rol, $id);

        if ($stmt->execute()) {
            header("Location: users.php");
            exit();
        } else {
            echo "Error al actualizar los datos.";
        }
    }
} else {
    echo "Cliente no encontrado.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Customer</title>
</head>
<body>
    <form method="POST">
        <label>Name:</label><input type="text" name="nombre_cliente" value="<?php echo $cliente['nombre_cliente']; ?>" required><br>
        <label>Last Name:</label><input type="text" name="apellido_paterno" value="<?php echo $cliente['apellido_paterno']; ?>" required><br>
        <label>Mother's Last Name:</label><input type="text" name="apellido_materno" value="<?php echo $cliente['apellido_materno']; ?>" required><br>
        <label>Phone:</label><input type="text" name="telefono_personal" value="<?php echo $cliente['telefono_personal']; ?>" required><br>
        <label>Email:</label><input type="email" name="correo_electronico" value="<?php echo $cliente['correo_electronico']; ?>" required><br>
        <label>Role:</label><input type="text" name="rol" value="<?php echo $cliente['rol']; ?>" required><br>
        <button type="submit">Save Changes</button>
    </form>
</body>
</html>
