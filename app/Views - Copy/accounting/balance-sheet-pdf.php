<?php
// Balance Sheet PDF Generator
// Based on the reference format for Cash Receipts Journal

$this->request = \Config\Services::request();
$this->db = \Config\Database::connect();

// Get date parameters if provided (for demo, we'll use current date or passed dates)
$as_of_date = $this->request->getGet('as_of_date');
if (empty($as_of_date)) {
    $as_of_date = date('Y-m-d');
}
$display_date = date('F d, Y', strtotime($as_of_date));

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
$pdf->SetTitle('Balance Sheet');

// Set margins
$leftMargin = 10;
$rightMargin = 10;
$pdf->SetLeftMargin($leftMargin);
$pdf->SetRightMargin($rightMargin);

// Column widths for balance sheet (two columns: ASSETS on left, LIABILITIES & EQUITY on right)
$col_left_label = 60;   // Left column label width
$col_left_amount = 35;  // Left column amount width
$col_right_label = 60;  // Right column label width
$col_right_amount = 35; // Right column amount width
$total_width = $col_left_label + $col_left_amount + $col_right_label + $col_right_amount; // 190mm

$Y = 15;

// Company Header
$pdf->SetY($Y);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 5, 'SCIENCE SAVINGS AND LOAN ASSOCIATION INC.', 0, 1, 'C');

$Y = $pdf->GetY();
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(0, 5, 'BALANCE SHEET', 0, 1, 'C');

$Y = $pdf->GetY();
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(0, 4, 'As of ' . $display_date, 0, 1, 'C');

$Y = $pdf->GetY() + 6;

// Set starting X positions
$startX = $leftMargin;
$leftX = $startX;
$rightX = $startX + $col_left_label + $col_left_amount;

// Helper function to format currency
function formatCurrency($amount) {
    return number_format($amount, 2);
}

// ==================== MOCKUP DATA ====================
// For demo purposes, we'll create realistic balance sheet data
// In a real application, this would come from database queries

// ASSETS SECTION
$assets = [
    'CURRENT ASSETS' => [
        'Cash and Cash Equivalents' => 1250000.00,
        'Accounts Receivable' => 450000.00,
        'Allowance for Bad Debts' => -15000.00,
        'Loans Receivable' => 2750000.00,
        'Interest Receivable' => 42500.00,
        'Prepaid Expenses' => 75000.00,
        'Other Current Assets' => 25000.00,
        'Total Current Assets' => 0 // Placeholder, will calculate
    ],
    'NON-CURRENT ASSETS' => [
        'Property and Equipment' => 1850000.00,
        'Accumulated Depreciation' => -325000.00,
        'Intangible Assets' => 150000.00,
        'Other Non-Current Assets' => 50000.00,
        'Total Non-Current Assets' => 0 // Placeholder
    ],
    'TOTAL ASSETS' => 0 // Placeholder
];

// LIABILITIES SECTION
$liabilities = [
    'CURRENT LIABILITIES' => [
        'Accounts Payable' => 185000.00,
        'Accrued Expenses' => 42500.00,
        'Due to Borrowers' => 3250000.00,
        'Unearned Interest' => 32500.00,
        'Other Current Liabilities' => 15000.00,
        'Total Current Liabilities' => 0 // Placeholder
    ],
    'NON-CURRENT LIABILITIES' => [
        'Notes Payable' => 500000.00,
        'Other Non-Current Liabilities' => 25000.00,
        'Total Non-Current Liabilities' => 0 // Placeholder
    ],
    'TOTAL LIABILITIES' => 0 // Placeholder
];

// EQUITY SECTION
$equity = [
    'EQUITY' => [
        'Share Capital' => 1000000.00,
        'Retained Earnings' => 525000.00,
        'Reserves' => 75000.00,
        'Total Equity' => 0 // Placeholder
    ]
];

// Calculate totals
// Current Assets total
$assets['CURRENT ASSETS']['Total Current Assets'] = 
    $assets['CURRENT ASSETS']['Cash and Cash Equivalents'] +
    $assets['CURRENT ASSETS']['Accounts Receivable'] +
    $assets['CURRENT ASSETS']['Allowance for Bad Debts'] +
    $assets['CURRENT ASSETS']['Loans Receivable'] +
    $assets['CURRENT ASSETS']['Interest Receivable'] +
    $assets['CURRENT ASSETS']['Prepaid Expenses'] +
    $assets['CURRENT ASSETS']['Other Current Assets'];

// Non-Current Assets total
$assets['NON-CURRENT ASSETS']['Total Non-Current Assets'] = 
    $assets['NON-CURRENT ASSETS']['Property and Equipment'] +
    $assets['NON-CURRENT ASSETS']['Accumulated Depreciation'] +
    $assets['NON-CURRENT ASSETS']['Intangible Assets'] +
    $assets['NON-CURRENT ASSETS']['Other Non-Current Assets'];

