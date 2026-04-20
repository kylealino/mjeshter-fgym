<?php
$this->request = \Config\Services::request();
$this->db = \Config\Database::connect();
$member_id = $this->request->getPostGet('member_id');

$member_no = "";
$first_name = "";
$last_name = "";
$middle_name = "";
$address = "";
$contact_number = "";
$email = "";
$username = "";
$password = "";

// New fields from the membership profile update form
$date_of_birth = "";
$place_of_birth = "";
$age = "";
$civil_status = "";
$gender = "";
$tin = "";
$gsis_number = "";

$permanent_street = "";
$permanent_barangay = "";
$permanent_city = "";
$permanent_province = "";
$permanent_zip = "";

$present_street = "";
$present_barangay = "";
$present_city = "";
$present_province = "";
$present_zip = "";

$home_phone = "";
$office_phone = "";

$department_agency = "";
$position = "";
$salary_grade = "";

$beneficiary1_name = "";
$beneficiary1_address = "";
$beneficiary1_contact = "";
$beneficiary1_relationship = "";
$beneficiary2_name = "";
$beneficiary2_address = "";
$beneficiary2_contact = "";
$beneficiary2_relationship = "";

if(!empty($member_id) || !is_null($member_id)) { 
    $query = $this->db->query("
    SELECT
        `member_id`,
        `member_no`,
        `first_name`,
        `last_name`,
        `middle_name`,
        `address`,
        `contact_number`,
        `email`,
        `username`,
        `password`,
        `date_of_birth`,
        `place_of_birth`,
        `age`,
        `civil_status`,
        `gender`,
        `tin`,
        `gsis_number`,
        `permanent_street`,
        `permanent_barangay`,
        `permanent_city`,
        `permanent_province`,
        `permanent_zip`,
        `present_street`,
        `present_barangay`,
        `present_city`,
        `present_province`,
        `present_zip`,
        `home_phone`,
        `office_phone`,
        `department_agency`,
        `position`,
        `salary_grade`,
        `beneficiary1_name`,
        `beneficiary1_address`,
        `beneficiary1_contact`,
        `beneficiary1_relationship`,
        `beneficiary2_name`,
        `beneficiary2_address`,
        `beneficiary2_contact`,
        `beneficiary2_relationship`,
        `created_by`,
        `created_at`
    FROM
        `tbl_members`
    WHERE
        `member_id` = '$member_id'"
    );

    $data = $query->getRowArray();
    if($data) {
        $member_no = $data['member_no'];
        $first_name = $data['first_name'];
        $last_name = $data['last_name'];
        $middle_name = $data['middle_name'];
        $address = $data['address'];
        $contact_number = $data['contact_number'];
        $email = $data['email'];
        $username = $data['username'];
        $password = $data['password'];
        
        $date_of_birth = $data['date_of_birth'];
        $place_of_birth = $data['place_of_birth'];
        $age = $data['age'];
        $civil_status = $data['civil_status'];
        $gender = $data['gender'];
        $tin = $data['tin'];
        $gsis_number = $data['gsis_number'];
        
        $permanent_street = $data['permanent_street'];
        $permanent_barangay = $data['permanent_barangay'];
        $permanent_city = $data['permanent_city'];
        $permanent_province = $data['permanent_province'];
        $permanent_zip = $data['permanent_zip'];
        
        $present_street = $data['present_street'];
        $present_barangay = $data['present_barangay'];
        $present_city = $data['present_city'];
        $present_province = $data['present_province'];
        $present_zip = $data['present_zip'];
        
        $home_phone = $data['home_phone'];
        $office_phone = $data['office_phone'];
        
        $department_agency = $data['department_agency'];
        $position = $data['position'];
        $salary_grade = $data['salary_grade'];
        
        $beneficiary1_name = $data['beneficiary1_name'];
        $beneficiary1_address = $data['beneficiary1_address'];
        $beneficiary1_contact = $data['beneficiary1_contact'];
        $beneficiary1_relationship = $data['beneficiary1_relationship'];
        $beneficiary2_name = $data['beneficiary2_name'];
        $beneficiary2_address = $data['beneficiary2_address'];
        $beneficiary2_contact = $data['beneficiary2_contact'];
        $beneficiary2_relationship = $data['beneficiary2_relationship'];
    }
}

echo view('templates/myheader.php');
?>
<style>
.status-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 4px 10px;
    font-size: 12px;
    font-weight: 600;
    border-radius: 50px;
    letter-spacing: 0.3px;
}

