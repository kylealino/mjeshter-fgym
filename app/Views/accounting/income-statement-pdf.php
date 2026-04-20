<?php
// Income Statement PDF Generator - SINGLE PAGE FIXED VERSION
// Optimized to fit perfectly on one A4 page

$this->request = \Config\Services::request();
$this->db = \Config\Database::connect();

// Get date parameters
$date_from = $this->request->getGet('date_from');
$date_to = $this->request->getGet('date_to');

if (empty($date_from) && empty($date_to)) {
    // Default dates for demo
    $date_to = date('Y-m-d');
    $date_from = date('Y-m-d', strtotime('first day of january this year'));
}

$period_text = !empty($date_from) && !empty($date_to) 
    ? 'For the period ' . date('F d, Y', strtotime($date_from)) . ' to ' . date('F d, Y', strtotime($date_to))
    : 'For the period __________________ to __________________';

$this->session = session();
$this->cuser = $this->session->get('__xsys_myuserzicas__');
if (empty($this->cuser)) {
    $this->cuser = 'Demo User';
}

require APPPATH . 'ThirdParty/fpdf/fpdf.php';

$currentDate = date("Y-m-d");
$formattedDate = date("F j, Y", strtotime($currentDate));

class PDF extends \FPDF {
    function Footer() {
        $this->SetY(-12);
        $this->SetFont('Arial', '', 6);
        $this->Cell(0, 3, 'This is a computer-generated document. No signature is required.', 0, 0, 'C');
        $this->SetXY(-35, -12);
        $this->SetFont('Arial', 'I', 6);
        $this->Cell(25, 3, 'Page ' . $this->PageNo() . ' of {nb}', 0, 0, 'R');
    }
}

$pdf = new PDF('P', 'mm', 'A4');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetTitle('Income Statement');

// Set margins
$leftMargin = 15;
$rightMargin = 15;
$pdf->SetLeftMargin($leftMargin);
$pdf->SetRightMargin($rightMargin);

// Column widths
$col_label = 120;    // Account label width
$col_amount = 45;    // Amount column width

$Y = 12;

// ==================== HEADER ====================
$pdf->SetY($Y);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 6, 'SCIENCE SAVINGS AND LOAN ASSOCIATION INC.', 0, 1, 'C');

$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(0, 5, 'INCOME STATEMENT', 0, 1, 'C');

$pdf->SetFont('Arial', '', 8);
$pdf->Cell(0, 4, $period_text, 0, 1, 'C');

$Y = $pdf->GetY() + 4;

// ==================== TABLE HEADER ====================
$pdf->SetFont('Arial', 'B', 9);
$pdf->SetFillColor(240, 240, 240);
$pdf->SetX($leftMargin);
$pdf->Cell($col_label, 8, 'ACCOUNT', 1, 0, 'L', true);
$pdf->Cell($col_amount, 8, 'AMOUNT (PHP)', 1, 1, 'R', true);

$startX = $leftMargin;

// Helper function
function formatCurrency($amount) {
    return number_format($amount, 2);
}

// ==================== MOCKUP DATA ====================
$revenues = [
    'Interest Income' => 425000.00,
    'Service Fees' => 156000.00,
    'Penalties & Charges' => 12500.00,
    'Investment Income' => 28500.00,
    'Other Income' => 7500.00,
];

$cost_of_services = [
    'Interest Expense' => 125000.00,
    'Provision for Bad Debts' => 25000.00,
    'Other Direct Costs' => 5000.00,
];

$operating_expenses = [
    'Salaries and Wages' => 185000.00,
    'Employee Benefits' => 42500.00,
    'Rent Expense' => 36000.00,
    'Utilities' => 18500.00,
    'Office Supplies' => 8500.00,
    'Communication' => 12500.00,
    'Professional Fees' => 25000.00,
    'Insurance' => 15000.00,
    'Advertising' => 12000.00,
    'Repairs & Maintenance' => 8500.00,
    'Depreciation' => 28000.00,
    'Taxes & Licenses' => 18500.00,
    'Miscellaneous' => 7500.00,
];

$other_items = [
    'Gain on Sale of Assets' => 5000.00,
    'Loss on Disposal' => -2500.00,
];

