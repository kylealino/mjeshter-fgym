<?php
$this->request = \Config\Services::request();
$this->db = \Config\Database::connect();

// ==============================
// GET CURRENT FINANCIAL DATA
// ==============================
$current_year = date('Y');
$current_month = date('m');
$current_date = date('Y-m-d');

// Trial Balance Data
$trial_balance_query = $this->db->query("
    SELECT 
        account_code,
        SUM(amount) as total_amount,
        SUM(CASE WHEN amount > 0 THEN amount ELSE 0 END) as total_debits,
        SUM(CASE WHEN amount < 0 THEN ABS(amount) ELSE 0 END) as total_credits
    FROM tbl_general_journal
    GROUP BY account_code
    ORDER BY account_code ASC
");
$trial_balance_data = $trial_balance_query->getResultArray();

// Income Statement Data
$revenue_query = $this->db->query("
    SELECT 
        account_code,
        SUM(amount) as total
    FROM tbl_cash_receipts_journal
    GROUP BY account_code
");
$revenues = $revenue_query->getResultArray();

$expense_query = $this->db->query("
    SELECT 
        account_code,
        SUM(amount) as total
    FROM tbl_cash_disbursement_journal
    GROUP BY account_code
");
$expenses = $expense_query->getResultArray();

// Balance Sheet Data
$assets = $this->db->query("
    SELECT COUNT(*) as total FROM tbl_gym_assets WHERE status = 'ACTIVE'
")->getRow()->total;

$total_members = $this->db->query("SELECT COUNT(*) as total FROM tbl_members")->getRow()->total;

echo view('templates/myheader.php');
?>
<style>
    :root {
        --gym-red: #dc2626;
        --gym-red-light: #fee2e2;
        --gym-black: #0a0a0a;
        --gym-gray: #6c757d;
        --gym-border: #e5e7eb;
        --gym-gray-dark: #6b7280;
    }

    body { background: #f8f9fa; }

    /* Dashboard Cards */
    .report-card {
        background: #ffffff;
        border-radius: 20px;
        border: 1px solid var(--gym-border);
        transition: all 0.3s ease;
        margin-bottom: 20px;
        cursor: pointer;
    }

    .report-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 25px -12px rgba(0,0,0,0.15);
        border-color: var(--gym-red);
    }

    .report-card .card-body {
        padding: 24px;
        text-align: center;
    }

    .report-icon {
        font-size: 48px;
        color: var(--gym-red);
        opacity: 0.7;
        margin-bottom: 16px;
    }

    .report-title {
        font-size: 16px;
        font-weight: 700;
        color: var(--gym-black);
        margin-bottom: 8px;
    }

    .report-desc {
        font-size: 11px;
        color: var(--gym-gray);
        margin-bottom: 0;
    }

    /* Section Styles */
    .section-title {
        font-size: 18px;
        font-weight: 700;
        color: var(--gym-black);
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid var(--gym-red);
        display: inline-block;
    }

    /* Report Section */
    .report-section {
        background: #ffffff;
        border-radius: 20px;
        padding: 20px 24px;
        margin-bottom: 24px;
        border: 1px solid var(--gym-border);
    }
    
    .report-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 18px;
        padding-bottom: 12px;
        border-bottom: 1px solid var(--gym-border);
    }

    .report-header h6 {
        font-size: 14px;
        font-weight: 600;
        color: var(--gym-black);
        margin: 0;
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
        color: var(--gym-gray);
        margin-bottom: 6px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .filter-group input, .filter-group select {
        width: 100%;
        padding: 9px 12px;
        border: 1.5px solid var(--gym-border);
        border-radius: 12px;
        font-size: 13px;
        color: var(--gym-black);
        background: #ffffff;
    }

    .filter-group input:focus, .filter-group select:focus {
        outline: none;
        border-color: var(--gym-red);
    }

    .report-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        align-items: center;
        margin-top: 16px;
    }

    .btn-report-action {
        background: transparent;
        border: 1.5px solid var(--gym-border);
        padding: 8px 20px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 500;
        color: var(--gym-gray-dark);
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-report-action:hover {
        background: var(--gym-red);
        border-color: var(--gym-red);
        color: #ffffff;
    }

    .btn-primary-action {
        background: var(--gym-red);
        border-color: var(--gym-red);
        color: #ffffff;
    }

    .btn-primary-action:hover {
        background: #b91c1c;
    }

    @media (max-width: 768px) {
        .report-filters { flex-direction: column; }
        .filter-group { width: 100%; }
        .report-actions { margin-top: 10px; }
    }
</style>

<div class="me-financial-msg"></div>
<input type="hidden" id="__siteurl" data-mesiteurl="<?=site_url();?>" />

<div class="row mb-2">
    <div class="col-12">
        <h4 class="fw-semibold my-3">Financial Reports</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="<?=site_url();?>mydashboard">
                        <i class="ti ti-home"></i>
                    </a>
                </li>
                <li class="breadcrumb-item">Accounting</li>
                <li class="breadcrumb-item active">Financial Reports</li>
            </ol>
        </nav>
    </div>
</div>

<!-- Report Filter Section -->
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
                    <label><i class="ti ti-calendar-stats me-1"></i> AS OF DATE (Balance Sheet)</label>
                    <input type="date" id="balance_date" value="<?=date('Y-m-d');?>">
                </div>
                <div class="filter-group">
                    <label><i class="ti ti-tag me-1"></i> ACCOUNT CODE (Ledger)</label>
                    <select id="ledger_account_code">
                        <option value="">-- All Accounts --</option>
                        <?php
                        $accounts = $this->db->query("SELECT account_code, account_name FROM tbl_chart_of_accounts ORDER BY account_code ASC")->getResultArray();
                        foreach($accounts as $acc):
                        ?>
                        <option value="<?=$acc['account_code'];?>"><?=$acc['account_code'];?> - <?=$acc['account_name'];?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="report-actions">
                <button class="btn-report-action" onclick="showTrialBalance()">
                    <i class="ti ti-scale"></i> Trial Balance
                </button>
                <button class="btn-report-action" onclick="showIncomeStatement()">
                    <i class="ti ti-chart-pie"></i> Income Statement
                </button>
                <button class="btn-report-action" onclick="showBalanceSheet()">
                    <i class="ti ti-building"></i> Balance Sheet
                </button>
                <button class="btn-report-action" onclick="showCashReconciliation()">
                    <i class="ti ti-cash-banknote"></i> Cash Reconciliation
                </button>
                <button class="btn-report-action btn-primary-action" onclick="showLedger()">
                    <i class="ti ti-book"></i> Ledger Report
                </button>
            </div>
            <div class="report-divider"></div>
        </div>
    </div>
</div>

<!-- Report Cards - Quick Access -->
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="report-card" onclick="showTrialBalance()">
            <div class="card-body">
                <div class="report-icon"><i class="ti ti-scale"></i></div>
                <div class="report-title">Trial Balance</div>
                <div class="report-desc">Check if debits equal credits</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="report-card" onclick="showIncomeStatement()">
            <div class="card-body">
                <div class="report-icon"><i class="ti ti-chart-pie"></i></div>
                <div class="report-title">Income Statement</div>
                <div class="report-desc">Revenue vs Expenses (P&L)</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="report-card" onclick="showBalanceSheet()">
            <div class="card-body">
                <div class="report-icon"><i class="ti ti-building"></i></div>
                <div class="report-title">Balance Sheet</div>
                <div class="report-desc">Assets = Liabilities + Equity</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="report-card" onclick="showCashReconciliation()">
            <div class="card-body">
                <div class="report-icon"><i class="ti ti-cash-banknote"></i></div>
                <div class="report-title">Cash Reconciliation</div>
                <div class="report-desc">Receipts vs Disbursements</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="report-card" onclick="showLedger()">
            <div class="card-body">
                <div class="report-icon"><i class="ti ti-book"></i></div>
                <div class="report-title">Ledger Report</div>
                <div class="report-desc">Per account transaction history</div>
            </div>
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
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
function showReport(pdfUrl) {
    var reportFrame = document.getElementById("reportFrame");
    var reportModal = new bootstrap.Modal(document.getElementById("reportModal"));
    reportFrame.src = pdfUrl;
    reportModal.show();
}

function showTrialBalance() {
    var from_date = document.getElementById('report_from_date').value;
    var to_date = document.getElementById('report_to_date').value;
    if(from_date && to_date) {
        showReport('<?=site_url();?>financial-reports?meaction=PRINT-TRIAL-BALANCE&from_date=' + from_date + '&to_date=' + to_date);
    } else {
        toastr.error('Please select FROM and TO dates');
    }
}

function showIncomeStatement() {
    var from_date = document.getElementById('report_from_date').value;
    var to_date = document.getElementById('report_to_date').value;
    if(from_date && to_date) {
        showReport('<?=site_url();?>financial-reports?meaction=PRINT-INCOME-STATEMENT&from_date=' + from_date + '&to_date=' + to_date);
    } else {
        toastr.error('Please select FROM and TO dates');
    }
}

function showBalanceSheet() {
    var as_of_date = document.getElementById('balance_date').value;
    if(as_of_date) {
        showReport('<?=site_url();?>financial-reports?meaction=PRINT-BALANCE-SHEET&as_of_date=' + as_of_date);
    } else {
        toastr.error('Please select AS OF DATE');
    }
}

function showCashReconciliation() {
    var from_date = document.getElementById('report_from_date').value;
    var to_date = document.getElementById('report_to_date').value;
    if(from_date && to_date) {
        showReport('<?=site_url();?>financial-reports?meaction=PRINT-CASH-RECONCILIATION&from_date=' + from_date + '&to_date=' + to_date);
    } else {
        toastr.error('Please select FROM and TO dates');
    }
}

function showLedger() {
    var from_date = document.getElementById('report_from_date').value;
    var to_date = document.getElementById('report_to_date').value;
    var account_code = document.getElementById('ledger_account_code').value;
    if(from_date && to_date) {
        showReport('<?=site_url();?>financial-reports?meaction=PRINT-LEDGER&from_date=' + from_date + '&to_date=' + to_date + '&account_code=' + account_code);
    } else {
        toastr.error('Please select FROM and TO dates');
    }
}
</script>

<?php
echo view('templates/myfooter.php');
?>