<?php
namespace App\Models;
use CodeIgniter\Model;

class POSModel extends Model
{
    protected $db;

    public function __construct(){
        parent::__construct();
        $this->session = session();
        $this->request = \Config\Services::request();
        $this->db = \Config\Database::connect();
        $this->cuser = $this->session->get('__xsys_myuserzicas__');
    }

    public function savePOS() { 
        $cartdata = $this->request->getPost('cartdata');
        $payment_method = $this->request->getPost('payment_method');
        $amount_tendered = $this->request->getPost('amount_tendered');
        $change_amount = $this->request->getPost('change_amount');
        $grand_total = $this->request->getPost('grand_total');

        $cseqn =  $this->get_ctr_pos('POS','CTRL_NO01');//TRANSACTION NO

        if (!empty($cartdata)) {
            for($aa = 0; $aa < count($cartdata); $aa++){
                $medata = explode("x|x",$cartdata[$aa]);
                $item_name = $medata[0]; 
                $item_type = $medata[1]; 
                $item_qty = $medata[2]; 
                $item_amount = $medata[3];
    
                $query = $this->db->query("
                    INSERT INTO `tbl_pos_dt`(
                        `postrxno`,
                        `item_name`,
                        `item_type`,
                        `item_qty`,
                        `item_amount`,
                        `created_by`
                    )
                    VALUES (?, ?, ?, ?, ?, ?)", 
                    [
                        $cseqn,
                        $item_name,
                        $item_type,
                        $item_qty,
                        $item_amount,
                        $this->cuser
                    ]
                );

                $query = $this->db->query("
                UPDATE
                    `tbl_products`
                SET
                    `stock_qty` = `stock_qty` - ?,
                    `updated_at` = NOW()
                WHERE product_name = ?
                ", [
                    $item_qty, $item_name
                ]);

                $query = $this->db->query("
                    SELECT `product_id` FROM `tbl_products` WHERE `product_name` = '$item_name'
                ");
                $rw = $query->getRowArray();
                $product_id = $rw['product_id'];

                $query = $this->db->query("
                    INSERT INTO `tbl_inventory_movements`(
                        `product_id`,
                        `product_name`,
                        `movement_type`,
                        `quantity`,
                        `reference_type`,
                        `reference_no`,
                        `remarks`,
                        `created_by`
                    )
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)", 
                    [
                        $product_id,
                        $item_name,
                        'OUT',
                        -abs($item_qty),
                        'POS',
                        $cseqn,
                        'POS TRANSACTION',
                        $this->cuser
                    ]
                );
                
            }
        }

        $query = $this->db->query("
            INSERT INTO `tbl_pos_payment`(
                `postrxno`,
                `payment_method`,
                `amount_tendered`,
                `change_amount`,
                `grand_total`,
                `created_by`
            )
            VALUES (?, ?, ?, ?, ?, ?)", 
            [
                $cseqn,
                $payment_method,
                $amount_tendered,
                $change_amount,
                $grand_total,
                $this->cuser
            ]
        );

        if ($query) {
            // Echo JavaScript to show the toast and then redirect - EXACTLY like your budget code
            echo "
            <script>
                toastr.success('POS Transaction Saved Successfully arararar!', 'Well Done!', {
                    progressBar: true,
                    closeButton: true,
                    timeOut: 1500,
                });
                setTimeout(function() {
                    window.location.href = 'pos?meaction=MAIN';
                }, 1500);
            </script>
            ";
            exit;
        } else {
            echo "<script>alert('An error occurred while saving.');</script>";
            exit;
        }
    
    }

    public function get_ctr_pos($tag,$mfld='') { 
		$accessquery = $this->db->query("
		CREATE TABLE if not exists `myctr_pos` (
		  `CTR_YEAR` varchar(4) DEFAULT '0000',
		  `CTR_MONTH` varchar(2) DEFAULT '00',
		  `CTR_DAY` varchar(2) DEFAULT '00',
		  `CTRL_NO01` varchar(15) DEFAULT '000',
		  `CTRL_NO02` varchar(15) DEFAULT '00000000',
		  `CTRL_NO03` varchar(15) DEFAULT '00000000',
		  `CTRL_NO04` varchar(15) DEFAULT '00000000',
		  `CTRL_NO05` varchar(15) DEFAULT '00000000',
		  `CTRL_NO06` varchar(15) DEFAULT '00000000',
		  `CTRL_NO07` varchar(15) DEFAULT '00000000',
		  `CTRL_NO08` varchar(15) DEFAULT '00000000',
		  `CTRL_NO09` varchar(15) DEFAULT '00000000',
		  `CTRL_NO10` varchar(15) DEFAULT '00000000',
		  `CTRL_NO11` varchar(15) DEFAULT '00000000',
		  `SS_CTR` varchar(15) DEFAULT '000000',
		  UNIQUE KEY `ctr01` (`CTR_YEAR`,`CTR_MONTH`,`CTR_DAY`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		");

		$xfield = (empty($mfld) ? 'CTRL_NO01' : $mfld);
		
		$q = $this->db->query("select date(now()) XSYSDATE");
		$rdate = $q->getRowArray();
		$xsysdate = $rdate['XSYSDATE'];
		$xsysdate_exp = explode('-', $xsysdate);
		$xsysyear =  $xsysdate_exp[0];
		$xsysmonth = $xsysdate_exp[1];
		$xsysday = $xsysdate_exp[2];
		
		$qctr = $this->db->query("select {$xfield} from myctr_pos WHERE CTR_YEAR = '$xsysyear' AND CTR_MONTH = '$xsysmonth' AND CTR_DAY = '$xsysday'  limit 1");
		if($qctr->getNumRows() == 0) {
			$xnumb = '001';
			$query = $this->db->query( "insert into myctr_pos (CTR_YEAR,CTR_MONTH,CTR_DAY,{$xfield}) values('$xsysyear','$xsysmonth','$xsysday','$xnumb')");
			$qctr->freeResult();
		} else {
			$qctr->freeResult();
			$qctr = $this->db->query( "select {$xfield} MYFIELD from myctr_pos WHERE CTR_YEAR = '$xsysyear' AND CTR_MONTH = '$xsysmonth' AND CTR_DAY = '$xsysday' limit 1");
			$rctr = $qctr->getRowArray();
			if(trim($rctr['MYFIELD'],' ') == '') { 
				$xnumb = '001';
			} else {
				$xnumb = $rctr['MYFIELD'];
				$qctr = $this->db->query("select ('{$xnumb}' + 1) XNUMB");
				$rctr = $qctr->getRowArray();
				$xnumb = trim($rctr['XNUMB'],' ');
				$xnumb = str_pad($xnumb + 0,3,"0",STR_PAD_LEFT);
				$query = $this->db->query("update myctr_pos set {$xfield} = '{$xnumb}'");
			}
		}
		return  $tag . $xsysmonth . $xsysday . $xsysyear. $xnumb;//.$supp
	} 
}