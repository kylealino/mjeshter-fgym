<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class MembersManagementController extends BaseController
{
    public function __construct()
    {
        $this->request = \Config\Services::request();
        $this->memberModel = model('App\Models\MembersManagementModel');
        $this->db = \Config\Database::connect();
        $this->session = session();
        $this->cuser = $this->session->get('__xsys_myuserzicas__');
    }

    private function loadMembersListView() {
        $membersdataquery = $this->db->query("
            SELECT
                member_id,
                member_no,
                first_name,
                last_name,
                middle_name,
                contact_number,
                mobile_number,
                email
            FROM tbl_members
            ORDER BY member_id DESC
        ");
        $membersdata = $membersdataquery->getResultArray();

        return view('members-management/members-main', [
            'membersdata' => $membersdata
        ]);
    }

    public function index() {
        
        $meaction = $this->request->getPostGet('meaction');

        switch ($meaction) {
            case 'MAIN': 
                return $this->loadMembersListView();
                break;

            case 'MEMBER-SAVE': 
                $result = $this->memberModel->saveMember();
                return $this->response->setJSON($result);
                break;

            case 'CHECK-RFID':
                return $this->checkRFID();
                break;
            
            case 'MEMBERS-UPDATE': 
                $result = $this->memberModel->saveMember();
                return $this->response->setJSON($result);
                break;

            case 'MEMBERS-PRINT':
                return view('members-management/members-profile-pdf');
                break;
                
            case 'MEMBERS-main':
                return $this->loadMembersListView();
                break;
                
            case 'GET-CHECKIN-HISTORY':
                echo $this->getCheckinHistory();
                exit;
                break;
        }
    }

    private function checkRFID()
    {
        $rfid_uid = $this->request->getPost('rfid_uid');

        $query = $this->db->query("
            SELECT member_id, CONCAT(first_name, ' ', last_name) as member_name
            FROM tbl_members
            WHERE rfid_uid = ?
            LIMIT 1
        ", [$rfid_uid]);

        $result = $query->getRowArray();

        if($result){
            return $this->response->setJSON([
                'exists' => true,
                'member_name' => $result['member_name']
            ]);
        }

        return $this->response->setJSON([
            'exists' => false
        ]);
    }
}