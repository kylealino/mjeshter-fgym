<?php
$this->request = \Config\Services::request();
$this->db = \Config\Database::connect();
$from_date = $this->request->getPostGet('from_date');
$to_date = $this->request->getPostGet('to_date');
$this->session = session();
$this->cuser = $this->session->get('__xsys_myuserzicas__');
require APPPATH . 'ThirdParty/fpdf/fpdf.php';

if(empty($from_date)) {
    $from_date = date('Y-m-01');
}
if(empty($to_date)) {
    $to_date = date('Y-m-t');
}

$formattedFrom = date('F d, Y', strtotime($from_date));
$formattedTo = date('F d, Y', strtotime($to_date));

// Calculate the beginning date (day BEFORE from_date)
// If from_date is June 1, beginning_date = May 31
$beginning_date = date('Y-m-d', strtotime($from_date . ' -1 day'));
$formattedBeginning = date('F d, Y', strtotime($beginning_date));

// Cash Receipts Total for the selected period
$cash_receipts = $this->db->query("
    SELECT COALESCE(SUM(amount),0) as total 
    FROM tbl_cash_receipts_journal 
    WHERE date BETWEEN '$from_date' AND '$to_date'
")->getRow()->total;

// Cash Disbursements Total for the selected period
$cash_disbursements = $this->db->query("
    SELECT COALESCE(SUM(amount),0) as total 
    FROM tbl_cash_disbursement_journal 
    WHERE date BETWEEN '$from_date' AND '$to_date'
")->getRow()->total;

// Beginning Cash Balance (as of the day BEFORE from_date)
// Example: If period is June 1-30, this gets balance as of May 31
$beginning_balance = $this->db->query("
    SELECT COALESCE(SUM(amount),0) as total 
    FROM tbl_general_journal 
    WHERE account_code IN ('1010', '1020') 
    AND date <= '$beginning_date'
")->getRow()->total;

// Ending Cash Balance
$ending_balance = $beginning_balance + $cash_receipts - $cash_disbursements;

$pdf = new FPDF('P', 'mm', 'A4');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetTitle('Cash Reconciliation Report');

// Header
$pdf->SetFont('Times', 'B', 16);
$pdf->Cell(0, 10, 'CASH RECONCILIATION REPORT', 0, 1, 'C');
$pdf->SetFont('Times', 'B', 12);
$pdf->Cell(0, 6, 'For the Period Ended ' . $formattedTo, 0, 1, 'C');
$pdf->SetFont('Times', 'I', 10);
$pdf->Cell(0, 6, $formattedFrom . ' - ' . $formattedTo, 0, 1, 'C');
$pdf->Ln(8);

// Beginning Balance
$pdf->SetFont('Times', 'B', 11);
$pdf->Cell(130, 8, 'Beginning Cash Balance (as of ' . $formattedBeginning . ')', 0, 0, 'L');
$pdf->Cell(60, 8, number_format($beginning_balance, 2), 0, 1, 'R');
$pdf->Ln(5);

// Cash Receipts Table
$pdf->SetFont('Times', 'B', 11);
$pdf->Cell(0, 8, 'CASH RECEIPTS', 0, 1, 'L');
$pdf->SetFont('Times', 'B', 9);
$pdf->Cell(100, 7, 'Source', 1, 0, 'C');
$pdf->Cell(90, 7, 'Amount (PHP)', 1, 1, 'C');

$pdf->SetFont('Times', '', 9);
$receipts_detail = $this->db->query("
    SELECT account_code, SUM(amount) as total
    FROM tbl_cash_receipts_journal
    WHERE date BETWEEN '$from_date' AND '$to_date'
    GROUP BY account_code
    ORDER BY account_code ASC
")->getResultArray();

if(count($receipts_detail) > 0) {
    foreach($receipts_detail as $rec) {
        $acc_query = $this->db->query("SELECT account_name FROM tbl_chart_of_accounts WHERE account_code = '{$rec['account_code']}'");
        $acc_name = $acc_query->getRow();
        $account_name = $acc_name ? $acc_name->account_name : $rec['account_code'];
        
        $pdf->Cell(100, 7, substr($rec['account_code'] . ' - ' . $account_name, 0, 55), 1, 0, 'L');
        $pdf->Cell(90, 7, number_format($rec['total'], 2), 1, 1, 'R');
    }
} else {
    $pdf->Cell(100, 7, 'No receipts for this period', 1, 0, 'L');
    $pdf->Cell(90, 7, '0.00', 1, 1, 'R');
}

$pdf->SetFont('Times', 'B', 9);
$pdf->Cell(100, 7, 'TOTAL CASH RECEIPTS', 1, 0, 'R');
$pdf->Cell(90, 7, number_format($cash_receipts, 2), 1, 1, 'R');
$pdf->Ln(5);

// Cash Disbursements Table
$pdf->SetFont('Times', 'B', 11);
$pdf->Cell(0, 8, 'CASH DISBURSEMENTS', 0, 1, 'L');
$pdf->SetFont('Times', 'B', 9);
$pdf->Cell(100, 7, 'Source', 1, 0, 'C');
$pdf->Cell(90, 7, 'Amount (PHP)', 1, 1, 'C');

$pdf->SetFont('Times', '', 9);
$disbursements_detail = $this->db->query("
    SELECT account_code, SUM(amount) as total
    FROM tbl_cash_disbursement_journal
    WHERE date BETWEEN '$from_date' AND '$to_date'
    GROUP BY account_code
    ORDER BY account_code ASC
")->getResultArray();

if(count($disbursements_detail) > 0) {
    foreach($disbursements_detail as $dis) {
        $acc_query = $this->db->query("SELECT account_name FROM tbl_chart_of_accounts WHERE account_code = '{$dis['account_code']}'");
        $acc_name = $acc_query->getRow();
        $account_name = $acc_name ? $acc_name->account_name : $dis['account_code'];
        
        $pdf->Cell(100, 7, substr($dis['account_code'] . ' - ' . $account_name, 0, 55), 1, 0, 'L');
        $pdf->Cell(90, 7, number_format($dis['total'], 2), 1, 1, 'R');
    }
} else {
    $pdf->Cell(100, 7, 'No disbursements for this period', 1, 0, 'L');
    $pdf->Cell(90, 7, '0.00', 1, 1, 'R');
}

$pdf->SetFont('Times', 'B', 9);
$pdf->Cell(100, 7, 'TOTAL CASH DISBURSEMENTS', 1, 0, 'R');
$pdf->Cell(90, 7, number_format($cash_disbursements, 2), 1, 1, 'R');
$pdf->Ln(5);

// Ending Balance
$pdf->SetFont('Times', 'B', 11);
$pdf->Cell(130, 8, 'Ending Cash Balance (as of ' . $formattedTo . ')', 0, 0, 'L');
$pdf->Cell(60, 8, number_format($ending_balance, 2), 0, 1, 'R');
$pdf->Ln(5);

// Formula
$pdf->SetFont('Times', 'I', 9);
$pdf->Cell(0, 5, 'Formula: Beginning Balance (' . $formattedBeginning . ') + Total Receipts - Total Disbursements = Ending Balance (' . $formattedTo . ')', 0, 1, 'C');
$pdf->Cell(0, 5, number_format($beginning_balance, 2) . ' + ' . number_format($cash_receipts, 2) . ' - ' . number_format($cash_disbursements, 2) . ' = ' . number_format($ending_balance, 2), 0, 1, 'C');

// Footer
$pdf->SetY(-20);
$pdf->SetFont('Times', 'I', 8);
$pdf->Cell(0, 5, 'Generated by: ' . $this->cuser, 0, 0, 'L');
$pdf->Cell(0, 5, 'Page ' . $pdf->PageNo() . ' of {nb}', 0, 1, 'R');

$pdf->Output();
exit;
?>