<?php
$this->request = \Config\Services::request();
$this->db = \Config\Database::connect();

$postrxno = $this->request->getPostGet('postrxno');

$query = $this->db->query("SELECT * FROM tbl_pos_payment ORDER BY recid DESC" );
$payments = $query->getResultArray();
if (!empty($postrxno)) {
    $query = $this->db->query("
    SELECT
        `recid`,
        `postrxno`,
        `item_name`,
        `item_type`,
        `item_qty`,
        `item_amount`,
        `created_by`,
        `created_at`
    FROM
        `tbl_pos_dt`
    WHERE
        `postrxno` = '$postrxno'
    ");
    $posbreakdown = $query->getResultArray();
}


// =============================================
// ANALYTICS CALCULATIONS
// =============================================

// Total Sales (Sum of grand_total)
$total_sales = 0;
foreach($payments as $row) {
    $total_sales += floatval($row['grand_total']);
}

// Total Transactions Count
$total_transactions = count($payments);

// Average Transaction Value
$avg_transaction = ($total_transactions > 0) ? $total_sales / $total_transactions : 0;

// Today's Sales (transactions from today)
$today = date('Y-m-d');
$today_sales = 0;
$today_count = 0;
foreach($payments as $row) {
    $transaction_date = date('Y-m-d', strtotime($row['created_at']));
    if($transaction_date == $today) {
        $today_sales += floatval($row['grand_total']);
        $today_count++;
    }
}

// Highest Transaction
$highest_transaction = 0;
foreach($payments as $row) {
    if(floatval($row['grand_total']) > $highest_transaction) {
        $highest_transaction = floatval($row['grand_total']);
    }
}

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

    /* Badges */
    .badge {
        font-size: 10px;
        font-weight: 600;
        padding: 5px 12px;
        border-radius: 30px;
        letter-spacing: 0.3px;
    }

    .badge-cash {
        background: var(--success);
        color: #ffffff;
    }

    .badge-gcash {
        background: var(--info);
        color: #ffffff;
    }

    .badge-maya {
        background: #006b6f;
        color: #ffffff;
    }

    .badge-card {
        background: #b8860b;
        color: #ffffff;
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

    /* Action Icons */
    .nav-icon-hover {
        transition: all 0.2s;
        display: inline-block;
        color: var(--info);
        font-size: 18px;
    }

    .nav-icon-hover:hover {
        transform: scale(1.1);
        color: var(--primary);
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

    /* Buttons */
    .btn-outline-secondary {
        border: 1.5px solid var(--gray-200);
        border-radius: 10px;
        padding: 5px 12px;
        font-size: 12px;
        transition: all 0.2s;
    }

    .btn-outline-secondary:hover {
        border-color: var(--danger);
        color: var(--danger);
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

<div class="me-pos-msg"></div>
<input type="hidden" id="__siteurl" data-mesiteurl="<?=site_url();?>"/>

<div class="row mb-2">
    <div class="col-12">

        <h4 class="fw-semibold my-3">Sales Transaction</h4>

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a class="text-muted text-decoration-none" href="<?=site_url();?>mydashboard">
                        <i class="ti ti-home fs-5"></i>
                    </a>
                </li>

                <li class="breadcrumb-item">POS & Cashiering</li>
                <li class="breadcrumb-item active">Sales Transaction</li>
            </ol>
        </nav>

    </div>
</div>

<!-- ============================================= -->
<!-- DASHBOARD CARDS SECTION - MATCHING ATTENDANCE MODULE -->
<!-- ============================================= -->

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card attendance-card h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="attendance-label">Total Sales</div>
                    <div class="attendance-value">₱<?=number_format($total_sales, 2);?></div>
                    <div class="attendance-sub">All time revenue</div>
                </div>
                <i class="ti ti-receipt attendance-icon"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card attendance-card h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="attendance-label">Transactions</div>
                    <div class="attendance-value"><?=$total_transactions;?></div>
                    <div class="attendance-sub">Total number of sales</div>
                </div>
                <i class="ti ti-shopping-cart attendance-icon"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card attendance-card h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="attendance-label">Highest Transaction</div>
                    <div class="attendance-value">₱<?=number_format($highest_transaction, 2);?></div>
                    <div class="attendance-sub">Largest single sale</div>
                </div>
                <i class="ti ti-trophy attendance-icon"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card attendance-card h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="attendance-label">Today's Sales</div>
                    <div class="attendance-value">₱<?=number_format($today_sales, 2);?></div>
                    <div class="attendance-sub"><?=$today_count;?> transaction(s)</div>
                </div>
                <i class="ti ti-calendar attendance-icon"></i>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Table Section -->
    <div class="col-md-7">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-0 fw-semibold">POS Transactions</h6>
                    <small class="text-muted">List of all recorded sales</small>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-light text-dark border">
                        <?=count($payments);?> records
                    </span>

                    <button class="btn btn-sm btn-outline-secondary" onclick="location.reload();">
                        <i class="ti ti-refresh"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="paymentTable" class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>POS No</th>
                                <th>Payment Method</th>
                                <th>Amount Tendered</th>
                                <th>Change</th>
                                <th>Grand Total</th>
                                <th>Cashier</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($payments as $row): ?>
                            <tr>
                                <td><strong><?=$row['postrxno'];?></strong></td>
                                <td>
                                    <?php if($row['payment_method'] == 'Cash'): ?>
                                        <span class="badge badge-cash"><?=$row['payment_method'];?></span>
                                    <?php elseif($row['payment_method'] == 'GCash'): ?>
                                        <span class="badge badge-gcash"><?=$row['payment_method'];?></span>
                                    <?php elseif($row['payment_method'] == 'Maya'): ?>
                                        <span class="badge badge-maya"><?=$row['payment_method'];?></span>
                                    <?php elseif($row['payment_method'] == 'Card'): ?>
                                        <span class="badge badge-card"><?=$row['payment_method'];?></span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary"><?=$row['payment_method'];?></span>
                                    <?php endif; ?>
                                </td>
                                <td>₱<?=number_format($row['amount_tendered'],2);?></td>
                                <td>₱<?=number_format($row['change_amount'],2);?></td>
                                <td><strong>₱<?=number_format($row['grand_total'],2);?></strong></td>
                                <td><?=$row['created_by'];?></td>
                                <td><?=date('M d, Y h:i A', strtotime($row['created_at']));?></td>
                                <td>                               
                                    <a class="nav-icon-hover text-decoration-none" href="<?=site_url();?>possales?meaction=MAIN&postrxno=<?=$row['postrxno'];?>" title="View breakdown">
                                        <i class="ti ti-receipt"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-5">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-0 fw-semibold">
                        <?php if(!empty($postrxno)): ?>
                            Breakdown: <span class="text-danger"><?=$postrxno;?></span>
                        <?php else: ?>
                            Sales Breakdown
                        <?php endif; ?>
                    </h6>
                    <small class="text-muted">Detailed items per transaction</small>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="breakdownTable" class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>POS No</th>
                                <th>Item name</th>
                                <th>Item Type</th>
                                <th>Item QTY</th>
                                <th>Item Amount</th>
                                <th>Cashier</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($postrxno) && count($posbreakdown) > 0): ?>
                                <?php foreach($posbreakdown as $row): ?>
                                <tr>
                                    <td><strong><?=$postrxno;?></strong></td>
                                    <td><?=$row['item_name'];?></td>
                                    <td><?=$row['item_type'];?></td>
                                    <td><?=$row['item_qty'];?></td>
                                    <td>₱<?=number_format($row['item_amount'],2);?></td>
                                    <td><?=$row['created_by'];?></td>
                                    <td><?=date('M d, Y h:i A', strtotime($row['created_at']));?></td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">
                                        <i class="ti ti-info-circle me-1"></i> Select a transaction to view breakdown
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="<?=base_url('assets/js/pos/pos.js?v=1');?>"></script>

<script>
$(document).ready(function () {
    // Initialize payment table DataTable
    $('#paymentTable').DataTable({
        pageLength: 5,
        lengthChange: false,
        scrollX: false,
        order: [[6, 'desc']],
        language: {
            search: "Search Transaction:"
        }
    });
    
    // Initialize breakdown table DataTable only if there are rows and columns
    var $breakdownTable = $('#breakdownTable');
    if ($breakdownTable.length && $breakdownTable.find('tbody tr').length > 1) {
        $breakdownTable.DataTable({
            pageLength: 5,
            lengthChange: false,
            scrollX: false,
            language: {
                search: "Search:",
                info: "Showing _START_ to _END_ of _TOTAL_ entries",
                infoEmpty: "No entries found"
            }
        });
    }
});
</script>

<?php
echo view('templates/myfooter.php');
?>