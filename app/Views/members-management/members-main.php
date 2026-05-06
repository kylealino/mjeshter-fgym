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
.form-section {
    background: #fff;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 20px;
    border: 1px solid #e0e0e0;
}
.form-section h6 {
    font-weight: 600;
    margin-bottom: 20px;
    color: #333;
    border-left: 3px solid #dc3545;
    padding-left: 10px;
}
.required:after {
    content: " *";
    color: red;
}
/* Fix for breadcrumb visibility */
.breadcrumb {
    background: transparent;
    padding: 0;
    margin-bottom: 1rem;
}
.breadcrumb-item a {
    text-decoration: none;
}
.breadcrumb-item.active {
    color: #dc3545;
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

<!-- REMOVED THE DUPLICATE container-fluid wrapper since body-wrapper already provides padding -->
<div class="me-membersmanagement-msg"></div>
<input type="hidden" id="__siteurl" data-mesiteurl="<?=site_url();?>" />

<div class="row mb-2 mt-5">
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

    <div class="card mb-3 <?= (!empty($member_id) && $membership_status == 'Active') ? 'border-success shadow-sm' : 'border-warning shadow-sm'; ?>">
        <div class="card-body">

            <h6 class="<?= (!empty($member_id) && $membership_status == 'Active') ? 'text-success' : 'text-warning'; ?>">
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
            <table id="datatablesSimple" class="table table-bordered table-striped table-hover">
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
