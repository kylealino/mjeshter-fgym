<?php
$this->request = \Config\Services::request();
$this->db = \Config\Database::connect();
$year = $this->request->getPostGet('year');
$this->session = session();
$this->cuser = $this->session->get('__xsys_myuserzicas__');
require APPPATH . 'ThirdParty/fpdf/fpdf.php';

if(empty($year)) {
    $year = date('Y');
}

$currentDate = date("Y-m-d");
$formattedDate = date("F j, Y", strtotime($currentDate));

// Monthly summary
$monthly_data = [];
$account_codes = ['4010-MEMBERSHIP', '4020-RETAIL', '4030-WALKIN', '4040-CROSSFIT', '4050-YOGA', '4060-ZUMBA'];

for($month = 1; $month <= 12; $month++) {
    $month_name = date('F', mktime(0, 0, 0, $month, 1));
    
    $query = $this->db->query("
        SELECT 
            COALESCE(SUM(`amount`),0) as total,
            COUNT(*) as count
        FROM `tbl_cash_receipts_journal`
        WHERE YEAR(`date`) = '$year' 
        AND MONTH(`date`) = '$month'
    ");
    $result = $query->getRow();
    
    $monthly_data[$month] = [
        'name' => $month_name,
        'total' => $result->total,
        'count' => $result->count
    ];
    
    foreach($account_codes as $code) {
        $query_code = $this->db->query("
            SELECT COALESCE(SUM(`amount`),0) as total
            FROM `tbl_cash_receipts_journal`
            WHERE YEAR(`date`) = '$year' 
            AND MONTH(`date`) = '$month'
            AND `account_code` = '$code'
        ");
        $result_code = $query_code->getRow();
        $monthly_data[$month]['breakdown'][$code] = $result_code->total;
    }
}

// Yearly totals breakdown
$total_yearly_breakdown = [];
foreach($account_codes as $code) {
    $query = $this->db->query("
        SELECT COALESCE(SUM(`amount`),0) as total
        FROM `tbl_cash_receipts_journal`
        WHERE YEAR(`date`) = '$year'
        AND `account_code` = '$code'
    ");
    $result = $query->getRow();
    $total_yearly_breakdown[$code] = $result->total;
}

$yearly_total = $this->db->query("
    SELECT COALESCE(SUM(`amount`),0) as total 
    FROM `tbl_cash_receipts_journal` 
    WHERE YEAR(`date`) = '$year'
")->getRow()->total;

$yearly_count = $this->db->query("
    SELECT COUNT(*) as total 
    FROM `tbl_cash_receipts_journal` 
    WHERE YEAR(`date`) = '$year'
")->getRow()->total;

$pdf = new FPDF('L', 'mm', 'A4');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetTitle('Monthly Cash Receipts Summary');

// Header
$pdf->SetFont('Times', 'B', 16);
$pdf->Cell(0, 10, 'CASH RECEIPTS JOURNAL', 0, 1, 'C');
$pdf->SetFont('Times', 'B', 12);
$pdf->Cell(0, 6, 'Monthly Cash Receipts Summary', 0, 1, 'C');
$pdf->SetFont('Times', 'I', 10);
$pdf->Cell(0, 6, 'For the Year Ended December 31, ' . $year, 0, 1, 'C');
$pdf->Ln(8);

// Summary Cards
$pdf->SetFont('Times', 'B', 10);
$pdf->Cell(95, 10, 'Total Transactions: ' . $yearly_count, 1, 0, 'L');
$pdf->Cell(95, 10, 'Total Revenue: PHP ' . number_format($yearly_total, 2), 1, 1, 'L');
$pdf->Ln(5);

// Calculate column widths (total width = 277mm with 10mm margins)
// 8 columns: Month(30), Membership(35), Retail(35), Walk-In(30), Crossfit(30), Yoga(30), Zumba(30), Total(35), Count(22)
// Total = 277mm

$pdf->SetFont('Times', 'B', 9);
$pdf->Cell(30, 8, 'Month', 1, 0, 'C');
$pdf->Cell(35, 8, 'Membership', 1, 0, 'C');
$pdf->Cell(35, 8, 'Retail', 1, 0, 'C');
$pdf->Cell(30, 8, 'Walk-In', 1, 0, 'C');
$pdf->Cell(30, 8, 'Crossfit', 1, 0, 'C');
$pdf->Cell(30, 8, 'Yoga', 1, 0, 'C');
$pdf->Cell(30, 8, 'Zumba', 1, 0, 'C');
$pdf->Cell(35, 8, 'Total', 1, 0, 'C');
$pdf->Cell(22, 8, 'Count', 1, 1, 'C');

$pdf->SetFont('Times', '', 9);

for($month = 1; $month <= 12; $month++) {
    $data = $monthly_data[$month];
    
    $pdf->Cell(30, 7, $data['name'], 1, 0, 'L');
    $pdf->Cell(35, 7, number_format($data['breakdown']['4010-MEMBERSHIP'] ?? 0, 2), 1, 0, 'R');
    $pdf->Cell(35, 7, number_format($data['breakdown']['4020-RETAIL'] ?? 0, 2), 1, 0, 'R');
    $pdf->Cell(30, 7, number_format($data['breakdown']['4030-WALKIN'] ?? 0, 2), 1, 0, 'R');
    $pdf->Cell(30, 7, number_format($data['breakdown']['4040-CROSSFIT'] ?? 0, 2), 1, 0, 'R');
    $pdf->Cell(30, 7, number_format($data['breakdown']['4050-YOGA'] ?? 0, 2), 1, 0, 'R');
    $pdf->Cell(30, 7, number_format($data['breakdown']['4060-ZUMBA'] ?? 0, 2), 1, 0, 'R');
    $pdf->Cell(35, 7, number_format($data['total'], 2), 1, 0, 'R');
    $pdf->Cell(22, 7, $data['count'], 1, 1, 'C');
}

// Yearly Totals Row
$pdf->SetFont('Times', 'B', 9);
$pdf->Cell(30, 8, 'YEARLY TOTAL', 1, 0, 'L');
$pdf->Cell(35, 8, number_format($total_yearly_breakdown['4010-MEMBERSHIP'] ?? 0, 2), 1, 0, 'R');
$pdf->Cell(35, 8, number_format($total_yearly_breakdown['4020-RETAIL'] ?? 0, 2), 1, 0, 'R');
$pdf->Cell(30, 8, number_format($total_yearly_breakdown['4030-WALKIN'] ?? 0, 2), 1, 0, 'R');
$pdf->Cell(30, 8, number_format($total_yearly_breakdown['4040-CROSSFIT'] ?? 0, 2), 1, 0, 'R');
$pdf->Cell(30, 8, number_format($total_yearly_breakdown['4050-YOGA'] ?? 0, 2), 1, 0, 'R');
$pdf->Cell(30, 8, number_format($total_yearly_breakdown['4060-ZUMBA'] ?? 0, 2), 1, 0, 'R');
$pdf->Cell(35, 8, number_format($yearly_total, 2), 1, 0, 'R');
$pdf->Cell(22, 8, $yearly_count, 1, 1, 'C');

// Monthly Trend Chart
$pdf->Ln(10);
$pdf->SetFont('Times', 'B', 11);
$pdf->Cell(0, 8, 'Monthly Revenue Trend', 0, 1, 'L');
$pdf->Ln(4);

$pdf->SetFont('Times', '', 8);
$max_total = 0;
foreach($monthly_data as $data) {
    if($data['total'] > $max_total) $max_total = $data['total'];
}
$max_total = $max_total > 0 ? $max_total : 1;
$chart_width = 180;

for($month = 1; $month <= 12; $month++) {
    $data = $monthly_data[$month];
    $bar_width = ($data['total'] / $max_total) * $chart_width;
    $bar_width = max(5, min($chart_width, $bar_width));
    
    $pdf->Cell(25, 5, substr($data['name'], 0, 3), 0, 0, 'L');
    
    $x = $pdf->GetX();
    $y = $pdf->GetY();
    $pdf->Rect($x, $y + 1, $bar_width, 3, 'F');
    $pdf->SetXY($x + $chart_width + 5, $y);
    $pdf->Cell(40, 5, 'PHP ' . number_format($data['total'], 2), 0, 1, 'L');
}

// Footer
$pdf->SetY(-20);
$pdf->SetFont('Times', 'I', 8);
$pdf->Cell(0, 5, 'Generated by: ' . $this->cuser, 0, 0, 'L');
$pdf->Cell(0, 5, 'Printed: ' . $formattedDate, 0, 0, 'C');
$pdf->Cell(0, 5, 'Page ' . $pdf->PageNo() . ' of {nb}', 0, 1, 'R');

$pdf->Output();
exit;
?>