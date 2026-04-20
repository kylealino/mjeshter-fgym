<?php
namespace App\Models;
use CodeIgniter\Model;

class LoanAvailmentModel extends Model
{

    protected $db;

    public function __construct(){
        parent::__construct();
        $this->session = session();
        $this->request = \Config\Services::request();
		$this->db = \Config\Database::connect();
		$this->cuser = $this->session->get('__xsys_myuserzicas__');
        
    }

	public function loanavailment_save() { 
		$loan_id = $this->request->getPostGet('loan_id');
		$member_id = $this->request->getPostGet('member_id');
		$loan_type = $this->request->getPostGet('loan_type');
		$loan_amount = $this->request->getPostGet('loan_amount');
		$interest_rate = $this->request->getPostGet('interest_rate');
		$term_months = $this->request->getPostGet('term_months');
		$start_date = $this->request->getPostGet('start_date');
		$maturity_date = $this->request->getPostGet('maturity_date');
		$loan_comakers = $this->request->getPostGet('loan_comakers');
		$status = $this->request->getPostGet('status');
		$ammortizationdata = $this->request->getPostGet('ammortizationdata');



		// var_dump(
		// 	$loan_id,
		// 	$member_id,
		// 	$loan_type,
		// 	$loan_amount,
		// 	$interest_rate,
		// 	$term_months,
		// 	$start_date,
		// 	$maturity_date,
		// 	$loan_comakers,
		// 	$status
		// 	$ammortizationdata
		// );
		// die();

		$query = $this->db->query("
			INSERT INTO `tbl_loans`(
				`member_id`,
				`loan_type`,
				`loan_amount`,
				`interest_rate`,
				`term_months`,
				`start_date`,
				`maturity_date`,
				`status`,
				`loan_comakers`,
				`created_by`
			)
			VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
		", [
			$member_id,
			$loan_type,
			$loan_amount,
			$interest_rate,
			$term_months,
			$start_date,
			$maturity_date,
			$status,
			$loan_comakers,
			$this->cuser
		]);

		$loan_id = $this->db->insertID();
		if (!empty($ammortizationdata)) {
			for($aa = 0; $aa < count($ammortizationdata); $aa++){
				$medata = explode("x|x",$ammortizationdata[$aa]);
				$period = $medata[0]; 
				$payment_date = date('Y/m/d', strtotime($medata[1]));
				$beginning_balance = $medata[2]; 
				$interest = $medata[3];
				$principal = $medata[4];
				$payment = $medata[5];  
				$ending_balance = $medata[6];  

				$query = $this->db->query("
					INSERT INTO `tbl_loans_ammortization`(
						`loan_id`,
						`member_id`,
						`period`,
						`payment_date`,
						`beginning_balance`,
						`interest`,
						`principal`,
						`payment`,
						`ending_balance`,
						`created_by`
					)
					VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", 
					[
						$loan_id,
						$member_id,
						$period,
						$payment_date,
						$beginning_balance,
						$interest,
						$principal,
						$payment,
						$ending_balance,
						$this->cuser
					]
				);
				
			}
		}

		$status = "Loan Saved successfully";
		$color = "success";

		if ($query) {
			// Echo JavaScript to show the toast and then redirect
			echo "
			<script>
				toastr.$color('{$status}!', 'Well Done!', {
						progressBar: true,
						closeButton: true,
						timeOut:2500,
					});
				setTimeout(function() {
						window.location.href = 'myloanavailment?meaction=MAIN'; // Redirect to MAIN view
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
	
	public function payee_delete() { 
		$recid = $this->request->getPostGet('recid');

		$accessquery = $this->db->query("
			SELECT `recid`FROM tbl_user_access WHERE `username` = '{$this->cuser}' AND `access_code` = '5004' AND `is_active` = '1'
		");
		if ($accessquery->getNumRows() == 0) {
			echo "
			<script>
			toastr.error('Deleting Access Denied! Please Contact the Administrator.', 'Oops!', {
					progressBar: true,
					closeButton: true,
					timeOut:2000,
				});
			</script>
			";
			die();
		}


		$query = $this->db->query("
			DELETE FROM `tbl_payee` WHERE `recid` = '$recid'
		");
		$status = "Payee deleted successfully";
		
		if ($query) {
			// Echo JavaScript to show the toast and then redirect
			echo "
			<script>
				toastr.warning('{$status}!', 'Well Done!', {
						progressBar: true,
						closeButton: true,
						timeOut:2500,
					});
				setTimeout(function() {
						window.location.href = 'mypayee?meaction=MAIN'; // Redirect to MAIN view
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