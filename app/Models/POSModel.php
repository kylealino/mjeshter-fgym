<?php
namespace App\Models;
use CodeIgniter\Model;

class POSModel extends Model
{
    protected $db;

    public function __construct(){
        parent::__construct();
        $this->session = session();
        $this->request = \Config\Services::request();
        $this->db = \Config\Database::connect();
        $this->cuser = $this->session->get('__xsys_myuserzicas__');
    }

    public function savePOS() { 
        $cartdata = $this->request->getPost('cartdata');
        
        // Debug - see what you're getting
        var_dump($cartdata);
        die();
        
    
    }
}