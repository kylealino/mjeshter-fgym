<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class GeneralJournalController extends BaseController
{
    public function __construct()
    {
        $this->request = \Config\Services::request();
        $this->generalJournalModel = model('App\Models\GeneralJournalModel');
        $this->db = \Config\Database::connect();
        $this->session = session();
        $this->cuser = $this->session->get('__xsys_myuserzicas__');
    }

    public function index() {
        
        $meaction = $this->request->getPostGet('meaction');

        switch ($meaction) {
            case 'MAIN': 
                return view('generaljournal/general-journal-main');
                break;

            case 'SAVE': 
                $result = $this->generalJournalModel->saveEntry();
                return $this->response->setJSON($result);
                break;

            case 'DELETE': 
                $result = $this->generalJournalModel->deleteEntry();
                return $this->response->setJSON($result);
                break;
                
            case 'PRINT-DAILY': 
                return view('generaljournal/general-journal-daily-pdf');
                break;

            case 'PRINT-SUMMARY': 
                return view('generaljournal/general-journal-summary-pdf');
                break;

            case 'PRINT-JOURNAL': 
                return view('generaljournal/general-journal-main-pdf');
                break;

            case 'PRINT-ACCOUNT': 
                return view('generaljournal/general-journal-account-pdf');
                break;

            case 'PRINT-MONTHLY': 
                return view('generaljournal/general-journal-monthly-pdf');
                break;
        }
    }
}