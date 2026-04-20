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

$pdf = new PDF('P', 'mm', 'A4');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetTitle('Cash Disbursement Journal');

// Margins
$leftMargin = 10;
$pdf->SetLeftMargin($leftMargin);
$pdf->SetRightMargin(10);

// ✅ FIXED WIDTHS (TOTAL = 190mm)
$col_date = 14;       
$col_cv = 18;         
$col_payee = 38;      
$col_desc = 28;       
$col_acct_code = 18;  
$col_acct_title = 32; 
$col_debit = 21;      
$col_credit = 21;     

$col_widths = [$col_date,$col_cv,$col_payee,$col_desc,$col_acct_code,$col_acct_title,$col_debit,$col_credit];

$Y = 12;

// HEADER
$pdf->SetY($Y);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 5, 'SCIENCE SAVINGS AND LOAN ASSOCIATION INC.', 0, 1, 'C');

$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(0, 5, 'Cash Disbursement Journal', 0, 1, 'C');

$pdf->SetFont('Arial', '', 8);
$period_text = (!empty($date_from) && !empty($date_to)) 
    ? 'For the period ' . date('F d, Y', strtotime($date_from)) . ' to ' . date('F d, Y', strtotime($date_to))
    : 'For the period __________________ to __________________';

$pdf->Cell(0, 4, $period_text, 0, 1, 'C');

$pdf->Ln(4);

// TABLE HEADER
$pdf->SetFont('Arial', 'B', 6);
$pdf->SetFillColor(230,230,230);

$headers = ['Date','CV No.','Payee','Description','Acct Code','Account Title','Debit','Credit'];

foreach ($headers as $i => $h) {
    $pdf->Cell($col_widths[$i], 7, $h, 1, 0, 'C', true);
}
$pdf->Ln();

// ================= HELPERS =================
function truncate($text, $maxLength) {
    return (strlen($text) > $maxLength) 
        ? substr($text, 0, $maxLength - 3) . '...' 
        : $text;
}

function addRow($pdf, $col_widths, $data) {

    // truncate text
    $data[2] = truncate($data[2], 25);
    $data[3] = truncate($data[3], 28);
    $data[5] = truncate($data[5], 22);

    $pdf->SetFont('Arial', '', 5.5);

    foreach ($data as $i => $cell) {

        $align = 'L';
        if ($i == 0 || $i == 1) $align = 'C';
        if ($i == 6 || $i == 7) $align = 'R';

        $pdf->Cell($col_widths[$i], 6, $cell, 1, 0, $align);
    }
    $pdf->Ln();
}

// ================= SAMPLE DATA =================

// Rent
addRow($pdf,$col_widths,['03/01/2026','CV-001','ABC Realty Corp','Office Rent - March','501010','Rent Expense','30,000.00','']);
addRow($pdf,$col_widths,['03/01/2026','CV-001','ABC Realty Corp','Office Rent - March','101001','Cash in Bank','','30,000.00']);

// Utilities
addRow($pdf,$col_widths,['03/05/2026','CV-002','MERALCO','Electric Bill','501020','Utilities Expense','9,500.00','']);
addRow($pdf,$col_widths,['03/05/2026','CV-002','MERALCO','Electric Bill','101001','Cash in Bank','','9,500.00']);

// Supplies
addRow($pdf,$col_widths,['03/10/2026','CV-003','National Bookstore','Office Supplies','501030','Office Supplies Expense','4,200.00','']);
addRow($pdf,$col_widths,['03/10/2026','CV-003','National Bookstore','Office Supplies','101001','Cash in Bank','','4,200.00']);

// Salaries
addRow($pdf,$col_widths,['03/15/2026','CV-004','Payroll','Salaries','501040','Salaries Expense','120,000.00','']);
addRow($pdf,$col_widths,['03/15/2026','CV-004','Payroll','Salaries','101001','Cash in Bank','','120,000.00']);

// AP Payment
addRow($pdf,$col_widths,['03/20/2026','CV-005','Supplier Co.','Payment of AP','201010','Accounts Payable','15,000.00','']);
addRow($pdf,$col_widths,['03/20/2026','CV-005','Supplier Co.','Payment of AP','101001','Cash in Bank','','15,000.00']);

// ================= TOTAL =================
$total = 30000 + 9500 + 4200 + 120000 + 15000;

$pdf->SetFont('Arial','B',7);
$pdf->SetFillColor(240,240,240);

// label
$pdf->Cell($col_date + $col_cv + $col_payee + $col_desc + $col_acct_code + $col_acct_title, 6, 'TOTAL', 1, 0, 'R', true);

// totals
$pdf->Cell($col_debit, 6, number_format($total,2), 1, 0, 'R', true);
$pdf->Cell($col_credit, 6, number_format($total,2), 1, 1, 'R', true);

// ================= SUMMARY =================
$pdf->Ln(5);

$pdf->SetFont('Arial','B',9);
$pdf->Cell(0,5,'SUMMARY',0,1);

$pdf->SetFont('Arial','',8);
$pdf->Cell(60,5,'Total Disbursement:',0,0);
$pdf->Cell(50,5,'PHP '.number_format($total,2),0,1);

$pdf->Ln(5);

// ================= SIGNATURE =================
$pdf->SetFont('Arial','',8);

$pdf->Cell(90,5,'Prepared by:',0,0);
$pdf->Cell(90,5,'Checked by:',0,1);

$pdf->Ln(10);

$pdf->Cell(90,5,'_________________________',0,0);
$pdf->Cell(90,5,'_________________________',0,1);

$pdf->Cell(90,5,$this->cuser,0,0);
$pdf->Cell(90,5,'Accounting Head',0,1);

// ================= FOOT =================
$pdf->Ln(10);
$pdf->SetFont('Arial','',6);
$pdf->Cell(0,3,'Generated on: '.$formattedDate,0,1);
$pdf->Cell(0,3,'System: Cash Disbursement Module',0,1);

$pdf->Output();
exit;
?>