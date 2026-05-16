<?php
$this->request = \Config\Services::request();
$this->db = \Config\Database::connect();

// ==============================
// FETCH DATA
// ==============================
$query = $this->db->query("SELECT * FROM tbl_inventory_movements ORDER BY movement_id DESC");
$movements = $query->getResultArray();

// fetch products for dropdown
$products = $this->db->query("SELECT product_id, product_name FROM tbl_products WHERE status='ACTIVE'")->getResultArray();

// ==============================
// DASHBOARD CALCULATIONS
// ==============================

$total_products = $this->db->query("SELECT COUNT(*) as total FROM tbl_products")->getRow()->total;

$total_stock = $this->db->query("SELECT COALESCE(SUM(stock_qty),0) as total FROM tbl_products")->getRow()->total;

$low_stock = $this->db->query("
    SELECT COUNT(*) as total 
    FROM tbl_products 
    WHERE stock_qty <= reorder_level
")->getRow()->total;

$total_movements = count($movements);

$currentInventory = $this->db->query("
    SELECT 
        product_name,
        category,
        stock_qty,
        reorder_level,
        selling_price,
        status
    FROM tbl_products
    ORDER BY stock_qty ASC
")->getResultArray();

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

    .bg-success {
        background: var(--success) !important;
    }

    .bg-danger {
        background: var(--danger) !important;
    }

    .bg-warning {
        background: var(--warning) !important;
        color: #ffffff;
    }

    .bg-primary {
        background: var(--primary) !important;
    }

    .bg-secondary {
        background: var(--gray-400) !important;
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
    }
</style>

<div class="me-inventory-msg"></div>
<div class="me-adjustment-msg"></div>
<input type="hidden" id="__siteurl" data-mesiteurl="<?=site_url();?>" />

<div class="row mb-2">
    <div class="col-12">
        <h4 class="fw-semibold my-3">Inventory Management</h4>

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="<?=site_url();?>mydashboard">
                        <i class="ti ti-home"></i>
                    </a>
                </li>
                <li class="breadcrumb-item">Inventory</li>
                <li class="breadcrumb-item active">Movements</li>
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
                    <div class="attendance-label">Total Products</div>
                    <div class="attendance-value"><?=$total_products;?></div>
                    <div class="attendance-sub">Active products</div>
                </div>
                <i class="ti ti-box attendance-icon"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card attendance-card h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="attendance-label">Total Stock</div>
                    <div class="attendance-value"><?=$total_stock;?></div>
                    <div class="attendance-sub">Units in inventory</div>
                </div>
                <i class="ti ti-package attendance-icon"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card attendance-card h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="attendance-label">Low Stock</div>
                    <div class="attendance-value"><?=$low_stock;?></div>
                    <div class="attendance-sub">Below reorder level</div>
                </div>
                <i class="ti ti-alert-triangle attendance-icon"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card attendance-card h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="attendance-label">Movements</div>
                    <div class="attendance-value"><?=$total_movements;?></div>
                    <div class="attendance-sub">Total transactions</div>
                </div>
                <i class="ti ti-exchange attendance-icon"></i>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- MOVEMENTS TABLE -->
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h6 class="fw-semibold mb-0">Inventory Movements</h6>
                <span class="badge bg-light text-dark border"><?=count($movements);?> records</span>
            </div>

            <div class="card-body">
                <table id="movementTable" class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Ref No</th>
                            <th>Product</th>
                            <th>Type</th>
                            <th>Qty</th>
                            <th>Reference</th>
                            <th>Remarks</th>
                            <th>User</th>
                            <th>Date</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach($movements as $row): ?>
                        <tr>
                            <td><strong><?=$row['reference_no'];?></strong></td>
                            <td><?=$row['product_name'];?></td>

                            <td>
                                <?php if($row['movement_type'] == 'IN'): ?>
                                    <span class="badge bg-success">IN</span>
                                <?php elseif($row['movement_type'] == 'OUT'): ?>
                                    <span class="badge bg-danger">OUT</span>
                                <?php else: ?>
                                    <span class="badge bg-warning">ADJ</span>
                                <?php endif; ?>
                            </td>

                            <td><?=$row['quantity'];?></td>
                            <td><?=$row['reference_type'];?></td>
                            <td><?=$row['remarks'];?></td>
                            <td><?=$row['created_by'];?></td>
                            <td><?=date('M d, Y h:i A', strtotime($row['created_at']));?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>

                </table>
            </div>
        </div>
    </div>
        <!-- RIGHT SIDE FORMS -->
    <div class="col-md-6">
        <!-- STOCK IN -->
        <div class="card mb-3">
        <form action="<?=site_url();?>inventory?meaction=STOCKIN-SAVE" method="post" class="inv-reg-form" id="invRegForm">
            <div class="card-header">
                <h6 class="fw-semibold mb-0">Stock In Entry</h6>
                <small class="text-muted">Add or restock products</small>
            </div>

            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Product Name</label>
                    <input type="text" id="product_name" class="form-control" name="product_name">
                </div>
                <div class="mb-3">
                    <label class="form-label">Category</label>
                    <select name="category" id="category" class="form-control">
                        <option value="">Select</option>
                        <option value="Merchandise">Merchandise</option>
                        <option value="Accessories">Accessories</option>
                        <option value="Beverages">Beverages</option>
                        <option value="Food">Food</option>
                        <option value="Supplements">Supplements</option>
                    </select>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Purchase Price</label>
                        <input type="number" id="purchase_price" class="form-control" name="purchase_price">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Selling Price</label>
                        <input type="number" id="selling_price" class="form-control" name="selling_price">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Quantity</label>
                        <input type="number" id="stock_qty" class="form-control" name="stock_qty">
                    </div>
                </div>

                <div class="col text-end mt-2">
                    <button type="submit" class="btn btn-danger w-100 mt-2">
                        <i class="ti ti-device-floppy me-1"></i>
                        Save Stock In
                    </button>
                </div>
            </div>
        </form>
        </div>
    </div>
    <div class="col-md-6">
        <!-- MANUAL ADJUSTMENT -->
        <div class="card">
        <form action="<?=site_url();?>inventory?meaction=ADJUSTMENT-SAVE" method="post" class="adj-reg-form" id="adjRegForm">
            <div class="card-header">
                <h6 class="fw-semibold mb-0">Manual Adjustment</h6>
                <small class="text-muted">Adjust stock manually</small>
            </div>

            <div class="card-body">

                <div class="mb-3">
                    <label class="form-label">Product</label>
                    <select class="form-control" name="adj_product_name" id="adj_product_name">
                        <option value="">-- Select Product --</option>
                        <?php foreach($products as $p): ?>
                            <option value="<?=$p['product_name'];?>"><?=$p['product_name'];?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Adjustment Type</label>
                    <select class="form-control" name="adj_type" id="adj_type">
                        <option value="INCREASE">Increase</option>
                        <option value="DECREASE">Decrease</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Reason</label>
                    <select name="remarks" id="remarks" class="form-control">
                        <option value="">Select</option>
                        <option value="Physical Count Correction">Physical Count Correction</option>
                        <option value="Damaged Item">Damaged Item</option>
                        <option value="Expired Item">Expired Item</option>
                        <option value="Lost / Missing">Lost / Missing</option>
                        <option value="Free / Complimentary">Free / Complimentary</option>
                        <option value="System Correction">System Correction</option>
                        <option value="Returned Item">Returned Item</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Quantity</label>
                    <input type="number" class="form-control" name="adj_qty" id="adj_qty">
                </div>


                <div class="col text-end mt-2">
                    <button type="submit" class="btn btn-danger w-100 mt-2">
                        <i class="ti ti-device-floppy me-1"></i>
                        Save Adjustment
                    </button>
                </div>

            </div>
        </form>
        </div>
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-12">
        <div class="card">

            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fw-semibold mb-0">Current Inventory</h6>
                    <small class="text-muted">
                        Live stock status of all products
                    </small>
                </div>

                <span class="badge bg-light text-dark border">
                    <?=count($currentInventory);?> products
                </span>
            </div>

            <div class="card-body">

                <table id="currentInventoryTable" class="table table-hover align-middle">

                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Category</th>
                            <th>Stock</th>
                            <th>Reorder Level</th>
                            <th>Selling Price</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach($currentInventory as $row): ?>
                        <tr>

                            <td>
                                <strong><?=$row['product_name'];?></strong>
                            </td>

                            <td><?=$row['category'];?></td>

                            <td>
                                <?php if($row['stock_qty'] <= $row['reorder_level']): ?>
                                    <span class="badge bg-danger">
                                        <?=$row['stock_qty'];?>
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-success">
                                        <?=$row['stock_qty'];?>
                                    </span>
                                <?php endif; ?>
                            </td>

                            <td><?=$row['reorder_level'];?></td>

                            <td>
                                ₱<?=number_format($row['selling_price'],2);?>
                            </td>

                            <td>
                                <?php if($row['status'] == 'ACTIVE'): ?>
                                    <span class="badge bg-primary">
                                        ACTIVE
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">
                                        INACTIVE
                                    </span>
                                <?php endif; ?>
                            </td>

                        </tr>
                        <?php endforeach; ?>
                    </tbody>

                </table>

            </div>

        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="<?=base_url('assets/js/inventory/inventory.js?v=1');?>"></script>

<script>
$(document).ready(function () {

    $('#movementTable').DataTable({
        pageLength: 10,
        lengthChange: false,
        order: [[6, 'desc']],
        language: {
            search: "Search Movement:"
        }
    });

    $('#currentInventoryTable').DataTable({
        pageLength: 5,
        lengthChange: false,
        order: [[2, 'asc']],
        language: {
            search: "Search Product:"
        }
    });

});

</script>

<?php
echo view('templates/myfooter.php');
?>