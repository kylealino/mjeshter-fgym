<?php
$this->request = \Config\Services::request();
$this->db = \Config\Database::connect();
$member_id = $this->request->getPostGet('member_id');
$active_tab = $this->request->getPostGet('tab') ?: 'list';

$member_no = "";
$rfid_uid = "";
$first_name = "";
$last_name = "";
$middle_name = "";
$gender = "";
$date_of_birth = "";
$age = "";
$email = "";
$mobile_number = "";
$emergency_contact_name = "";
$emergency_contact_number = "";
$emergency_contact_relationship = "";
$address = "";
$city = "";
$health_conditions = "";
$allergies = "";
$fitness_goals = "";
$experience_level = "Beginner";
$membership_plan = "";
$membership_start_date = "";
$membership_end_date = "";
$membership_status = "Pending";
$username = "";
$password = "";
$referred_by = "";
$how_did_you_hear = "";
$waiver_signed = 0;
$terms_accepted = 0;

if(!empty($member_id)) { 
    $query = $this->db->query("SELECT * FROM tbl_members WHERE member_id = ?", [$member_id]);
    $data = $query->getRowArray();
    if($data) {
        extract($data);
        $active_tab = 'form';
    }
}

// ==============================
// DASHBOARD CALCULATIONS
// ==============================
$total_members = $this->db->query("SELECT COUNT(*) as total FROM tbl_members")->getRow()->total;
$active_members = $this->db->query("SELECT COUNT(*) as total FROM tbl_members WHERE membership_status = 'Active'")->getRow()->total;
$expired_members = $this->db->query("SELECT COUNT(*) as total FROM tbl_members WHERE membership_status = 'Expired'")->getRow()->total;
$pending_members = $this->db->query("SELECT COUNT(*) as total FROM tbl_members WHERE membership_status = 'Pending'")->getRow()->total;
$expiring_soon = $this->db->query("
    SELECT COUNT(*) as total 
    FROM tbl_members 
    WHERE membership_end_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY)
    AND membership_status = 'Active'
")->getRow()->total;

