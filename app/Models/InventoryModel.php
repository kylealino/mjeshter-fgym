<?php
namespace App\Models;
use CodeIgniter\Model;

class InventoryModel extends Model
{
    protected $db;

    public function __construct(){
        parent::__construct();
        $this->session = session();
        $this->request = \Config\Services::request();
        $this->db = \Config\Database::connect();
        $this->cuser = $this->session->get('__xsys_myuserzicas__');
    }

    public function saveInventory() { 
        $product_name = $this->request->getPost('product_name');
        $category = $this->request->getPost('category');
        $purchase_price = $this->request->getPost('purchase_price');
        $selling_price = $this->request->getPost('selling_price');
        $stock_qty = $this->request->getPost('stock_qty');

        if(empty($product_name) || empty($category) || empty($purchase_price) || empty($selling_price) || empty($stock_qty)){
            return ['status' => 'error', 'message' => 'All fields are required!'];
        }

        $reference_no = $this->get_ctr_stockin('STOCKIN','CTRL_NO01');

        // Insert into products
        $query = $this->db->query("
            INSERT INTO `tbl_products`(
                `product_name`,
                `category`,
                `purchase_price`,
                `selling_price`,
                `stock_qty`,
                `created_by`
            )
            VALUES (?, ?, ?, ?, ?, ?)", 
            [
                $product_name,
                $category,
                $purchase_price,
                $selling_price,
                $stock_qty,
                $this->cuser
            ]
        );

        $product_id = $this->db->insertID();

        // Insert into inventory movements
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
                $product_name,
                'IN',
                $stock_qty,
                'STOCK IN',
                $reference_no,
                'NEW ADDED PRODUCT',
                $this->cuser
            ]
        );

        // =============================================
        // INSERT INTO CASH DISBURSEMENT JOURNAL
        // =============================================
        $total_cost = $purchase_price * $stock_qty;
        $disbursement_id = $this->get_ctr_cashdisbursement('CD', 'CTRL_NO01');
        
        // Determine payee based on category
        $payee = '';
        switch($category) {
            case 'Merchandise':
                $payee = 'Apparel Supplier';
                break;
            case 'Accessories':
                $payee = 'Accessories Supplier';
                break;
            case 'Beverages':
                $payee = 'Beverage Distributor';
                break;
            case 'Food':
                $payee = 'Food Supplier';
                break;
            case 'Supplements':
                $payee = 'Supplement Distributor';
                break;
            default:
                $payee = 'General Supplier';
        }

        $description = "Stock In: {$product_name} ({$stock_qty} pcs @ ₱" . number_format($purchase_price,2) . ")";

        $this->db->query("
            INSERT INTO `tbl_cash_disbursement_journal`(
                `date`,
                `transaction_id`,
                `account_code`,
                `amount`,
                `payee`,
                `description`,
                `created_by`,
                `created_at`
            )
            VALUES (?, ?, ?, ?, ?, ?, ?, NOW())", 
            [
                date('Y-m-d'),
                $disbursement_id,
                '5010',
                $total_cost,
                $payee,
                $description,
                $this->cuser
            ]
        );

        if ($query) {
            return ['status' => 'success', 'message' => $product_name . ' Saved Successfully!'];
        } else {
            return ['status' => 'error', 'message' => 'An error occurred while saving.'];
        }
    }

    public function saveAdjustment() { 
        $adj_product_name = $this->request->getPost('adj_product_name');
        $adj_type = $this->request->getPost('adj_type');
        $remarks = $this->request->getPost('remarks');
        $adj_qty = $this->request->getPost('adj_qty');

        if(empty($adj_product_name) || empty($adj_type) || empty($remarks) || empty($adj_qty)){
            return ['status' => 'error', 'message' => 'All fields are required!'];
        }

        $reference_no = $this->get_ctr_adjustment('ADJUSTMENT','CTRL_NO01');

        // Get product details first
        $product_query = $this->db->query("
            SELECT `product_id`, `purchase_price`, `category` 
            FROM `tbl_products` 
            WHERE `product_name` = '$adj_product_name'
        ");
        $product_data = $product_query->getRowArray();
        $product_id = $product_data['product_id'];
        $purchase_price = $product_data['purchase_price'];
        $category = $product_data['category'];

        $original_qty = $adj_qty;
        
        if ($adj_type == 'DECREASE') {
            $adj_qty = -abs($adj_qty);
        } else {
            $adj_qty = abs($adj_qty);
        }

        $query = $this->db->query("
            UPDATE `tbl_products`
            SET `stock_qty` = `stock_qty` + ?,
                `updated_at` = NOW()
            WHERE product_name = ?
        ", [$adj_qty, $adj_product_name]);

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
                $adj_product_name,
                'ADJUSTMENT',
                $adj_qty,
                'ADJUSTMENT',
                $reference_no,
                $remarks,
                $this->cuser
            ]
        );

        // =============================================
        // INSERT INTO CASH DISBURSEMENT JOURNAL (ONLY FOR INCREASE)
        // =============================================
        if ($adj_type == 'INCREASE') {
            $total_cost = $purchase_price * $original_qty;
            $disbursement_id = $this->get_ctr_cashdisbursement('CD', 'CTRL_NO01');
            
            // Determine payee based on category
            $payee = '';
            switch($category) {
                case 'Merchandise':
                    $payee = 'Apparel Supplier';
                    break;
                case 'Accessories':
                    $payee = 'Accessories Supplier';
                    break;
                case 'Beverages':
                    $payee = 'Beverage Distributor';
                    break;
                case 'Food':
                    $payee = 'Food Supplier';
                    break;
                case 'Supplements':
                    $payee = 'Supplement Distributor';
                    break;
                default:
                    $payee = 'General Supplier';
            }

            $description = "Stock Adjustment (INCREASE): {$adj_product_name} (+{$original_qty} pcs @ ₱" . number_format($purchase_price,2) . ") - {$remarks}";

            $this->db->query("
                INSERT INTO `tbl_cash_disbursement_journal`(
                    `date`,
                    `transaction_id`,
                    `account_code`,
                    `amount`,
                    `payee`,
                    `description`,
                    `created_by`,
                    `created_at`
                )
                VALUES (?, ?, ?, ?, ?, ?, ?, NOW())", 
                [
                    date('Y-m-d'),
                    $disbursement_id,
                    '5010',
                    $total_cost,
                    $payee,
                    $description,
                    $this->cuser
                ]
            );
        }

        if ($query) {
            return ['status' => 'success', 'message' => $adj_product_name . ' Adjustment Successful!'];
        } else {
            return ['status' => 'error', 'message' => 'An error occurred while saving adjustment.'];
        }
    }

    public function get_ctr_stockin($tag,$mfld='') { 
        $accessquery = $this->db->query("
        CREATE TABLE if not exists `myctr_stockin` (
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
        
        $qctr = $this->db->query("select {$xfield} from myctr_stockin WHERE CTR_YEAR = '$xsysyear' AND CTR_MONTH = '$xsysmonth' AND CTR_DAY = '$xsysday'  limit 1");
        if($qctr->getNumRows() == 0) {
            $xnumb = '001';
            $query = $this->db->query( "insert into myctr_stockin (CTR_YEAR,CTR_MONTH,CTR_DAY,{$xfield}) values('$xsysyear','$xsysmonth','$xsysday','$xnumb')");
            $qctr->freeResult();
        } else {
            $qctr->freeResult();
            $qctr = $this->db->query( "select {$xfield} MYFIELD from myctr_stockin WHERE CTR_YEAR = '$xsysyear' AND CTR_MONTH = '$xsysmonth' AND CTR_DAY = '$xsysday' limit 1");
            $rctr = $qctr->getRowArray();
            if(trim($rctr['MYFIELD'],' ') == '') { 
                $xnumb = '001';
            } else {
                $xnumb = $rctr['MYFIELD'];
                $qctr = $this->db->query("select ('{$xnumb}' + 1) XNUMB");
                $rctr = $qctr->getRowArray();
                $xnumb = trim($rctr['XNUMB'],' ');
                $xnumb = str_pad($xnumb + 0,3,"0",STR_PAD_LEFT);
                $query = $this->db->query("update myctr_stockin set {$xfield} = '{$xnumb}'");
            }
        }
        return $tag . '-' . $xnumb;
    } 

    public function get_ctr_adjustment($tag,$mfld='') { 
        $accessquery = $this->db->query("
        CREATE TABLE if not exists `myctr_adjustment` (
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
        
        $qctr = $this->db->query("select {$xfield} from myctr_adjustment WHERE CTR_YEAR = '$xsysyear' AND CTR_MONTH = '$xsysmonth' AND CTR_DAY = '$xsysday'  limit 1");
        if($qctr->getNumRows() == 0) {
            $xnumb = '001';
            $query = $this->db->query( "insert into myctr_adjustment (CTR_YEAR,CTR_MONTH,CTR_DAY,{$xfield}) values('$xsysyear','$xsysmonth','$xsysday','$xnumb')");
            $qctr->freeResult();
        } else {
            $qctr->freeResult();
            $qctr = $this->db->query( "select {$xfield} MYFIELD from myctr_adjustment WHERE CTR_YEAR = '$xsysyear' AND CTR_MONTH = '$xsysmonth' AND CTR_DAY = '$xsysday' limit 1");
            $rctr = $qctr->getRowArray();
            if(trim($rctr['MYFIELD'],' ') == '') { 
                $xnumb = '001';
            } else {
                $xnumb = $rctr['MYFIELD'];
                $qctr = $this->db->query("select ('{$xnumb}' + 1) XNUMB");
                $rctr = $qctr->getRowArray();
                $xnumb = trim($rctr['XNUMB'],' ');
                $xnumb = str_pad($xnumb + 0,3,"0",STR_PAD_LEFT);
                $query = $this->db->query("update myctr_adjustment set {$xfield} = '{$xnumb}'");
            }
        }
        return $tag . '-' . $xnumb;
    }

    public function get_ctr_cashdisbursement($tag,$mfld='') { 
        $accessquery = $this->db->query("
        CREATE TABLE if not exists `myctr_cashdisbursement` (
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
        
        $qctr = $this->db->query("select {$xfield} from myctr_cashdisbursement WHERE CTR_YEAR = '$xsysyear' AND CTR_MONTH = '$xsysmonth' AND CTR_DAY = '$xsysday'  limit 1");
        if($qctr->getNumRows() == 0) {
            $xnumb = '001';
            $query = $this->db->query( "insert into myctr_cashdisbursement (CTR_YEAR,CTR_MONTH,CTR_DAY,{$xfield}) values('$xsysyear','$xsysmonth','$xsysday','$xnumb')");
            $qctr->freeResult();
        } else {
            $qctr->freeResult();
            $qctr = $this->db->query( "select {$xfield} MYFIELD from myctr_cashdisbursement WHERE CTR_YEAR = '$xsysyear' AND CTR_MONTH = '$xsysmonth' AND CTR_DAY = '$xsysday' limit 1");
            $rctr = $qctr->getRowArray();
            if(trim($rctr['MYFIELD'],' ') == '') { 
                $xnumb = '001';
            } else {
                $xnumb = $rctr['MYFIELD'];
                $qctr = $this->db->query("select ('{$xnumb}' + 1) XNUMB");
                $rctr = $qctr->getRowArray();
                $xnumb = trim($rctr['XNUMB'],' ');
                $xnumb = str_pad($xnumb + 0,3,"0",STR_PAD_LEFT);
                $query = $this->db->query("update myctr_cashdisbursement set {$xfield} = '{$xnumb}'");
            }
        }
        return $tag . '-' . $xnumb;
    }
}