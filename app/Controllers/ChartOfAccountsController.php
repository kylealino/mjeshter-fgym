<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class ChartOfAccountsController extends BaseController
{
    public function __construct()
    {
        $this->request = \Config\Services::request();
        $this->coaModel = model('App\Models\ChartOfAccountsModel');
        $this->db = \Config\Database::connect();
        $this->session = session();
        $this->cuser = $this->session->get('__xsys_myuserzicas__');
    }

    public function index() {
        
        $meaction = $this->request->getPostGet('meaction');

        switch ($meaction) {
            case 'MAIN': 
                return view('chartofaccounts/coa-main');
                break;

            case 'SAVE': 
                $result = $this->coaModel->saveAccount();
                echo json_encode($result);
                break;

            case 'EDIT': 
                $result = $this->coaModel->updateAccount();
                echo json_encode($result);
                break;

            case 'DELETE': 
                $result = $this->coaModel->deleteAccount();
                echo json_encode($result);
                break;
        }
    }
}