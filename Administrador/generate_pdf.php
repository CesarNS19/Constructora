<?php
require __DIR__ . '../../vendor/setasign/fpdf/fpdf.php';
require '../Login/conexion.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['folio'])) {
    $folio_obra = $_GET['folio'];

    $sql = "SELECT o.*, e.nombre_empresa, c.nombre_cliente, c.apellido_paterno, c.apellido_materno, 
                   d.ciudad, d.estado, c.correo_electronico, s.nombre_servicio
            FROM obras o
            LEFT JOIN empresa e ON o.id_empresa = e.id_empresa
            LEFT JOIN clientes c ON o.id_cliente = c.id_cliente
            LEFT JOIN servicios s ON o.id_servicio = s.id_servicio
            LEFT JOIN direcciones d ON o.id_direccion = d.id_direccion
            WHERE o.folio_obra = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param('i', $folio_obra);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $obra = $result->fetch_assoc();

        if ($obra) {
            try {
                $file_path = "../pdf/Contract_" . $folio_obra . ".pdf";

                // Eliminar archivo previo si existe
                if (file_exists($file_path)) {
                    unlink($file_path);
                }

                // Crear PDF
                $pdf = new FPDF();
                $pdf->AddPage();
                $pdf->SetMargins(10, 10, 10);

                // Encabezado
                $pdf->SetFont('Arial', 'B', 16);
                $pdf->Cell(0, 10, 'CONTRACT', 0, 1, 'C');
                $pdf->SetFont('Arial', '', 10);
                $pdf->Cell(0, 5, 'Ph # 609-966-9947 & Email: familydrywall@gmail.com', 0, 1, 'C');
                $pdf->SetFont('Arial', 'B', 12);
                $pdf->Cell(0, 7, 'FAMILY DRYWALL LLC.', 0, 1, 'C');
                $pdf->SetFont('Arial', '', 10);
                $pdf->Cell(0, 5, '1100 S. NEW RD, PLEASANTVILLE, N.J. 08232', 0, 1, 'C');
                $pdf->Ln(10);

                // Información de la obra
                $pdf->SetFont('Arial', 'B', 10);
                $pdf->Cell(70, 10, 'CONTRACT SUBMITTED TO: DATE:', 'L,T,B', 0, 'L');
                $pdf->SetFont('Arial', '', 10);
                $pdf->Cell(120, 10, date('d/m/Y', strtotime($obra['fecha_inicio'])), 'T,R,B', 1, 'L');

                $pdf->SetFont('Arial', 'B', 10);
                $pdf->Cell(70, 10, 'BUILDER NAME:', 'L,T,B', 0, 'L');
                $pdf->SetFont('Arial', '', 10);
                $pdf->Cell(120, 10, mb_convert_encoding($obra['nombre_empresa'], 'ISO-8859-1', 'UTF-8'), 'T,R,B', 1, 'L');

                $pdf->SetFont('Arial', 'B', 10);
                $pdf->Cell(70, 10, 'JOB LOCATION:', 'L,T,B', 0, 'L');
                $pdf->SetFont('Arial', '', 10);
                $pdf->Cell(120, 10, mb_convert_encoding($obra['ciudad'], 'ISO-8859-1', 'UTF-8'), 'T,R,B', 1, 'L');

                $pdf->SetFont('Arial', 'B', 10);
                $pdf->Cell(70, 10, 'CITY, STATE:', 'L,T,B', 0, 'L');
                $pdf->SetFont('Arial', '', 10);
                $pdf->Cell(120, 10, mb_convert_encoding($obra['ciudad'] . ', ' . $obra['estado'], 'ISO-8859-1', 'UTF-8'), 'T,R,B', 1, 'L');

                $pdf->SetFont('Arial', 'B', 10);
                $pdf->Cell(70, 10, 'EMAIL:', 'L,T,B', 0, 'L');
                $pdf->SetFont('Arial', '', 10);
                $pdf->Cell(120, 10, mb_convert_encoding($obra['correo_electronico'], 'ISO-8859-1', 'UTF-8'), 'T,R,B', 1, 'L');

                $pdf->SetFont('Arial', 'B', 10);
                $pdf->Cell(70, 10, 'JOB:', 'L,T,B', 0, 'L');
                $pdf->SetFont('Arial', '', 10);
                $pdf->Cell(120, 10, 'Drywall and Spackle', 'T,R,B', 1, 'L');
                $pdf->Ln(10);

                // Especificaciones
                $pdf->SetFont('Arial', 'B', 12);
                $pdf->Cell(0, 10, 'SPECIFICATIONS: Use Screws.', 0, 1);
                $pdf->SetFont('Arial', '', 10);
                $pdf->MultiCell(0, 8, '   ' . mb_convert_encoding($obra['nombre_servicio'] . ', ' . $obra['observaciones'], 'ISO-8859-1', 'UTF-8'), 1);
                $pdf->Ln(10);

                // Condiciones
                $pdf->SetFont('Arial', 'B', 10);
                $pdf->MultiCell(0, 6, "We hereby propose to furnish labor -- complete in accordance with the above specifications, for the sum of $" . $obra['anticipo'] . " with payment to be made as follows:");
                $pdf->Cell(0, 8, 'HALF OF THE AMOUNT DUE WHEN SHEETROCK INSTALLED -- $' . $obra['adeudo'], 0, 1);
                $pdf->Cell(0, 8, 'FINAL PAYMENT DUE AT COMPLETION OF JOB -- $' . $obra['total_obra'], 0, 1);
                $pdf->Ln(8);

                // Términos
                $pdf->SetFont('Arial', 'B', 10);
                $pdf->MultiCell(0, 5, "All material is guaranteed to be as specified. All work to be completed in a workmanlike manner according to standard practices. Any alteration or deviation from above specifications involving extra costs will be executed only upon written orders and will become an extra charge over and above the estimate. All agreements contingent upon strikes, accident, or delays beyond our control.");
                $pdf->Ln(10);

                $signature_path = "../signatures/signature_.png"; // Ruta de la firma
                $pdf->Cell(10, 10, 'Authorized: Signature:', 0, 0, 'L');
                if (file_exists($signature_path)) {
                    $pdf->Image($signature_path, $pdf->GetX() + 40, $pdf->GetY() - 5, 30); // Ajusta la anchura a 30px
                }
                $pdf->Ln(20);
                
                // Aceptación del contrato
                $pdf->SetFont('Arial', 'B', 10);
                $pdf->Cell(0, 10, 'ACCEPTANCE OF CONTRACT', 0, 1, 'C');
                $pdf->MultiCell(0, 6, 'The above prices, specifications and conditions are hereby accepted. You are authorized to do the work as specified. Payment will be made as outlined above.', 0, 'L');
                $pdf->Ln(10);
                $pdf->Cell(30, 10, 'Accepted: Signature:', 0, 0, 'L');
                $pdf->Cell(0, 10, '       _______________________________', 0, 1, 'L');

                // Guardar el PDF
                if (!is_dir('../pdf')) {
                    mkdir('../pdf', 0755, true);
                }

                $pdf->Output('F', $file_path);
                $_SESSION['status_message'] = "PDF generado exitosamente.";
                $_SESSION['status_type'] = "success";
            } catch (Exception $e) {
                $_SESSION['status_message'] = "Error al generar el PDF: " . $e->getMessage();
                $_SESSION['status_type'] = "error";
            }
        } else {
            $_SESSION['status_message'] = "No se encontró la obra con el folio proporcionado.";
            $_SESSION['status_type'] = "error";
        }
    } else {
        $_SESSION['status_message'] = "Error en la consulta a la base de datos: " . $stmt->error;
        $_SESSION['status_type'] = "error";
    }

    $stmt->close();
    $con->close();
    header("Location: works.php");
    exit();
} else {
    $_SESSION['status_message'] = "Folio no proporcionado.";
    $_SESSION['status_type'] = "error";
    header("Location: works.php");
    exit();
}
?>
