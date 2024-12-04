<?php
require '../Login/conexion.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $folio_presupuesto = $_POST['folio_presupuesto'];
    $num_ext = $_POST['num_ext'];
    $num_int = $_POST['num_int'];
    $calle = $_POST['calle'];
    $ciudad = $_POST['ciudad'];
    $estado = $_POST['estado'];
    $codigo_postal = $_POST['codigo_postal'];

    $sql_check = "SELECT COUNT(*) as count FROM direcciones WHERE folio_presupuesto = ?";
    $stmt_check = $con->prepare($sql_check);
    $stmt_check->bind_param("i", $folio_presupuesto);
    $stmt_check->execute();
    $result = $stmt_check->get_result();
    $row = $result->fetch_assoc();
    $stmt_check->close();

    if ($row['count'] > 0) {
        $_SESSION['status_message'] = "Ya existe una dirección para este presupuesto.";
        $_SESSION['status_type'] = "error";
    } else {
        $sql_insert = "INSERT INTO direcciones(folio_presupuesto, num_ext, num_int, calle, ciudad, estado, codigo_postal)
                       VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt_insert = $con->prepare($sql_insert);
        $stmt_insert->bind_param("issssss", $folio_presupuesto, $num_ext, $num_int, $calle, $ciudad, $estado, $codigo_postal);

        if ($stmt_insert->execute()) {
            $_SESSION['status_message'] = "Dirección del presupuesto agregada exitosamente.";
            $_SESSION['status_type'] = "success";
        } else {
            $_SESSION['status_message'] = "Error al agregar la dirección del presupuesto: " . $stmt_insert->error;
            $_SESSION['status_type'] = "danger";
        }

        $stmt_insert->close();
    }

    header("Location: budget.php");
    exit();
}

$con->close();
?>
