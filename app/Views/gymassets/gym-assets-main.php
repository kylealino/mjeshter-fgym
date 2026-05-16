<?php
$this->request = \Config\Services::request();
$this->db = \Config\Database::connect();

// ==============================
// FETCH DATA
// ==============================
$query = $this->db->query("SELECT * FROM tbl_gym_assets ORDER BY asset_id DESC");
$assets = $query->getResultArray();

// ==============================
// DASHBOARD CALCULATIONS
// ==============================
$total_assets = $this->db->query("SELECT COUNT(*) as total FROM tbl_gym_assets")->getRow()->total;
$total_cost = $this->db->query("SELECT COALESCE(SUM(acquisition_cost),0) as total FROM tbl_gym_assets")->getRow()->total;
$total_depreciation = $this->db->query("SELECT COALESCE(SUM(monthly_depreciation),0) as total FROM tbl_gym_assets")->getRow()->total;
$active_assets = $this->db->query("SELECT COUNT(*) as total FROM tbl_gym_assets WHERE status = 'ACTIVE'")->getRow()->total;

echo view('templates/myheader.php');
?>
<style>
    :root {
        --gym-red: #dc2626;
        --gym-red-light: #fee2e2;
        --gym-black: #0a0a0a;
        --gym-gray: #6c757d;
        --gym-border: #e5e7eb;
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

    .btn-secondary {
        background: #ffffff;
        border: 1.5px solid var(--gym-border);
        border-radius: 12px;
        padding: 10px 20px;
        font-size: 13px;
        font-weight: 600;
        color: #475569;
    }

    .btn-secondary:hover {
        border-color: var(--gym-red);
        color: var(--gym-red);
    }

    /* Badges */
    .badge {
        font-size: 10px;
        font-weight: 600;
        padding: 4px 12px;
        border-radius: 30px;
    }

    .bg-success {
        background: #10b981 !important;
    }

    .bg-secondary {
        background: #6b7280 !important;
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
    }
</style>

<div class="me-gymassets-msg"></div>
<input type="hidden" id="__siteurl" data-mesiteurl="<?=site_url();?>" />

<div class="row mb-2">
    <div class="col-12">
        <h4 class="fw-semibold my-3">Gym Assets</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="<?=site_url();?>mydashboard">
                        <i class="ti ti-home"></i>
                    </a>
                </li>
                <li class="breadcrumb-item">Assets</li>
                <li class="breadcrumb-item active">Gym Assets</li>
            </ol>
        </nav>
    </div>
</div>

<!-- Dashboard Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="attendance-card">
            <div class="card-body">
                <div>
                    <div class="attendance-label">Total Assets</div>
                    <div class="attendance-value"><?=number_format($total_assets);?></div>
                    <div class="attendance-sub">All gym equipment</div>
                </div>
                <i class="ti ti-barbell attendance-icon"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="attendance-card">
            <div class="card-body">
                <div>
                    <div class="attendance-label">Total Cost</div>
                    <div class="attendance-value">₱<?=number_format($total_cost,2);?></div>
                    <div class="attendance-sub">Acquisition value</div>
                </div>
                <i class="ti ti-currency-peso attendance-icon"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="attendance-card">
            <div class="card-body">
                <div>
                    <div class="attendance-label">Monthly Depreciation</div>
                    <div class="attendance-value">₱<?=number_format($total_depreciation,2);?></div>
                    <div class="attendance-sub">Total per month</div>
                </div>
                <i class="ti ti-chart-line attendance-icon"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="attendance-card">
            <div class="card-body">
                <div>
                    <div class="attendance-label">Active Assets</div>
                    <div class="attendance-value"><?=number_format($active_assets);?></div>
                    <div class="attendance-sub">In use</div>
                </div>
                <i class="ti ti-checkbox attendance-icon"></i>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- ASSETS TABLE -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h6 class="fw-semibold mb-0"><i class="ti ti-list me-2"></i>Gym Assets List</h6>
                <span class="badge bg-light text-dark border"><?=count($assets);?> records</span>
            </div>
            <div class="card-body">
                <table id="assetsTable" class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Asset Name</th>
                            <th>Category</th>
                            <th>Cost</th>
                            <th>Useful Life</th>
                            <th>Monthly Depreciation</th>
                            <th>Status</th>
                            <th width="80">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($assets as $row): ?>
                        <tr>
                            <td><strong><?=$row['asset_name'];?></strong></td>
                            <td><?=$row['asset_category'];?></td>
                            <td>₱<?=number_format($row['acquisition_cost'],2);?></td>
                            <td><?=$row['useful_life_months'];?> months</td>
                            <td>₱<?=number_format($row['monthly_depreciation'],2);?></td>
                            <td>
                                <?php if($row['status'] == 'ACTIVE'): ?>
                                    <span class="badge bg-success">ACTIVE</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">DISPOSED</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <button class="btn btn-sm text-primary p-0 border-0 bg-transparent me-2 btn-icon" 
                                        onclick="editAsset(<?=$row['asset_id'];?>, '<?=addslashes($row['asset_name']);?>', '<?=$row['asset_category'];?>', '<?=$row['acquisition_cost'];?>', '<?=$row['useful_life_months'];?>', '<?=addslashes($row['notes']);?>', '<?=$row['status'];?>')" 
                                        title="Edit">
                                    <i class="ti ti-edit"></i>
                                </button>
                                <button class="btn btn-sm text-danger p-0 border-0 bg-transparent btn-icon" 
                                        onclick="showDeleteModal(<?=$row['asset_id'];?>, '<?=addslashes($row['asset_name']);?>')" 
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

    <!-- ADD ASSET FORM -->
    <div class="col-md-4">
        <div class="card">
            <form class="asset-reg-form" id="assetRegForm">
                <div class="card-header">
                    <h6 class="fw-semibold mb-0"><i class="ti ti-plus me-2"></i>Add New Asset</h6>
                    <small class="text-muted">Record new gym equipment</small>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Asset Name</label>
                        <input type="text" id="asset_name" class="form-control" name="asset_name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Asset Category</label>
                        <select name="asset_category" id="asset_category" class="form-control" required>
                            <option value="">Select Category</option>
                            <option value="Cardio Equipment">Cardio Equipment</option>
                            <option value="Strength Equipment">Strength Equipment</option>
                            <option value="Free Weights">Free Weights</option>
                            <option value="Accessories">Accessories</option>
                            <option value="Facility">Facility</option>
                            <option value="Furniture">Furniture</option>
                            <option value="Electronics">Electronics</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Acquisition Cost (₱)</label>
                        <input type="number" step="0.01" id="acquisition_cost" class="form-control" name="acquisition_cost" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Useful Life (Months)</label>
                        <input type="number" id="useful_life_months" class="form-control" name="useful_life_months" required>
                        <small class="text-muted">e.g., 60 months = 5 years</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" id="notes" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="text-end mt-3">
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="ti ti-device-floppy me-1"></i>
                            Save Asset
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Asset Modal -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Asset</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="edit_asset_id">
                
                <div class="mb-3">
                    <label class="form-label">Asset Name</label>
                    <input type="text" id="edit_asset_name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Asset Category</label>
                    <select id="edit_asset_category" class="form-control" required>
                        <option value="">Select Category</option>
                        <option value="Cardio Equipment">Cardio Equipment</option>
                        <option value="Strength Equipment">Strength Equipment</option>
                        <option value="Free Weights">Free Weights</option>
                        <option value="Accessories">Accessories</option>
                        <option value="Facility">Facility</option>
                        <option value="Furniture">Furniture</option>
                        <option value="Electronics">Electronics</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Acquisition Cost (₱)</label>
                    <input type="number" step="0.01" id="edit_acquisition_cost" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Useful Life (Months)</label>
                    <input type="number" id="edit_useful_life_months" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select id="edit_status" class="form-control" required>
                        <option value="ACTIVE">ACTIVE</option>
                        <option value="DISPOSED">DISPOSED</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Notes</label>
                    <textarea id="edit_notes" class="form-control" rows="2"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" onclick="updateAsset()">Save Changes</button>
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
                    <i class="ti ti-alert-triangle" style="font-size: 48px; color: #dc2626;"></i>
                    <h4 class="mt-3">Are you sure?</h4>
                    <p class="text-muted">You are about to delete asset: <br>
                        <strong id="delete_asset_name" class="text-danger"></strong>
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
<script src="<?=base_url('assets/js/gymassets/gym-assets.js?v=1');?>"></script>

<script>
$(document).ready(function () {
    $('#assetsTable').DataTable({
        pageLength: 10,
        lengthChange: false,
        order: [[0, 'asc']],
        language: {
            search: "Search Asset:"
        }
    });
});

var deleteId = null;
var deleteName = '';

function editAsset(id, name, category, cost, useful_life, notes, status) {
    document.getElementById('edit_asset_id').value = id;
    document.getElementById('edit_asset_name').value = name;
    document.getElementById('edit_asset_category').value = category;
    document.getElementById('edit_acquisition_cost').value = cost;
    document.getElementById('edit_useful_life_months').value = useful_life;
    document.getElementById('edit_notes').value = notes || '';
    document.getElementById('edit_status').value = status || 'ACTIVE';
    
    var editModal = new bootstrap.Modal(document.getElementById('editModal'));
    editModal.show();
}

function showDeleteModal(id, name) {
    deleteId = id;
    deleteName = name;
    document.getElementById('delete_asset_name').innerHTML = name;
    var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    deleteModal.show();
}

function deleteAsset() {
    if(deleteId) {
        var mparam = {
            asset_id: deleteId,
            meaction: 'DELETE'
        };

        jQuery.ajax({
            type: "POST",
            url: '<?=site_url();?>gym-assets',
            data: mparam,
            dataType: 'json',
            success: function(response) {
                console.log('Delete response:', response);
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
                console.log('AJAX Error:', xhr.responseText);
                toastr.error("Error: " + error);
            }
        });
    }
}

function updateAsset() {
    var asset_id = document.getElementById('edit_asset_id').value;
    var asset_name = document.getElementById('edit_asset_name').value;
    var asset_category = document.getElementById('edit_asset_category').value;
    var acquisition_cost = document.getElementById('edit_acquisition_cost').value;
    var useful_life_months = document.getElementById('edit_useful_life_months').value;
    var notes = document.getElementById('edit_notes').value;
    var status = document.getElementById('edit_status').value;

    var mparam = {
        asset_id: asset_id,
        asset_name: asset_name,
        asset_category: asset_category,
        acquisition_cost: acquisition_cost,
        useful_life_months: useful_life_months,
        notes: notes,
        status: status,
        meaction: 'EDIT'
    };

    jQuery.ajax({
        type: "POST",
        url: '<?=site_url();?>gym-assets',
        data: mparam,
        dataType: 'json',
        success: function(response) {
            console.log('Update response:', response);
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
            console.log('AJAX Error:', xhr.responseText);
            toastr.error("Error: " + error);
        }
    });
}

$('#confirmDeleteBtn').on('click', function() {
    deleteAsset();
    var modal = bootstrap.Modal.getInstance(document.getElementById('deleteModal'));
    modal.hide();
});
</script>
<!-- Toastr CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<!-- Toastr JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<?php
echo view('templates/myfooter.php');
?>