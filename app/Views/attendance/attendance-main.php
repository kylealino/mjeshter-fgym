<?php
$this->request = \Config\Services::request();
$this->db = \Config\Database::connect();

// =====================================================
// SELECTED DATE
// =====================================================
$selected_date = $this->request->getGet('selected_date');

if(empty($selected_date)){
    $selected_date = date('Y-m-d');
}

// =====================================================
// MEMBER ATTENDANCE
// =====================================================
$memberQuery = $this->db->query("
    SELECT 
        ch.checkin_id,
        m.first_name,
        m.last_name,
        m.membership_plan,
        ch.rfid_uid,
        ch.checkin_time,
        ch.checkout_time,
        ch.status
    FROM tbl_checkin_history ch
    LEFT JOIN tbl_members m
        ON m.member_id = ch.member_id
    WHERE DATE(ch.checkin_time) = ?
    ORDER BY ch.checkin_time DESC
", [$selected_date]);

$memberAttendance = $memberQuery->getResultArray();

// =====================================================
// WALKIN ATTENDANCE
// =====================================================
$walkinQuery = $this->db->query("
    SELECT *
    FROM tbl_walkin_checkin_history
    WHERE DATE(checkin_time) = ?
    ORDER BY checkin_time DESC
", [$selected_date]);

$walkinAttendance = $walkinQuery->getResultArray();

// =====================================================
// COUNTS
// =====================================================
$totalMembersToday = count($memberAttendance);
$totalWalkinsToday = count($walkinAttendance);

echo view('templates/myheader.php');
?>

<style>

.table thead th{
    font-size:13px;
    font-weight:600;
    border-bottom:1px solid #dee2e6;
}

.table tbody td{
    font-size:13px;
    vertical-align:middle;
}

/* =========================================
DASHBOARD CARDS
========================================= */

.attendance-card{
    border-radius:14px;
    border:none;
    overflow:hidden;
    transition:.2s;
}

.attendance-card:hover{
    transform:translateY(-2px);
}

.attendance-value{
    font-size:38px;
    font-weight:700;
    line-height:1;
}

.attendance-icon{
    font-size:50px;
    opacity:.15;
}

/* =========================================
FILTER BOX
========================================= */

.filter-box{
    border-radius:14px;
    border:none;
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

<div class="row mb-2 mt-5">
    <div class="col-12">
        <h4 class="fw-semibold my-3">Attendance Log</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a class="text-muted text-decoration-none" href="<?=site_url();?>attendance">
                        <i class="ti ti-home fs-5"></i>
                    </a>
                </li>
                <li class="breadcrumb-item">Membership</li>
                <li class="breadcrumb-item active">Attendance Log</li>
            </ol>
        </nav>
    </div>
</div>


<div class="row">
    <div class="col-sm-6">
        <!-- DASHBOARD -->
        <div class="row ">
            <div class="col-md-6">
                <div class="card attendance-card shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="text-muted mb-2">
                                    Membership Attendance
                                </div>
                                <div class="attendance-value text-primary">
                                    <?=$totalMembersToday;?>
                                </div>
                            </div>
                            <div>
                                <i class="ti ti-user-check attendance-icon text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card attendance-card shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="text-muted mb-2">
                                    Walk-In Attendance
                                </div>
                                <div class="attendance-value text-danger">
                                    <?=$totalWalkinsToday;?>
                                </div>
                            </div>
                            <div>
                                <i class="ti ti-users attendance-icon text-danger"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6">
        <!-- FILTER -->
        <div class="card filter-box shadow-sm mb-4">
            <div class="card-body">
                <div class="row align-items-end">
                    <div class="col-md-4 mb-2">
                        <label class="form-label fw-semibold">
                            Attendance Date
                        </label>
                        <input type="date"
                            id="attendance_date"
                            class="form-control form-control-sm"
                            value="<?=$selected_date;?>">
                    </div>
                    <div class="col-md-2 mb-2">
                        <button type="button"
                            class="btn btn-danger btn-sm w-100"
                            onclick="filterAttendance()">

                            <i class="ti ti-search me-1"></i>
                            Filter
                        </button>
                    </div>
                    <div class="col-md-2 mb-2">
                        <a href="<?=site_url();?>attendance?meaction=MAIN"
                            class="btn btn-light border btn-sm w-100">
                            <i class="ti ti-refresh me-1"></i>
                            Today
                        </a>
                    </div>
                    <div class="col-md-4 mb-2 text-end">
                        <div class="px-3 py-2 bg-light border rounded d-inline-block">
                            <small class="text-muted d-block">
                                CURRENT VIEW
                            </small>
                            <strong>
                                <?=date('F d, Y', strtotime($selected_date));?>
                            </strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




<div class="row">
    <!-- MEMBERSHIP -->
    <div class="col-md-8">
        <div class="card shadow-sm mb-4">

            <div class="card-header bg-white d-flex justify-content-between align-items-center">

                <h5 class="mb-0 fw-semibold">
                    Membership Attendance
                </h5>

                <span class="badge bg-primary">
                    <?=$totalMembersToday;?> Records
                </span>

            </div>

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-hover align-middle"
                        id="memberAttendanceTable">

                        <thead>

                            <tr>
                                <th>Member</th>
                                <th>Plan</th>
                                <th>RFID</th>
                                <th>Check In</th>
                                <th>Check Out</th>
                                <th>Status</th>
                            </tr>

                        </thead>

                        <tbody>

                            <?php foreach($memberAttendance as $row): ?>

                            <tr>

                                <td>
                                    <strong>
                                        <?=$row['last_name'];?>,
                                        <?=$row['first_name'];?>
                                    </strong>
                                </td>

                                <td>
                                    <?=$row['membership_plan'];?>
                                </td>

                                <td>
                                    <?=$row['rfid_uid'];?>
                                </td>

                                <td>
                                    <?=date('h:i A', strtotime($row['checkin_time']));?>
                                </td>

                                <td>

                                    <?php if(!empty($row['checkout_time'])): ?>

                                        <?=date('h:i A', strtotime($row['checkout_time']));?>

                                    <?php else: ?>

                                        <span class="badge bg-success">
                                            Currently Inside
                                        </span>

                                    <?php endif; ?>

                                </td>

                                <td>

                                    <?php if($row['status'] == 'Active'): ?>

                                        <span class="badge bg-success">
                                            Active
                                        </span>

                                    <?php else: ?>

                                        <span class="badge bg-secondary">
                                            <?=$row['status'];?>
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
    <!-- WALKIN -->
    <div class="col-md-4">

        <div class="card shadow-sm">

            <div class="card-header bg-white d-flex justify-content-between align-items-center">

                <h5 class="mb-0 fw-semibold">
                    Walk-In Attendance
                </h5>

                <span class="badge bg-danger">
                    <?=$totalWalkinsToday;?> Records
                </span>

            </div>

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-hover align-middle"
                        id="walkinAttendanceTable">

                        <thead>

                            <tr>
                                <th>Name</th>
                                <th>Check In</th>
                            </tr>

                        </thead>

                        <tbody>

                            <?php foreach($walkinAttendance as $row): ?>

                            <tr>

                                <td>
                                    <strong>
                                        <?=$row['walkin_name'];?>
                                    </strong>
                                </td>

                                <td>
                                    <?=date('h:i A', strtotime($row['checkin_time']));?>
                                </td>

                            </tr>

                            <?php endforeach; ?>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>

function filterAttendance(){

    var selected_date = $('#attendance_date').val();

    if(selected_date == ''){
        toastr.error('Please select attendance date');
        return;
    }

    window.location.href =
        "<?=site_url();?>attendance?meaction=MAIN&selected_date="
        + selected_date;
}

$(document).ready(function(){

    $('#memberAttendanceTable').DataTable({
        pageLength: 10,
        order: [[3, 'desc']]
    });

    $('#walkinAttendanceTable').DataTable({
        pageLength: 10,
        order: [[1, 'ASC']],
        lengthChange: false
    });

});

</script>

<?php
echo view('templates/myfooter.php');
?>