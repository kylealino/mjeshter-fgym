<?php
$this->request = \Config\Services::request();
$this->db = \Config\Database::connect();

// ==============================
// FETCH DATA
// ==============================
$query = $this->db->query("
    SELECT * FROM tbl_chart_of_accounts 
    ORDER BY 
        CASE account_type
            WHEN 'ASSET' THEN 1
            WHEN 'LIABILITY' THEN 2
            WHEN 'EQUITY' THEN 3
            WHEN 'REVENUE' THEN 4
            WHEN 'EXPENSE' THEN 5
        END,
        account_code ASC
");
$accounts = $query->getResultArray();

// ==============================
// DASHBOARD CALCULATIONS
// ==============================
$total_assets = $this->db->query("SELECT COUNT(*) as total FROM tbl_chart_of_accounts WHERE account_type = 'ASSET' AND is_active = 1")->getRow()->total;
$total_liabilities = $this->db->query("SELECT COUNT(*) as total FROM tbl_chart_of_accounts WHERE account_type = 'LIABILITY' AND is_active = 1")->getRow()->total;
$total_equity = $this->db->query("SELECT COUNT(*) as total FROM tbl_chart_of_accounts WHERE account_type = 'EQUITY' AND is_active = 1")->getRow()->total;
$total_revenue = $this->db->query("SELECT COUNT(*) as total FROM tbl_chart_of_accounts WHERE account_type = 'REVENUE' AND is_active = 1")->getRow()->total;
$total_expense = $this->db->query("SELECT COUNT(*) as total FROM tbl_chart_of_accounts WHERE account_type = 'EXPENSE' AND is_active = 1")->getRow()->total;
$total_accounts = count($accounts);

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
        --purple: #8b5cf6;
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

    .form-control, select.form-control {
        border: 1.5px solid var(--gray-200);
        border-radius: 12px;
        padding: 10px 14px;
        font-size: 13px;
        color: var(--gray-700);
        background: #ffffff;
        transition: all 0.2s;
        width: 100%;
    }

    .form-control:focus, select.form-control:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(30,58,95,0.08);
    }

    textarea.form-control {
        resize: vertical;
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

    .btn-secondary {
        background: #ffffff;
        border: 1.5px solid var(--gray-200);
        border-radius: 12px;
        padding: 10px 20px;
        font-size: 13px;
        font-weight: 600;
        color: var(--gray-600);
        transition: all 0.2s;
    }

    .btn-secondary:hover {
        border-color: var(--primary);
        color: var(--primary);
        background: #ffffff;
    }

    /* Badges */
    .badge {
        font-size: 10px;
        font-weight: 600;
        padding: 5px 12px;
        border-radius: 30px;
        letter-spacing: 0.3px;
    }

    .bg-success {
        background: var(--success) !important;
    }

    .bg-danger {
        background: var(--danger) !important;
    }

    .bg-secondary {
        background: var(--gray-400) !important;
    }

    .bg-primary {
        background: var(--primary) !important;
    }

    .bg-info {
        background: var(--info) !important;
    }

    .bg-warning {
        background: var(--warning) !important;
    }

    /* Account Type Badges */
    .account-type-badge {
        padding: 4px 10px;
        border-radius: 30px;
        font-size: 10px;
        font-weight: 600;
        display: inline-block;
    }
    .account-type-ASSET { background: #dbeafe; color: #1e40af; }
    .account-type-LIABILITY { background: #fed7aa; color: #9a3412; }
    .account-type-EQUITY { background: #dcfce7; color: #166534; }
    .account-type-REVENUE { background: #d1fae5; color: #065f46; }
    .account-type-EXPENSE { background: #fee2e2; color: #991b1b; }

    .balance-DEBIT { color: var(--success); font-weight: 600; }
    .balance-CREDIT { color: var(--danger); font-weight: 600; }

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

    /* Action Buttons */
    .btn-icon {
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .btn-icon:hover {
        transform: scale(1.1);
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
        
        .card-header {
            flex-direction: column;
            gap: 10px;
            align-items: flex-start !important;
        }
    }
</style>

<div class="me-coa-msg"></div>
<input type="hidden" id="__siteurl" data-mesiteurl="<?=site_url();?>" />

<div class="row mb-2">
    <div class="col-12">
        <h4 class="fw-semibold my-3">Chart of Accounts</h4>

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="<?=site_url();?>mydashboard">
                        <i class="ti ti-home"></i>
                    </a>
                </li>
                <li class="breadcrumb-item">Accounting</li>
                <li class="breadcrumb-item active">Chart of Accounts</li>
            </ol>
        </nav>
    </div>
</div>

<!-- Dashboard Cards - Matching Attendance Module Style -->
<div class="row g-3 mb-4">
    <div class="col-md-2">
        <div class="card attendance-card h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="attendance-label">Total Accounts</div>
                    <div class="attendance-value"><?=$total_accounts;?></div>
                    <div class="attendance-sub">All accounts</div>
                </div>
                <i class="ti ti-chart-bar attendance-icon"></i>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card attendance-card h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="attendance-label">Assets</div>
                    <div class="attendance-value"><?=$total_assets;?></div>
                    <div class="attendance-sub">Asset accounts</div>
                </div>
                <i class="ti ti-wallet attendance-icon"></i>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card attendance-card h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="attendance-label">Liabilities</div>
                    <div class="attendance-value"><?=$total_liabilities;?></div>
                    <div class="attendance-sub">Liability accounts</div>
                </div>
                <i class="ti ti-receipt attendance-icon"></i>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card attendance-card h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="attendance-label">Equity</div>
                    <div class="attendance-value"><?=$total_equity;?></div>
                    <div class="attendance-sub">Equity accounts</div>
                </div>
                <i class="ti ti-percentage attendance-icon"></i>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card attendance-card h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="attendance-label">Revenue</div>
                    <div class="attendance-value"><?=$total_revenue;?></div>
                    <div class="attendance-sub">Income accounts</div>
                </div>
                <i class="ti ti-currency-dollar attendance-icon"></i>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card attendance-card h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="attendance-label">Expenses</div>
                    <div class="attendance-value"><?=$total_expense;?></div>
                    <div class="attendance-sub">Expense accounts</div>
                </div>
                <i class="ti ti-shopping-cart attendance-icon"></i>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- ACCOUNTS TABLE -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h6 class="fw-semibold mb-0">Chart of Accounts</h6>
                <span class="badge bg-light text-dark border"><?=count($accounts);?> accounts</span>
            </div>

            <div class="card-body">
                <table id="coaTable" class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Account Code</th>
                            <th>Account Name</th>
                            <th>Type</th>
                            <th>Category</th>
                            <th>Normal Balance</th>
                            <th>Status</th>
                            <th width="120">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach($accounts as $row): ?>
                        <tr>
                            <td><strong><?=$row['account_code'];?></strong></td>
                            <td><?=$row['account_name'];?></td>
                            <td>
                                <span class="account-type-badge account-type-<?=$row['account_type'];?>">
                                    <?=$row['account_type'];?>
                                </span>
                            </td>
                            <td><?=$row['account_category'] ?: '-';?></td>
                            <td>
                                <span class="balance-<?=$row['normal_balance'];?>">
                                    <?=$row['normal_balance'];?>
                                </span>
                            </td>
                            <td>
                                <?php if($row['is_active'] == 1): ?>
                                    <span class="badge bg-success">Active</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <!-- EDIT BUTTON -->
                                <button class="btn btn-sm text-primary p-0 border-0 bg-transparent me-2 btn-icon" 
                                        onclick="editAccount(<?=$row['account_id'];?>, '<?=addslashes($row['account_code']);?>', '<?=addslashes($row['account_name']);?>', '<?=$row['account_type'];?>', '<?=addslashes($row['account_category']);?>', '<?=$row['normal_balance'];?>', '<?=addslashes($row['description']);?>')" 
                                        title="Edit">
                                    <i class="ti ti-edit"></i>
                                </button>
                                <!-- DELETE BUTTON -->
                                <button class="btn btn-sm text-danger p-0 border-0 bg-transparent btn-icon" 
                                        onclick="showDeleteModal(<?=$row['account_id'];?>, '<?=addslashes($row['account_name']);?>')" 
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

    <!-- ADD ACCOUNT FORM -->
    <div class="col-md-4">
        <div class="card">
            <form action="<?=site_url();?>chartofaccounts?meaction=SAVE" method="post" class="coa-reg-form" id="coaRegForm">
                <div class="card-header">
                    <h6 class="fw-semibold mb-0">Add New Account</h6>
                    <small class="text-muted">Create a new chart of account</small>
                </div>

                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Account Code</label>
                        <input type="text" id="account_code" class="form-control" name="account_code" placeholder="e.g., 4070" required>
                        <small class="text-muted">Unique code for this account</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Account Name</label>
                        <input type="text" id="account_name" class="form-control" name="account_name" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Account Type</label>
                        <select name="account_type" id="account_type" class="form-control" required>
                            <option value="">Select Type</option>
                            <option value="ASSET">ASSET</option>
                            <option value="LIABILITY">LIABILITY</option>
                            <option value="EQUITY">EQUITY</option>
                            <option value="REVENUE">REVENUE</option>
                            <option value="EXPENSE">EXPENSE</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Account Category</label>
                        <input type="text" id="account_category" class="form-control" name="account_category" placeholder="e.g., Current Assets, Operating Expenses">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Normal Balance</label>
                        <select name="normal_balance" id="normal_balance" class="form-control" required>
                            <option value="">Select Normal Balance</option>
                            <option value="DEBIT">DEBIT</option>
                            <option value="CREDIT">CREDIT</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Parent Account (Optional)</label>
                        <select name="parent_account_id" id="parent_account_id" class="form-control">
                            <option value="">None</option>
                            <?php foreach($accounts as $parent): ?>
                                <option value="<?=$parent['account_id'];?>"><?=$parent['account_code'];?> - <?=$parent['account_name'];?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" id="description" class="form-control" rows="2"></textarea>
                    </div>

                    <div class="col text-end mt-2">
                        <button type="submit" class="btn btn-danger w-100 mt-2">
                            <i class="ti ti-device-floppy me-1"></i>
                            Save Account
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Account Modal -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="edit_account_id">
                
                <div class="mb-3">
                    <label class="form-label">Account Code</label>
                    <input type="text" id="edit_account_code" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Account Name</label>
                    <input type="text" id="edit_account_name" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Account Type</label>
                    <select id="edit_account_type" class="form-control" required>
                        <option value="ASSET">ASSET</option>
                        <option value="LIABILITY">LIABILITY</option>
                        <option value="EQUITY">EQUITY</option>
                        <option value="REVENUE">REVENUE</option>
                        <option value="EXPENSE">EXPENSE</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Account Category</label>
                    <input type="text" id="edit_account_category" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Normal Balance</label>
                    <select id="edit_normal_balance" class="form-control" required>
                        <option value="DEBIT">DEBIT</option>
                        <option value="CREDIT">CREDIT</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea id="edit_description" class="form-control" rows="2"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" onclick="updateAccount()">Save Changes</button>
            </div>
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
                    <i class="ti ti-alert-triangle" style="font-size: 48px; color: var(--danger);"></i>
                    <h4 class="mt-3">Are you sure?</h4>
                    <p class="text-muted">You are about to delete account: <br>
                        <strong id="delete_account_name" class="text-danger"></strong>
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?=base_url('assets/js/chartofaccounts/coa.js');?>"></script>

<script>
$(document).ready(function () {
    $('#coaTable').DataTable({
        pageLength: 10,
        lengthChange: false,
        order: [[0, 'asc']],
        language: {
            search: "Search Account:"
        }
    });
    
    $('#confirmDeleteBtn').on('click', function() {
        deleteAccount();
        var modal = bootstrap.Modal.getInstance(document.getElementById('deleteModal'));
        modal.hide();
    });
});

function editAccount(id, code, name, type, category, balance, description) {
    document.getElementById('edit_account_id').value = id;
    document.getElementById('edit_account_code').value = code;
    document.getElementById('edit_account_name').value = name;
    document.getElementById('edit_account_type').value = type;
    document.getElementById('edit_account_category').value = category || '';
    document.getElementById('edit_normal_balance').value = balance;
    document.getElementById('edit_description').value = description || '';
    
    var editModal = new bootstrap.Modal(document.getElementById('editModal'));
    editModal.show();
}

var deleteId = null;
var deleteName = '';

function showDeleteModal(id, name) {
    deleteId = id;
    deleteName = name;
    document.getElementById('delete_account_name').innerHTML = name;
    var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    deleteModal.show();
}

function deleteAccount() {
    if(deleteId) {
        var mparam = {
            account_id: deleteId,
            meaction: 'DELETE'
        };

        jQuery.ajax({
            type: "POST",
            url: '<?=site_url();?>chartofaccounts',
            data: mparam,
            dataType: 'json',
            success: function(data) {
                if(data.status == 'success'){
                    toastr.success(data.message);
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                } else {
                    toastr.error(data.message);
                }
            },
            error: function(xhr, status, error) {
                toastr.error("Error: " + error);
            }
        });
    }
}

function updateAccount() {
    var account_id = document.getElementById('edit_account_id').value;
    var account_code = document.getElementById('edit_account_code').value;
    var account_name = document.getElementById('edit_account_name').value;
    var account_type = document.getElementById('edit_account_type').value;
    var account_category = document.getElementById('edit_account_category').value;
    var normal_balance = document.getElementById('edit_normal_balance').value;
    var description = document.getElementById('edit_description').value;

    var mparam = {
        account_id: account_id,
        account_code: account_code,
        account_name: account_name,
        account_type: account_type,
        account_category: account_category,
        normal_balance: normal_balance,
        description: description,
        meaction: 'EDIT'
    };

    jQuery.ajax({
        type: "POST",
        url: '<?=site_url();?>chartofaccounts',
        data: mparam,
        dataType: 'json',
        success: function(data) {
            console.log(data);
            if(data.status == 'success'){
                toastr.success(data.message);
                setTimeout(function() {
                    location.reload();
                }, 1500);
            } else {
                toastr.error(data.message);
            }
        },
        error: function(xhr, status, error) {
            toastr.error("Error: " + error);
        }
    });
}
</script>

<?php
echo view('templates/myfooter.php');
?>