<?php
$this->request = \Config\Services::request();
$this->db = \Config\Database::connect();

// Get account_id from URL for editing
$account_id = $this->request->getGet('account_id');

$account_code = "";
$account_name = "";
$account_type = "";
$parent_code = "";
$is_active = "1";

if(!empty($account_id)) { 
    $query = $this->db->query("SELECT * FROM tbl_coa WHERE account_id = '$account_id'");
    $data = $query->getRowArray();
    if($data) {
        $account_code = $data['account_code'] ?? '';
        $account_name = $data['account_name'] ?? '';
        $account_type = $data['account_type'] ?? '';
        $parent_code = $data['parent_code'] ?? '';
        $is_active = $data['is_active'] ?? 1;
    }
}

echo view('templates/myheader.php');
?>

<style>
    /* Professional Chart of Accounts - Clean & Elegant */
    
    /* Status Pills - Professional */
    .status-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 3px 10px;
        font-size: 11px;
        font-weight: 500;
        border-radius: 30px;
        letter-spacing: 0.3px;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    }
    
    .status-pill::before {
        content: '';
        width: 5px;
        height: 5px;
        border-radius: 50%;
    }
    
    .status-active {
        background: #e8f5e9;
        color: #2e7d32;
    }
    .status-active::before {
        background: #2e7d32;
    }
    
    .status-inactive {
        background: #ffebee;
        color: #c62828;
    }
    .status-inactive::before {
        background: #c62828;
    }
    
    /* Account Type Badges - Subtle */
    .type-badge {
        display: inline-flex;
        align-items: center;
        padding: 2px 10px;
        font-size: 10px;
        font-weight: 500;
        border-radius: 30px;
        letter-spacing: 0.3px;
        font-family: 'Inter', monospace;
    }
    .type-asset { background: #e3f2fd; color: #1565c0; }
    .type-liability { background: #fff3e0; color: #ef6c00; }
    .type-equity { background: #e8f5e9; color: #2e7d32; }
    .type-revenue { background: #e0f2f1; color: #00897b; }
    .type-expense { background: #fbe9e7; color: #d84315; }
    
    /* Edit Mode Badge */
    .edit-mode-badge {
        background: #f5f5f5;
        color: #1976d2;
        font-size: 0.7rem;
        padding: 0.3rem 1rem;
        border-radius: 30px;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 1.25rem;
        font-weight: 500;
    }
    
    /* Account Tree - Clean Professional */
    .account-tree {
        font-size: 0.875rem;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    }
    
    .account-item {
        margin-bottom: 0;
        border-bottom: 1px solid #f0f0f0;
    }
    
    .account-item:last-child {
        border-bottom: none;
    }
    
    .account-card {
        background: #fff;
        padding: 0.7rem 1rem;
        transition: all 0.2s ease;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 0.75rem;
        border-left: 2px solid transparent;
    }
    
    .account-card:hover {
        background: #fafafa;
        border-left-color: #1976d2;
        padding-left: 1rem;
    }
    
    .account-info {
        display: flex;
        align-items: center;
        gap: 1.25rem;
        flex-wrap: wrap;
    }
    
    .account-code {
        font-family: 'SF Mono', 'Courier New', monospace;
        font-weight: 500;
        font-size: 0.75rem;
        background: #f5f5f5;
        padding: 0.2rem 0.6rem;
        border-radius: 4px;
        color: #1976d2;
        letter-spacing: 0.3px;
    }
    
    .account-name {
        font-weight: 500;
        color: #2c3e50;
        font-size: 0.85rem;
    }
    
    .account-actions {
        display: flex;
        gap: 0.5rem;
        opacity: 0.5;
        transition: opacity 0.2s ease;
    }
    
    .account-card:hover .account-actions {
        opacity: 1;
    }
    
    .action-icon {
        background: none;
        border: none;
        padding: 0.3rem;
        cursor: pointer;
        color: #7f8c8d;
        transition: all 0.2s ease;
        border-radius: 4px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    
    .action-icon:hover {
        color: #1976d2;
        background: #e3f2fd;
    }
    
    /* Filter Buttons - Clean */
    .filter-group {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }
    
    .filter-btn {
        padding: 0.25rem 1rem;
        font-size: 0.7rem;
        border-radius: 30px;
        border: 1px solid #e0e0e0;
        background: #fff;
        color: #5f6368;
        cursor: pointer;
        transition: all 0.2s ease;
        font-weight: 500;
    }
    
    .filter-btn.active {
        background: #1976d2;
        border-color: #1976d2;
        color: #fff;
    }
    
    .filter-btn:hover:not(.active) {
        background: #f5f5f5;
        border-color: #bdbdbd;
    }
    
    /* Stats Cards - Minimal Professional */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    
    .stat-card {
        background: #fff;
        border: 1px solid #e8e8e8;
        border-radius: 12px;
        padding: 1rem 0.75rem;
        text-align: center;
        transition: all 0.2s ease;
    }
    
    .stat-card:hover {
        border-color: #d0d0d0;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }
    
    .stat-number {
        font-size: 1.5rem;
        font-weight: 600;
        color: #1a2c3e;
        margin-bottom: 0.25rem;
        font-family: 'Inter', monospace;
    }
    
    .stat-label {
        font-size: 0.65rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #7f8c8d;
        font-weight: 500;
    }
    
    /* Card Header - Consistent */
    .card-header {
        background: #fff;
        border-bottom: 1px solid #e8e8e8;
    }
    
    /* Form Elements */
    .form-control-sm, .form-select-sm {
        border-color: #e0e0e0;
        font-size: 0.8rem;
    }
    
    .form-control-sm:focus, .form-select-sm:focus {
        border-color: #1976d2;
        box-shadow: 0 0 0 2px rgba(25,118,210,0.1);
    }
    
    /* Breadcrumb */
    .breadcrumb {
        font-size: 0.75rem;
    }
    
    /* Indentation Helper */
    .account-item {
        position: relative;
    }
    
    /* Connector lines for better hierarchy (optional) */
    .account-item:not([style*="padding-left: 0px"]) .account-card::before {
        content: '';
        position: absolute;
        left: 12px;
        top: 0;
        bottom: 0;
        width: 1px;
        background: #e8e8e8;
        transform: translateX(-50%);
        pointer-events: none;
    }
</style>

<div class="container-fluid">
    <div class="row me-mycoa-outp-msg mx-0">
    </div>
    <input type="hidden" id="__siteurl" data-mesiteurl="<?=site_url();?>" />
    
    <!-- Page Header -->
    <div class="row mb-3 mt-0">
        <div class="col-12">
            <h4 class="fw-semibold mb-1" style="color: #1a2c3e;">Chart of Accounts</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a class="text-muted text-decoration-none" href="<?=site_url();?>"><i class="ti ti-home fs-5"></i></a>
                    </li>
                    <li class="breadcrumb-item" aria-current="page">Accounting</li>
                    <li class="breadcrumb-item active fw-semibold" aria-current="page">Chart of Accounts</li>
                </ol>
            </nav>
        </div>
    </div>
    
    <!-- Stats Overview -->
    <?php
    // Get stats from database
    $totalAccountsQuery = $this->db->query("SELECT COUNT(*) as total FROM tbl_coa")->getRowArray();
    $activeAccountsQuery = $this->db->query("SELECT COUNT(*) as total FROM tbl_coa WHERE is_active = 1")->getRowArray();
    $assetCountQuery = $this->db->query("SELECT COUNT(*) as total FROM tbl_coa WHERE account_type = 'Asset'")->getRowArray();
    $expenseCountQuery = $this->db->query("SELECT COUNT(*) as total FROM tbl_coa WHERE account_type = 'Expense'")->getRowArray();
    ?>
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-number"><?= number_format($totalAccountsQuery['total'] ?? 0); ?></div>
            <div class="stat-label">Total Accounts</div>
        </div>
        <div class="stat-card">
            <div class="stat-number"><?= number_format($activeAccountsQuery['total'] ?? 0); ?></div>
            <div class="stat-label">Active</div>
        </div>
        <div class="stat-card">
            <div class="stat-number"><?= number_format($assetCountQuery['total'] ?? 0); ?></div>
            <div class="stat-label">Assets</div>
        </div>
        <div class="stat-card">
            <div class="stat-number"><?= number_format($expenseCountQuery['total'] ?? 0); ?></div>
            <div class="stat-label">Expenses</div>
        </div>
    </div>
    
    <!-- Add/Edit Account Card -->
    <div class="card">
        <div class="card-header p-2">
            <div class="row align-items-center">
                <div class="col-sm-6 d-flex align-items-center text-start">
                    <h6 class="mb-0 lh-base px-3 fw-semibold d-flex align-items-center" style="color: #1a2c3e;">
                        <i class="ti ti-pencil fs-5 me-2" style="color: #1976d2;"></i>
                        <span class="pt-1"><?= !empty($account_id) ? 'Edit Account' : 'Add New Account'; ?></span>
                    </h6>
                </div>
                <div class="col-sm-6 text-end pe-3">
                    <?php if(!empty($account_id)): ?>
                        <a href="<?= site_url('mycoa?meaction=MAIN'); ?>" class="btn btn-outline-secondary btn-sm">
                            <i class="ti ti-plus"></i> Add New
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="card-body p-0 px-4 py-3 my-1">
            <?php if(!empty($account_id)): ?>
                <div class="edit-mode-badge">
                    <i class="ti ti-edit fs-6"></i>
                    Editing: <?= $account_code; ?> - <?= $account_name; ?>
                </div>
            <?php endif; ?>
            
            <form action="<?=site_url();?>mycoa?meaction=COA-SAVE" method="post" class="mycoa-validation">
                <input type="hidden" name="account_id" id="account_id" value="<?= $account_id; ?>">
                <div class="row">
                    <!-- LEFT COLUMN -->
                    <div class="col-sm-6">
                        <div class="row mb-3 mt-1">
                            <div class="col-sm-4"><span class="text-secondary" style="font-size: 0.75rem;">Account Code:</span></div>
                            <div class="col-sm-8">
                                <input type="text" name="account_code" id="account_code" class="form-control form-control-sm" value="<?= $account_code; ?>" placeholder="e.g., 1010" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-4"><span class="text-secondary" style="font-size: 0.75rem;">Account Name:</span></div>
                            <div class="col-sm-8">
                                <input type="text" name="account_name" id="account_name" class="form-control form-control-sm" value="<?= $account_name; ?>" placeholder="e.g., Cash on Hand" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-4"><span class="text-secondary" style="font-size: 0.75rem;">Account Type:</span></div>
                            <div class="col-sm-8">
                                <select name="account_type" id="account_type" class="form-select form-select-sm" required>
                                    <option value="">Select Type</option>
                                    <option value="Asset" <?= $account_type == 'Asset' ? 'selected' : ''; ?>>Asset</option>
                                    <option value="Liability" <?= $account_type == 'Liability' ? 'selected' : ''; ?>>Liability</option>
                                    <option value="Equity" <?= $account_type == 'Equity' ? 'selected' : ''; ?>>Equity</option>
                                    <option value="Revenue" <?= $account_type == 'Revenue' ? 'selected' : ''; ?>>Revenue</option>
                                    <option value="Expense" <?= $account_type == 'Expense' ? 'selected' : ''; ?>>Expense</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- RIGHT COLUMN -->
                    <div class="col-sm-6">
                        <div class="row mb-3 mt-1">
                            <div class="col-sm-4"><span class="text-secondary" style="font-size: 0.75rem;">Parent Account:</span></div>
                            <div class="col-sm-8">
                                <select name="parent_code" id="parent_code" class="form-select form-select-sm">
                                    <option value="">— None (Main Account) —</option>
                                    <?php
                                    $parents = $this->db->query("SELECT account_code, account_name FROM tbl_coa WHERE account_code != '$account_code' OR account_code IS NULL ORDER BY account_code")->getResultArray();
                                    foreach($parents as $p) {
                                        $selected = ($parent_code == $p['account_code']) ? 'selected' : '';
                                        echo '<option value="' . $p['account_code'] . '" ' . $selected . '>' . $p['account_code'] . ' - ' . $p['account_name'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-4"><span class="text-secondary" style="font-size: 0.75rem;">Status:</span></div>
                            <div class="col-sm-8">
                                <select name="is_active" id="is_active" class="form-select form-select-sm">
                                    <option value="1" <?= $is_active == '1' ? 'selected' : ''; ?>>Active</option>
                                    <option value="0" <?= $is_active == '0' ? 'selected' : ''; ?>>Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- BUTTONS -->
                <div class="row mt-3 mb-2">
                    <div class="col-sm-12 text-end">
                        <?php if(!empty($account_id)): ?>
                            <a href="<?= site_url('mycoa?meaction=MAIN'); ?>" class="btn btn-outline-secondary btn-sm">
                                <i class="ti ti-x"></i> Cancel
                            </a>
                        <?php endif; ?>
                        <button type="submit" class="btn bg-<?= empty($account_id) ? 'success' : 'info' ?>-subtle text-<?= empty($account_id) ? 'success' : 'info' ?> btn-sm">
                            <i class="ti ti-device-floppy mt-1 fs-4 me-1"></i>
                            <?= empty($account_id) ? 'Save Account' : 'Update Account'; ?>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Chart of Accounts Structure Card -->
    <div class="card mt-3">
        <div class="card-header p-2">
            <div class="row align-items-center">
                <div class="col-sm-6 d-flex align-items-center text-start">
                    <h6 class="mb-0 lh-base px-3 fw-semibold d-flex align-items-center" style="color: #1a2c3e;">
                        <i class="ti ti-list-tree fs-5 me-2" style="color: #1976d2;"></i>
                        <span class="pt-1">Chart of Accounts Structure</span>
                    </h6>
                </div>
                <div class="col-sm-6 text-end pe-3">
                    <div class="filter-group">
                        <button class="filter-btn active" data-filter="all">All</button>
                        <button class="filter-btn" data-filter="Asset">Assets</button>
                        <button class="filter-btn" data-filter="Liability">Liabilities</button>
                        <button class="filter-btn" data-filter="Equity">Equity</button>
                        <button class="filter-btn" data-filter="Revenue">Revenue</button>
                        <button class="filter-btn" data-filter="Expense">Expenses</button>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card-body p-0 px-3 py-2">
            <div class="account-tree" id="accountTree">
                <?php
                // Get all accounts with proper null checks
                $query = $this->db->query("SELECT * FROM tbl_coa ORDER BY account_code ASC");
                $accounts = $query->getResultArray();
                
                // Build tree structure
                $tree = [];
                foreach ($accounts as $row) {
                    $parentKey = isset($row['parent_code']) && !empty($row['parent_code']) ? $row['parent_code'] : null;
                    $tree[$parentKey][] = $row;
                }
                
                function renderTree($parent, $tree, $level = 0, $filter = 'all') {
                    if (!isset($tree[$parent])) return;
                    
                    foreach ($tree[$parent] as $row) {
                        $display = ($filter === 'all' || $row['account_type'] === $filter);
                        if(!$display && $filter !== 'all') continue;
                        
                        $account_code = $row['account_code'] ?? '';
                        $account_name = $row['account_name'] ?? '';
                        $account_type = $row['account_type'] ?? '';
                        $is_active = $row['is_active'] ?? 1;
                        $account_id = $row['account_id'] ?? '';
                        ?>
                        <div class="account-item" data-type="<?= $account_type; ?>" data-active="<?= $is_active; ?>" style="padding-left: <?= $level * 28 ?>px;">
                            <div class="account-card">
                                <div class="account-info">
                                    <span class="account-code"><?= $account_code; ?></span>
                                    <span class="account-name"><?= htmlspecialchars($account_name); ?></span>
                                    <span class="type-badge type-<?= strtolower($account_type); ?>">
                                        <?= $account_type; ?>
                                    </span>
                                    <span class="status-pill <?= $is_active ? 'status-active' : 'status-inactive'; ?>">
                                        <?= $is_active ? 'Active' : 'Inactive'; ?>
                                    </span>
                                </div>
                                <div class="account-actions">
                                    <a href="<?= site_url('mycoa?meaction=MAIN&account_id=' . $account_id); ?>" class="action-icon" title="Edit Account">
                                        <i class="ti ti-edit"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <?php
                        renderTree($account_code, $tree, $level + 1, $filter);
                    }
                }
                ?>
                <div id="treeContent">
                    <?php renderTree(null, $tree, 0, 'all'); ?>
                </div>
                <?php if(empty($accounts)): ?>
                    <div class="text-center py-5 text-muted">
                        <i class="ti ti-folder-off fs-1 d-block mb-2"></i>
                        <p class="mb-0">No accounts found. Create your first account above.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="<?=base_url('assets/js/accounting/mycoa.js?v=2');?>"></script>
<script src="<?=base_url('assets/js/mysysapps.js');?>"></script>

<script>
$(document).ready(function() {
    // Filter functionality
    $('.filter-btn').click(function() {
        $('.filter-btn').removeClass('active');
        $(this).addClass('active');
        
        const filter = $(this).data('filter');
        
        if(filter === 'all') {
            $('.account-item').show();
        } else {
            $('.account-item').hide();
            $(`.account-item[data-type="${filter}"]`).show();
        }
    });
    
    <?php if(!empty($account_id)): ?>
        // Scroll to form on page load when editing
        setTimeout(function() {
            $('html, body').animate({
                scrollTop: $('.card').offset().top - 20
            }, 500);
        }, 300);
    <?php endif; ?>
});

__mysys_coa_ent.__coa_saving();
</script>

<?php
echo view('templates/myfooter.php');
?>