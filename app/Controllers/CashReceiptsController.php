<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class CashReceiptsController extends BaseController
{
    public function __construct()
    {
        $this->request = \Config\Services::request();
        $this->cashReceiptsModel = model('App\Models\CashReceiptsModel');
        $this->db = \Config\Database::connect();
        $this->session = session();
        $this->cuser = $this->session->get('__xsys_myuserzicas__');
    }

    public function index() {
        
        $meaction = $this->request->getPostGet('meaction');

        switch ($meaction) {
            case 'MAIN': 
                return view('cashreceipts/cashreceipts-main');
                break;

            case 'SAVE': 
                $this->cashReceiptsModel->saveCashReceipt();
                break;

            case 'PRINT-DAILY': 
                return view('cashreceipts/cashreceipts-daily-pdf');
                break;

            case 'PRINT-SUMMARY': 
                return view('cashreceipts/cashreceipts-summary-pdf');
                break;

            case 'PRINT-JOURNAL': 
                return view('cashreceipts/cashreceipts-journal-pdf');
                break;

            case 'PRINT-INCOME': 
                return view('cashreceipts/cashreceipts-income-pdf');
                break;

            case 'PRINT-MONTHLY': 
                return view('cashreceipts/cashreceipts-monthly-pdf');
                break;
        }
    }
}