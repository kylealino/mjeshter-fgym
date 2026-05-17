<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class InventoryController extends BaseController
{
    public function __construct()
    {
        $this->request = \Config\Services::request();
        $this->inventoryModel = model('App\Models\InventoryModel');
        $this->db = \Config\Database::connect();
        $this->session = session();
        $this->cuser = $this->session->get('__xsys_myuserzicas__');
    }

    public function index() {
        
        $meaction = $this->request->getPostGet('meaction');

        switch ($meaction) {
            case 'MAIN': 
                return view('inventory/inventory-main');
                break;

            case 'STOCKIN-SAVE': 
                $result = $this->inventoryModel->saveInventory();
                return $this->response->setJSON($result);
                break;

            case 'ADJUSTMENT-SAVE': 
                $result = $this->inventoryModel->saveAdjustment();
                return $this->response->setJSON($result);
                break;
        }
    }
}