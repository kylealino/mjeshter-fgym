<?php
namespace App\Models;
use CodeIgniter\Model;

class MembersManagementModel extends Model
{
    protected $db;

    public function __construct(){
        parent::__construct();
        $this->session = session();
        $this->request = \Config\Services::request();
        $this->db = \Config\Database::connect();
        $this->cuser = $this->session->get('__xsys_myuserzicas__');
    }

    public function saveMember() { 
        $member_id = $this->request->getPost('member_id');
        
        // Personal Information
        $member_no = $this->request->getPost('member_no');
        $rfid_uid = $this->request->getPost('rfid_uid');
        $last_name = $this->request->getPost('last_name');
        $first_name = $this->request->getPost('first_name');
        $middle_name = $this->request->getPost('middle_name');
        $gender = $this->request->getPost('gender');
        $date_of_birth = $this->request->getPost('date_of_birth');
        $age = $this->request->getPost('age');
        
        // Contact Information
        $email = $this->request->getPost('email');
        $mobile_number = $this->request->getPost('mobile_number');
        $emergency_contact_name = $this->request->getPost('emergency_contact_name');
        $emergency_contact_number = $this->request->getPost('emergency_contact_number');
        $emergency_contact_relationship = $this->request->getPost('emergency_contact_relationship');
        $address = $this->request->getPost('address');
        $city = $this->request->getPost('city');
        
        // Health Information
        $health_conditions = $this->request->getPost('health_conditions');
        $allergies = $this->request->getPost('allergies');
        $fitness_goals = $this->request->getPost('fitness_goals');
        $experience_level = $this->request->getPost('experience_level');
        
        // Membership
        $membership_plan = $this->request->getPost('membership_plan');
        $membership_start_date = $this->request->getPost('membership_start_date');
        $membership_end_date = $this->request->getPost('membership_end_date');
        $membership_status = $this->request->getPost('membership_status');
        
        // Legal
        $waiver_signed = $this->request->getPost('waiver_signed') ? 1 : 0;
        $terms_accepted = $this->request->getPost('terms_accepted') ? 1 : 0;

        // Validation
        if(empty($last_name)) {
            return ['status' => 'error', 'message' => 'Last name is required!'];
        }
        if(empty($first_name)) {
            return ['status' => 'error', 'message' => 'First name is required!'];
        }
        if(empty($email)) {
            return ['status' => 'error', 'message' => 'Email is required!'];
        }
        if(empty($terms_accepted)) {
            return ['status' => 'error', 'message' => 'Please accept terms and conditions!'];
        }
        
        // Check if RFID already exists
        if(!empty($rfid_uid)) {
            $checkRFID = $this->db->query("SELECT member_id FROM tbl_members WHERE rfid_uid = ? AND member_id != ?", [$rfid_uid, $member_id ? $member_id : 0]);
            if($checkRFID->getNumRows() > 0) {
                return ['status' => 'error', 'message' => 'RFID card already assigned to another member!'];
            }
        }
        
        if(empty($member_id)) {
            // Insert new member
            $membership_status = 'Pending';
            $query = $this->db->query("
                INSERT INTO tbl_members (
                    member_no, rfid_uid, last_name, first_name, middle_name,
                    gender, date_of_birth, age, email, mobile_number,
                    emergency_contact_name, emergency_contact_number, emergency_contact_relationship,
                    address, city, health_conditions, allergies, fitness_goals,
                    experience_level, membership_plan, membership_start_date, membership_end_date,
                    membership_status, waiver_signed, terms_accepted, created_by
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ", [
                $member_no, $rfid_uid, $last_name, $first_name, $middle_name,
                $gender, $date_of_birth, $age, $email, $mobile_number,
                $emergency_contact_name, $emergency_contact_number, $emergency_contact_relationship,
                $address, $city, $health_conditions, $allergies, $fitness_goals,
                $experience_level, $membership_plan, $membership_start_date, $membership_end_date,
                $membership_status, $waiver_signed, $terms_accepted, $this->cuser
            ]);
            
            if($query) {
                return ['status' => 'success', 'message' => 'Member Registered Successfully!'];
            } else {
                return ['status' => 'error', 'message' => 'Database error occurred!'];
            }
        } else {
            // Update existing member
            $query = $this->db->query("
                UPDATE tbl_members SET
                    member_no = ?, rfid_uid = ?, last_name = ?, first_name = ?, middle_name = ?,
                    gender = ?, date_of_birth = ?, age = ?, email = ?, mobile_number = ?,
                    emergency_contact_name = ?, emergency_contact_number = ?, emergency_contact_relationship = ?,
                    address = ?, city = ?, health_conditions = ?, allergies = ?, fitness_goals = ?,
                    experience_level = ?, membership_plan = ?, membership_start_date = ?, membership_end_date = ?,
                    membership_status = ?, waiver_signed = ?, terms_accepted = ?
                WHERE member_id = ?
            ", [
                $member_no, $rfid_uid, $last_name, $first_name, $middle_name,
                $gender, $date_of_birth, $age, $email, $mobile_number,
                $emergency_contact_name, $emergency_contact_number, $emergency_contact_relationship,
                $address, $city, $health_conditions, $allergies, $fitness_goals,
                $experience_level, $membership_plan, $membership_start_date, $membership_end_date,
                $membership_status, $waiver_signed, $terms_accepted, $member_id
            ]);
            
            if($query) {
                return ['status' => 'success', 'message' => 'Member Updated Successfully!'];
            } else {
                return ['status' => 'error', 'message' => 'Database error occurred!'];
            }
        }
    }
}