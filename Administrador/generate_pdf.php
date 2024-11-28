<?php
require __DIR__ . '../../vendor/setasign/fpdf/fpdf.php';
require '../Login/conexion.php';
session_start(); // Iniciar la sesión para manejar las alertas

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['folio'])) {
    $folio_obra = $_GET['folio'];

    // Consulta para obtener los datos necesarios de la obra
    $sql = "SELECT o.*, e.nombre_empresa, c.nombre_cliente, c.apellido_paterno, c.apellido_materno, 
                   d.ciudad, d.estado, c.correo_electronico
            FROM obras o
            LEFT JOIN empresa e ON o.id_empresa = e.id_empresa
            LEFT JOIN clientes c ON o.id_cliente = c.id_cliente
            LEFT JOIN direcciones d ON o.id_direccion = d.id_direccion
            WHERE o.folio_obra = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param('i', $folio_obra);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $obra = $result->fetch_assoc();

        if ($obra) {
            try {
                // Ruta del archivo PDF
                $file_path = "../pdf/proposal_" . $folio_obra . ".pdf";

                // Eliminar el archivo existente si ya existe
                if (file_exists($file_path)) {
                    unlink($file_path); // Borra el archivo
                }

                // Crear el PDF
                $pdf = new FPDF();
                $pdf->AddPage();
                $pdf->SetFont('Arial', 'B', 14);

                // Encabezado
                $pdf->Cell(0, 10, 'PROPOSAL', 0, 1, 'C');
                $pdf->SetFont('Arial', '', 12);
                $pdf->Cell(0, 10, 'Ph # 609-966-9947 & Email: familydrywall@gmail.com', 0, 1, 'C');
                $pdf->Cell(0, 10, 'FAMILY DRYWALL LLC.', 0, 1, 'C');
                $pdf->Cell(0, 10, '1100 S. NEW RD, PLEASANTVILLE, N.J. 08232', 0, 1, 'C');
                $pdf->Ln(10);

                // Información de la obra
                $pdf->SetFont('Arial', 'B', 12);
                $pdf->Cell(50, 10, 'PROPOSAL SUBMITTED TO DATE:', 0, 0);
                $pdf->SetFont('Arial', '', 12);
                $pdf->Cell(100, 10, date('m/d/Y', strtotime($obra['fecha_inicio'])), 0, 1);

                $pdf->SetFont('Arial', 'B', 12);
                $pdf->Cell(50, 10, 'BUILDER NAME:', 0, 0);
                $pdf->SetFont('Arial', '', 12);
                $pdf->Cell(100, 10, $obra['nombre_empresa'], 0, 1);

                $pdf->SetFont('Arial', 'B', 12);
                $pdf->Cell(50, 10, 'JOB LOCATION:', 0, 0);
                $pdf->SetFont('Arial', '', 12);
                $pdf->Cell(100, 10, $obra['ciudad'], 0, 1);

                $pdf->SetFont('Arial', 'B', 12);
                $pdf->Cell(50, 10, 'CITY, STATE:', 0, 0);
                $pdf->SetFont('Arial', '', 12);
                $pdf->Cell(100, 10, $obra['ciudad'] . ', ' . $obra['estado'], 0, 1);

                $pdf->SetFont('Arial', 'B', 12);
                $pdf->Cell(50, 10, 'EMAIL:', 0, 0);
                $pdf->SetFont('Arial', '', 12);
                $pdf->Cell(100, 10, $obra['correo_electronico'], 0, 1);

                $pdf->SetFont('Arial', 'B', 12);
                $pdf->Cell(50, 10, 'JOB:', 0, 0);
                $pdf->SetFont('Arial', '', 12);
                $pdf->Cell(100, 10, 'Drywall and Spackle', 0, 1);
                $pdf->Ln(10);

                // Especificaciones
                $pdf->SetFont('Arial', 'B', 12);
                $pdf->Cell(0, 10, 'SPECIFICATIONS: Use Screws', 0, 1);
                $pdf->SetFont('Arial', '', 12);
                $pdf->Cell(0, 10, '144 SHEETROCK TO INSTALL AND SPACKLE.', 0, 1);
                $pdf->Ln(10);

                // Condiciones
                $pdf->SetFont('Arial', '', 12);
                $pdf->MultiCell(0, 10, "We hereby propose to furnish labor -- complete in accordance with the above specifications, for the sum of $". $obra['anticipo']." with payment to be made as follows:
                HALF OF THE AMOUNT DUE WHEN SHEETROCK INSTALLED -- $".
                $obra['adeudo']." 
                FINAL PAYMENT DUE AT COMPLETION OF JOB -- $". $obra['total_obra']."
                All agreements contingent upon strikes, accidents, or delays beyond our control.", 0, 'L');
                        $pdf->Ln(20);

                // Firmas
                $pdf->Cell(0, 10, 'Authorized Signature: _____________________', 0, 1, 'L');
                $pdf->Cell(0, 10, 'Accepted Signature: ______________________', 0, 1, 'L');
                $pdf->Ln(10);

                // Crear la carpeta si no existe
                if (!is_dir('../pdf')) {
                    mkdir('../pdf', 0755, true);
                }

                // Guardar el PDF
                $pdf->Output('F', $file_path);

                // Mensaje de éxito
                $_SESSION['status_message'] = "PDF generado exitosamente.";
                $_SESSION['status_type'] = "success";
            } catch (Exception $e) {
                // Mensaje de error
                $_SESSION['status_message'] = "Error al generar el PDF: " . $e->getMessage();
                $_SESSION['status_type'] = "danger";
            }
        } else {
            $_SESSION['status_message'] = "No se encontró la obra con el folio proporcionado.";
            $_SESSION['status_type'] = "danger";
        }
    } else {
        $_SESSION['status_message'] = "Error en la consulta a la base de datos: " . $stmt->error;
        $_SESSION['status_type'] = "danger";
    }

    $stmt->close();
    $con->close();
    // Redirigir a la página principal (o la que desees)
    header("Location: works.php");
    exit();
} else {
    $_SESSION['status_message'] = "Folio no proporcionado.";
    $_SESSION['status_type'] = "danger";
    header("Location: works.php");
    exit();
}
?>