// Get members data for the list table
$membersdata = $this->db->query("
    SELECT 
        member_id,
        member_no,
        first_name,
        last_name,
        mobile_number,
        email,
        membership_status
    FROM tbl_members
    ORDER BY member_id DESC
")->getResultArray();

echo view('templates/myheader.php');
?>

<style>
    :root {
        --primary: #1e3a5f;
        --primary-dark: #0f2b44;
        --primary-light: #2c5a8c;
        --gray-50: #f8fafc;
        --gray-100: #f1f5f9;
        --gray-200: #e2e8f0;
        --gray-300: #cbd5e1;
        --gray-400: #94a3b8;
        --gray-500: #64748b;
        --gray-600: #475569;
        --gray-700: #334155;
        --gray-800: #1e293b;
        --danger: #dc2626;
        --success: #10b981;
        --warning: #f59e0b;
        --info: #3b82f6;
    }

    body {
        background: var(--gray-50);
    }

    /* Dashboard Cards - Matching Attendance Module */
    .attendance-card {
        background: #ffffff;
        border-radius: 20px;
        border: 1px solid var(--gray-200);
        transition: all 0.3s ease;
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

    /* Custom Tabs */
    .custom-tabs {
        border-bottom: 1px solid var(--gray-200);
        margin-bottom: 24px;
        gap: 8px;
    }

    .custom-tabs .nav-link {
        border: none;
        background: transparent;
        color: var(--gray-500);
        font-size: 13px;
        font-weight: 600;
        padding: 12px 24px;
        border-radius: 12px 12px 0 0;
        transition: all 0.2s;
        cursor: pointer;
    }

    .custom-tabs .nav-link.active {
        color: var(--danger);
        background: transparent;
        border-bottom: 2px solid var(--danger);
    }

    .custom-tabs .nav-link:hover:not(.active) {
        color: var(--primary);
        background: var(--gray-50);
    }

    /* Tab Content */
    .tab-content {
        display: none;
    }

    .tab-content.active {
        display: block;
    }

    /* Form Sections */
    .form-section {
        background: #ffffff;
        border-radius: 20px;
        padding: 20px;
        margin-bottom: 20px;
        border: 1px solid var(--gray-200);
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    }
    
    .form-section h6 {
        font-weight: 600;
        margin-bottom: 20px;
        color: var(--gray-800);
        border-left: 3px solid var(--danger);
        padding-left: 12px;
        font-size: 14px;
    }
    
    .required:after {
        content: " *";
        color: var(--danger);
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
    
    /* Cards */
    .card {
        border: 1px solid var(--gray-200);
        border-radius: 20px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        background: #ffffff;
        margin-bottom: 24px;
    }
    
    .card-body {
        padding: 24px;
    }
    
    /* Form Controls */
    .form-label {
        font-size: 12px;
        font-weight: 600;
        color: var(--gray-600);
        margin-bottom: 6px;
        display: block;
    }
    
    .form-control, .form-select {
        border: 1.5px solid var(--gray-200);
        border-radius: 12px;
        padding: 10px 14px;
        font-size: 13px;
        color: var(--gray-700);
        background: #ffffff;
        transition: all 0.2s;
    }
    
    .form-control:focus, .form-select:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(30, 58, 95, 0.08);
    }
    
    textarea.form-control {
        resize: vertical;
    }
    
    /* Checkboxes */
    .form-check-input {
        width: 18px;
        height: 18px;
        margin-top: 2px;
        border: 1.5px solid var(--gray-300);
        border-radius: 5px;
    }
    
    .form-check-input:checked {
        background-color: var(--primary);
        border-color: var(--primary);
    }
    
    .form-check-label {
        font-size: 13px;
        color: var(--gray-600);
        margin-left: 8px;
    }
    
    /* Buttons */
    .btn-danger {
        background: var(--danger);
        border: none;
        border-radius: 12px;
        padding: 10px 24px;
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
        border: 1.5px solid var(--gray-200);
        border-radius: 12px;
        padding: 10px 24px;
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
    
    /* Alerts */
    .alert {
        border-radius: 14px;
        padding: 16px 20px;
        border-left-width: 4px;
    }
    
    .alert-warning {
        background: #fffbeb;
        border-color: var(--warning);
        color: #92400e;
    }
    
    .alert-success {
        background: #ecfdf5;
        border-color: var(--success);
        color: #065f46;
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
        box-shadow: 0 0 0 3px rgba(30, 58, 95, 0.08);
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
        background: var(--primary) !important;
        border-color: var(--primary) !important;
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
    
    /* Table Styles */
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
        padding: 14px 16px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    
    .table tbody td {
        font-size: 13px;
        color: var(--gray-700);
        padding: 12px 16px;
        border-bottom: 1px solid var(--gray-100);
        vertical-align: middle;
    }
    
    .table-hover tbody tr:hover {
        background: var(--gray-50);
    }
    
    /* Status Pill */
    .status-pill {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 30px;
        font-size: 11px;
        font-weight: 600;
    }
    
    .status-active {
        background: #ecfdf5;
        color: #10b981;
    }
    
    .status-expired {
        background: #fee2e2;
        color: #dc2626;
    }
    
    .status-pending {
        background: #fef3c7;
        color: #d97706;
    }
    
    /* Action Icons */
    .nav-icon-hover {
        transition: all 0.2s;
        display: inline-block;
    }
    
    .nav-icon-hover:hover {
        transform: scale(1.1);
    }

    /* Add Member Button */
    .add-member-btn {
        background: var(--danger);
        color: white;
        border: none;
        border-radius: 12px;
        padding: 8px 20px;
        font-size: 12px;
        font-weight: 600;
        transition: all 0.2s;
    }

    .add-member-btn:hover {
        background: #b91c1c;
        transform: translateY(-1px);
        color: white;
    }
    
    /* Progress Images Grid - FIXED: 2 COLUMNS AT A TIME */
    .progress-grid-2cols {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 24px;
    }
    
    .progress-card {
        transition: all 0.3s ease;
        overflow: hidden;
        border-radius: 16px;
        background: white;
        border: 1px solid var(--gray-200);
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    }
    
    .progress-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 20px -12px rgba(0,0,0,0.15);
    }
    
    .progress-img-container {
        position: relative;
        overflow: hidden;
        background: var(--gray-100);
        border-radius: 16px 16px 0 0;
    }
    
    .progress-img {
        width: 100%;
        height: 220px;
        object-fit: cover;
        cursor: pointer;
        transition: transform 0.3s ease;
        display: block;
    }
    
    .progress-img:hover {
        transform: scale(1.02);
    }
    
    .delete-img-btn {
        position: absolute;
        top: 12px;
        right: 12px;
        width: 34px;
        height: 34px;
        border-radius: 50%;
        background: rgba(220, 38, 38, 0.9);
        border: none;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        cursor: pointer;
        z-index: 10;
        box-shadow: 0 2px 6px rgba(0,0,0,0.2);
    }
    
    .delete-img-btn:hover {
        background: #b91c1c;
        transform: scale(1.1);
    }
    
    .progress-card .card-body {
        padding: 16px;
    }
    
    /* Responsive: on mobile, switch to 1 column */
    @media (max-width: 768px) {
        .progress-grid-2cols {
            grid-template-columns: 1fr;
            gap: 20px;
        }
        
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
        
        .card-body {
            padding: 18px;
        }
        
        .btn-danger, .btn-secondary {
            width: 100%;
            margin-bottom: 10px;
        }
        
        .custom-tabs .nav-link {
            padding: 10px 16px;
            font-size: 12px;
        }
        
        .attendance-value {
            font-size: 24px;
        }
        
        .attendance-icon {
            font-size: 34px;
        }
    }
    
    @media (max-width: 480px) {
        .progress-img {
            height: 180px;
        }
    }
</style>

<div class="me-membersmanagement-msg"></div>
<input type="hidden" id="__siteurl" data-mesiteurl="<?=site_url();?>" />

<div class="row mb-2">
    <div class="col-12">
        <h4 class="fw-semibold my-3">Members Management</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a class="text-muted text-decoration-none" href="<?=site_url();?>mydashboard">
                        <i class="ti ti-home fs-5"></i>
                    </a>
                </li>
                <li class="breadcrumb-item">Membership</li>
                <li class="breadcrumb-item active">Members Management</li>
            </ol>
        </nav>
    </div>
</div>

<!-- Dashboard Cards -->
<div class="row g-3 mb-4">
    <div class="col">
        <div class="attendance-card">
            <div class="card-body">
                <div>
                    <div class="attendance-label">Total Members</div>
                    <div class="attendance-value"><?=number_format($total_members);?></div>
                    <div class="attendance-sub">All registered</div>
                </div>
                <i class="ti ti-users attendance-icon"></i>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="attendance-card">
            <div class="card-body">
                <div>
                    <div class="attendance-label">Active</div>
                    <div class="attendance-value text-success"><?=number_format($active_members);?></div>
                    <div class="attendance-sub">Active memberships</div>
                </div>
                <i class="ti ti-circle-check attendance-icon"></i>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="attendance-card">
            <div class="card-body">
                <div>
                    <div class="attendance-label">Expired</div>
                    <div class="attendance-value text-danger"><?=number_format($expired_members);?></div>
                    <div class="attendance-sub">Expired memberships</div>
                </div>
                <i class="ti ti-alert-circle attendance-icon"></i>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="attendance-card">
            <div class="card-body">
                <div>
                    <div class="attendance-label">Pending</div>
                    <div class="attendance-value text-warning"><?=number_format($pending_members);?></div>
                    <div class="attendance-sub">Pending approvals</div>
                </div>
                <i class="ti ti-clock attendance-icon"></i>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="attendance-card">
            <div class="card-body">
                <div>
                    <div class="attendance-label">Expiring Soon</div>
                    <div class="attendance-value text-danger"><?=number_format($expiring_soon);?></div>
                    <div class="attendance-sub">Within 7 days</div>
                </div>
                <i class="ti ti-calendar-exclamation attendance-icon"></i>
            </div>
        </div>
    </div>
</div>

<!-- Custom Tabs -->
<ul class="nav custom-tabs" id="memberTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link <?= $active_tab == 'list' ? 'active' : ''; ?>" data-tab="list">
            <i class="ti ti-list me-2"></i> Member List
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link <?= $active_tab == 'form' ? 'active' : ''; ?>" data-tab="form">
            <i class="ti ti-user-plus me-2"></i> <?= empty($member_id) ? 'Register New Member' : 'Edit Member' ?>
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link <?= $active_tab == 'progress' ? 'active' : ''; ?>" data-tab="progress">
            <i class="ti ti-camera me-2"></i> Progress Photos
        </button>
    </li>
</ul>

<!-- Tab Content: Member List -->
<div class="tab-content <?= $active_tab == 'list' ? 'active' : ''; ?>" id="listTab">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h6 class="fw-semibold mb-0"><i class="ti ti-users me-2"></i>Member List</h6>
                <small class="text-muted">View and manage all registered members</small>
            </div>
            <button class="add-member-btn" onclick="switchToFormTab()">
                <i class="ti ti-plus me-1"></i> Add New Member
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="datatablesSimple" class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Action</th>
                            <th>Member No.</th>
                            <th>Last Name</th>
                            <th>First Name</th>
                            <th>Contact No.</th>
                            <th>Email</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody class="align-middle">
                        <?php if(!empty($membersdata)):
                            foreach ($membersdata as $data):
                                $mid = $data['member_id'];
                                $member_status = $data['membership_status'] ?? 'Pending';
                                $status_class = '';
                                if($member_status == 'Active') $status_class = 'status-active';
                                elseif($member_status == 'Expired') $status_class = 'status-expired';
                                else $status_class = 'status-pending';
                        ?>
                        <tr>
                            <td class="text-center align-middle">
                                <div class="d-flex justify-content-center gap-2">
                                    <a class="text-info nav-icon-hover fs-6" href="<?=site_url();?>membersmanagement?meaction=MAIN&member_id=<?=$mid?>" title="Edit Member">
                                        <i class="ti ti-pencil" aria-hidden="true"></i>
                                    </a>
                                    <a class="text-success nav-icon-hover fs-6" href="<?=site_url();?>memberprofile?meaction=MAIN&member_id=<?=$mid?>" title="View Profile">
                                        <i class="ti ti-eye" aria-hidden="true"></i>
                                    </a>
                                </div>
                            </td>
                            <td class="text-center"><?=$data['member_no'];?></td>
                            <td class="text-center"><?=$data['last_name'];?></td>
                            <td class="text-center"><?=$data['first_name'];?></td>
                            <td class="text-center"><?=$data['mobile_number'];?></td>
                            <td class="text-center"><?=$data['email'];?></td>
                            <td class="text-center">
                                <span class="status-pill <?=$status_class;?>"><?=$member_status;?></span>
                            </td>
                        </tr>
                        <?php endforeach; endif;?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Tab Content: Member Form -->
<div class="tab-content <?= $active_tab == 'form' ? 'active' : ''; ?>" id="formTab">
    <form class="member-reg-form" id="memberRegForm">
        <input type="hidden" name="member_id" value="<?=$member_id;?>"/>
        <input type="hidden" name="meaction" value="MEMBER-SAVE"/>

        <div class="card mb-3">
            <div class="card-body">
                <h6><i class="ti ti-user me-2"></i> Personal Information</h6>
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label class="form-label">RFID Card UID</label>
                        <input type="text" name="rfid_uid" id="rfid_uid" class="form-control" value="<?=$rfid_uid;?>" placeholder="Tap RFID card">
                        <small class="text-muted">Optional - Can be assigned later</small>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label required">Member No.</label>
                        <input type="text" name="member_no" class="form-control" value="<?=$member_no;?>" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label required">Last Name</label>
                        <input type="text" name="last_name" class="form-control" value="<?=$last_name;?>" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label required">First Name</label>
                        <input type="text" name="first_name" class="form-control" value="<?=$first_name;?>" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Middle Name</label>
                        <input type="text" name="middle_name" class="form-control" value="<?=$middle_name;?>">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label">Gender</label>
                        <select name="gender" class="form-control">
                            <option value="">Select</option>
                            <option value="Male" <?= $gender == 'Male' ? 'selected' : '' ?>>Male</option>
                            <option value="Female" <?= $gender == 'Female' ? 'selected' : '' ?>>Female</option>
                            <option value="Other" <?= $gender == 'Other' ? 'selected' : '' ?>>Other</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label">Date of Birth</label>
                        <input type="date" name="date_of_birth" id="date_of_birth" class="form-control" value="<?=$date_of_birth;?>">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label">Age</label>
                        <input type="number" name="age" id="age" class="form-control" value="<?=$age;?>" readonly>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-body">
                <h6><i class="ti ti-phone me-2"></i> Contact Information</h6>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label required">Email Address</label>
                        <input type="email" name="email" class="form-control" value="<?=$email;?>" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label required">Mobile Number</label>
                        <input type="text" name="mobile_number" class="form-control" value="<?=$mobile_number;?>" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Emergency Contact Name</label>
                        <input type="text" name="emergency_contact_name" class="form-control" value="<?=$emergency_contact_name;?>">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Emergency Contact No.</label>
                        <input type="text" name="emergency_contact_number" class="form-control" value="<?=$emergency_contact_number;?>">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Relationship</label>
                        <input type="text" name="emergency_contact_relationship" class="form-control" value="<?=$emergency_contact_relationship;?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Address</label>
                        <textarea name="address" class="form-control" rows="2"><?=$address;?></textarea>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">City</label>
                        <input type="text" name="city" class="form-control" value="<?=$city;?>">
                    </div>
                </div> 
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-body">   
                <h6><i class="ti ti-heart me-2"></i> Health & Fitness Information</h6>
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Health Conditions</label>
                        <textarea name="health_conditions" class="form-control" rows="2" placeholder="e.g., Diabetes, High Blood Pressure, Asthma"><?=$health_conditions;?></textarea>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Allergies</label>
                        <textarea name="allergies" class="form-control" rows="2" placeholder="e.g., Dust, Pollen, Food allergies"><?=$allergies;?></textarea>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Fitness Goals</label>
                        <textarea name="fitness_goals" class="form-control" rows="2" placeholder="e.g., Lose weight, Build muscle, Improve endurance"><?=$fitness_goals;?></textarea>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Experience Level</label>
                        <select name="experience_level" class="form-control">
                            <option value="Beginner" <?= $experience_level == 'Beginner' ? 'selected' : '' ?>>Beginner</option>
                            <option value="Intermediate" <?= $experience_level == 'Intermediate' ? 'selected' : '' ?>>Intermediate</option>
                            <option value="Advanced" <?= $experience_level == 'Advanced' ? 'selected' : '' ?>>Advanced</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-3 <?= (!empty($member_id) && $membership_status == 'Active') ? 'border-success shadow-sm' : ''; ?>">
            <div class="card-body">
                <h6 class="<?= (!empty($member_id) && $membership_status == 'Active') ? 'text-success' : ''; ?>">
                    <i class="ti ti-id me-2"></i> Membership Details
                </h6>

                <?php if(empty($member_id) && $membership_status !== 'Active'): ?>
                    <div class="alert alert-warning border-warning">
                        <div class="d-flex align-items-center">
                            <i class="ti ti-alert-circle fs-5 me-2"></i>
                            <div>
                                <strong>No Active Membership Found</strong><br>
                                Please transact the member's membership first in the 
                                <b>POS Membership Module</b> before accessing membership details.
                            </div>
                        </div>
                    </div>
                <?php elseif(!empty($member_id) && $membership_status == ''): ?>
                    <div class="alert alert-warning border-warning">
                        <div class="d-flex align-items-center">
                            <i class="ti ti-alert-circle fs-5 me-2"></i>
                            <div>
                                <strong>No Active Membership Found</strong><br>
                                Please transact the member's membership first in the 
                                <b>POS Membership Module</b> before accessing membership details.
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="alert alert-success border-success">
                        <div class="d-flex align-items-center">
                            <i class="ti ti-circle-check fs-5 me-2"></i>
                            <div>
                                <strong>Membership Active</strong><br>
                                This member currently has an active membership subscription.
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Membership Plan</label>
                            <select class="form-control" id="membership_plan" disabled>
                                <option value="">--select--</option>
                                <option value="1 Month" <?= $membership_plan == '1 Month' ? 'selected' : '' ?>>1 Month</option>
                                <option value="3 Months" <?= $membership_plan == '3 Months' ? 'selected' : '' ?>>3 Months</option>
                                <option value="6 Months" <?= $membership_plan == '6 Months' ? 'selected' : '' ?>>6 Months</option>
                                <option value="12 Months" <?= $membership_plan == '12 Months' ? 'selected' : '' ?>>12 Months</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Start Date</label>
                            <input type="date" name="membership_start_date" id="start_date" class="form-control" value="<?=$membership_start_date;?>" disabled>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">End Date</label>
                            <input type="date" name="membership_end_date" id="end_date" class="form-control" value="<?=$membership_end_date;?>" disabled>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Status</label>
                            <select name="membership_status" class="form-control" disabled>
                                <option value="Pending" <?= $membership_status == 'Pending' ? 'selected' : '' ?>>Pending</option>
                                <option value="Active" <?= $membership_status == 'Active' ? 'selected' : '' ?>>Active</option>
                                <option value="Expired" <?= $membership_status == 'Expired' ? 'selected' : '' ?>>Expired</option>
                                <option value="Suspended" <?= $membership_status == 'Suspended' ? 'selected' : '' ?>>Suspended</option>
                            </select>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-body">
                <h6><i class="ti ti-file-text me-2"></i> Agreements</h6>
                <div class="row">
                    <div class="col-md-12 mb-2">
                        <div class="form-check">
                            <input type="checkbox" name="waiver_signed" class="form-check-input" id="waiver" value="1" <?= $waiver_signed ? 'checked' : '' ?>>
                            <label class="form-check-label" for="waiver">
                                I acknowledge the risks associated with physical exercise and release the gym from liability
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12 mb-2">
                        <div class="form-check">
                            <input type="checkbox" name="terms_accepted" class="form-check-input" id="terms" value="1" <?= $terms_accepted ? 'checked' : '' ?> required>
                            <label class="form-check-label required" for="terms">
                                I agree to the terms and conditions of the gym
                            </label>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col text-end">
                        <button type="submit" class="btn btn-danger">
                            <i class="ti ti-device-floppy me-1"></i>
                            <?= empty($member_id) ? 'Register Member' : 'Update Member' ?>
                        </button>
                        <a href="<?=site_url();?>membersmanagement?meaction=MAIN" class="btn btn-secondary">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Tab Content: Progress Images - FIXED 2 COLUMNS GRID LAYOUT -->
<div class="tab-content <?= $active_tab == 'progress' ? 'active' : ''; ?>" id="progressTab">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h6 class="fw-semibold mb-0"><i class="ti ti-camera me-2"></i>Quarterly Progress Photos</h6>
                <small class="text-muted">Upload and track member's fitness progress by quarter</small>
            </div>
            <?php if(!empty($member_id)): ?>
            <button class="btn btn-danger btn-sm" onclick="showUploadModal()">
                <i class="ti ti-upload me-1"></i> Upload Progress Photo
            </button>
            <?php endif; ?>
        </div>
        <div class="card-body">
            <?php if(empty($member_id)): ?>
                <div class="text-center py-5 text-muted">
                    <i class="ti ti-camera-off fs-1 d-block mb-2"></i>
                    <p>Select a member first to view their progress photos</p>
                </div>
            <?php else: ?>
                <div id="progressImagesContainer">
                    <div class="text-center py-5">
                        <div class="spinner-border text-primary" role="status"></div>
                        <p class="mt-2">Loading progress photos...</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Upload Progress Image Modal -->
<div class="modal fade" id="uploadProgressModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="ti ti-upload me-2"></i> Upload Progress Photo
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="uploadProgressForm" enctype="multipart/form-data">
                    <input type="hidden" name="member_id" value="<?= $member_id; ?>">
                    
                    <div class="mb-3">
                        <label class="form-label">Quarter</label>
                        <select name="quarter" id="quarter" class="form-select" required>
                            <option value="">Select Quarter</option>
                            <option value="Q1">Q1 (January - March)</option>
                            <option value="Q2">Q2 (April - June)</option>
                            <option value="Q3">Q3 (July - September)</option>
                            <option value="Q4">Q4 (October - December)</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Year</label>
                        <select name="year" id="year" class="form-select" required>
                            <option value="">Select Year</option>
                            <?php 
                            $currentYear = date('Y');
                            for($y = $currentYear - 2; $y <= $currentYear + 1; $y++):
                            ?>
                            <option value="<?= $y; ?>" <?= $y == $currentYear ? 'selected' : ''; ?>><?= $y; ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Progress Photo</label>
                        <input type="file" name="progress_image" id="progress_image" class="form-control" accept="image/*" required>
                        <small class="text-muted">Max 5MB. Allowed: JPG, PNG, GIF, WEBP</small>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Notes (Optional)</label>
                        <textarea name="notes" id="notes" class="form-control" rows="2" placeholder="e.g., Weight: 70kg, Body fat: 15%"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" onclick="uploadProgressImage()">Upload</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteProgressModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <i class="ti ti-alert-triangle fs-1 text-danger mb-3 d-block"></i>
                <p>Are you sure you want to delete this progress photo?</p>
                <p class="text-muted small mb-0">This action cannot be undone.</p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
            </div>
        </div>
    </div>
</div>

<!-- View Image Modal -->
<div class="modal fade" id="viewImageModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title" id="viewImageTitle">Progress Photo</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img id="viewImageSrc" src="" class="img-fluid rounded" style="max-height: 70vh;">
                <p id="viewImageNotes" class="mt-3 text-muted"></p>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="<?=base_url('assets/js/members-management/member-management.js?v=1');?>"></script>

<script>
$(document).ready(function () {
    $('#datatablesSimple').DataTable({
        pageLength: 5,
        lengthChange: false,
        order: [[6, 'desc']],
        scrollX: false,
        language: {
            search: "Search Member:"
        }
    });
});

// Tab switching
document.querySelectorAll('.custom-tabs .nav-link').forEach(function(tab) {
    tab.addEventListener('click', function() {
        var tabName = this.getAttribute('data-tab');
        
        document.querySelectorAll('.custom-tabs .nav-link').forEach(function(t) {
            t.classList.remove('active');
        });
        this.classList.add('active');
        
        document.querySelectorAll('.tab-content').forEach(function(content) {
            content.classList.remove('active');
        });
        
        if (tabName === 'list') {
            document.getElementById('listTab').classList.add('active');
        } else if (tabName === 'form') {
            document.getElementById('formTab').classList.add('active');
        } else if (tabName === 'progress') {
            document.getElementById('progressTab').classList.add('active');
            if (currentMemberId) {
                loadProgressImages();
            }
        }
        
        var url = new URL(window.location.href);
        url.searchParams.set('tab', tabName);
        window.history.pushState({}, '', url);
    });
});

function switchToFormTab() {
    document.querySelector('.custom-tabs .nav-link[data-tab="form"]').click();
    var url = new URL(window.location.href);
    url.searchParams.set('tab', 'form');
    window.history.pushState({}, '', url);
}

// Auto-calculate age from date of birth
var dobField = document.getElementById('date_of_birth');
if(dobField) {
    dobField.addEventListener('change', function() {
        const dob = new Date(this.value);
        if(dob) {
            const today = new Date();
            let age = today.getFullYear() - dob.getFullYear();
            const m = today.getMonth() - dob.getMonth();
            if(m < 0 || (m === 0 && today.getDate() < dob.getDate())) {
                age--;
            }
            if(age > 0 && age < 120) {
                document.getElementById('age').value = age;
            }
        }
    });
}

// ==============================
// PROGRESS IMAGES FUNCTIONS
// ==============================

let currentMemberId = '<?= $member_id; ?>';
let deleteProgressId = null;

function showUploadModal() {
    if (!currentMemberId) {
        toastr.error('Please select a member first!');
        return;
    }
    $('#uploadProgressForm')[0].reset();
    $('#uploadProgressModal').modal('show');
}

function uploadProgressImage() {
    var formData = new FormData($('#uploadProgressForm')[0]);
    formData.append('meaction', 'UPLOAD-PROGRESS-IMAGE');
    
    $.ajax({
        type: "POST",
        url: '<?=site_url();?>membersmanagement',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function(response) {
            if(response.status == 'success'){
                toastr.success(response.message);
                $('#uploadProgressModal').modal('hide');
                loadProgressImages();
            } else {
                toastr.error(response.message);
            }
        },
        error: function(xhr, status, error) {
            toastr.error("Error: " + error);
        }
    });
}

function loadProgressImages() {
    if (!currentMemberId) return;
    
    $.ajax({
        type: "POST",
        url: '<?=site_url();?>membersmanagement',
        data: {
            member_id: currentMemberId,
            meaction: 'GET-PROGRESS-IMAGES'
        },
        dataType: 'json',
        success: function(response) {
            if(response.status == 'success'){
                renderProgressImages(response.data);
            } else {
                $('#progressImagesContainer').html('<div class="text-center py-5 text-muted"><i class="ti ti-camera-off fs-1 d-block mb-2"></i><p>No progress photos uploaded yet</p><button class="btn btn-danger btn-sm mt-2" onclick="showUploadModal()"><i class="ti ti-upload me-1"></i> Upload First Photo</button></div>');
            }
        },
        error: function(xhr, status, error) {
            $('#progressImagesContainer').html('<div class="text-center py-5 text-muted"><i class="ti ti-error fs-1 d-block mb-2"></i><p>Error loading images</p></div>');
        }
    });
}

function renderProgressImages(images) {
    if (!images || images.length === 0) {
        $('#progressImagesContainer').html('<div class="text-center py-5 text-muted"><i class="ti ti-camera-off fs-1 d-block mb-2"></i><p>No progress photos uploaded yet</p><button class="btn btn-danger btn-sm mt-2" onclick="showUploadModal()"><i class="ti ti-upload me-1"></i> Upload First Photo</button></div>');
        return;
    }
    
    // FIXED: 2 columns layout using CSS Grid with class "progress-grid-2cols"
    let html = '<div class="progress-grid-2cols">';
    
    images.forEach(function(img) {
        let imageUrl = '<?= base_url(); ?>/' + img.image_path;
        
        html += `
            <div class="progress-card">
                <div class="progress-img-container">
                    <img src="${imageUrl}" class="progress-img" onclick="viewImage('${imageUrl}', '${escapeHtml(img.notes)}')" alt="Progress Photo">
                    <button class="delete-img-btn" onclick="confirmDelete(${img.progress_id})" title="Delete Photo">
                        <i class="ti ti-trash" style="font-size: 16px;"></i>
                    </button>
                </div>
                <div class="card-body">
                    <h6 class="mb-2"><span class="badge bg-primary">${img.quarter} ${img.year}</span></h6>
                    <small class="text-muted d-block"><i class="ti ti-calendar me-1"></i>Uploaded: ${img.uploaded_date}</small>
                    ${img.notes ? `<p class="mt-2 mb-0 small text-muted"><i class="ti ti-notes me-1"></i>${escapeHtml(img.notes)}</p>` : ''}
                </div>
            </div>
        `;
    });
    
    html += '</div>';
    $('#progressImagesContainer').html(html);
}

// Helper function to escape HTML special characters
function escapeHtml(text) {
    if (!text) return '';
    return text
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#39;');
}

function confirmDelete(progressId) {
    deleteProgressId = progressId;
    $('#deleteProgressModal').modal('show');
}

function deleteProgressImage() {
    if (!deleteProgressId) return;
    
    $.ajax({
        type: "POST",
        url: '<?=site_url();?>membersmanagement',
        data: {
            progress_id: deleteProgressId,
            meaction: 'DELETE-PROGRESS-IMAGE'
        },
        dataType: 'json',
        success: function(response) {
            if(response.status == 'success'){
                toastr.success(response.message);
                $('#deleteProgressModal').modal('hide');
                loadProgressImages();
            } else {
                toastr.error(response.message);
            }
        },
        error: function(xhr, status, error) {
            toastr.error("Error: " + error);
        }
    });
}

function viewImage(imageUrl, notes) {
    $('#viewImageSrc').attr('src', imageUrl);
    $('#viewImageNotes').text(notes || 'No notes provided');
    $('#viewImageModal').modal('show');
}

// Delete button handler
$('#confirmDeleteBtn').off('click').on('click', function() {
    deleteProgressImage();
});

// Load progress images if member is selected
if (currentMemberId) {
    loadProgressImages();
}
</script>

<?php
echo view('templates/myfooter.php');
?>