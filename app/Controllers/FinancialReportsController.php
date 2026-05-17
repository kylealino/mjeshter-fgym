<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class FinancialReportsController extends BaseController
{
    public function __construct()
    {
        $this->request = \Config\Services::request();
        $this->db = \Config\Database::connect();
        $this->session = session();
        $this->cuser = $this->session->get('__xsys_myuserzicas__');
    }

    public function index() {
        
        $meaction = $this->request->getPostGet('meaction');

        switch ($meaction) {
            case 'MAIN': 
                return view('financialreports/financial-reports-main');
                break;

            case 'PRINT-TRIAL-BALANCE': 
                return view('financialreports/financial-reports-trial-balance-pdf');
                break;

            case 'PRINT-INCOME-STATEMENT': 
                return view('financialreports/financial-reports-income-statement-pdf');
                break;

            case 'PRINT-BALANCE-SHEET': 
                return view('financialreports/financial-reports-balance-sheet-pdf');
                break;

            case 'PRINT-CASH-RECONCILIATION': 
                return view('financialreports/financial-reports-cash-reconciliation-pdf');
                break;

            case 'PRINT-LEDGER': 
                return view('financialreports/financial-reports-ledger-pdf');
                break;
        }
    }
}