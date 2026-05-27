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

    public function uploadProgressImage() {
        $member_id = $this->request->getPost('member_id');
        $quarter = $this->request->getPost('quarter');
        $year = $this->request->getPost('year');
        $notes = $this->request->getPost('notes');
        
        // Validation
        if (empty($member_id)) {
            return ['status' => 'error', 'message' => 'Member ID is required!'];
        }
        if (empty($quarter)) {
            return ['status' => 'error', 'message' => 'Please select a quarter!'];
        }
        if (empty($year)) {
            return ['status' => 'error', 'message' => 'Please select a year!'];
        }
        
        // Check if file was uploaded
        $file = $this->request->getFile('progress_image');
        if (!$file || !$file->isValid()) {
            return ['status' => 'error', 'message' => 'Please select an image to upload!'];
        }
        
        // Validate file type
        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];
        if (!in_array($file->getMimeType(), $allowedTypes)) {
            return ['status' => 'error', 'message' => 'Only JPG, PNG, GIF, and WEBP images are allowed!'];
        }
        
        // Validate file size (max 5MB)
        if ($file->getSize() > 5 * 1024 * 1024) {
            return ['status' => 'error', 'message' => 'Image size must be less than 5MB!'];
        }
        
        // Check if image already exists for this quarter and year
        $check = $this->db->query("
            SELECT progress_id FROM tbl_member_progress_images 
            WHERE member_id = ? AND quarter = ? AND year = ?
        ", [$member_id, $quarter, $year])->getRow();
        
        if ($check) {
            return ['status' => 'error', 'message' => 'Progress image already exists for this quarter! Delete existing image first.'];
        }
        
        // Create upload directory if not exists
        $uploadPath = FCPATH . 'uploads/progress_images/';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }
        
        // Generate unique filename
        $filename = 'member_' . $member_id . '_' . $quarter . '_' . $year . '_' . time() . '.' . $file->getExtension();
        
        // Move file to upload directory
        if ($file->move($uploadPath, $filename)) {
            $imagePath = 'uploads/progress_images/' . $filename;
            
            $query = $this->db->query("
                INSERT INTO `tbl_member_progress_images` (
                    `member_id`,
                    `quarter`,
                    `year`,
                    `image_path`,
                    `notes`,
                    `uploaded_by`
                ) VALUES (?, ?, ?, ?, ?, ?)
            ", [
                $member_id,
                $quarter,
                $year,
                $imagePath,
                $notes,
                $this->cuser
            ]);
            
            if ($query) {
                return ['status' => 'success', 'message' => 'Progress image uploaded successfully!'];
            } else {
                // Delete file if database insert fails
                unlink($uploadPath . $filename);
                return ['status' => 'error', 'message' => 'Failed to save to database!'];
            }
        } else {
            return ['status' => 'error', 'message' => 'Failed to upload image!'];
        }
    }

    public function getProgressImages() {
        $member_id = $this->request->getPost('member_id');
        
        if (empty($member_id)) {
            return ['status' => 'error', 'message' => 'Member ID is required!'];
        }
        
        $query = $this->db->query("
            SELECT 
                progress_id,
                quarter,
                year,
                image_path,
                notes,
                uploaded_by,
                DATE_FORMAT(uploaded_at, '%M %d, %Y') as uploaded_date
            FROM tbl_member_progress_images
            WHERE member_id = ?
            ORDER BY year DESC, 
                FIELD(quarter, 'Q1', 'Q2', 'Q3', 'Q4') DESC
        ", [$member_id]);
        
        $images = $query->getResultArray();
        
        return ['status' => 'success', 'data' => $images];
    }

    public function deleteProgressImage() {
        $progress_id = $this->request->getPost('progress_id');
        
        if (empty($progress_id)) {
            return ['status' => 'error', 'message' => 'Progress ID is required!'];
        }
        
        // Get image path first
        $image = $this->db->query("SELECT image_path FROM tbl_member_progress_images WHERE progress_id = ?", [$progress_id])->getRow();
        
        if ($image) {
            // Delete physical file
            $filePath = FCPATH . $image->image_path;
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
        
        $query = $this->db->query("DELETE FROM tbl_member_progress_images WHERE progress_id = ?", [$progress_id]);
        
        if ($query) {
            return ['status' => 'success', 'message' => 'Progress image deleted successfully!'];
        } else {
            return ['status' => 'error', 'message' => 'Failed to delete image!'];
        }
    }
}