<?php

namespace App\Models;

use CodeIgniter\Model;

class AttendanceModel extends Model
{
    protected $db;

    public function __construct()
    {
        parent::__construct();

        $this->request = \Config\Services::request();
        $this->db = \Config\Database::connect();
        $this->session = session();
        $this->cuser = $this->session->get('__xsys_myuserzicas__');
    }

    // =====================================================
    // MEMBER CHECKIN
    // =====================================================

    public function checkinMember()
    {
        $member_id = $this->request->getPost('member_id');

        if(empty($member_id)){
            echo "
            <script>
                toastr.error('Please select member');
            </script>
            ";
            exit;
        }

        $query = $this->db->query("
            INSERT INTO tbl_checkin_history (
                member_id,
                checkin_time,
                checkin_method,
                checked_in_by,
                status,
                is_walkin
            ) VALUES (
                ?,
                NOW(),
                'Manual',
                ?,
                'Active',
                0
            )
        ",[
            $member_id,
            $this->cuser
        ]);

        if($query){
            echo "
            <script>
                toastr.success('Member checked-in successfully');

                setTimeout(function(){
                    window.location.href='attendance';
                },1000);
            </script>
            ";
        }

        exit;
    }

    // =====================================================
    // WALKIN
    // =====================================================

    public function saveWalkin()
    {
        $walkin_name = $this->request->getPost('walkin_name');
        $walkin_email = $this->request->getPost('walkin_email');
        $payment_method = $this->request->getPost('payment_method');
        $payment_amount = $this->request->getPost('payment_amount');

        if(empty($walkin_name)){
            echo "
            <script>
                toastr.error('Walk-in name is required');
            </script>
            ";
            exit;
        }

        $query = $this->db->query("
            INSERT INTO tbl_checkin_history (
                walkin_name,
                walkin_contact,
                checkin_time,
                checkin_method,
                checked_in_by,
                status,
                notes,
                is_walkin
            ) VALUES (
                ?,
                ?,
                NOW(),
                'Manual',
                ?,
                'Active',
                ?,
                1
            )
        ",[
            $walkin_name,
            $walkin_email,
            $this->cuser,
            'Payment Method: '.$payment_method.' | Amount: '.$payment_amount
        ]);

        if($query){

            echo "
            <script>
                toastr.success('Walk-in added successfully');

                setTimeout(function(){
                    window.location.href='attendance';
                },1000);
            </script>
            ";
        }

        exit;
    }
}