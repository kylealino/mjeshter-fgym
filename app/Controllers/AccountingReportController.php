<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class AccountingReportController  extends BaseController
{
    public function __construct()
	{
		$this->request = \Config\Services::request();
        $this->mycoa = model('App\Models\COAModel');
        $this->db = \Config\Database::connect();
        $this->session = session();
        $this->cuser = $this->session->get('__xsys_myuserzicas__');
	}

    public function index() {
        
        $meaction = $this->request->getPostGet('meaction');
    
        switch ($meaction) {
            case 'MAIN': 
                return view('accounting/report-main');
                break;

            case 'COA-SAVE': 
                $this->mycoa->coa_save();
                return redirect()->to('mycoa?meaction=MAIN');
                break;
            case 'cash-receipts':
				return view('accounting/cash-receipts-pdf');
				break;
            case 'cash-disbursement':
				return view('accounting/cash-disbursement-pdf');
				break;
            case 'balance-sheet':
				return view('accounting/balance-sheet-pdf');
				break;
            case 'income-statement':
				return view('accounting/income-statement-pdf');
				break;
            case 'cash-flow':
				return view('accounting/cash-flow-pdf');
				break;

        }
    }

}
