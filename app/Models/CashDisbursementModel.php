<?php
namespace App\Models;
use CodeIgniter\Model;

class CashDisbursementModel extends Model
{
    protected $db;

    public function __construct(){
        parent::__construct();
        $this->session = session();
        $this->request = \Config\Services::request();
        $this->db = \Config\Database::connect();
        $this->cuser = $this->session->get('__xsys_myuserzicas__');
    }

    public function saveDisbursement() { 
        $date = $this->request->getPost('date');
        $account_code = $this->request->getPost('account_code');
        $amount = $this->request->getPost('amount');
        $payee = $this->request->getPost('payee');
        $description = $this->request->getPost('description');

        $transaction_id = $this->get_ctr_cashdisbursement('CD','CTRL_NO01');

        $query = $this->db->query("
            INSERT INTO `tbl_cash_disbursement_journal`(
                `date`,
                `transaction_id`,
                `account_code`,
                `amount`,
                `payee`,
                `description`,
                `created_by`
            )
            VALUES (?, ?, ?, ?, ?, ?, ?)", 
            [
                $date,
                $transaction_id,
                $account_code,
                $amount,
                $payee,
                $description,
                $this->cuser
            ]
        );

        if ($query) {
            return ['status' => 'success', 'message' => 'Cash Disbursement Saved Successfully!'];
        } else {
            return ['status' => 'error', 'message' => 'An error occurred while saving.'];
        }
    }

    public function deleteDisbursement() { 
        $journal_id = $this->request->getPost('journal_id');

        $query = $this->db->query("DELETE FROM `tbl_cash_disbursement_journal` WHERE `journal_id` = ?", [$journal_id]);

        if ($query) {
            return ['status' => 'success', 'message' => 'Disbursement Deleted Successfully!'];
        } else {
            return ['status' => 'error', 'message' => 'An error occurred while deleting.'];
        }
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
        return  $tag . '-' . $xnumb;
    } 
}