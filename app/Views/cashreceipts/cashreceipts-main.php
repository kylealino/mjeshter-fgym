<?php
$this->request = \Config\Services::request();
$this->db = \Config\Database::connect();

// ==============================
// FETCH DATA
// ==============================
$query = $this->db->query("SELECT * FROM tbl_cash_receipts_journal ORDER BY journal_id DESC");
$cashreceipts = $query->getResultArray();

// ==============================
// DASHBOARD CALCULATIONS
// ==============================
$total_receipts = $this->db->query("SELECT COUNT(*) as total FROM tbl_cash_receipts_journal")->getRow()->total;
$total_amount = $this->db->query("SELECT COALESCE(SUM(amount),0) as total FROM tbl_cash_receipts_journal")->getRow()->total;

// ==============================
// FETCH CHART OF ACCOUNTS FOR DROPDOWN
// ==============================
$coa_query = $this->db->query("
    SELECT account_code, account_name 
    FROM tbl_chart_of_accounts 
    WHERE account_type = 'REVENUE' AND is_active = 1 
    ORDER BY account_code ASC
");
$revenue_accounts = $coa_query->getResultArray();

// ==============================
// CALCULATE TOTALS FOR REPORT SECTION
// ==============================
$current_month = date('m');
$current_year = date('Y');

$monthly_total = $this->db->query("
    SELECT COALESCE(SUM(amount),0) as total 
    FROM tbl_cash_receipts_journal 
    WHERE MONTH(date) = '$current_month' AND YEAR(date) = '$current_year'
")->getRow()->total;

$walkin_total = $this->db->query("
    SELECT COALESCE(SUM(amount),0) as total 
    FROM tbl_cash_receipts_journal 
    WHERE account_code = '4030-WALKIN'
")->getRow()->total;

$membership_total = $this->db->query("
    SELECT COALESCE(SUM(amount),0) as total 
    FROM tbl_cash_receipts_journal 
    WHERE account_code = '4010-MEMBERSHIP'
")->getRow()->total;

$retail_total = $this->db->query("
    SELECT COALESCE(SUM(amount),0) as total 
    FROM tbl_cash_receipts_journal 
    WHERE account_code = '4020-RETAIL'
")->getRow()->total;

echo view('templates/myheader.php');
?>
<style>
    :root {
        --primary: #1e3a5f;
        --primary-dark: #0f2b44;
        --danger: #dc2626;
        --danger-dark: #b91c1c;
        --success: #10b981;
        --warning: #f59e0b;
        --info: #3b82f6;
        --gray-50: #f8fafc;
        --gray-100: #f1f5f9;
        --gray-200: #e2e8f0;
        --gray-300: #cbd5e1;
        --gray-400: #94a3b8;
        --gray-500: #64748b;
        --gray-600: #475569;
        --gray-700: #334155;
        --gray-800: #1e293b;
    }

    body {
        background: var(--gray-50);
    }

    /* Dashboard Cards - Matching Attendance Module */
    .attendance-card {
        border-radius: 20px;
        border: 1px solid var(--gray-200);
        transition: all 0.3s ease;
        background: #ffffff;
        margin-bottom: 20px;
    }

    .attendance-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 20px -12px rgba(0,0,0,0.1);
        border-color: var(--gray-300);
    }

    .attendance-card .card-body {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px;
    }

    .attendance-value {
        font-size: 32px;
        font-weight: 700;
        line-height: 1.2;
        color: var(--gray-800);
    }

    .attendance-icon {
        font-size: 42px;
        opacity: 0.12;
        color: var(--primary);
    }

    .attendance-label {
        font-size: 12px;
        font-weight: 600;
        color: var(--gray-500);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 6px;
    }

    .attendance-sub {
        font-size: 11px;
        color: var(--gray-400);
        margin-top: 6px;
    }

    /* Cards */
    .card {
        border-radius: 20px;
        border: 1px solid var(--gray-200);
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        background: #ffffff;
        margin-bottom: 24px;
    }

    .card-header {
        background: #ffffff;
        border-bottom: 1px solid var(--gray-200);
        padding: 16px 24px;
    }

    .card-body {
        padding: 20px 24px;
    }

    /* Form Controls */
    .form-label {
        font-size: 12px;
        font-weight: 600;
        color: var(--gray-600);
        margin-bottom: 6px;
        display: block;
    }

    .form-control, .form-select {
        border: 1.5px solid var(--gray-200);
        border-radius: 12px;
        padding: 10px 14px;
        font-size: 13px;
        color: var(--gray-700);
        background: #ffffff;
        transition: all 0.2s;
        width: 100%;
    }

    .form-control:focus, .form-select:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(30,58,95,0.08);
    }

    /* Buttons */
    .btn-danger {
        background: var(--danger);
        border: none;
        border-radius: 12px;
        padding: 10px 20px;
        font-size: 13px;
        font-weight: 600;
        transition: all 0.2s;
    }

    .btn-danger:hover {
        background: var(--danger-dark);
        transform: translateY(-1px);
    }

    /* Badges */
    .badge {
        font-size: 10px;
        font-weight: 600;
        padding: 5px 12px;
        border-radius: 30px;
        letter-spacing: 0.3px;
    }

    /* Breadcrumb */
    .breadcrumb {
        background: transparent;
        padding: 0;
        margin-bottom: 1rem;
    }

    .breadcrumb-item a {
        text-decoration: none;
        color: var(--gray-500);
        font-size: 12px;
    }

    .breadcrumb-item.active {
        color: var(--primary);
        font-weight: 600;
    }

    /* Tables */
    .table {
        width: 100%;
        border-collapse: collapse;
    }

    .table thead th {
        font-size: 12px;
        font-weight: 600;
        color: var(--gray-500);
        background: var(--gray-50);
        border-bottom: 1px solid var(--gray-200);
        padding: 14px 12px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        text-align: center;
    }

    .table tbody td {
        font-size: 13px;
        color: var(--gray-700);
        padding: 12px;
        border-bottom: 1px solid var(--gray-100);
        vertical-align: middle;
        text-align: center;
    }

    .table-hover tbody tr:hover {
        background: var(--gray-50);
    }

    /* DataTables */
    .dataTables_wrapper {
        font-family: 'Inter', sans-serif;
        overflow-x: visible !important;
    }

    .dataTables_filter {
        float: right;
        margin-bottom: 20px;
    }

    .dataTables_filter label {
        font-size: 12px;
        font-weight: 500;
        color: var(--gray-500);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .dataTables_filter input {
        width: 220px;
        padding: 8px 14px;
        border: 1.5px solid var(--gray-200);
        border-radius: 12px;
        font-size: 12px;
        transition: all 0.2s;
    }

    .dataTables_filter input:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(30,58,95,0.08);
    }

    .dataTables_paginate {
        float: right;
        margin-top: 20px;
    }

    .dataTables_paginate .paginate_button {
        padding: 6px 12px !important;
        margin: 0 3px !important;
        border-radius: 10px !important;
        border: 1px solid var(--gray-200) !important;
        background: #ffffff !important;
        color: var(--gray-600) !important;
        font-size: 11px !important;
        font-weight: 600 !important;
        transition: all 0.2s;
    }

    .dataTables_paginate .paginate_button.current {
        background: var(--danger) !important;
        border-color: var(--danger) !important;
        color: #ffffff !important;
    }

    .dataTables_paginate .paginate_button:hover {
        background: var(--gray-50) !important;
        border-color: var(--gray-300) !important;
        color: var(--primary) !important;
    }

    .dataTables_info {
        float: left;
        font-size: 12px;
        color: var(--gray-500);
        margin-top: 20px;
    }

    /* Professional Report Section Styles */
    .report-section {
        background: #ffffff;
        border-radius: 20px;
        padding: 20px 24px;
        margin-bottom: 24px;
        border: 1px solid var(--gray-200);
    }
    
    .report-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 18px;
        padding-bottom: 12px;
        border-bottom: 1px solid var(--gray-200);
    }

    .report-header h6 {
        font-size: 14px;
        font-weight: 600;
        color: var(--gray-800);
        margin: 0;
        letter-spacing: 0.3px;
    }

    .report-header i {
        color: var(--gray-500);
        font-size: 18px;
    }

    .report-filters {
        display: flex;
        gap: 16px;
        flex-wrap: wrap;
        align-items: flex-end;
        margin-bottom: 20px;
    }

    .filter-group {
        flex: 1;
        min-width: 160px;
    }

    .filter-group label {
        display: block;
        font-size: 11px;
        font-weight: 600;
        color: var(--gray-500);
        margin-bottom: 6px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .filter-group input {
        width: 100%;
        padding: 9px 12px;
        border: 1.5px solid var(--gray-200);
        border-radius: 12px;
        font-size: 13px;
        color: var(--gray-700);
        background: #ffffff;
        transition: all 0.2s;
    }

    .filter-group input:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(30,58,95,0.08);
    }

    .report-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        align-items: center;
    }

    .btn-report-action {
        background: transparent;
        border: 1.5px solid var(--gray-200);
        padding: 8px 16px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 500;
        color: var(--gray-600);
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-report-action i {
        font-size: 14px;
    }

    .btn-report-action:hover {
        background: var(--gray-50);
        border-color: var(--danger);
        color: var(--danger);
    }

    .btn-primary-action {
        background: var(--danger);
        border-color: var(--danger);
        color: #ffffff;
    }

    .btn-primary-action:hover {
        background: var(--danger-dark);
        border-color: var(--danger-dark);
        color: #ffffff;
    }

    .report-divider {
        margin: 20px 0 0 0;
        height: 1px;
        background: linear-gradient(90deg, var(--gray-200) 0%, transparent 100%);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .dataTables_filter,
        .dataTables_paginate,
        .dataTables_info {
            float: none;
            text-align: center;
        }
        
        .dataTables_filter {
            margin-bottom: 15px;
        }
        
        .dataTables_filter label {
            justify-content: center;
        }
        
        .dataTables_paginate {
            margin-top: 15px;
        }
        
        .dataTables_info {
            margin-top: 15px;
            margin-bottom: 10px;
        }
        
        .attendance-value {
            font-size: 24px;
        }
        
        .attendance-icon {
            font-size: 34px;
        }
        
        .report-filters {
            flex-direction: column;
        }
        
        .filter-group {
            width: 100%;
        }
        
        .report-actions {
            margin-top: 10px;
        }
        
        .card-header {
            flex-direction: column;
            gap: 10px;
            align-items: flex-start !important;
        }
    }
</style>

<div class="me-cashreceipt-msg"></div>
<input type="hidden" id="__siteurl" data-mesiteurl="<?=site_url();?>" />

<div class="row mb-2">
    <div class="col-12">
        <h4 class="fw-semibold my-3">Cash Receipts Journal</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="<?=site_url();?>mydashboard">
                        <i class="ti ti-home"></i>
                    </a>
                </li>
                <li class="breadcrumb-item">Accounting</li>
                <li class="breadcrumb-item active">Cash Receipts</li>
            </ol>
        </nav>
    </div>
</div>

<!-- Dashboard Cards - Matching Attendance Module Style -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card attendance-card h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="attendance-label">Total Receipts</div>
                    <div class="attendance-value"><?=number_format($total_receipts);?></div>
                    <div class="attendance-sub">All time transactions</div>
                </div>
                <i class="ti ti-receipt attendance-icon"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card attendance-card h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="attendance-label">Total Amount</div>
                    <div class="attendance-value">₱<?=number_format($total_amount,2);?></div>
                    <div class="attendance-sub">Total revenue collected</div>
                </div>
                <i class="ti ti-currency-peso attendance-icon"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card attendance-card h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="attendance-label">This Month</div>
                    <div class="attendance-value">₱<?=number_format($monthly_total,2);?></div>
                    <div class="attendance-sub"><?=date('F Y');?></div>
                </div>
                <i class="ti ti-calendar attendance-icon"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card attendance-card h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="attendance-label">Membership</div>
                    <div class="attendance-value">₱<?=number_format($membership_total,2);?></div>
                    <div class="attendance-sub">Membership revenue</div>
                </div>
                <i class="ti ti-chart-pie attendance-icon"></i>
            </div>
        </div>
    </div>
</div>

<!-- Professional Report Section -->
<div class="row mb-3">
    <div class="col-12">
        <div class="report-section">
            <div class="report-header">
                <h6><i class="ti ti-file-report me-2"></i>Financial Reports</h6>
                <i class="ti ti-chart-line"></i>
            </div>
            
            <div class="report-filters">
                <div class="filter-group">
                    <label><i class="ti ti-calendar me-1"></i> FROM DATE</label>
                    <input type="date" id="report_from_date" value="<?=date('Y-m-01');?>">
                </div>
                <div class="filter-group">
                    <label><i class="ti ti-calendar me-1"></i> TO DATE</label>
                    <input type="date" id="report_to_date" value="<?=date('Y-m-t');?>">
                </div>
                <div class="filter-group">
                    <label><i class="ti ti-calendar-stats me-1"></i> YEAR</label>
                    <input type="number" id="report_year" value="<?=date('Y');?>">
                </div>
                <div class="report-actions">
                    <button class="btn-report-action" onclick="showDailyReport()">
                        <i class="ti ti-calendar-day"></i> Daily
                    </button>
                    <button class="btn-report-action" onclick="showSummaryReport()">
                        <i class="ti ti-chart-bar"></i> Summary
                    </button>
                    <button class="btn-report-action" onclick="showJournalReport()">
                        <i class="ti ti-book"></i> Journal
                    </button>
                    <button class="btn-report-action" onclick="showIncomeReport()">
                        <i class="ti ti-chart-pie"></i> Income
                    </button>
                    <button class="btn-report-action btn-primary-action" onclick="showMonthlyReport()">
                        <i class="ti ti-calendar-month"></i> Monthly
                    </button>
                </div>
            </div>
            <div class="report-divider"></div>
        </div>
    </div>
</div>

<!-- Main Content: Table (col-7) and Form (col-5) beside each other -->
<div class="row">
    <!-- RECEIPTS TABLE - col-7 -->
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h6 class="fw-semibold mb-0"><i class="ti ti-list me-2"></i>Cash Receipts Journal</h6>
                <span class="badge bg-light text-dark border"><?=count($cashreceipts);?> records</span>
            </div>
            <div class="card-body">
                <table id="receiptTable" class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Transaction ID</th>
                            <th>Journal ID</th>
                            <th>Account Code</th>
                            <th>Amount</th>
                            <th width="60">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($cashreceipts as $row): ?>
                        <tr>
                            <td><?=date('Y-m-d', strtotime($row['date']));?></td>
                            <td><strong><?=$row['transaction_id'];?></strong></td>
                            <td><?=$row['journal_id'];?></td>
                            <td><?=$row['account_code'];?></td>
                            <td>₱<?=number_format($row['amount'],2);?></td>
                            <td>
                                <button class="btn btn-sm text-danger p-0 border-0 bg-transparent" 
                                        onclick="printReceipt('<?=$row['transaction_id'];?>')" 
                                        title="Print Receipt">
                                    <i class="ti ti-printer"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ADD RECEIPT FORM - col-5 -->
    <div class="col-lg-5">
        <div class="card">
            <form action="<?=site_url();?>cashreceipts?meaction=SAVE" method="post" class="cr-reg-form" id="crRegForm">
                <div class="card-header">
                    <h6 class="fw-semibold mb-0"><i class="ti ti-plus me-2"></i>Add Cash Receipt</h6>
                    <small class="text-muted">Record new cash receipt transaction</small>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Date</label>
                        <input type="date" id="date" class="form-control" name="date" value="<?=date('Y-m-d');?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Account Code</label>
                        <select name="account_code" id="account_code" class="form-select" required>
                            <option value="">Select Account Code</option>
                            <?php foreach($revenue_accounts as $acc): ?>
                            <option value="<?=$acc['account_code'];?>"><?=$acc['account_code'];?> - <?=$acc['account_name'];?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Amount</label>
                        <input type="number" step="0.01" id="amount" class="form-control" name="amount" placeholder="0.00" required>
                    </div>
                    <div class="text-end mt-3">
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="ti ti-device-floppy me-1"></i>
                            Save Cash Receipt
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Report Modals -->
<div class="modal fade" id="reportModal" tabindex="-1" aria-labelledby="reportModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Report Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <iframe id="reportFrame" src="" style="width: 100%; height: 80vh;" frameborder="0"></iframe>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="<?=base_url('assets/js/cashreceipts/cashreceipts.js?v=1');?>"></script>

<script>
$(document).ready(function () {
    $('#receiptTable').DataTable({
        pageLength: 10,
        lengthChange: false,
        order: [[0, 'desc']],
        language: {
            search: "Search Receipt:"
        }
    });
});

function showReport(pdfUrl) {
    var reportFrame = document.getElementById("reportFrame");
    var reportModal = new bootstrap.Modal(document.getElementById("reportModal"));
    reportFrame.src = pdfUrl;
    reportModal.show();
}

function showDailyReport() {
    var date = document.getElementById('report_from_date').value;
    if(date) {
        showReport('<?=site_url();?>cashreceipts?meaction=PRINT-DAILY&report_date=' + date);
    }
}

function showSummaryReport() {
    var from_date = document.getElementById('report_from_date').value;
    var to_date = document.getElementById('report_to_date').value;
    if(from_date && to_date) {
        showReport('<?=site_url();?>cashreceipts?meaction=PRINT-SUMMARY&from_date=' + from_date + '&to_date=' + to_date);
    }
}

function showJournalReport() {
    var from_date = document.getElementById('report_from_date').value;
    var to_date = document.getElementById('report_to_date').value;
    if(from_date && to_date) {
        showReport('<?=site_url();?>cashreceipts?meaction=PRINT-JOURNAL&from_date=' + from_date + '&to_date=' + to_date);
    }
}

function showIncomeReport() {
    var from_date = document.getElementById('report_from_date').value;
    var to_date = document.getElementById('report_to_date').value;
    if(from_date && to_date) {
        showReport('<?=site_url();?>cashreceipts?meaction=PRINT-INCOME&from_date=' + from_date + '&to_date=' + to_date);
    }
}

function showMonthlyReport() {
    var year = document.getElementById('report_year').value;
    if(year) {
        showReport('<?=site_url();?>cashreceipts?meaction=PRINT-MONTHLY&year=' + year);
    }
}

function printReceipt(transactionId) {
    alert('Print receipt for: ' + transactionId);
}
</script>

<?php
echo view('templates/myfooter.php');
?>