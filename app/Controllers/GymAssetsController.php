<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class GymAssetsController extends BaseController
{
    public function __construct()
    {
        $this->request = \Config\Services::request();
        $this->gymAssetsModel = model('App\Models\GymAssetsModel');
        $this->db = \Config\Database::connect();
        $this->session = session();
        $this->cuser = $this->session->get('__xsys_myuserzicas__');
    }

    public function index() {
        
        $meaction = $this->request->getPostGet('meaction');

        switch ($meaction) {
            case 'MAIN': 
                return view('gymassets/gym-assets-main');
                break;

            case 'SAVE': 
                $result = $this->gymAssetsModel->saveAsset();
                return $this->response->setJSON($result);
                break;

            case 'EDIT': 
                $result = $this->gymAssetsModel->updateAsset();
                return $this->response->setJSON($result);
                break;

            case 'DELETE': 
                $result = $this->gymAssetsModel->deleteAsset();
                return $this->response->setJSON($result);
                break;
        }
    }
}