.status-pill::before {
    content: '';
    width: 6px;
    height: 6px;
    border-radius: 50%;
}

.status-active {
    background: rgba(25, 135, 84, 0.1);
    color: #198754;
}
.status-active::before {
    background: #198754;
}

.status-inactive {
    background: rgba(13, 110, 253, 0.1);
    color: #0d6efd;
}
.status-inactive::before {
    background: #0d6efd;
}

.form-section {
    border-bottom: 1px solid #e9ecef;
    padding-bottom: 20px;
    margin-bottom: 20px;
}
.form-section:last-child {
    border-bottom: none;
    margin-bottom: 0;
    padding-bottom: 0;
}
.form-section h6 {
    font-weight: 600;
    margin-bottom: 18px;
    color: #2c3e50;
    font-size: 14px;
    letter-spacing: 0.5px;
}
.section-title-icon {
    width: 28px;
    display: inline-block;
}
</style>

<div class="container-fluid">
    <div class="row me-mymembers-outp-msg mx-0">
    </div>
    <input type="hidden" id="__siteurl" data-mesiteurl="<?=site_url();?>" />
    
    <div class="row mb-2 mt-0">
        <h4 class="fw-semibold mb-8">List of Members</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a class="text-muted text-decoration-none" href="<?=site_url();?>"><i class="ti ti-home fs-5"></i></a>
                </li>
                <li class="breadcrumb-item" aria-current="page">Members Management</li>
                <li class="breadcrumb-item" aria-current="page"><span class="form-label fw-bold">List of Members</span></li>
            </ol>
        </nav>
    </div>
    
    <div class="card">
        <div class="card-header p-1">
            <div class="row">
                <div class="col-sm-6 d-flex align-items-center text-start">
                    <h6 class="mb-0 lh-base px-3 fw-semibold d-flex align-items-center">
                        <i class="ti ti-pencil fs-5 me-1"></i>
                        <span class="pt-1"><?= empty($member_id) ? 'Add new member' : 'Edit Member' ?></span>
                    </h6>
                </div>
            </div>
        </div>						
        <div class="card-body p-0 px-4 py-3 my-2">
            <form action="<?=site_url();?>mymembers?meaction=MEMBERS-SAVE" method="post" class="mymembers-validation" id="memberForm">
                <input type="hidden" class="form-control form-control-sm" id="member_id" name="member_id" value="<?=$member_id;?>"/>
                
                <!-- I. Member Information -->
                <div class="form-section">
                    <h6><span class="section-title-icon"><i class="ti ti-user me-2"></i></span> I. Member Information</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row mb-2">
                                <div class="col-sm-4">
                                    <label class="form-label fw-semibold small">Member No.:</label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="text" id="member_no" name="member_no" value="<?=$member_no;?>" class="form-control form-control-sm"/>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-4">
                                    <label class="form-label fw-semibold small">Last Name:</label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="text" id="last_name" name="last_name" value="<?=$last_name;?>" class="form-control form-control-sm"/>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-4">
                                    <label class="form-label fw-semibold small">First Name:</label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="text" id="first_name" name="first_name" value="<?=$first_name;?>" class="form-control form-control-sm"/>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-4">
                                    <label class="form-label fw-semibold small">Middle Name:</label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="text" id="middle_name" name="middle_name" value="<?=$middle_name;?>" class="form-control form-control-sm"/>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-4">
                                    <label class="form-label fw-semibold small">Date of Birth:</label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="date" id="date_of_birth" name="date_of_birth" value="<?=$date_of_birth;?>" class="form-control form-control-sm"/>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-4">
                                    <label class="form-label fw-semibold small">Place of Birth:</label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="text" id="place_of_birth" name="place_of_birth" value="<?=$place_of_birth;?>" class="form-control form-control-sm"/>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row mb-2">
                                <div class="col-sm-4">
                                    <label class="form-label fw-semibold small">Age:</label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="number" id="age" name="age" value="<?=$age;?>" class="form-control form-control-sm"/>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-4">
                                    <label class="form-label fw-semibold small">Civil Status:</label>
                                </div>
                                <div class="col-sm-8">
                                    <select id="civil_status" name="civil_status" class="form-control form-control-sm">
                                        <option value="">Select</option>
                                        <option value="Single" <?= $civil_status == 'Single' ? 'selected' : '' ?>>Single</option>
                                        <option value="Married" <?= $civil_status == 'Married' ? 'selected' : '' ?>>Married</option>
                                        <option value="Widowed" <?= $civil_status == 'Widowed' ? 'selected' : '' ?>>Widowed</option>
                                        <option value="Divorced" <?= $civil_status == 'Divorced' ? 'selected' : '' ?>>Divorced</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-4">
                                    <label class="form-label fw-semibold small">Gender:</label>
                                </div>
                                <div class="col-sm-8">
                                    <select id="gender" name="gender" class="form-control form-control-sm">
                                        <option value="">Select</option>
                                        <option value="Male" <?= $gender == 'Male' ? 'selected' : '' ?>>Male</option>
                                        <option value="Female" <?= $gender == 'Female' ? 'selected' : '' ?>>Female</option>
                                        <option value="Other" <?= $gender == 'Other' ? 'selected' : '' ?>>Other</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-4">
                                    <label class="form-label fw-semibold small">TIN:</label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="text" id="tin" name="tin" value="<?=$tin;?>" class="form-control form-control-sm"/>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-4">
                                    <label class="form-label fw-semibold small">GSIS Number:</label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="text" id="gsis_number" name="gsis_number" value="<?=$gsis_number;?>" class="form-control form-control-sm"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- II. Contact Information -->
                <div class="form-section">
                    <h6><span class="section-title-icon"><i class="ti ti-phone me-2"></i></span> II. Contact Information</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row mb-2">
                                <div class="col-sm-4">
                                    <label class="form-label fw-semibold small">Permanent Address:</label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="text" id="permanent_street" name="permanent_street" placeholder="Street" value="<?=$permanent_street;?>" class="form-control form-control-sm mb-1"/>
                                    <input type="text" id="permanent_barangay" name="permanent_barangay" placeholder="Barangay" value="<?=$permanent_barangay;?>" class="form-control form-control-sm mb-1"/>
                                    <input type="text" id="permanent_city" name="permanent_city" placeholder="City/Municipality" value="<?=$permanent_city;?>" class="form-control form-control-sm mb-1"/>
                                    <div class="row g-1">
                                        <div class="col-7">
                                            <input type="text" id="permanent_province" name="permanent_province" placeholder="Province" value="<?=$permanent_province;?>" class="form-control form-control-sm"/>
                                        </div>
                                        <div class="col-5">
                                            <input type="text" id="permanent_zip" name="permanent_zip" placeholder="Zip Code" value="<?=$permanent_zip;?>" class="form-control form-control-sm"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row mb-2">
                                <div class="col-sm-4">
                                    <label class="form-label fw-semibold small">Present Address:</label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="text" id="present_street" name="present_street" placeholder="Street" value="<?=$present_street;?>" class="form-control form-control-sm mb-1"/>
                                    <input type="text" id="present_barangay" name="present_barangay" placeholder="Barangay" value="<?=$present_barangay;?>" class="form-control form-control-sm mb-1"/>
                                    <input type="text" id="present_city" name="present_city" placeholder="City/Municipality" value="<?=$present_city;?>" class="form-control form-control-sm mb-1"/>
                                    <div class="row g-1">
                                        <div class="col-7">
                                            <input type="text" id="present_province" name="present_province" placeholder="Province" value="<?=$present_province;?>" class="form-control form-control-sm"/>
                                        </div>
                                        <div class="col-5">
                                            <input type="text" id="present_zip" name="present_zip" placeholder="Zip Code" value="<?=$present_zip;?>" class="form-control form-control-sm"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-6">
                            <div class="row mb-2">
                                <div class="col-sm-4">
                                    <label class="form-label fw-semibold small">Mobile Number:</label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="text" id="contact_number" name="contact_number" value="<?=$contact_number;?>" class="form-control form-control-sm"/>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-4">
                                    <label class="form-label fw-semibold small">Email Address:</label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="email" id="email" name="email" value="<?=$email;?>" class="form-control form-control-sm"/>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row mb-2">
                                <div class="col-sm-4">
                                    <label class="form-label fw-semibold small">Home Phone No.:</label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="text" id="home_phone" name="home_phone" value="<?=$home_phone;?>" class="form-control form-control-sm"/>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-4">
                                    <label class="form-label fw-semibold small">Office Phone No.:</label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="text" id="office_phone" name="office_phone" value="<?=$office_phone;?>" class="form-control form-control-sm"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- III. Employment Information -->
                <div class="form-section">
                    <h6><span class="section-title-icon"><i class="ti ti-briefcase me-2"></i></span> III. Employment Information</h6>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="row mb-2">
                                <div class="col-sm-5">
                                    <label class="form-label fw-semibold small">Department/Agency:</label>
                                </div>
                                <div class="col-sm-7">
                                    <select id="department_agency" name="department_agency" class="form-control form-control-sm">
                                        <option value="">Select</option>
                                        <option value="DOST-FNRI" <?= $department_agency == 'DOST-FNRI' ? 'selected' : '' ?>>DOST-FNRI</option>
                                        <option value="DOST-ITDI" <?= $department_agency == 'DOST-ITDI' ? 'selected' : '' ?>>DOST-ITDI</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="row mb-2">
                                <div class="col-sm-5">
                                    <label class="form-label fw-semibold small">Position:</label>
                                </div>
                                <div class="col-sm-7">
                                    <input type="text" id="position" name="position" value="<?=$position;?>" class="form-control form-control-sm"/>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="row mb-2">
                                <div class="col-sm-5">
                                    <label class="form-label fw-semibold small">Salary Grade:</label>
                                </div>
                                <div class="col-sm-7">
                                    <input type="text" id="salary_grade" name="salary_grade" value="<?=$salary_grade;?>" class="form-control form-control-sm"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- IV. Contact Person(s)/Beneficiaries -->
                <div class="form-section">
                    <h6><span class="section-title-icon"><i class="ti ti-users me-2"></i></span> IV. Contact Person(s)/Beneficiaries</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="bg-light p-2 rounded mb-2">
                                <label class="form-label fw-semibold small mb-2">Beneficiary 1</label>
                                <div class="row mb-2">
                                    <div class="col-sm-4">
                                        <label class="form-label small">Full Name:</label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="text" id="beneficiary1_name" name="beneficiary1_name" value="<?=$beneficiary1_name;?>" class="form-control form-control-sm"/>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-sm-4">
                                        <label class="form-label small">Address:</label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="text" id="beneficiary1_address" name="beneficiary1_address" value="<?=$beneficiary1_address;?>" class="form-control form-control-sm"/>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-sm-4">
                                        <label class="form-label small">Contact No.:</label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="text" id="beneficiary1_contact" name="beneficiary1_contact" value="<?=$beneficiary1_contact;?>" class="form-control form-control-sm"/>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-sm-4">
                                        <label class="form-label small">Relationship:</label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="text" id="beneficiary1_relationship" name="beneficiary1_relationship" value="<?=$beneficiary1_relationship;?>" class="form-control form-control-sm"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="bg-light p-2 rounded mb-2">
                                <label class="form-label fw-semibold small mb-2">Beneficiary 2</label>
                                <div class="row mb-2">
                                    <div class="col-sm-4">
                                        <label class="form-label small">Full Name:</label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="text" id="beneficiary2_name" name="beneficiary2_name" value="<?=$beneficiary2_name;?>" class="form-control form-control-sm"/>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-sm-4">
                                        <label class="form-label small">Address:</label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="text" id="beneficiary2_address" name="beneficiary2_address" value="<?=$beneficiary2_address;?>" class="form-control form-control-sm"/>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-sm-4">
                                        <label class="form-label small">Contact No.:</label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="text" id="beneficiary2_contact" name="beneficiary2_contact" value="<?=$beneficiary2_contact;?>" class="form-control form-control-sm"/>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-sm-4">
                                        <label class="form-label small">Relationship:</label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="text" id="beneficiary2_relationship" name="beneficiary2_relationship" value="<?=$beneficiary2_relationship;?>" class="form-control form-control-sm"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- V. Login Information -->
                <div class="form-section">
                    <h6><span class="section-title-icon"><i class="ti ti-lock me-2"></i></span> V. Login Information</h6>
                    <div class="row">
                        <div class="col-md-5">
                            <div class="row mb-2">
                                <div class="col-sm-4">
                                    <label class="form-label fw-semibold small">Username:</label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="text" id="username" name="username" value="<?=$username;?>" class="form-control form-control-sm"/>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="row mb-2">
                                <div class="col-sm-4">
                                    <label class="form-label fw-semibold small">Password:</label>
                                </div>
                                <div class="col-sm-8">
                                    <div class="input-group input-group-sm">
                                        <input type="password" id="password" name="password" value="<?=$password;?>" class="form-control"/>
                                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                            <i class="ti ti-eye" id="toggleIcon"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row mt-3 mb-2">  
                    <div class="col-sm-12 text-end">
                        <button type="submit" class="btn bg-<?= empty($member_id) ? 'success' : 'info' ?>-subtle text-<?= empty($member_id) ? 'success' : 'info' ?> btn-sm">
                            <i class="ti ti-brand-doctrine mt-1 fs-4 me-1"></i>
                            <?= empty($member_id) ? 'Save' : 'Update' ?>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header p-1">
            <div class="row">
                <div class="col-sm-6 d-flex align-items-center text-start">
                    <h6 class="mb-0 lh-base px-3 fw-semibold d-flex align-items-center">
                        <i class="ti ti-list fs-5 me-1"></i>
                        <span class="pt-1">Member List</span>
                    </h6>
                </div>
            </div>
        </div>						
        <div class="card-body p-0 px-4 py-2 my-2">
            <table id="datatablesSimple" class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>Action</th>
                        <th>Member No.</th>
                        <th>Last Name</th>
                        <th>First Name</th>
                        <th>Contact No.</th>
                        <th>Email</th>
                        <th>Loan Count</th>
                        <th>Loan Amount</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody class="align-middle">
                    <?php if(!empty($membersdata)):
                        foreach ($membersdata as $data):
                            $member_id = $data['member_id'];
                            $member_no = $data['member_no'];
                            $first_name = $data['first_name'];
                            $last_name = $data['last_name'];
                            $middle_name = $data['middle_name'];
                            $address = $data['address'];
                            $contact_number = $data['contact_number'];
                            $email = $data['email'];
                            $loan_count = $data['loan_count'];
                            $loan_amount = $data['loan_amount'];
                    ?>
                    <tr>
                        <td class="text-center align-middle">
                            <div class="d-flex text-warning justify-content-center gap-2">
                                <a class="text-info nav-icon-hover fs-6 me-2" href="mymembers?meaction=MAIN&member_id=<?= $member_id ?>" title="Edit Member">
                                    <i class="ti ti-pencil" aria-hidden="true"></i>
                                </a>
                                <button class="btn btn-sm fs-6 text-warning p-0 border-0 bg-transparent" 
                                        onclick="__mysys_members_ent.__showPdfInModal('<?= base_url('mymembers?meaction=MEMBERS-PRINT&member_id='.$member_id) ?>')" 
                                        title="Print Members Profile" >
                                <i class="ti ti-printer"></i>
                                </button>
                            </div>
                        </td>
                        <td class="text-center"><?=$member_no;?></td>
                        <td class="text-center"><?=$last_name;?></td>
                        <td class="text-center"><?=$first_name;?></td>
                        <td class="text-center"><?=$contact_number;?></td>
                        <td class="text-center"><?=$email;?></td>
                        <td class="text-center"><?=$loan_count;?></td>
                        <td class="text-center"><?=$loan_amount?></td>
                        <td class="text-center"><span class="status-pill status-active">Active</span></td>
                    </tr>
                    <?php endforeach; endif;?>
                </tbody>
             </table>
        </div>
    </div>
