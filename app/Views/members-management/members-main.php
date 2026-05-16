<?php
$this->request = \Config\Services::request();
$this->db = \Config\Database::connect();
$member_id = $this->request->getPostGet('member_id');

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
    }
}

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
        --gray-900: #0f172a;
        --danger: #dc2626;
        --success: #10b981;
        --warning: #f59e0b;
    }

    body {
        background: var(--gray-50);
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
    
    /* Action Icons */
    .nav-icon-hover {
        transition: all 0.2s;
        display: inline-block;
    }
    
    .nav-icon-hover:hover {
        transform: scale(1.1);
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
        
        .card-body {
            padding: 18px;
        }
        
        .btn-danger, .btn-secondary {
            width: 100%;
            margin-bottom: 10px;
        }
    }
</style>

<!-- REMOVED THE DUPLICATE container-fluid wrapper since body-wrapper already provides padding -->
<div class="me-membersmanagement-msg"></div>
<input type="hidden" id="__siteurl" data-mesiteurl="<?=site_url();?>" />

<div class="row mb-2">
    <div class="col-12">
        <?php if(empty($member_id)):?>
            <h4 class="fw-semibold my-3">Registration</h4>
        <?php else:?>
            <h4 class="fw-semibold my-3">Member Update</h4>
        <?php endif;?>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a class="text-muted text-decoration-none" href="<?=site_url();?>mydashboard">
                        <i class="ti ti-home fs-5"></i>
                    </a>
                </li>
                <li class="breadcrumb-item">Members Management</li>
                <li class="breadcrumb-item active">Registration</li>
            </ol>
        </nav>
    </div>
</div>
<form action="<?=site_url();?>membersmanagement" method="post" class="member-reg-form" id="memberRegForm">
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

                <!-- HIGHLIGHT ACTIVE MEMBERSHIP -->
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

                        <input type="date"
                            name="membership_start_date"
                            id="start_date"
                            class="form-control"
                            value="<?=$membership_start_date;?>"
                            disabled>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">End Date</label>

                        <input type="date"
                            name="membership_end_date"
                            id="end_date"
                            class="form-control"
                            value="<?=$membership_end_date;?>"
                            disabled>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Status</label>

                        <select name="membership_status"
                            class="form-control"
                            disabled>

                            <option value="Active" <?= $membership_status == 'Active' ? 'selected' : '' ?>>Active</option>
                            <option value="Pending" <?= $membership_status == 'Pending' ? 'selected' : '' ?>>Pending</option>
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
                    <a href="<?=site_url();?>membersmanagement" class="btn btn-secondary">Cancel</a>
                </div>
            </div>
        </div>
    </div>
</form>

<div class="card">
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
                            $member_id = $data['member_id'];
                    ?>
                    <tr>
                        <td class="text-center align-middle">
                            <div class="d-flex justify-content-center gap-2">
                                <a class="text-info nav-icon-hover fs-6" href="<?=site_url();?>membersmanagement?meaction=MAIN&member_id=<?=$member_id?>" title="Edit Member">
                                    <i class="ti ti-pencil" aria-hidden="true"></i>
                                </a>
                                <a class="text-success nav-icon-hover fs-6" href="<?=site_url();?>memberprofile?meaction=MAIN&member_id=<?=$member_id?>" title="View Profile">
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
                            <span class="status-pill status-active">Active</span>
                        </td>
                    </tr>
                    <?php endforeach; endif;?>
                </tbody>
            </table>
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
            search: "Search Transaction:"
        }
    });
});
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

</script>

<?php
echo view('templates/myfooter.php');
?>