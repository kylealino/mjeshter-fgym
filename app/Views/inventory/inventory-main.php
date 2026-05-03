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
</style>
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
            <div class="card-header">
                <h6 class="fw-semibold mb-0">Stock In Entry</h6>
                <small class="text-muted">Add or restock products</small>
            </div>

            <div class="card-body">

                <div class="mb-2">
                    <label>Product</label>
                    <select class="form-control" name="product_id">
                        <option value="">-- Select Product --</option>
                        <?php foreach($products as $p): ?>
                            <option value="<?=$p['product_id'];?>"><?=$p['product_name'];?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-2">
                    <label>Product Name</label>
                    <input type="text" class="form-control" name="product_name">
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <label>Quantity</label>
                        <input type="number" class="form-control" name="qty">
                    </div>
                    <div class="col-md-6">
                        <label>Purchase Price</label>
                        <input type="number" class="form-control" name="price">
                    </div>
                </div>

                <div class="mt-2">
                    <label>Supplier</label>
                    <input type="text" class="form-control" name="supplier">
                </div>

                <div class="mt-2">
                    <label>Remarks</label>
                    <input type="text" class="form-control" name="remarks">
                </div>

                <button class="btn btn-danger w-100 mt-3">
                    Save Stock In
                </button>

            </div>
        </div>
    </div>
    <div class="col-md-6">
        <!-- MANUAL ADJUSTMENT -->
        <div class="card">
            <div class="card-header">
                <h6 class="fw-semibold mb-0">Manual Adjustment</h6>
                <small class="text-muted">Adjust stock manually</small>
            </div>

            <div class="card-body">

                <div class="mb-2">
                    <label>Product</label>
                    <select class="form-control" name="adj_product_id">
                        <option value="">-- Select Product --</option>
                        <?php foreach($products as $p): ?>
                            <option value="<?=$p['product_id'];?>"><?=$p['product_name'];?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-2">
                    <label>Adjustment Type</label>
                    <select class="form-control" name="adj_type">
                        <option value="INCREASE">Increase</option>
                        <option value="DECREASE">Decrease</option>
                    </select>
                </div>

                <div class="mb-2">
                    <label>Quantity</label>
                    <input type="number" class="form-control" name="adj_qty">
                </div>

                <div class="mb-2">
                    <label>Reason</label>
                    <input type="text" class="form-control" name="adj_reason">
                </div>

                <button class="btn btn-danger w-100 mt-3">
                    Save Adjustment
                </button>

            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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

});
</script>

<?php
echo view('templates/myfooter.php');
?>