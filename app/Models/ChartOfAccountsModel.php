<?php
namespace App\Models;
use CodeIgniter\Model;

class ChartOfAccountsModel extends Model
{
    protected $db;

    public function __construct(){
        parent::__construct();
        $this->session = session();
        $this->request = \Config\Services::request();
        $this->db = \Config\Database::connect();
        $this->cuser = $this->session->get('__xsys_myuserzicas__');
    }

    public function saveAccount() { 
        $account_code = $this->request->getPost('account_code');
        $account_name = $this->request->getPost('account_name');
        $account_type = $this->request->getPost('account_type');
        $account_category = $this->request->getPost('account_category');
        $normal_balance = $this->request->getPost('normal_balance');
        $parent_account_id = $this->request->getPost('parent_account_id');
        $description = $this->request->getPost('description');

        // Check if account code already exists
        $check = $this->db->query("SELECT COUNT(*) as count FROM tbl_chart_of_accounts WHERE account_code = ?", [$account_code])->getRow();
        
        if($check->count > 0) {
            return ['status' => 'error', 'message' => 'Account Code already exists!'];
        }

        $query = $this->db->query("
            INSERT INTO `tbl_chart_of_accounts`(
                `account_code`,
                `account_name`,
                `account_type`,
                `account_category`,
                `normal_balance`,
                `parent_account_id`,
                `description`,
                `created_by`
            )
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)", 
            [
                $account_code,
                $account_name,
                $account_type,
                $account_category,
                $normal_balance,
                $parent_account_id ?: NULL,
                $description,
                $this->cuser
            ]
        );

        if ($query) {
            return ['status' => 'success', 'message' => 'Account Saved Successfully!'];
        } else {
            return ['status' => 'error', 'message' => 'An error occurred while saving.'];
        }
    }

    public function updateAccount() { 
        $account_id = $this->request->getPost('account_id');
        $account_code = $this->request->getPost('account_code');
        $account_name = $this->request->getPost('account_name');
        $account_type = $this->request->getPost('account_type');
        $account_category = $this->request->getPost('account_category');
        $normal_balance = $this->request->getPost('normal_balance');
        $description = $this->request->getPost('description');

        // Check if account code already exists for a different account
        $check = $this->db->query("SELECT COUNT(*) as count FROM tbl_chart_of_accounts WHERE account_code = ? AND account_id != ?", [$account_code, $account_id])->getRow();
        
        if($check->count > 0) {
            return ['status' => 'error', 'message' => 'Account Code already exists!'];
        }

        $query = $this->db->query("
            UPDATE `tbl_chart_of_accounts`
            SET 
                `account_code` = ?,
                `account_name` = ?,
                `account_type` = ?,
                `account_category` = ?,
                `normal_balance` = ?,
                `description` = ?,
                `updated_at` = NOW()
            WHERE `account_id` = ?
            ", 
            [
                $account_code,
                $account_name,
                $account_type,
                $account_category,
                $normal_balance,
                $description,
                $account_id
            ]
        );

        if ($query) {
            return ['status' => 'success', 'message' => 'Account Updated Successfully!'];
        } else {
            return ['status' => 'error', 'message' => 'An error occurred while updating.'];
        }
    }

    public function deleteAccount() { 
        $account_id = $this->request->getPost('account_id');

        $query = $this->db->query("DELETE FROM `tbl_chart_of_accounts` WHERE `account_id` = ?", [$account_id]);

        if ($query) {
            return ['status' => 'success', 'message' => 'Account Deleted Successfully!'];
        } else {
            return ['status' => 'error', 'message' => 'An error occurred while deleting.'];
        }
    }
}