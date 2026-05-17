<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class POSController extends BaseController
{
    public function __construct()
    {
        $this->request = \Config\Services::request();
        $this->posModel = model('App\Models\POSModel');
        $this->db = \Config\Database::connect();
        $this->session = session();
        $this->cuser = $this->session->get('__xsys_myuserzicas__');
    }

    public function index() {
        
        $meaction = $this->request->getPostGet('meaction');

        switch ($meaction) {
            case 'MAIN': 
                return view('pos/pos-main');
                break;

            case 'POS-SAVE': 
                $result = $this->posModel->savePOS();
                return $this->response->setJSON($result);
                break;
        }
    }
}