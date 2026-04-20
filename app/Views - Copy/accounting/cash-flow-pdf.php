<?php
// Cash Flow Statement PDF Generator
// Professional format matching Balance Sheet and Income Statement

$this->request = \Config\Services::request();
$this->db = \Config\Database::connect();

// Get date parameters
$date_from = $this->request->getGet('date_from');
$date_to = $this->request->getGet('date_to');

if (empty($date_from) && empty($date_to)) {
    // Default to current year-to-date
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
$pdf->SetTitle('Cash Flow Statement');

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
$pdf->Cell(0, 5, 'STATEMENT OF CASH FLOWS', 0, 1, 'C');

$pdf->SetFont('Arial', '', 8);
$pdf->Cell(0, 4, $period_text, 0, 1, 'C');

$Y = $pdf->GetY() + 4;

// ==================== TABLE HEADER ====================
$pdf->SetFont('Arial', 'B', 9);
$pdf->SetFillColor(240, 240, 240);
$pdf->SetX($leftMargin);
$pdf->Cell($col_label, 8, 'CASH FLOW ACTIVITIES', 1, 0, 'L', true);
$pdf->Cell($col_amount, 8, 'AMOUNT (PHP)', 1, 1, 'R', true);

$startX = $leftMargin;

// Helper function
function formatCurrency($amount) {
    return number_format($amount, 2);
}

// ==================== MOCKUP DATA ====================
// CASH FLOWS FROM OPERATING ACTIVITIES
$operating_activities = [
    'Net Income' => 474500.00,
    'Adjustments for non-cash items:' => null,
    'Depreciation Expense' => 28000.00,
    'Provision for Bad Debts' => 25000.00,
    'Changes in working capital:' => null,
    '(Increase)/Decrease in Accounts Receivable' => -45000.00,
    '(Increase)/Decrease in Loans Receivable' => -125000.00,
    'Increase/(Decrease) in Accounts Payable' => 35000.00,
    'Increase/(Decrease) in Accrued Expenses' => 12500.00,
    'Increase/(Decrease) in Due to Borrowers' => 75000.00,
];

// CASH FLOWS FROM INVESTING ACTIVITIES
$investing_activities = [
    'Proceeds from Sale of Assets' => 15000.00,
    'Purchase of Property and Equipment' => -95000.00,
    'Purchase of Intangible Assets' => -25000.00,
];

// CASH FLOWS FROM FINANCING ACTIVITIES
$financing_activities = [
    'Proceeds from Notes Payable' => 100000.00,
    'Repayment of Notes Payable' => -50000.00,
    'Dividends Paid' => -75000.00,
    'Share Capital Contributions' => 0,
];

// Calculate subtotals
$net_cash_operating = 0;
$operating_calculated = [];

foreach ($operating_activities as $label => $amount) {
    if ($amount !== null) {
        $net_cash_operating += $amount;
        $operating_calculated[$label] = $amount;
    }
}

$net_cash_investing = array_sum($investing_activities);
$net_cash_financing = array_sum($financing_activities);
$net_increase_cash = $net_cash_operating + $net_cash_investing + $net_cash_financing;

// Beginning and Ending Cash balances
$beginning_cash = 1250000.00;
$ending_cash = $beginning_cash + $net_increase_cash;

// ==================== RENDER CONTENT ====================

// CASH FLOWS FROM OPERATING ACTIVITIES
$pdf->SetFont('Arial', 'B', 9);
$pdf->SetX($startX);
$pdf->Cell($col_label, 7, 'A. CASH FLOWS FROM OPERATING ACTIVITIES', 0, 1, 'L');

$pdf->SetFont('Arial', '', 8);
foreach ($operating_activities as $label => $amount) {
    if ($amount === null) {
        // Section header within operating activities
        $pdf->SetX($startX + 3);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell($col_label - 3, 5, $label, 0, 1, 'L');
        $pdf->SetFont('Arial', '', 8);
    } else {
        $pdf->SetX($startX + 5);
        $pdf->Cell($col_label - 5, 5.5, $label, 0, 0, 'L');
        $pdf->SetX($startX + $col_label);
        
        // Color negative amounts in red
        if ($amount < 0) {
            $pdf->SetTextColor(200, 0, 0);
        }
        $pdf->Cell($col_amount, 5.5, formatCurrency($amount), 0, 1, 'R');
        $pdf->SetTextColor(0, 0, 0);
    }
}

$pdf->SetX($startX);
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell($col_label, 6, 'Net Cash Provided by Operating Activities', 'T', 0, 'L');
$pdf->SetX($startX + $col_label);
if ($net_cash_operating < 0) {
    $pdf->SetTextColor(200, 0, 0);
}
$pdf->Cell($col_amount, 6, formatCurrency($net_cash_operating), 'T', 1, 'R');
$pdf->SetTextColor(0, 0, 0);

$pdf->Ln(3);

// CASH FLOWS FROM INVESTING ACTIVITIES
$pdf->SetFont('Arial', 'B', 9);
$pdf->SetX($startX);
$pdf->Cell($col_label, 7, 'B. CASH FLOWS FROM INVESTING ACTIVITIES', 0, 1, 'L');

$pdf->SetFont('Arial', '', 8);
foreach ($investing_activities as $label => $amount) {
    $pdf->SetX($startX + 5);
    $pdf->Cell($col_label - 5, 5.5, $label, 0, 0, 'L');
    $pdf->SetX($startX + $col_label);
    if ($amount < 0) {
        $pdf->SetTextColor(200, 0, 0);
    }
    $pdf->Cell($col_amount, 5.5, formatCurrency($amount), 0, 1, 'R');
    $pdf->SetTextColor(0, 0, 0);
}

$pdf->SetX($startX);
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell($col_label, 6, 'Net Cash Used in Investing Activities', 'T', 0, 'L');
$pdf->SetX($startX + $col_label);
if ($net_cash_investing < 0) {
    $pdf->SetTextColor(200, 0, 0);
}
$pdf->Cell($col_amount, 6, formatCurrency($net_cash_investing), 'T', 1, 'R');
$pdf->SetTextColor(0, 0, 0);

$pdf->Ln(3);

// CASH FLOWS FROM FINANCING ACTIVITIES
$pdf->SetFont('Arial', 'B', 9);
$pdf->SetX($startX);
$pdf->Cell($col_label, 7, 'C. CASH FLOWS FROM FINANCING ACTIVITIES', 0, 1, 'L');

$pdf->SetFont('Arial', '', 8);
foreach ($financing_activities as $label => $amount) {
    $pdf->SetX($startX + 5);
    $pdf->Cell($col_label - 5, 5.5, $label, 0, 0, 'L');
    $pdf->SetX($startX + $col_label);
    if ($amount < 0) {
        $pdf->SetTextColor(200, 0, 0);
    }
    $pdf->Cell($col_amount, 5.5, formatCurrency($amount), 0, 1, 'R');
    $pdf->SetTextColor(0, 0, 0);
}

$pdf->SetX($startX);
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell($col_label, 6, 'Net Cash Provided by Financing Activities', 'T', 0, 'L');
$pdf->SetX($startX + $col_label);
if ($net_cash_financing < 0) {
    $pdf->SetTextColor(200, 0, 0);
}
$pdf->Cell($col_amount, 6, formatCurrency($net_cash_financing), 'T', 1, 'R');
$pdf->SetTextColor(0, 0, 0);

$pdf->Ln(3);

// NET INCREASE/DECREASE IN CASH
$pdf->SetFont('Arial', 'B', 9);
$pdf->SetFillColor(245, 245, 245);
$pdf->SetX($startX);
$pdf->Cell($col_label, 7, 'D. NET INCREASE (DECREASE) IN CASH', 0, 0, 'L');
$pdf->SetX($startX + $col_label);
if ($net_increase_cash < 0) {
    $pdf->SetTextColor(200, 0, 0);
}
$pdf->Cell($col_amount, 7, formatCurrency($net_increase_cash), 0, 1, 'R');
$pdf->SetTextColor(0, 0, 0);

$pdf->Ln(2);

// CASH AT BEGINNING OF PERIOD
$pdf->SetFont('Arial', '', 8);
$pdf->SetX($startX + 5);
$pdf->Cell($col_label - 5, 5.5, 'Cash at Beginning of Period', 0, 0, 'L');
$pdf->SetX($startX + $col_label);
$pdf->Cell($col_amount, 5.5, formatCurrency($beginning_cash), 0, 1, 'R');

// CASH AT END OF PERIOD
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetFillColor(230, 240, 255);
$pdf->SetX($startX);
$pdf->Cell($col_label, 8, 'CASH AT END OF PERIOD', 'T', 0, 'L', true);
$pdf->SetX($startX + $col_label);
$pdf->Cell($col_amount, 8, formatCurrency($ending_cash), 'T', 1, 'R', true);

$currentY = $pdf->GetY() + 5;

// ==================== SUPPLEMENTARY INFORMATION ====================
$pdf->SetFont('Arial', 'B', 8);
$pdf->SetX($startX);
$pdf->Cell(0, 5, 'SUPPLEMENTARY INFORMATION:', 0, 1, 'L');

$pdf->SetFont('Arial', '', 7);
$pdf->SetX($startX + 5);
$pdf->Cell(60, 4, 'Interest Paid:', 0, 0, 'L');
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(50, 4, formatCurrency(125000.00), 0, 0, 'L');

$pdf->SetFont('Arial', '', 7);
$pdf->Cell(50, 4, 'Income Tax Paid:', 0, 0, 'L');
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(50, 4, formatCurrency(85000.00), 0, 1, 'L');

$pdf->SetX($startX + 5);
$pdf->SetFont('Arial', '', 7);
$pdf->Cell(60, 4, 'Non-cash Investing and Financing Activities:', 0, 1, 'L');
$pdf->SetX($startX + 10);
$pdf->SetFont('Arial', '', 6.5);
$pdf->Cell(0, 3.5, '- Acquisition of assets through loan payable: PHP 0.00', 0, 1, 'L');

$currentY = $pdf->GetY() + 4;

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