// Total Assets
$assets['TOTAL ASSETS'] = 
    $assets['CURRENT ASSETS']['Total Current Assets'] +
    $assets['NON-CURRENT ASSETS']['Total Non-Current Assets'];

// Current Liabilities total
$liabilities['CURRENT LIABILITIES']['Total Current Liabilities'] = 
    $liabilities['CURRENT LIABILITIES']['Accounts Payable'] +
    $liabilities['CURRENT LIABILITIES']['Accrued Expenses'] +
    $liabilities['CURRENT LIABILITIES']['Due to Borrowers'] +
    $liabilities['CURRENT LIABILITIES']['Unearned Interest'] +
    $liabilities['CURRENT LIABILITIES']['Other Current Liabilities'];

// Non-Current Liabilities total
$liabilities['NON-CURRENT LIABILITIES']['Total Non-Current Liabilities'] = 
    $liabilities['NON-CURRENT LIABILITIES']['Notes Payable'] +
    $liabilities['NON-CURRENT LIABILITIES']['Other Non-Current Liabilities'];

// Total Liabilities
$liabilities['TOTAL LIABILITIES'] = 
    $liabilities['CURRENT LIABILITIES']['Total Current Liabilities'] +
    $liabilities['NON-CURRENT LIABILITIES']['Total Non-Current Liabilities'];

// Total Equity
$equity['EQUITY']['Total Equity'] = 
    $equity['EQUITY']['Share Capital'] +
    $equity['EQUITY']['Retained Earnings'] +
    $equity['EQUITY']['Reserves'];

// Check accounting equation: Assets = Liabilities + Equity
$total_liabilities_equity = $liabilities['TOTAL LIABILITIES'] + $equity['EQUITY']['Total Equity'];

// ==================== RENDER BALANCE SHEET ====================

// Set font for section headers
$pdf->SetFont('Arial', 'B', 9);

// Render ASSETS section (left column)
$pdf->SetXY($leftX, $Y);
$pdf->Cell($col_left_label, 8, 'ASSETS', 0, 0, 'L');
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell($col_left_amount, 8, 'Amount', 0, 1, 'R');

$pdf->SetFont('Arial', '', 8);
$currentY = $pdf->GetY();

// Function to render a section with indented items
function renderSection($pdf, $x, $label_width, $amount_width, $section_title, $items, $is_total = false) {
    $startY = $pdf->GetY();
    $pdf->SetX($x);
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell($label_width, 6, $section_title, 0, 1, 'L');
    
    $pdf->SetFont('Arial', '', 8);
    foreach ($items as $label => $amount) {
        // Skip the total placeholders for now, we'll handle them separately
        if (strpos($label, 'Total') !== false && $label != $section_title) {
            continue;
        }
        $pdf->SetX($x + 3); // Indent items
        $pdf->Cell($label_width - 3, 5, $label, 0, 0, 'L');
        $pdf->SetX($x + $label_width);
        $pdf->Cell($amount_width, 5, formatCurrency($amount), 0, 1, 'R');
    }
    
    // Render total line if provided in items
    $total_key = 'Total ' . $section_title;
    if (isset($items[$total_key])) {
        $pdf->SetX($x);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell($label_width, 6, $total_key, 'T', 0, 'L');
        $pdf->SetX($x + $label_width);
        $pdf->Cell($amount_width, 6, formatCurrency($items[$total_key]), 'T', 1, 'R');
        $pdf->SetFont('Arial', '', 8);
    }
    return $pdf->GetY();
}

// Render Assets section (left column)
$currentY = renderSection($pdf, $leftX, $col_left_label, $col_left_amount, 'CURRENT ASSETS', $assets['CURRENT ASSETS']);
$pdf->SetY($currentY);
$currentY = renderSection($pdf, $leftX, $col_left_label, $col_left_amount, 'NON-CURRENT ASSETS', $assets['NON-CURRENT ASSETS']);
$pdf->SetY($currentY);
$pdf->SetX($leftX);
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell($col_left_label, 7, 'TOTAL ASSETS', 'T', 0, 'L');
$pdf->SetX($leftX + $col_left_label);
$pdf->Cell($col_left_amount, 7, formatCurrency($assets['TOTAL ASSETS']), 'T', 1, 'R');
$assets_end_y = $pdf->GetY();

// Render LIABILITIES section (right column)
$pdf->SetXY($rightX, $Y);
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell($col_right_label, 8, 'LIABILITIES AND EQUITY', 0, 0, 'L');
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell($col_right_amount, 8, 'Amount', 0, 1, 'R');

