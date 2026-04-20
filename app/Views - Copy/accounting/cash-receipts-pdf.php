<?php
$this->request = \Config\Services::request();
$this->db = \Config\Database::connect();

$date_from = $this->request->getGet('date_from');
$date_to = $this->request->getGet('date_to');

$this->session = session();
$this->cuser = $this->session->get('__xsys_myuserzicas__');
require APPPATH . 'ThirdParty/fpdf/fpdf.php';

$currentDate = date("Y-m-d");
$formattedDate = date("F j, Y", strtotime($currentDate));

class PDF extends \FPDF {
    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', '', 6);
        $this->Cell(0, 4, 'This is a computer-generated document. No signature is required.', 0, 0, 'C');
        $this->SetXY(-35, -15);
        $this->SetFont('Arial', 'I', 7);
        $this->Cell(25, 4, 'Page ' . $this->PageNo() . ' of {nb}', 0, 0, 'R');
    }
}

$pdf = new PDF('P', 'mm', 'A4'); // Changed to A4
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetTitle('Cash Receipts Journal');

// Set margins - reduced for A4
$leftMargin = 10;
$rightMargin = 10;
$pdf->SetLeftMargin($leftMargin);
$pdf->SetRightMargin($rightMargin);

// Total usable width = 210mm (A4) - 20mm = 190mm
// Adjusted column widths to fit A4 portrait
$col_date = 18;      // Date
$col_ref = 22;       // Reference  
$col_code = 22;      // Account Code
$col_title = 50;     // Account Title
$col_debit = 20;     // Debit
$col_credit = 20;    // Credit
$col_explain = 38;   // Explanation

$Y = 15;

// Company Header - SSLAI
$pdf->SetY($Y);
$pdf->SetFont('Arial', 'B', 12); // Reduced font size
$pdf->Cell(0, 5, 'SCIENCE SAVINGS AND LOAN ASSOCIATION INC.', 0, 1, 'C');

$Y = $pdf->GetY();
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(0, 5, 'Cash Receipts Journal', 0, 1, 'C');

$Y = $pdf->GetY();
$pdf->SetFont('Arial', '', 8);
$period_text = !empty($date_from) && !empty($date_to) 
    ? 'For the period ' . date('F d, Y', strtotime($date_from)) . ' to ' . date('F d, Y', strtotime($date_to))
    : 'For the period __________________ to __________________';
$pdf->Cell(0, 4, $period_text, 0, 1, 'C');

$Y = $pdf->GetY() + 5;

// TABLE HEADER
$pdf->SetFont('Arial', 'B', 6); // Smaller font for header
$pdf->SetFillColor(240, 240, 240);
$startX = $leftMargin;

$pdf->SetXY($startX, $Y);
$pdf->Cell($col_date, 7, 'Date', 1, 0, 'C', true);
$pdf->Cell($col_ref, 7, 'Reference', 1, 0, 'C', true);
$pdf->Cell($col_code, 7, 'Account Code', 1, 0, 'C', true);
$pdf->Cell($col_title, 7, 'Account Title', 1, 0, 'C', true);
$pdf->Cell($col_debit, 7, 'Debit', 1, 0, 'C', true);
$pdf->Cell($col_credit, 7, 'Credit', 1, 0, 'C', true);
$pdf->Cell($col_explain, 7, 'Explanation', 1, 1, 'C', true);

$Y = $pdf->GetY();
$pdf->SetFont('Arial', '', 6);

// Helper function to add row
function addRow($pdf, $startX, $col_widths, $data, $rowHeight = 5.5) {
    $x = $startX;
    $y = $pdf->GetY();
    
    foreach ($data as $i => $cell) {
        $pdf->SetXY($x, $y);
        $align = ($i == 4 || $i == 5) ? 'R' : 'L';
        $pdf->SetFont('Arial', '', 6);
        $pdf->Cell($col_widths[$i], $rowHeight, $cell, 1, 0, $align);
        $x += $col_widths[$i];
    }
    $pdf->SetY($y + $rowHeight);
}

$col_widths = [$col_date, $col_ref, $col_code, $col_title, $col_debit, $col_credit, $col_explain];
$rowHeight = 5.5;

// SAMPLE DATA ROWS
addRow($pdf, $startX, $col_widths, [
    '12/31/24', 'OR-2024-001', '10101010', 'Cash on Hand', '25,000.00', '', 'Collection'
], $rowHeight);

addRow($pdf, $startX, $col_widths, [
    '12/31/24', 'OR-2024-001', '10102020', 'Accounts Receivable', '', '25,000.00', 'To record'
], $rowHeight);

addRow($pdf, $startX, $col_widths, [
    '01/15/25', 'OR-2025-001', '40101010', 'Service Revenue', '15,000.00', '', 'Consultancy'
], $rowHeight);

addRow($pdf, $startX, $col_widths, [
    '01/15/25', 'OR-2025-001', '10101010', 'Cash on Hand', '15,000.00', '', 'Collection'
], $rowHeight);

