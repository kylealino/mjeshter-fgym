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
// ZUMBA ATTENDANCE
// =====================================================

$zumbaQuery = $this->db->query("
    SELECT *
    FROM tbl_zumba_checkin_history
    WHERE DATE(checkin_time) = ?
    ORDER BY checkin_time DESC
", [$selected_date]);

$zumbaAttendance = $zumbaQuery->getResultArray();

// =====================================================
// CROSSFIT ATTENDANCE
// =====================================================

$crossfitQuery = $this->db->query("
    SELECT *
    FROM tbl_crossfit_checkin_history
    WHERE DATE(checkin_time) = ?
    ORDER BY checkin_time DESC
", [$selected_date]);

$crossfitAttendance = $crossfitQuery->getResultArray();

// =====================================================
// YOGA ATTENDANCE
// =====================================================

$yogaQuery = $this->db->query("
    SELECT *
    FROM tbl_yoga_checkin_history
    WHERE DATE(checkin_time) = ?
    ORDER BY checkin_time DESC
", [$selected_date]);

$yogaAttendance = $yogaQuery->getResultArray();

// =====================================================
// COUNTS
// =====================================================

$totalMembersToday = count($memberAttendance);
$totalWalkinsToday = count($walkinAttendance);
$totalZumbaToday = count($zumbaAttendance);
$totalCrossfitToday = count($crossfitAttendance);
$totalYogaToday = count($yogaAttendance);

echo view('templates/myheader.php');
?>

<style>

body{
    background:#f5f6fa;
}

/* =========================================
TABLES
========================================= */

.table thead th{
    font-size:13px;
    font-weight:600;
    border-bottom:1px solid #e9ecef;
    color:#495057;
    white-space:nowrap;
}

.table tbody td{
    font-size:13px;
    vertical-align:middle;
    color:#343a40;
    padding-top:14px;
    padding-bottom:14px;
}

.table-hover tbody tr:hover{
    background:#fafafa;
}

/* =========================================
CARDS
========================================= */

.card{
    border:none;
    border-radius:12px;
    box-shadow:0 2px 10px rgba(0,0,0,.04);
}

.card-header{
    background:#fff !important;
    border-bottom:1px solid #f1f3f5;
    padding:16px 20px;
}

.card-body{
    padding:20px;
}

/* =========================================
DASHBOARD CARDS
========================================= */

.attendance-card{
    border-radius:12px;
    border:none;
    transition:.2s ease;
    background:#fff;
}

.attendance-card:hover{
    transform:translateY(-2px);
    box-shadow:0 4px 14px rgba(0,0,0,.06);
}

.attendance-value{
    font-size:32px;
    font-weight:700;
    line-height:1;
    color:#212529 !important;
}

.attendance-icon{
    font-size:42px;
    opacity:.10;
    color:#212529 !important;
}

/* =========================================
FILTER SECTION
========================================= */

.form-label{
    font-size:12px;
    font-weight:600;
    color:#495057;
}

.form-control{
    border:1px solid #dee2e6;
    border-radius:8px;
    min-height:38px;
    font-size:13px;
    box-shadow:none !important;
}

.form-control:focus{
    border-color:#adb5bd;
}

/* =========================================
BUTTONS
========================================= */

.btn-dark{
    background:#212529;
    border-color:#212529;
}

.btn-dark:hover{
    background:#000;
    border-color:#000;
}

.btn-light{
    background:#fff;
}

/* =========================================
BADGES
========================================= */

.badge{
    font-size:11px;
    font-weight:600;
    padding:6px 10px;
    border-radius:20px;
}

.bg-primary{
    background:#212529 !important;
}

.bg-danger{
    background:#495057 !important;
}

.bg-success{
    background:#198754 !important;
}

.bg-secondary{
    background:#6c757d !important;
}

/* =========================================
BREADCRUMB
========================================= */

.breadcrumb-item,
.breadcrumb-item a{
    font-size:13px;
    color:#6c757d;
    text-decoration:none;
}

.breadcrumb-item.active{
    color:#212529;
    font-weight:500;
}

/* =========================================
DATATABLES
========================================= */

.dataTables_wrapper{
    font-family:'Inter', sans-serif;
    overflow-x:visible !important;
}

.table-responsive{
    overflow-x:visible !important;
    overflow-y:visible !important;
}

.dataTables_filter{
    float:right;
    margin-bottom:18px;
}

.dataTables_filter label{
    font-size:12px;
    font-weight:500;
    color:#6c757d;
    display:flex;
    align-items:center;
    gap:8px;
}

.dataTables_filter input{
    width:200px;
    padding:6px 10px;
    border:1px solid #dee2e6;
    border-radius:8px;
    font-size:12px;
    transition:.2s;
}

.dataTables_filter input:focus{
    outline:none;
    border-color:#adb5bd;
    box-shadow:none;
}

.dataTables_paginate{
    float:right;
    margin-top:20px;
}

.dataTables_paginate .paginate_button{
    padding:5px 10px !important;
    margin:0 2px !important;
    border-radius:6px !important;
    border:1px solid #e9ecef !important;
    background:#fff !important;
    color:#495057 !important;
    font-size:11px !important;
    font-weight:500 !important;
    transition:.2s;
}

.dataTables_paginate .paginate_button.current{
    background:#212529 !important;
    border-color:#212529 !important;
    color:#fff !important;
}

.dataTables_paginate .paginate_button:hover{
    background:#f8f9fa !important;
    border-color:#dee2e6 !important;
    color:#212529 !important;
}

.dataTables_paginate .paginate_button.current:hover{
    background:#000 !important;
    border-color:#000 !important;
    color:#fff !important;
}

.dataTables_info{
    float:left;
    font-size:11px;
    color:#6c757d;
    margin-top:20px;
}

.dataTables_length label{
    font-size:12px;
    color:#6c757d;
}

.dataTables_length select{
    border:1px solid #dee2e6;
    border-radius:6px;
    padding:3px 8px;
    font-size:12px;
}

/* =========================================
CURRENT VIEW BOX
========================================= */

.current-view-box{
    padding:8px 14px;
    background:#fff;
    border:1px solid #e5e7eb;
    border-radius:10px;
    min-width:140px;
    text-align:center;
    box-shadow:0 1px 2px rgba(0,0,0,.04);
}

/* =========================================
TABS
========================================= */

.custom-tabs{
    border-bottom:1px solid #e9ecef;
    margin-bottom:20px;
}

.custom-tabs .nav-link{
    border:none !important;
    background:none !important;
    color:#6c757d;
    font-size:13px;
    font-weight:600;
    padding:12px 18px;
    border-radius:8px 8px 0 0;
    transition:.2s;
}

.custom-tabs .nav-link.active{
    color:#212529;
    background:#f8f9fa !important;
    border-bottom:2px solid #212529 !important;
}

.custom-tabs .nav-link:hover{
    color:#212529;
}

/* =========================================
RESPONSIVE
========================================= */

@media (max-width:768px){

    .dataTables_filter,
    .dataTables_paginate,
    .dataTables_info{
        float:none;
        text-align:center;
    }

    .dataTables_filter{
        margin-bottom:15px;
    }

    .dataTables_filter label{
        justify-content:center;
    }

    .dataTables_paginate{
        margin-top:15px;
    }

    .dataTables_info{
        margin-top:15px;
        margin-bottom:10px;
    }

    .attendance-value{
        font-size:24px;
    }

    .attendance-icon{
        font-size:34px;
    }
}

</style>

<div class="row mb-2 mt-5">

    <div class="col-6">
        <h4 class="fw-semibold my-3">Attendance Log</h4>

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a class="text-muted text-decoration-none" href="<?=site_url();?>attendance">
                        <i class="ti ti-home fs-5"></i>
                    </a>
                </li>

                <li class="breadcrumb-item">Membership</li>

                <li class="breadcrumb-item active">
                    Attendance Log
                </li>
            </ol>
        </nav>
    </div>

    <div class="col-6">
        <div class="d-flex justify-content-end align-items-end gap-2 flex-wrap my-3">
            <div style="min-width:260px;">
                <label class="form-label fw-semibold small mb-1">
                    Attendance Date
                </label>
                <input type="date"
                    id="attendance_date"
                    class="form-control form-control-sm"
                    value="<?=$selected_date;?>">
            </div>
            <div class="d-flex gap-2">
                <button type="button"
                    class="btn btn-dark btn-sm px-4"
                    onclick="filterAttendance()">

                    <i class="ti ti-search me-1"></i>
                    Filter
                </button>
                <a href="<?=site_url();?>attendance?meaction=MAIN"
                    class="btn btn-light border btn-sm px-4">

                    <i class="ti ti-refresh me-1"></i>
                    Today
                </a>
            </div>
            <div class="current-view-box">
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

<!-- DASHBOARD -->
<div class="row g-3 mb-4">
    <div class="col">
        <div class="card attendance-card h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted small mb-1">
                        Membership
                    </div>
                    <div class="attendance-value">
                        <?=$totalMembersToday;?>
                    </div>
                </div>
                <i class="ti ti-user-check attendance-icon"></i>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card attendance-card h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted small mb-1">
                        Walk-In
                    </div>
                    <div class="attendance-value">
                        <?=$totalWalkinsToday;?>
                    </div>
                </div>
                <i class="ti ti-users attendance-icon"></i>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card attendance-card h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted small mb-1">
                        Zumba
                    </div>
                    <div class="attendance-value">
                        <?=$totalZumbaToday;?>
                    </div>
                </div>
                <i class="ti ti-activity-heartbeat attendance-icon"></i>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card attendance-card h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted small mb-1">
                        Crossfit
                    </div>
                    <div class="attendance-value">
                        <?=$totalCrossfitToday;?>
                    </div>
                </div>
                <i class="ti ti-barbell attendance-icon"></i>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card attendance-card h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted small mb-1">
                        Yoga
                    </div>

                    <div class="attendance-value">
                        <?=$totalYogaToday;?>
                    </div>
                </div>
                <i class="ti ti-stretching attendance-icon"></i>
            </div>
        </div>
    </div>
</div>

<!-- TABS -->
<div class="card shadow-sm">
    <div class="card-body">
        <ul class="nav nav-tabs custom-tabs" id="attendanceTabs" role="tablist">
            <li class="nav-item">
                <button class="nav-link active"
                    data-bs-toggle="tab"
                    data-bs-target="#membershipTab">

                    Membership
                    (<?=$totalMembersToday;?>)
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link"
                    data-bs-toggle="tab"
                    data-bs-target="#walkinTab">

                    Walk-In
                    (<?=$totalWalkinsToday;?>)
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link"
                    data-bs-toggle="tab"
                    data-bs-target="#zumbaTab">

                    Zumba
                    (<?=$totalZumbaToday;?>)
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link"
                    data-bs-toggle="tab"
                    data-bs-target="#crossfitTab">

                    Crossfit
                    (<?=$totalCrossfitToday;?>)
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link"
                    data-bs-toggle="tab"
                    data-bs-target="#yogaTab">

                    Yoga
                    (<?=$totalYogaToday;?>)
                </button>
            </li>
        </ul>

        <div class="tab-content">
            <!-- MEMBERSHIP -->
            <div class="tab-pane fade show active" id="membershipTab">
                <div class="table-responsive">
                    <table class="table table-hover align-middle" id="memberAttendanceTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Member</th>
                                <th>Plan</th>
                                <th>RFID</th>
                                <th>Check In</th>
                                <th>Check Out</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $count=1; foreach($memberAttendance as $row): ?>
                            <tr>
                                <td><?=$count;?></td>
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
                            <?php $count++; endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- WALKIN -->
            <div class="tab-pane fade" id="walkinTab">
                <div class="table-responsive">
                    <table class="table table-hover align-middle" id="walkinAttendanceTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Check In</th>
                                <th>Check Out</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $count=1; foreach($walkinAttendance as $row): ?>
                            <tr>
                                <td><?=$count;?></td>
                                <td>
                                    <strong>
                                        <?=$row['walkin_name'];?>
                                    </strong>
                                </td>
                                <td>
                                    <?=date('h:i A', strtotime($row['checkin_time']));?>
                                </td>
                                <td>
                                    <?=date('h:i A', strtotime($row['checkin_time']));?>
                                </td>
                            </tr>
                            <?php $count++; endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- ZUMBA -->
            <div class="tab-pane fade" id="zumbaTab">
                <div class="table-responsive">
                    <table class="table table-hover align-middle" id="zumbaAttendanceTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Check In</th>
                                <th>Check Out</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $count=1; foreach($zumbaAttendance as $row): ?>
                            <tr>
                                <td><?=$count;?></td>
                                <td>
                                    <strong>
                                        <?=$row['zumba_name'];?>
                                    </strong>
                                </td>
                                <td>
                                    <?=date('h:i A', strtotime($row['checkin_time']));?>
                                </td>
                                <td>
                                    <?=date('h:i A', strtotime($row['checkout_time']));?>
                                </td>
                            </tr>
                            <?php $count++; endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- CROSSFIT -->
            <div class="tab-pane fade" id="crossfitTab">
                <div class="table-responsive">
                    <table class="table table-hover align-middle" id="crossfitAttendanceTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Check In</th>
                                <th>Check Out</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $count=1; foreach($crossfitAttendance as $row): ?>
                            <tr>
                                <td><?=$count;?></td>
                                <td>
                                    <strong>
                                        <?=$row['crossfit_name'];?>
                                    </strong>
                                </td>
                                <td>
                                    <?=date('h:i A', strtotime($row['checkin_time']));?>
                                </td>
                                <td>
                                    <?=date('h:i A', strtotime($row['checkout_time']));?>
                                </td>
                            </tr>
                            <?php $count++; endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- YOGA -->
            <div class="tab-pane fade" id="yogaTab">
                <div class="table-responsive">
                    <table class="table table-hover align-middle" id="yogaAttendanceTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Check In</th>
                                <th>Check Out</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $count=1; foreach($yogaAttendance as $row): ?>
                            <tr>
                                <td><?=$count;?></td>
                                <td>
                                    <strong>
                                        <?=$row['yoga_name'];?>
                                    </strong>
                                </td>
                                <td>
                                    <?=date('h:i A', strtotime($row['checkin_time']));?>
                                </td>
                                <td>
                                    <?=date('h:i A', strtotime($row['checkout_time']));?>
                                </td>
                            </tr>
                            <?php $count++; endforeach;  ?>
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
        order: [[0, 'asc']]
    });

    $('#walkinAttendanceTable').DataTable({
        pageLength: 10,
        order: [[0, 'asc']],
        lengthChange: false
    });

    $('#zumbaAttendanceTable').DataTable({
        pageLength: 10,
        order: [[0, 'asc']],
        lengthChange: false
    });

    $('#crossfitAttendanceTable').DataTable({
        pageLength: 10,
        order: [[0, 'asc']],
        lengthChange: false
    });

    $('#yogaAttendanceTable').DataTable({
        pageLength: 10,
        order: [[0, 'asc']],
        lengthChange: false
    });

});

</script>

<?php
echo view('templates/myfooter.php');
?>