// Calculate totals
$total_revenues = array_sum($revenues);
$total_cost_services = array_sum($cost_of_services);
$total_operating_expenses = array_sum($operating_expenses);
$net_other = $other_items['Gain on Sale of Assets'] + $other_items['Loss on Disposal'];

$gross_profit = $total_revenues - $total_cost_services;
$operating_income = $gross_profit - $total_operating_expenses;
$net_income = $operating_income + $net_other;

// ==================== RENDER CONTENT ====================
$pdf->SetFont('Arial', 'B', 9);
$pdf->SetX($startX);
$pdf->Cell($col_label, 7, 'REVENUES', 0, 1, 'L');

$pdf->SetFont('Arial', '', 8);
foreach ($revenues as $label => $amount) {
    $pdf->SetX($startX + 5);
    $pdf->Cell($col_label - 5, 6, $label, 0, 0, 'L');
    $pdf->SetX($startX + $col_label);
    $pdf->Cell($col_amount, 6, formatCurrency($amount), 0, 1, 'R');
}

$pdf->SetX($startX);
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell($col_label, 6, 'Total Revenues', 'T', 0, 'L');
$pdf->SetX($startX + $col_label);
$pdf->Cell($col_amount, 6, formatCurrency($total_revenues), 'T', 1, 'R');

$pdf->Ln(2);

// COST OF SERVICES
$pdf->SetFont('Arial', 'B', 9);
$pdf->SetX($startX);
$pdf->Cell($col_label, 7, 'COST OF SERVICES', 0, 1, 'L');

$pdf->SetFont('Arial', '', 8);
foreach ($cost_of_services as $label => $amount) {
    $pdf->SetX($startX + 5);
    $pdf->Cell($col_label - 5, 6, $label, 0, 0, 'L');
    $pdf->SetX($startX + $col_label);
    $pdf->Cell($col_amount, 6, formatCurrency($amount), 0, 1, 'R');
}

$pdf->SetX($startX);
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell($col_label, 6, 'Total Cost of Services', 'T', 0, 'L');
$pdf->SetX($startX + $col_label);
$pdf->Cell($col_amount, 6, formatCurrency($total_cost_services), 'T', 1, 'R');

$pdf->Ln(2);

// GROSS PROFIT
$pdf->SetFont('Arial', 'B', 9);
$pdf->SetFillColor(245, 245, 245);
$pdf->SetX($startX);
$pdf->Cell($col_label, 7, 'GROSS PROFIT', 0, 0, 'L');
$pdf->SetX($startX + $col_label);
$pdf->Cell($col_amount, 7, formatCurrency($gross_profit), 0, 1, 'R');

$pdf->Ln(2);

// OPERATING EXPENSES
$pdf->SetFont('Arial', 'B', 9);
$pdf->SetX($startX);
$pdf->Cell($col_label, 7, 'OPERATING EXPENSES', 0, 1, 'L');

$pdf->SetFont('Arial', '', 7.5);
// Display in two columns to save space
$half_count = ceil(count($operating_expenses) / 2);
$expenses_list = array_keys($operating_expenses);
$expenses_values = array_values($operating_expenses);

$start_y = $pdf->GetY();
$left_x = $startX + 5;
$right_x = $startX + 65; // Position for second column

for ($i = 0; $i < $half_count; $i++) {
    // Left column
    if (isset($expenses_list[$i])) {
        $pdf->SetXY($left_x, $start_y + ($i * 5.5));
        $pdf->Cell(55, 5, $expenses_list[$i], 0, 0, 'L');
        $pdf->SetX($left_x + 55);
        $pdf->Cell(35, 5, formatCurrency($expenses_values[$i]), 0, 0, 'R');
    }
    
    // Right column
    $right_index = $i + $half_count;
    if (isset($expenses_list[$right_index])) {
        $pdf->SetXY($right_x, $start_y + ($i * 5.5));
        $pdf->Cell(55, 5, $expenses_list[$right_index], 0, 0, 'L');
        $pdf->SetX($right_x + 55);
        $pdf->Cell(35, 5, formatCurrency($expenses_values[$right_index]), 0, 1, 'R');
    }
}

$pdf->SetY($start_y + ($half_count * 5.5) + 3);

// Total Operating Expenses
$pdf->SetX($startX);
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell($col_label, 6, 'Total Operating Expenses', 'T', 0, 'L');
$pdf->SetX($startX + $col_label);
$pdf->Cell($col_amount, 6, formatCurrency($total_operating_expenses), 'T', 1, 'R');

