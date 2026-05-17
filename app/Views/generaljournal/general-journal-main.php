<?php
$this->request = \Config\Services::request();
$this->db = \Config\Database::connect();

// ==============================
// FETCH DATA
// ==============================
$query = $this->db->query("SELECT * FROM tbl_general_journal ORDER BY journal_id DESC");
$entries = $query->getResultArray();

// ==============================
// DASHBOARD CALCULATIONS
// ==============================
$total_entries = $this->db->query("SELECT COUNT(*) as total FROM tbl_general_journal")->getRow()->total;
$total_amount = $this->db->query("SELECT COALESCE(SUM(amount),0) as total FROM tbl_general_journal")->getRow()->total;

// ==============================
// FETCH CHART OF ACCOUNTS FOR DROPDOWN
// ==============================
$coa_query = $this->db->query("
    SELECT account_code, account_name 
    FROM tbl_chart_of_accounts 
    WHERE is_active = 1 
    ORDER BY account_code ASC
");
$all_accounts = $coa_query->getResultArray();

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

    /* Dashboard Cards */
    .attendance-card {
        background: #ffffff;
        border-radius: 20px;
        border: 1px solid var(--gym-border);
        transition: all 0.3s ease;
        margin-bottom: 20px;
    }

    .attendance-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 20px -12px rgba(0,0,0,0.1);
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
        color: var(--gym-black);
    }

    .attendance-icon {
        font-size: 42px;
        opacity: 0.12;
        color: var(--gym-red);
    }

    .attendance-label {
        font-size: 12px;
        font-weight: 600;
        color: var(--gym-gray);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 6px;
    }

    .attendance-sub {
        font-size: 11px;
        color: #6b7280;
        margin-top: 6px;
    }

    /* Cards */
    .card {
        border-radius: 20px;
        border: 1px solid var(--gym-border);
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        background: #ffffff;
        margin-bottom: 24px;
    }

    .card-header {
        background: #ffffff;
        border-bottom: 1px solid var(--gym-border);
        padding: 16px 24px;
    }

    .card-body {
        padding: 20px 24px;
    }

    /* Form Controls */
    .form-label {
        font-size: 12px;
        font-weight: 600;
        color: #475569;
        margin-bottom: 6px;
        display: block;
    }

    .form-control, select.form-control {
        border: 1.5px solid var(--gym-border);
        border-radius: 12px;
        padding: 10px 14px;
        font-size: 13px;
        color: var(--gym-black);
        background: #ffffff;
        transition: all 0.2s;
        width: 100%;
    }

    .form-control:focus, select.form-control:focus {
        outline: none;
        border-color: var(--gym-red);
        box-shadow: 0 0 0 3px rgba(220,38,38,0.08);
    }

    /* Buttons */
    .btn-danger {
        background: var(--gym-red);
        border: none;
        border-radius: 12px;
        padding: 10px 20px;
        font-size: 13px;
        font-weight: 600;
        transition: all 0.2s;
    }

    .btn-danger:hover {
        background: #b91c1c;
        transform: translateY(-1px);
    }

    /* Badges */
    .badge {
        font-size: 10px;
        font-weight: 600;
        padding: 4px 12px;
        border-radius: 30px;
    }

    /* Tables */
    .table {
        width: 100%;
        border-collapse: collapse;
    }

    .table thead th {
        font-size: 12px;
        font-weight: 600;
        color: #6b7280;
        background: #f8fafc;
        border-bottom: 1px solid var(--gym-border);
        padding: 14px 12px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        text-align: center;
    }

    .table tbody td {
        font-size: 13px;
        color: #334155;
        padding: 12px;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
        text-align: center;
    }

    .table-hover tbody tr:hover {
        background: #f8fafc;
    }

    /* DataTables */
    .dataTables_wrapper {
        font-family: 'Inter', sans-serif;
    }

    .dataTables_filter {
        float: right;
        margin-bottom: 20px;
    }

    .dataTables_filter label {
        font-size: 12px;
        font-weight: 500;
        color: #6b7280;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .dataTables_filter input {
        width: 220px;
        padding: 8px 14px;
        border: 1.5px solid var(--gym-border);
        border-radius: 12px;
        font-size: 12px;
    }

    .dataTables_filter input:focus {
        outline: none;
        border-color: var(--gym-red);
        box-shadow: 0 0 0 3px rgba(220,38,38,0.08);
    }

    .dataTables_paginate {
        float: right;
        margin-top: 20px;
    }

    .dataTables_paginate .paginate_button {
        padding: 6px 12px !important;
        margin: 0 3px !important;
        border-radius: 10px !important;
        border: 1px solid var(--gym-border) !important;
        background: #ffffff !important;
        color: #475569 !important;
        font-size: 11px !important;
        font-weight: 600 !important;
    }

    .dataTables_paginate .paginate_button.current {
        background: var(--gym-red) !important;
        border-color: var(--gym-red) !important;
        color: #ffffff !important;
    }

    .dataTables_info {
        float: left;
        font-size: 12px;
        color: #6b7280;
        margin-top: 20px;
    }

    /* Action Buttons */
    .btn-icon {
        transition: all 0.2s;
    }

    .btn-icon:hover {
        transform: scale(1.1);
    }

    /* Professional Report Section Styles */
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
        letter-spacing: 0.3px;
    }

    .report-header i {
        color: var(--gym-gray);
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
        color: var(--gym-gray);
        margin-bottom: 6px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .filter-group input {
        width: 100%;
        padding: 9px 12px;
        border: 1.5px solid var(--gym-border);
        border-radius: 12px;
        font-size: 13px;
        color: var(--gym-black);
        background: #ffffff;
        transition: all 0.2s;
    }

    .filter-group input:focus {
        outline: none;
        border-color: var(--gym-red);
        box-shadow: 0 0 0 3px rgba(220,38,38,0.08);
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
        padding: 8px 16px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 500;
        color: var(--gym-gray-dark);
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
        background: #f8fafc;
        border-color: var(--gym-red);
        color: var(--gym-red);
    }

    .btn-primary-action {
        background: var(--gym-red);
        border-color: var(--gym-red);
        color: #ffffff;
    }

    .btn-primary-action:hover {
        background: #b91c1c;
        border-color: #b91c1c;
        color: #ffffff;
    }

    .report-divider {
        margin: 20px 0 0 0;
        height: 1px;
        background: linear-gradient(90deg, var(--gym-border) 0%, transparent 100%);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .attendance-value {
            font-size: 24px;
        }
        .attendance-icon {
            font-size: 34px;
        }
        .dataTables_filter,
        .dataTables_paginate,
        .dataTables_info {
            float: none;
            text-align: center;
        }
        .card-header {
            flex-direction: column;
            gap: 10px;
            align-items: flex-start !important;
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
    }
</style>

<div class="me-generaljournal-msg"></div>
<input type="hidden" id="__siteurl" data-mesiteurl="<?=site_url();?>" />

<div class="row mb-2">
    <div class="col-12">
        <h4 class="fw-semibold my-3">General Journal</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="<?=site_url();?>mydashboard">
                        <i class="ti ti-home"></i>
                    </a>
                </li>
                <li class="breadcrumb-item">Accounting</li>
                <li class="breadcrumb-item active">General Journal</li>
            </ol>
        </nav>
    </div>
</div>

<!-- Dashboard Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="attendance-card">
            <div class="card-body">
                <div>
                    <div class="attendance-label">Total Entries</div>
                    <div class="attendance-value"><?=number_format($total_entries);?></div>
                    <div class="attendance-sub">Journal entries</div>
                </div>
                <i class="ti ti-book attendance-icon"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="attendance-card">
            <div class="card-body">
                <div>
                    <div class="attendance-label">Total Amount</div>
                    <div class="attendance-value">₱<?=number_format($total_amount,2);?></div>
                    <div class="attendance-sub">Total journal amount</div>
                </div>
                <i class="ti ti-currency-peso attendance-icon"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="attendance-card">
            <div class="card-body">
                <div>
                    <div class="attendance-label">Total Accounts</div>
                    <div class="attendance-value"><?=number_format(count($all_accounts));?></div>
                    <div class="attendance-sub">Active accounts</div>
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
                <h6><i class="ti ti-file-report me-2"></i>Journal Reports</h6>
                <i class="ti ti-chart-line"></i>
            </div>
            
            <div class="report-filters">
                <div class="filter-group">
                    <label><i class="ti ti-calendar me-1"></i> REPORT DATE</label>
                    <input type="date" id="report_date" value="<?=date('Y-m-d');?>">
                </div>
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
                <button class="btn-report-action" onclick="showAccountReport()">
                    <i class="ti ti-chart-pie"></i> Account
                </button>
                <button class="btn-report-action btn-primary-action" onclick="showMonthlyReport()">
                    <i class="ti ti-calendar-month"></i> Monthly
                </button>
            </div>
            <div class="report-divider"></div>
        </div>
    </div>
</div>

<div class="row">
    <!-- ENTRIES TABLE -->
    <div class="col-md-7">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h6 class="fw-semibold mb-0"><i class="ti ti-list me-2"></i>General Journal</h6>
                <span class="badge bg-light text-dark border"><?=count($entries);?> entries</span>
            </div>
            <div class="card-body">
                <table id="journalTable" class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Transaction ID</th>
                            <th>Journal ID</th>
                            <th>Account Code</th>
                            <th>Amount</th>
                            <th>Description</th>
                            <th width="60">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($entries as $row): ?>
                        <tr>
                            <td><?=date('Y-m-d', strtotime($row['date']));?></td>
                            <td><strong><?=$row['transaction_id'];?></strong></td>
                            <td><?=$row['journal_id'];?></td>
                            <td><?=$row['account_code'];?></td>
                            <td>₱<?=number_format($row['amount'],2);?></td>
                            <td><?=substr($row['description'] ?? '', 0, 40);?>...</td>
                            <td>
                                <button class="btn btn-sm text-danger p-0 border-0 bg-transparent btn-icon" 
                                        onclick="showDeleteModal(<?=$row['journal_id'];?>, '<?=addslashes($row['transaction_id']);?>')" 
                                        title="Delete">
                                    <i class="ti ti-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ADD ENTRY FORM -->
    <div class="col-md-5">
        <div class="card">
            <form class="gj-reg-form" id="gjRegForm">
                <div class="card-header">
                    <h6 class="fw-semibold mb-0"><i class="ti ti-plus me-2"></i>Add Journal Entry</h6>
                    <small class="text-muted">Record new journal entry</small>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Date</label>
                        <input type="date" id="date" class="form-control" name="date" value="<?=date('Y-m-d');?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Account Code</label>
                        <select name="account_code" id="account_code" class="form-control" required>
                            <option value="">Select Account Code</option>
                            <?php foreach($all_accounts as $acc): ?>
                            <option value="<?=$acc['account_code'];?>"><?=$acc['account_code'];?> - <?=$acc['account_name'];?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Amount</label>
                        <input type="number" step="0.01" id="amount" class="form-control" name="amount" placeholder="0.00" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" id="description" class="form-control" rows="2" placeholder="Journal entry description..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Reference No. (Optional)</label>
                        <input type="text" id="reference_no" class="form-control" name="reference_no" placeholder="e.g., INV-001, OR-123">
                    </div>
                    <div class="text-end mt-3">
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="ti ti-device-floppy me-1"></i>
                            Save Entry
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="ti ti-trash me-2"></i> Confirm Delete
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center py-3">
                    <i class="ti ti-alert-triangle" style="font-size: 48px; color: #dc2626;"></i>
                    <h4 class="mt-3">Are you sure?</h4>
                    <p class="text-muted">You are about to delete journal entry: <br>
                        <strong id="delete_transaction_name" class="text-danger"></strong>
                    </p>
                    <p class="text-muted small">This action cannot be undone.</p>
                </div>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="ti ti-x"></i> Cancel
                </button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                    <i class="ti ti-trash"></i> Delete
                </button>
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
<script src="<?=base_url('assets/js/generaljournal/general-journal.js?v=1');?>"></script>

<script>
$(document).ready(function () {
    $('#journalTable').DataTable({
        pageLength: 10,
        lengthChange: false,
        order: [[0, 'desc']],
        language: {
            search: "Search Journal:"
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
    var date = document.getElementById('report_date').value;
    if(date) {
        showReport('<?=site_url();?>general-journal?meaction=PRINT-DAILY&report_date=' + date);
    } else {
        toastr.error('Please select a date');
    }
}

function showSummaryReport() {
    var from_date = document.getElementById('report_from_date').value;
    var to_date = document.getElementById('report_to_date').value;
    if(from_date && to_date) {
        showReport('<?=site_url();?>general-journal?meaction=PRINT-SUMMARY&from_date=' + from_date + '&to_date=' + to_date);
    } else {
        toastr.error('Please select both FROM and TO dates');
    }
}

function showJournalReport() {
    var from_date = document.getElementById('report_from_date').value;
    var to_date = document.getElementById('report_to_date').value;
    if(from_date && to_date) {
        showReport('<?=site_url();?>general-journal?meaction=PRINT-JOURNAL&from_date=' + from_date + '&to_date=' + to_date);
    } else {
        toastr.error('Please select both FROM and TO dates');
    }
}

function showAccountReport() {
    var from_date = document.getElementById('report_from_date').value;
    var to_date = document.getElementById('report_to_date').value;
    if(from_date && to_date) {
        showReport('<?=site_url();?>general-journal?meaction=PRINT-ACCOUNT&from_date=' + from_date + '&to_date=' + to_date);
    } else {
        toastr.error('Please select both FROM and TO dates');
    }
}

function showMonthlyReport() {
    var year = document.getElementById('report_year').value;
    if(year) {
        showReport('<?=site_url();?>general-journal?meaction=PRINT-MONTHLY&year=' + year);
    } else {
        toastr.error('Please select a year');
    }
}

var deleteId = null;
var deleteTransaction = null;

function showDeleteModal(id, transaction) {
    deleteId = id;
    deleteTransaction = transaction;
    document.getElementById('delete_transaction_name').innerHTML = transaction;
    var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    deleteModal.show();
}

function deleteEntry() {
    if(deleteId) {
        var mparam = {
            journal_id: deleteId,
            meaction: 'DELETE'
        };

        jQuery.ajax({
            type: "POST",
            url: '<?=site_url();?>general-journal',
            data: mparam,
            dataType: 'json',
            success: function(response) {
                if(response.status == 'success'){
                    toastr.success(response.message);
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                } else {
                    toastr.error(response.message);
                }
            },
            error: function(xhr, status, error) {
                toastr.error("Error: " + error);
            }
        });
    }
}

$('#confirmDeleteBtn').on('click', function() {
    deleteEntry();
    var modal = bootstrap.Modal.getInstance(document.getElementById('deleteModal'));
    modal.hide();
});
</script>

<?php
echo view('templates/myfooter.php');
?>