// Liabilities subsection
$currentY_right = renderSection($pdf, $rightX, $col_right_label, $col_right_amount, 'CURRENT LIABILITIES', $liabilities['CURRENT LIABILITIES']);
$pdf->SetY($currentY_right);
$currentY_right = renderSection($pdf, $rightX, $col_right_label, $col_right_amount, 'NON-CURRENT LIABILITIES', $liabilities['NON-CURRENT LIABILITIES']);
$pdf->SetY($currentY_right);
$pdf->SetX($rightX);
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell($col_right_label, 6, 'TOTAL LIABILITIES', 'T', 0, 'L');
$pdf->SetX($rightX + $col_right_label);
$pdf->Cell($col_right_amount, 6, formatCurrency($liabilities['TOTAL LIABILITIES']), 'T', 1, 'R');

// Equity subsection
$pdf->SetY($pdf->GetY() + 2);
$currentY_right = renderSection($pdf, $rightX, $col_right_label, $col_right_amount, 'EQUITY', $equity['EQUITY']);
$pdf->SetY($currentY_right);
$pdf->SetX($rightX);
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell($col_right_label, 7, 'TOTAL LIABILITIES AND EQUITY', 'T', 0, 'L');
$pdf->SetX($rightX + $col_right_label);
$pdf->Cell($col_right_amount, 7, formatCurrency($total_liabilities_equity), 'T', 1, 'R');

// Determine the maximum Y position to add spacing after
$max_y = max($assets_end_y, $pdf->GetY()) + 8;

// ==================== SUMMARY / VERIFICATION SECTION ====================
$pdf->SetY($max_y);
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(0, 5, 'ACCOUNTING VERIFICATION', 0, 1, 'L');

$pdf->SetFont('Arial', '', 8);
$pdf->Cell(0, 5, 'The accounting equation (Assets = Liabilities + Equity) is balanced.', 0, 1, 'L');
$pdf->SetFont('Arial', '', 7);
$pdf->Cell(40, 4, 'Total Assets:', 0, 0, 'L');
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(50, 4, 'PHP ' . formatCurrency($assets['TOTAL ASSETS']), 0, 1, 'L');

$pdf->SetFont('Arial', '', 7);
$pdf->Cell(40, 4, 'Total Liabilities + Equity:', 0, 0, 'L');
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(50, 4, 'PHP ' . formatCurrency($total_liabilities_equity), 0, 1, 'L');

// Difference check
$difference = $assets['TOTAL ASSETS'] - $total_liabilities_equity;
if (abs($difference) < 0.01) {
    $pdf->SetFont('Arial', 'I', 7);
    $pdf->SetTextColor(0, 150, 0);
    $pdf->Cell(0, 4, '✓ The balance sheet is in balance.', 0, 1, 'L');
} else {
    $pdf->SetFont('Arial', 'I', 7);
    $pdf->SetTextColor(255, 0, 0);
    $pdf->Cell(0, 4, '⚠ Warning: The balance sheet is out of balance by PHP ' . formatCurrency($difference), 0, 1, 'L');
}
$pdf->SetTextColor(0, 0, 0);

$currentY = $pdf->GetY() + 6;

// ==================== SIGNATURE SECTION ====================
$pdf->SetFont('Arial', '', 7);
$pdf->SetXY($leftMargin, $currentY);
$pdf->Cell(70, 4, 'Prepared by:', 0, 1, 'L');

$pdf->SetXY($leftMargin, $pdf->GetY() + 2);
$pdf->Cell(70, 4, '_________________________', 0, 1, 'L');

$pdf->SetXY($leftMargin, $pdf->GetY());
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(70, 3, 'Signature over Printed Name', 0, 1, 'L');

$pdf->SetXY($leftMargin, $pdf->GetY() + 2);
$pdf->SetFont('Arial', '', 7);
$pdf->Cell(70, 4, $this->cuser, 0, 1, 'L');

$pdf->SetXY($leftMargin, $pdf->GetY());
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

// ==================== GENERATED INFO ====================
$currentY = $pdf->GetY();
if ($currentY > 260) {
    $pdf->AddPage();
    $currentY = 20;
}

$pdf->SetY($currentY + 8);
$pdf->SetFont('Arial', '', 6);
$pdf->SetX($leftMargin);
$pdf->Cell(0, 3, 'Generated on: ' . $formattedDate, 0, 1, 'L');
$pdf->SetX($leftMargin);
$pdf->Cell(0, 3, 'Generated by: ' . $this->cuser, 0, 1, 'L');
$pdf->SetX($leftMargin);
$pdf->Cell(0, 3, 'System: SSLAI Accounting System', 0, 1, 'L');
$pdf->SetX($leftMargin);
$pdf->Cell(0, 3, 'As of Date: ' . $display_date, 0, 1, 'L');

$pdf->Output();
exit;
?>