</div>
<div class="modal fade" id="pdfModal" tabindex="-1" aria-labelledby="pdfModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="pdfModalLabel">Membership Profile Preview</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <iframe id="pdfFrame" src="" style="width: 100%; height: 80vh;" frameborder="0"></iframe>
        </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="<?=base_url('assets/js/members-management/mymembers.js?v=4');?>"></script>
<script src="<?=base_url('assets/js/mysysapps.js');?>"></script>

<script>
__mysys_members_ent.__members_saving();
$(document).ready(function () {
    $('#datatablesSimple').DataTable({
        pageLength: 5,
        lengthChange: false,
        language: {
            search: "Search:"
        }
    });
});

document.getElementById('togglePassword').addEventListener('click', function () {
    const input = document.getElementById('password');
    const icon = document.getElementById('toggleIcon');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('ti-eye');
        icon.classList.add('ti-eye-off');
    } else {
        input.type = 'password';
        icon.classList.remove('ti-eye-off');
        icon.classList.add('ti-eye');
    }
});

// Auto-calculate age from date of birth
document.getElementById('date_of_birth').addEventListener('change', function() {
    const dob = new Date(this.value);
    if (dob) {
        const today = new Date();
        let age = today.getFullYear() - dob.getFullYear();
        const m = today.getMonth() - dob.getMonth();
        if (m < 0 || (m === 0 && today.getDate() < dob.getDate())) {
            age--;
        }
        if (age > 0 && age < 120) {
            document.getElementById('age').value = age;
        }
    }
});
</script>

<?php
echo view('templates/myfooter.php');
?>