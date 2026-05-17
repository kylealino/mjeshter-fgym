<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class CashDisbursementController extends BaseController
{
    public function __construct()
    {
        $this->request = \Config\Services::request();
        $this->cashDisbursementModel = model('App\Models\CashDisbursementModel');
        $this->db = \Config\Database::connect();
        $this->session = session();
        $this->cuser = $this->session->get('__xsys_myuserzicas__');
    }

    public function index() {
        
        $meaction = $this->request->getPostGet('meaction');

        switch ($meaction) {
            case 'MAIN': 
                return view('cashdisbursement/cash-disbursement-main');
                break;

            case 'SAVE': 
                $result = $this->cashDisbursementModel->saveDisbursement();
                return $this->response->setJSON($result);
                break;

            case 'DELETE': 
                $result = $this->cashDisbursementModel->deleteDisbursement();
                return $this->response->setJSON($result);
                break;

            case 'PRINT-DAILY': 
                return view('cashdisbursement/cashdisbursement-daily-pdf');
                break;

            case 'PRINT-SUMMARY': 
                return view('cashdisbursement/cashdisbursement-summary-pdf');
                break;

            case 'PRINT-JOURNAL': 
                return view('cashdisbursement/cashdisbursement-journal-pdf');
                break;

            case 'PRINT-EXPENSE': 
                return view('cashdisbursement/cashdisbursement-expense-pdf');
                break;

            case 'PRINT-MONTHLY': 
                return view('cashdisbursement/cashdisbursement-monthly-pdf');
                break;
        }
    }
}