addRow($pdf, $startX, $col_widths, [
    '01/31/25', 'OR-2025-002', '40201010', 'Interest Income', '2,500.00', '', 'Bank interest'
], $rowHeight);

addRow($pdf, $startX, $col_widths, [
    '01/31/25', 'OR-2025-002', '10101010', 'Cash on Hand', '2,500.00', '', 'Collection'
], $rowHeight);

$Y = $pdf->GetY();

// TOTALS ROW
$pdf->SetFont('Arial', 'B', 6);
$pdf->SetFillColor(245, 245, 245);

$totalDebit = 57500.00;
$totalCredit = 25000.00;

// Calculate position for totals
$totalStartX = $startX + $col_date + $col_ref + $col_code + $col_title;
$pdf->SetXY($totalStartX, $Y);
$pdf->Cell($col_debit + $col_credit, 6, 'TOTALS', 1, 0, 'C', true);
$pdf->SetXY($totalStartX + $col_debit + $col_credit, $Y);
$pdf->Cell($col_explain, 6, '', 1, 1, 'C', true);

$pdf->SetXY($totalStartX, $Y);
$pdf->Cell($col_debit, 6, number_format($totalDebit, 2), 1, 0, 'R', true);
$pdf->Cell($col_credit, 6, number_format($totalCredit, 2), 1, 0, 'R', true);
$pdf->Cell($col_explain, 6, '', 1, 1, 'R', true);

$currentY = $pdf->GetY() + 5;

// SUMMARY SECTION
$pdf->SetFont('Arial', 'B', 8);
$pdf->SetXY($startX, $currentY);
$pdf->Cell(0, 5, 'SUMMARY', 0, 1, 'L');

$pdf->SetFont('Arial', '', 8);
$pdf->SetXY($startX, $pdf->GetY());
$pdf->Cell(45, 5, 'Total Debit Amount:', 0, 0, 'L');
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(50, 5, 'PHP ' . number_format($totalDebit, 2), 0, 1, 'L');

$pdf->SetFont('Arial', '', 8);
$pdf->SetXY($startX, $pdf->GetY());
$pdf->Cell(45, 5, 'Total Credit Amount:', 0, 0, 'L');
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(50, 5, 'PHP ' . number_format($totalCredit, 2), 0, 1, 'L');

$pdf->SetFont('Arial', '', 8);
$pdf->SetXY($startX, $pdf->GetY());
$pdf->Cell(45, 5, 'Net Cash Receipts:', 0, 0, 'L');
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(50, 5, 'PHP ' . number_format($totalDebit - $totalCredit, 2), 0, 1, 'L');

$currentY = $pdf->GetY() + 8;

// SIGNATURE SECTION
$pdf->SetFont('Arial', '', 7);
$pdf->SetXY($startX, $currentY);
$pdf->Cell(70, 4, 'Prepared by:', 0, 1, 'L');

$pdf->SetXY($startX, $pdf->GetY() + 2);
$pdf->Cell(70, 4, '_________________________', 0, 1, 'L');

$pdf->SetXY($startX, $pdf->GetY());
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(70, 3, 'Signature over Printed Name', 0, 1, 'L');

$pdf->SetXY($startX, $pdf->GetY() + 2);
$pdf->SetFont('Arial', '', 7);
$pdf->Cell(70, 4, $this->cuser, 0, 1, 'L');

$pdf->SetXY($startX, $pdf->GetY());
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(70, 3, 'Prepared by', 0, 1, 'L');

// Right side - Approved by
$pdf->SetXY(110, $currentY);
$pdf->SetFont('Arial', '', 7);
$pdf->Cell(70, 4, 'Approved by:', 0, 1, 'L');

$pdf->SetXY(110, $pdf->GetY() + 2);
$pdf->Cell(70, 4, '_________________________', 0, 1, 'L');

$pdf->SetXY(110, $pdf->GetY());
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(70, 3, 'Signature over Printed Name', 0, 1, 'L');

$pdf->SetXY(110, $pdf->GetY() + 2);
$pdf->SetFont('Arial', '', 7);
$pdf->Cell(70, 4, '_________________________', 0, 1, 'L');

$pdf->SetXY(110, $pdf->GetY());
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(70, 3, 'Accounting Head / Designee', 0, 1, 'L');

// Generated info
$currentY = $pdf->GetY();
if ($currentY > 260) {
    $pdf->AddPage();
    $currentY = 20;
}

$pdf->SetY($currentY + 6);
$pdf->SetFont('Arial', '', 6);
$pdf->SetXY($startX, $pdf->GetY());
$pdf->Cell(0, 3, 'Generated on: ' . $formattedDate, 0, 1, 'L');
$pdf->SetXY($startX, $pdf->GetY());
$pdf->Cell(0, 3, 'Generated by: ' . $this->cuser, 0, 1, 'L');
$pdf->SetXY($startX, $pdf->GetY());
$pdf->Cell(0, 3, 'System: SSLAI Accounting System', 0, 1, 'L');

$pdf->Output();
exit;
?>