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

            case 'SAVE-WALKIN':
                $this->attendanceModel->saveWalkin();
                break;

            case 'CHECKIN-MEMBER':
                $this->attendanceModel->checkinMember();
                break;

            case 'MAIN':
            default:

                // TODAY CHECKINS
                $query = $this->db->query("
                    SELECT 
                        a.*,
                        CONCAT(b.first_name,' ',b.last_name) AS member_name,
                        b.member_no
                    FROM tbl_checkin_history a
                    LEFT JOIN tbl_members b
                    ON a.member_id = b.member_id
                    WHERE DATE(a.checkin_time) = CURDATE()
                    ORDER BY a.checkin_time DESC
                ");

                $attendance = $query->getResultArray();

                // ACTIVE MEMBERS
                $memberquery = $this->db->query("
                    SELECT
                        member_id,
                        member_no,
                        first_name,
                        last_name
                    FROM tbl_members
                    WHERE membership_status = 'Active'
                    ORDER BY first_name ASC
                ");

                $members = $memberquery->getResultArray();

                return view('attendance/attendance-main',[
                    'attendance' => $attendance,
                    'members' => $members
                ]);

                break;
        }
    }
}