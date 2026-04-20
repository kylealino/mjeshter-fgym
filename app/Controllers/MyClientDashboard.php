<?php

namespace App\Controllers;

class MyClientDashboard extends BaseController
{
    public function __construct(){
		$this->request = \Config\Services::request();
        $this->db = \Config\Database::connect();
        $this->session = session();
        $this->cuser = $this->session->get('__xsys_myuserzicas__');
	}

    public function index(){
        return view('MyClientDashboard'); 
    }

    public function logout(){
        $this->session->destroy();
        return redirect()->to('myclientlogin');
    }

}
