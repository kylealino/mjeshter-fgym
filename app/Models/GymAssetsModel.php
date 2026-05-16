<?php
namespace App\Models;
use CodeIgniter\Model;

class GymAssetsModel extends Model
{
    protected $db;

    public function __construct(){
        parent::__construct();
        $this->session = session();
        $this->request = \Config\Services::request();
        $this->db = \Config\Database::connect();
        $this->cuser = $this->session->get('__xsys_myuserzicas__');
    }

    public function saveAsset() { 
        $asset_name = $this->request->getPost('asset_name');
        $asset_category = $this->request->getPost('asset_category');
        $acquisition_cost = $this->request->getPost('acquisition_cost');
        $useful_life_months = $this->request->getPost('useful_life_months');
        $monthly_depreciation = $acquisition_cost / $useful_life_months;
        $notes = $this->request->getPost('notes');

        $query = $this->db->query("
            INSERT INTO `tbl_gym_assets`(
                `asset_name`,
                `asset_category`,
                `acquisition_cost`,
                `useful_life_months`,
                `monthly_depreciation`,
                `notes`,
                `created_by`
            )
            VALUES (?, ?, ?, ?, ?, ?, ?)", 
            [
                $asset_name,
                $asset_category,
                $acquisition_cost,
                $useful_life_months,
                $monthly_depreciation,
                $notes,
                $this->cuser
            ]
        );

        if ($query) {
            return ['status' => 'success', 'message' => 'Asset Saved Successfully!'];
        } else {
            return ['status' => 'error', 'message' => 'An error occurred while saving.'];
        }
    }

    public function updateAsset() { 
        $asset_id = $this->request->getPost('asset_id');
        $asset_name = $this->request->getPost('asset_name');
        $asset_category = $this->request->getPost('asset_category');
        $acquisition_cost = $this->request->getPost('acquisition_cost');
        $useful_life_months = $this->request->getPost('useful_life_months');
        $monthly_depreciation = $acquisition_cost / $useful_life_months;
        $notes = $this->request->getPost('notes');
        $status = $this->request->getPost('status');

        $query = $this->db->query("
            UPDATE `tbl_gym_assets`
            SET 
                `asset_name` = ?,
                `asset_category` = ?,
                `acquisition_cost` = ?,
                `useful_life_months` = ?,
                `monthly_depreciation` = ?,
                `notes` = ?,
                `status` = ?,
                `updated_at` = NOW()
            WHERE `asset_id` = ?
            ", 
            [
                $asset_name,
                $asset_category,
                $acquisition_cost,
                $useful_life_months,
                $monthly_depreciation,
                $notes,
                $status,
                $asset_id
            ]
        );

        if ($query) {
            return ['status' => 'success', 'message' => 'Asset Updated Successfully!'];
        } else {
            return ['status' => 'error', 'message' => 'An error occurred while updating.'];
        }
    }

    public function deleteAsset() { 
        $asset_id = $this->request->getPost('asset_id');

        $query = $this->db->query("DELETE FROM `tbl_gym_assets` WHERE `asset_id` = ?", [$asset_id]);

        if ($query) {
            return ['status' => 'success', 'message' => 'Asset Deleted Successfully!'];
        } else {
            return ['status' => 'error', 'message' => 'An error occurred while deleting.'];
        }
    }
}