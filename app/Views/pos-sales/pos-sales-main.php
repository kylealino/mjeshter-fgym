<?php
$this->request = \Config\Services::request();
$this->db = \Config\Database::connect();

$postrxno = $this->request->getPostGet('postrxno');

$query = $this->db->query("SELECT * FROM tbl_pos_payment ORDER BY recid DESC");
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

.card {
    border-radius: 10px;
    border: none;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

/* Dashboard Cards Styling */
.dashboard-card {
    background: white;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    transition: transform 0.2s;
}

.dashboard-card:hover {
    transform: translateY(-3px);
}

.dashboard-card .card-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    margin-bottom: 15px;
}

.dashboard-card .card-title {
    font-size: 12px;
    color: #6c757d;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 5px;
}

.dashboard-card .card-value {
    font-size: 28px;
    font-weight: 700;
    color: #1a1a1a;
    margin-bottom: 0;
}

.dashboard-card .card-sub {
    font-size: 11px;
    color: #6c757d;
    margin-top: 8px;
}

.table thead th {
    background: #f8f9fa;
    font-weight: 600;
    text-align: center;
}

.table tbody td {
    text-align: center;
    vertical-align: middle;
}

.badge-cash {
    background: #198754;
}

.badge-gcash {
    background: #0d6efd;
}

.badge-maya {
    background: #006b6f;
}

.badge-card {
    background: #b8860b;
}

.page-title {
    font-weight: 600;
}

/* DataTables wrapper adjustments */
.dataTables_wrapper {
    font-family: 'Inter', sans-serif;
    overflow-x: visible !important;
}

/* Remove side-by-side scroll */
.table-responsive {
    overflow-x: visible !important;
    overflow-y: visible !important;
}

/* Search bar - right aligned with fixed width */
.dataTables_filter {
    float: right;
    margin-bottom: 20px;
}

.dataTables_filter label {
    font-size: 12px;
    font-weight: 500;
    color: #555;
    display: flex;
    align-items: center;
    gap: 8px;
}

.dataTables_filter input {
    width: 200px;
    padding: 5px 8px;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    font-size: 12px;
    transition: all 0.2s;
}

.dataTables_filter input:focus {
    outline: none;
    border-color: #dc2626;
    box-shadow: 0 0 0 2px rgba(220, 38, 38, 0.1);
}

/* Pagination - right aligned, SMALLER and COMPACT */
.dataTables_paginate {
    float: right;
    margin-top: 20px;
}

.dataTables_paginate .paginate_button {
    padding: 3px 8px !important;
    margin: 0 2px !important;
    border-radius: 4px !important;
    border: 1px solid #e2e8f0 !important;
    background: #fff !important;
    color: #333 !important;
    font-size: 11px !important;
    font-weight: 500 !important;
    cursor: pointer;
    display: inline-block !important;
}

.dataTables_paginate .paginate_button.current {
    background: #dc2626 !important;
    border-color: #dc2626 !important;
    color: #fff !important;
}

.dataTables_paginate .paginate_button:hover {
    background: #f1f5f9 !important;
    border-color: #cbd5e1 !important;
    color: #333 !important;
}

.dataTables_paginate .paginate_button.current:hover {
    background: #b91c1c !important;
    border-color: #b91c1c !important;
    color: #fff !important;
}

/* Previous/Next buttons - same small size */
.dataTables_paginate .paginate_button.previous,
.dataTables_paginate .paginate_button.next {
    padding: 3px 10px !important;
}

/* Table info (entries count) - left aligned */
.dataTables_info {
    float: left;
    font-size: 11px;
    color: #666;
    margin-top: 20px;
}

/* Make table container not scroll horizontally */
.dataTables_scroll {
    overflow-x: visible !important;
}

/* Responsive behavior */
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
    
    .dashboard-card .card-value {
        font-size: 22px;
    }
}

</style>

<div class="me-pos-msg"></div>
<input type="hidden" id="__siteurl" data-mesiteurl="<?=site_url();?>"/>

<div class="row mb-2 mt-5">
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
<!-- DASHBOARD CARDS SECTION -->
<!-- ============================================= -->


<div class="row">
    <div class="col-md-3">
        <div class="dashboard-card">
            <div class="card-icon" style="background: rgba(220, 38, 38, 0.1); color: #dc2626;">
                <i class="ti ti-receipt"></i>
            </div>
            <div class="card-title">Total Sales</div>
            <div class="card-value">₱<?=number_format($total_sales, 2);?></div>
            <div class="card-sub">All time revenue</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="dashboard-card">
            <div class="card-icon" style="background: rgba(59, 130, 246, 0.1); color: #3b82f6;">
                <i class="ti ti-shopping-cart"></i>
            </div>
            <div class="card-title">Transactions</div>
            <div class="card-value"><?=$total_transactions;?></div>
            <div class="card-sub">Total number of sales</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="dashboard-card">
            <div class="card-icon" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b;">
                <i class="ti ti-trophy"></i>
            </div>
            <div class="card-title">Highest Transaction</div>
            <div class="card-value">₱<?=number_format($highest_transaction, 2);?></div>
            <div class="card-sub">Largest single sale</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="dashboard-card">
            <div class="card-icon" style="background: rgba(139, 92, 246, 0.1); color: #8b5cf6;">
                <i class="ti ti-calendar"></i>
            </div>
            <div class="card-title">Today's Sales</div>
            <div class="card-value">₱<?=number_format($today_sales, 2);?></div>
            <div class="card-sub"><?=$today_count;?> transaction(s) today</div>
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

                    <!-- Optional: Add filter or refresh -->
                    <button class="btn btn-sm btn-outline-secondary">
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
                                        <span class="badge badge-cash text-white"><?=$row['payment_method'];?></span>
                                    <?php elseif($row['payment_method'] == 'GCash'): ?>
                                        <span class="badge badge-gcash text-white"><?=$row['payment_method'];?></span>
                                    <?php elseif($row['payment_method'] == 'Maya'): ?>
                                        <span class="badge badge-maya text-white"><?=$row['payment_method'];?></span>
                                    <?php elseif($row['payment_method'] == 'Card'): ?>
                                        <span class="badge badge-card text-white"><?=$row['payment_method'];?></span>
                                    <?php endif; ?>

                                    <td>₱<?=number_format($row['amount_tendered'],2);?></td>
                                    <td>₱<?=number_format($row['change_amount'],2);?></td>
                                    <td><strong>₱<?=number_format($row['grand_total'],2);?></strong></td>
                                    <td><?=$row['created_by'];?></td>
                                    <td><?=date('M d, Y h:i A', strtotime($row['created_at']));?></td>
                                    <td>                               
                                        <a class="text-info nav-icon-hover fs-7 text-decoration-none" href="<?=site_url();?>possales?meaction=MAIN&postrxno=<?=$row['postrxno'];?>" title="View breakdown">
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
                        <?php if(!empty($postrxno)):?>
                        <tbody>
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
                        </tbody>
                        <?php else:?>
                        <tbody>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tbody>
                        <?php endif;?>
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
    $('#paymentTable').DataTable({
        pageLength: 10,
        lengthChange: false,
        order: [[0, 'desc']],
        scrollX: false,
        language: {
            search: "Search Transaction:"
        }
    });
    $('#breakdownTable').DataTable({
        pageLength: 10,
        lengthChange: false,
        order: [[0, 'desc']],
        scrollX: false,
        language: {
            search: "Search Transaction:"
        }
    });
});
</script>

<?php
echo view('templates/myfooter.php');
?>