$pdf->Ln(2);

// OPERATING INCOME
$pdf->SetFont('Arial', 'B', 9);
$pdf->SetX($startX);
$pdf->Cell($col_label, 7, 'OPERATING INCOME', 0, 0, 'L');
$pdf->SetX($startX + $col_label);
$pdf->Cell($col_amount, 7, formatCurrency($operating_income), 0, 1, 'R');

$pdf->Ln(2);

// OTHER INCOME/EXPENSES
$pdf->SetFont('Arial', 'B', 9);
$pdf->SetX($startX);
$pdf->Cell($col_label, 7, 'OTHER INCOME (EXPENSES)', 0, 1, 'L');

$pdf->SetFont('Arial', '', 8);
foreach ($other_items as $label => $amount) {
    $pdf->SetX($startX + 5);
    $pdf->Cell($col_label - 5, 6, $label, 0, 0, 'L');
    $pdf->SetX($startX + $col_label);
    $pdf->Cell($col_amount, 6, formatCurrency($amount), 0, 1, 'R');
}

$pdf->SetX($startX);
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell($col_label, 6, 'Net Other Income', 'T', 0, 'L');
$pdf->SetX($startX + $col_label);
$pdf->Cell($col_amount, 6, formatCurrency($net_other), 'T', 1, 'R');

$pdf->Ln(3);

// NET INCOME
$pdf->SetFont('Arial', 'B', 11);
$pdf->SetFillColor(230, 240, 255);
$pdf->SetX($startX);
$pdf->Cell($col_label, 8, 'NET INCOME', 'T', 0, 'L', true);
$pdf->SetX($startX + $col_label);
$pdf->Cell($col_amount, 8, formatCurrency($net_income), 'T', 1, 'R', true);

$currentY = $pdf->GetY() + 5;

// ==================== SIGNATURE SECTION ====================
// Left - Prepared by
$pdf->SetFont('Arial', '', 7);
$pdf->SetXY($leftMargin, $currentY);
$pdf->Cell(70, 4, 'Prepared by:', 0, 1, 'L');

$pdf->SetXY($leftMargin, $pdf->GetY() + 1);
$pdf->Cell(70, 4, '_________________________', 0, 1, 'L');

$pdf->SetXY($leftMargin, $pdf->GetY());
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(70, 3, 'Signature over Printed Name', 0, 1, 'L');

$pdf->SetXY($leftMargin, $pdf->GetY() + 1);
$pdf->SetFont('Arial', '', 7);
$pdf->Cell(70, 4, $this->cuser, 0, 1, 'L');

$pdf->SetXY($leftMargin, $pdf->GetY());
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(70, 3, 'Prepared by', 0, 1, 'L');

// Right - Approved by
$pdf->SetXY(115, $currentY);
$pdf->SetFont('Arial', '', 7);
$pdf->Cell(70, 4, 'Approved by:', 0, 1, 'L');

$pdf->SetXY(115, $pdf->GetY() + 1);
$pdf->Cell(70, 4, '_________________________', 0, 1, 'L');

$pdf->SetXY(115, $pdf->GetY());
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(70, 3, 'Signature over Printed Name', 0, 1, 'L');

$pdf->SetXY(115, $pdf->GetY() + 1);
$pdf->SetFont('Arial', '', 7);
$pdf->Cell(70, 4, '_________________________', 0, 1, 'L');

$pdf->SetXY(115, $pdf->GetY());
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(70, 3, 'Accounting Head / Designee', 0, 1, 'L');

// ==================== GENERATED INFO ====================
$finalY = $pdf->GetY() + 5;

if ($finalY < 270) {
    $pdf->SetY($finalY);
} else {
    $pdf->SetY(265);
}

$pdf->SetFont('Arial', '', 6);
$pdf->SetX($leftMargin);
$pdf->Cell(0, 3, 'Generated on: ' . $formattedDate . ' | Generated by: ' . $this->cuser . ' | System: SSLAI Accounting System', 0, 1, 'L');
$pdf->SetX($leftMargin);
$pdf->Cell(0, 3, 'Reporting Period: ' . $period_text, 0, 1, 'L');

$pdf->Output();
exit;
?>