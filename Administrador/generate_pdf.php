<?php
require __DIR__ . '../../vendor/setasign/fpdf/fpdf.php';

if (isset($_GET['folio'])) {
    $folio_obra = $_GET['folio'];

    require('../Login/conexion.php');

    $sql = "SELECT o.*, e.nombre_empresa, c.nombre_cliente, c.apellido_paterno, c.apellido_materno, d.ciudad, d.estado, c.correo_electronico
            FROM obras o
            LEFT JOIN empresa e ON o.id_empresa = e.id_empresa
            LEFT JOIN clientes c ON o.id_cliente = c.id_cliente
            LEFT JOIN direcciones d ON o.id_direccion = d.id_direccion
            WHERE o.folio_obra = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param('i', $folio_obra);
    $stmt->execute();
    $result = $stmt->get_result();
    $obra = $result->fetch_assoc();

    if ($obra) {
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

        // Detalles de la propuesta
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

        // Salida del PDF
        $pdf->Output('I', 'proposal_' . $folio_obra . '.pdf');
    } else {
        echo "No se encontró la obra.";
    }
} else {
    echo "Folio no proporcionado.";
}
?>
