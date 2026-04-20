<?php
namespace App\Models;
use CodeIgniter\Model;

class COAModel extends Model
{

    protected $db;

    public function __construct(){
        parent::__construct();
        $this->session = session();
        $this->request = \Config\Services::request();
		$this->db = \Config\Database::connect();
		$this->cuser = $this->session->get('__xsys_myuserzicas__');
        
    }

	public function coa_save() { 
		$account_id   = $this->request->getPostGet('account_id');
		$account_code   = $this->request->getPostGet('account_code');
		$account_name = $this->request->getPostGet('account_name');
		$account_type = $this->request->getPostGet('account_type');
		$parent_code = $this->request->getPostGet('parent_code');
		$is_active       = $this->request->getPostGet('is_active');

		if (empty($account_id)) {
			$query = $this->db->query("
				INSERT INTO `tbl_coa`(
					`account_code`,
					`account_name`,
					`account_type`,
					`parent_code`,
					`is_active`,
					`created_by`
				) VALUES (?, ?, ?, ?, ?, ?)
			", [
				$account_code,
				$account_name,
				$account_type,
				$parent_code,
				$is_active,
				$this->cuser
			]);

			$status = "Account saved successfully";
			$color = "success";
		}else{
			$query = $this->db->query("
				UPDATE `tbl_coa` SET
					`account_code` = ?,
					`account_name` = ?,
					`account_type` = ?,
					`parent_code` = ?,
					`is_active` = ?
				WHERE `account_id` = ?
			", [
				$account_code,
				$account_name,
				$account_type,
				$parent_code,
				$is_active,
				$account_id
			]);

			$status = "Account updated successfully";
			$color = "success";
		}


		if ($query) {
			// Echo JavaScript to show the toast and then redirect
			echo "
			<script>
				toastr.{$color}('{$status}!', 'Well Done!', {
						progressBar: true,
						closeButton: true,
						timeOut:2500,
					});
				setTimeout(function() {
						window.location.href = 'mycoa?meaction=MAIN'; // Redirect to MAIN view
					}, 2500); // 2-second delay for user to see the toast
			</script>
			";
			exit; // Stop further PHP execution after the toast
		} else {
			// If there's an error, show an alert message
			echo "<script type='text/javascript'>
					alert('An error occurred while executing the query.');
				  </script>";
			exit;
		}
	}
	
	
} //end main class
?>