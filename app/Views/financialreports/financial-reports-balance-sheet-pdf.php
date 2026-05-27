<?php
$this->request = \Config\Services::request();
$this->db = \Config\Database::connect();
$as_of_date = $this->request->getPostGet('as_of_date');
$this->session = session();
$this->cuser = $this->session->get('__xsys_myuserzicas__');
require APPPATH . 'ThirdParty/fpdf/fpdf.php';

if(empty($as_of_date)) {
    $as_of_date = date('Y-m-d');
}

$formattedDate = date('F d, Y', strtotime($as_of_date));

// =============================================
// CASH BALANCE (Cash Receipts - Cash Disbursements)
// =============================================

// Total Cash Receipts (Money IN)
$cash_receipts_total = $this->db->query("
    SELECT COALESCE(SUM(amount),0) as total 
    FROM tbl_cash_receipts_journal 
    WHERE date <= '$as_of_date'
")->getRow()->total;

// Total Cash Disbursements (Money OUT)
$cash_disbursements_total = $this->db->query("
    SELECT COALESCE(SUM(amount),0) as total 
    FROM tbl_cash_disbursement_journal 
    WHERE date <= '$as_of_date'
")->getRow()->total;

// Net Cash Balance = Receipts - Disbursements
$cash_balance = $cash_receipts_total - $cash_disbursements_total;

// =============================================
// ACCOUNTS RECEIVABLE
// =============================================
$ar_balance = $this->db->query("
    SELECT COALESCE(SUM(amount),0) as total 
    FROM tbl_general_journal 
    WHERE account_code = '1030' 
    AND date <= '$as_of_date'
")->getRow()->total;

// =============================================
// INVENTORY (Products value)
// =============================================
$inventory_balance = $this->db->query("
    SELECT COALESCE(SUM(stock_qty * purchase_price),0) as total 
    FROM tbl_products
")->getRow()->total;

// =============================================
// FIXED ASSETS (Gym Equipment)
// =============================================
$assets_total = $this->db->query("
    SELECT COALESCE(SUM(acquisition_cost),0) as total 
    FROM tbl_gym_assets 
    WHERE status = 'ACTIVE'
")->getRow()->total;

// =============================================
// TOTAL ASSETS
// =============================================
$total_assets = $cash_balance + $ar_balance + $inventory_balance + $assets_total;

// =============================================
// LIABILITIES
// =============================================
$accounts_payable = $this->db->query("
    SELECT COALESCE(SUM(amount),0) as total 
    FROM tbl_cash_disbursement_journal 
    WHERE account_code = '2010' 
    AND date <= '$as_of_date'
")->getRow()->total;

// =============================================
// EQUITY
// =============================================
$owner_capital = $this->db->query("
    SELECT COALESCE(SUM(amount),0) as total 
    FROM tbl_general_journal 
    WHERE account_code = '3010' 
    AND date <= '$as_of_date'
")->getRow()->total;

// Retained Earnings (Net Income)
$total_revenue = $this->db->query("
    SELECT COALESCE(SUM(amount),0) as total 
    FROM tbl_cash_receipts_journal 
    WHERE date <= '$as_of_date'
")->getRow()->total;

$total_expenses = $this->db->query("
    SELECT COALESCE(SUM(amount),0) as total 
    FROM tbl_cash_disbursement_journal 
    WHERE date <= '$as_of_date'
")->getRow()->total;

$net_income = $total_revenue - $total_expenses;

$total_liabilities = $accounts_payable;
$total_equity = $owner_capital + $net_income;
$total_liabilities_equity = $total_liabilities + $total_equity;

$pdf = new FPDF('P', 'mm', 'A4');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetTitle('Balance Sheet');

// Header
$pdf->SetFont('Times', 'B', 16);
$pdf->Cell(0, 10, 'BALANCE SHEET', 0, 1, 'C');
$pdf->SetFont('Times', 'B', 12);
$pdf->Cell(0, 6, 'As of ' . $formattedDate, 0, 1, 'C');
$pdf->Ln(8);

// ASSETS
$pdf->SetFont('Times', 'B', 11);
$pdf->Cell(95, 8, 'ASSETS', 1, 0, 'C');
$pdf->Cell(95, 8, 'Amount (PHP)', 1, 1, 'C');

$pdf->SetFont('Times', '', 10);
$pdf->Cell(95, 7, '  Cash on Hand & In Bank', 1, 0, 'L');
$pdf->Cell(95, 7, number_format($cash_balance, 2), 1, 1, 'R');
$pdf->Cell(95, 7, '  Accounts Receivable', 1, 0, 'L');
$pdf->Cell(95, 7, number_format($ar_balance, 2), 1, 1, 'R');
$pdf->Cell(95, 7, '  Inventory', 1, 0, 'L');
$pdf->Cell(95, 7, number_format($inventory_balance, 2), 1, 1, 'R');
$pdf->Cell(95, 7, '  Gym Assets (Equipment)', 1, 0, 'L');
$pdf->Cell(95, 7, number_format($assets_total, 2), 1, 1, 'R');

$pdf->SetFont('Times', 'B', 10);
$pdf->Cell(95, 8, 'TOTAL ASSETS', 1, 0, 'R');
$pdf->Cell(95, 8, number_format($total_assets, 2), 1, 1, 'R');

$pdf->Ln(8);

// LIABILITIES
$pdf->SetFont('Times', 'B', 11);
$pdf->Cell(95, 8, 'LIABILITIES', 1, 0, 'C');
$pdf->Cell(95, 8, 'Amount (PHP)', 1, 1, 'C');

$pdf->SetFont('Times', '', 10);
$pdf->Cell(95, 7, '  Accounts Payable', 1, 0, 'L');
$pdf->Cell(95, 7, number_format($accounts_payable, 2), 1, 1, 'R');

$pdf->SetFont('Times', 'B', 10);
$pdf->Cell(95, 8, 'TOTAL LIABILITIES', 1, 0, 'R');
$pdf->Cell(95, 8, number_format($total_liabilities, 2), 1, 1, 'R');

$pdf->Ln(8);

// EQUITY
$pdf->SetFont('Times', 'B', 11);
$pdf->Cell(95, 8, 'EQUITY', 1, 0, 'C');
$pdf->Cell(95, 8, 'Amount (PHP)', 1, 1, 'C');

$pdf->SetFont('Times', '', 10);
$pdf->Cell(95, 7, '  Owner\'s Capital', 1, 0, 'L');
$pdf->Cell(95, 7, number_format($owner_capital, 2), 1, 1, 'R');
$pdf->Cell(95, 7, '  Retained Earnings (Net Income)', 1, 0, 'L');
$pdf->Cell(95, 7, number_format($net_income, 2), 1, 1, 'R');

$pdf->SetFont('Times', 'B', 10);
$pdf->Cell(95, 8, 'TOTAL EQUITY', 1, 0, 'R');
$pdf->Cell(95, 8, number_format($total_equity, 2), 1, 1, 'R');

$pdf->Ln(8);

// TOTAL LIABILITIES & EQUITY
$pdf->SetFont('Times', 'B', 11);
$pdf->Cell(95, 8, 'TOTAL LIABILITIES & EQUITY', 1, 0, 'R');
$pdf->Cell(95, 8, number_format($total_liabilities_equity, 2), 1, 1, 'R');

$pdf->Ln(5);

// Verification
$pdf->SetFont('Times', 'I', 9);
if(abs($total_assets - $total_liabilities_equity) < 0.01) {
    $pdf->SetTextColor(0, 128, 0);
    $pdf->Cell(0, 6, '✓ The Balance Sheet is balanced. Assets = Liabilities + Equity', 0, 1, 'C');
} else {
    $pdf->SetTextColor(255, 0, 0);
    $pdf->Cell(0, 6, '✗ The Balance Sheet is OUT OF BALANCE!', 0, 1, 'C');
}
$pdf->SetTextColor(0, 0, 0);

// Footer
$pdf->SetY(-20);
$pdf->SetFont('Times', 'I', 8);
$pdf->Cell(0, 5, 'Generated by: ' . $this->cuser, 0, 0, 'L');
$pdf->Cell(0, 5, 'Page ' . $pdf->PageNo() . ' of {nb}', 0, 1, 'R');

$pdf->Output();
exit;
?>