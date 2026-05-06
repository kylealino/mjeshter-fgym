<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class AttendanceController extends BaseController
{
    public function __construct()
    {
        $this->request = \Config\Services::request();
        $this->db = \Config\Database::connect();
        $this->session = session();
        $this->cuser = $this->session->get('__xsys_myuserzicas__');

        $this->attendanceModel = model('App\Models\AttendanceModel');
    }

    public function index()
    {
        $meaction = $this->request->getPostGet('meaction');

        switch ($meaction) {
            case 'MAIN':
                return view('attendance/attendance-main');
                break;
                
            case 'SAVE-WALKIN':
                $this->attendanceModel->saveWalkin();
                break;

            case 'CHECKIN-MEMBER':
                $this->attendanceModel->checkinMember();
                break;


        }
    }
}