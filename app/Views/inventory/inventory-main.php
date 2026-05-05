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
    .dash-card {
    background: #fff;
    border-radius: 14px;
    padding: 18px;
    display: flex;
    align-items: center;
    gap: 15px;
    box-shadow: 0 4px 18px rgba(0,0,0,0.06);
    transition: 0.2s ease-in-out;
}

.dash-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.10);
}

.dash-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 22px;
}

.dash-info h6 {
    margin: 0;
    font-size: 13px;
    color: #6c757d;
    font-weight: 500;
}

.dash-info h3 {
    margin: 0;
    font-size: 22px;
    font-weight: 700;
    color: #212529;
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
<div class="me-inventory-msg"></div>
<div class="me-adjustment-msg"></div>
<input type="hidden" id="__siteurl" data-mesiteurl="<?=site_url();?>" />

<div class="row mb-2 mt-5">
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

<div class="row mb-3">
    <div class="col-md-3">
        <div class="dash-card">
            <div class="dash-icon bg-primary">
                <i class="ti ti-box"></i>
            </div>
            <div class="dash-info">
                <h6>Total Products</h6>
                <h3><?=$total_products;?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="dash-card">
            <div class="dash-icon bg-success">
                <i class="ti ti-package"></i>
            </div>
            <div class="dash-info">
                <h6>Total Stock</h6>
                <h3><?=$total_stock;?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="dash-card">
            <div class="dash-icon bg-danger">
                <i class="ti ti-alert-triangle"></i>
            </div>
            <div class="dash-info">
                <h6>Low Stock</h6>
                <h3><?=$low_stock;?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="dash-card">
            <div class="dash-icon bg-warning">
                <i class="ti ti-exchange"></i>
            </div>
            <div class="dash-info">
                <h6>Movements</h6>
                <h3><?=$total_movements;?></h3>
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
                <!-- <div class="mb-2">
                    <label>Supplier</label>
                    <input type="text" id="supplier" class="form-control" name="supplier">
                </div> -->
                <div class="mb-2">
                    <label>Product Name</label>
                    <input type="text" id="product_name" class="form-control" name="product_name">
                </div>
                <div class="mb-2">
                    <label>Category</label>
                    <select name="category" id="category" class="form-control">
                        <option value="">Select</option>
                        <option value="Merchandise">
                            Merchandise
                        </option>
                        <option value="Accessories">
                            Accessories
                        </option>
                        <option value="Beverages">
                            Beverages
                        </option>
                        <option value="Food">
                            Food
                        </option>
                        <option value="Supplements">
                            Supplements
                        </option>
                    </select>
                </div>
                <div class="row mt-2">
                    <div class="col-md-6">
                        <label>Purchase Price</label>
                        <input type="number" id="purchase_price" class="form-control" name="purchase_price">
                    </div>
                    <div class="col-md-6">
                        <label>Selling Price</label>
                        <input type="number" id="selling_price" class="form-control" name="selling_price">
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-6">
                        <label>Quantity</label>
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

                <div class="mb-2">
                    <label>Product</label>
                    <select class="form-control" name="adj_product_name" id="adj_product_name">
                        <option value="">-- Select Product --</option>
                        <?php foreach($products as $p): ?>
                            <option value="<?=$p['product_name'];?>"><?=$p['product_name'];?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-2">
                    <label>Adjustment Type</label>
                    <select class="form-control" name="adj_type" id="adj_type">
                        <option value="INCREASE">Increase</option>
                        <option value="DECREASE">Decrease</option>
                    </select>
                </div>

                <div class="mb-2">
                    <label>Reason</label>
                    <select name="remarks" id="remarks" class="form-control" id="remarks">
                        <option value="">Select</option>
                        <option value="Physical Count Correction">
                            Physical Count Correction
                        </option>
                        <option value="Damaged Item">
                            Damaged Item
                        </option>
                        <option value="Expired Item">
                            Expired Item
                        </option>
                        <option value="Lost / Missing">
                            Lost / Missing
                        </option>
                        <option value="Free / Complimentary">
                            Free / Complimentary
                        </option>
                        <option value="System Correction">
                            System Correction
                        </option>
                        <option value="Returned Item">
                            Returned Item
                        </option>

                    </select>
                </div>

                <div class="mb-2">
                    <label>Quantity</label>
                    <input type="number" class="form-control" name="adj_qty" id="adj_qty">
                </div>


                <div class="col text-end mt-2">
                    <button type="submit" class="btn btn-danger w-100 mt-2">
                        <i class="ti ti-device-floppy me-1"></i>
                        Save Adjustment
                    </button>
                </div>

            </div>
        </div>
    </div>
</div>

<?php
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
?>

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