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

    // public function savePOS() { 
    //     $cartdata = $this->request->getPostGet('cartdata');
    //     $payment_method = $this->request->getPostGet('payment_method');
    //     $amount_tendered = $this->request->getPostGet('amount_tendered');
    //     $change_amount = $this->request->getPostGet('change_amount');
    //     $grand_total = $this->request->getPostGet('grand_total');

    //     //MEMBERSHIP VARIABLES
    //     $member_id = $this->request->getPostGet('member_id');
    //     $plan = $this->request->getPostGet('plan');
    //     $membership_start_date = $this->request->getPostGet('membership_start_date');
    //     $membership_end_date = $this->request->getPostGet('membership_end_date');
    //     $membership_status = $this->request->getPostGet('membership_status');   

    //     //WALKING
    //     $walkin_name = $this->request->getPostGet('walkin_name');
    //     $email = \Config\Services::email();

    //     $cseqn =  $this->get_ctr_pos('POS','CTRL_NO01');//TRANSACTION NO

    //     if (!empty($cartdata)) {
    //         for($aa = 0; $aa < count($cartdata); $aa++){
    //             $medata = explode("x|x",$cartdata[$aa]);
    //             $item_name = $medata[0]; 
    //             $item_type = $medata[1]; 
    //             $item_qty = $medata[2]; 
    //             $item_amount = $medata[3];

    //             $first_word = explode(' ', $item_name)[0];
                
    //             $query = $this->db->query("
    //                 INSERT INTO `tbl_pos_dt`(
    //                     `postrxno`,
    //                     `item_name`,
    //                     `item_type`,
    //                     `item_qty`,
    //                     `item_amount`,
    //                     `created_by`
    //                 )
    //                 VALUES (?, ?, ?, ?, ?, ?)", 
    //                 [
    //                     $cseqn,
    //                     $item_name,
    //                     $item_type,
    //                     $item_qty,
    //                     $item_amount,
    //                     $this->cuser
    //                 ]
    //             );

    //             //KINUKUHA YUNG 1ST WORD NG ITEMS SA CART PARA MA TRANSACT SA INVENTORY, ATTENDANCE O MEMBERSHIP YUNG CART
    //             if ($first_word == 'Membership') {
    //                 $query = $this->db->query("
    //                 UPDATE
    //                     `tbl_members`
    //                 SET
    //                     `membership_plan` = ?,
    //                     `membership_start_date` = ?,
    //                     `membership_end_date` = ?,
    //                     `membership_status` = ?,
    //                     `updated_at` = NOW()
    //                 WHERE member_id = ?
    //                 ", [
    //                     $plan, $membership_start_date, $membership_end_date, $membership_status, $member_id
    //                 ]);

    //                 $queryy = $this->db->query("SELECT rfid_uid,email,membership_start_date,membership_end_date,first_name FROM tbl_members WHERE member_id = ?", [$member_id]);
    //                 $data = $queryy->getRowArray();
    //                 $rfid_uid = $data['rfid_uid'];
    //                 $from = $data['email'];
    //                 $membership_end_date = $data['membership_end_date'];
    //                 $membership_start_date = $data['membership_start_date'];
    //                 $first_name = $data['first_name'];

    //                 $this->db->query("
    //                     INSERT INTO tbl_checkin_history
    //                     (member_id, rfid_uid, checkin_time, checkin_method, status)
    //                     VALUES (?, ?, NOW(), 'RFID', 'Active')
    //                 ", [$member_id, $rfid_uid]);

    //                 $this->db->query("
    //                     UPDATE tbl_members
    //                     SET is_loggedin = 1
    //                     WHERE member_id = ?
    //                 ", [$member_id]);

    //                 // IMPORTANT
    //                 // FORMAT DATES
    //                 $start_date = date('F d, Y', strtotime($membership_start_date));
    //                 $end_date   = date('F d, Y', strtotime($membership_end_date));

    //                 $email->setFrom(
    //                     'kylealino@gmail.com',
    //                     'MJESHTER FITNESS GYM'
    //                 );

    //                 $email->setTo($from);

    //                 $email->setSubject('Welcome to MJESHTER FITNESS GYM - Membership Activated');

    //                 // ============================
    //                 // PROFESSIONAL HTML EMAIL
    //                 // ============================
    //                 $message = '
    //                 <div style="font-family: Arial, sans-serif; background:#f4f4f4; padding:30px;">

    //                     <div style="
    //                         max-width:700px;
    //                         margin:auto;
    //                         background:#ffffff;
    //                         border-radius:12px;
    //                         overflow:hidden;
    //                         box-shadow:0 5px 15px rgba(0,0,0,0.08);
    //                     ">

    //                         <!-- HEADER -->
    //                         <div style="
    //                             background:#111827;
    //                             color:#ffffff;
    //                             padding:30px;
    //                             text-align:center;
    //                         ">
    //                             <h1 style="margin:0; font-size:30px;">
    //                                 MJESHTER FITNESS GYM
    //                             </h1>

    //                             <p style="
    //                                 margin-top:10px;
    //                                 font-size:16px;
    //                                 color:#d1d5db;
    //                             ">
    //                                 Membership Payment Successfully Received
    //                             </p>
    //                         </div>

    //                         <!-- BODY -->
    //                         <div style="padding:40px; color:#374151;">

    //                             <h2 style="margin-top:0;">
    //                                 Welcome to the MJESHTER Fitness Community! 💪
    //                             </h2>

    //                             <p style="
    //                                 font-size:15px;
    //                                 line-height:1.8;
    //                             ">
    //                                 Hi <strong>'.$first_name.'</strong>,
    //                             </p>

    //                             <p style="
    //                                 font-size:15px;
    //                                 line-height:1.8;
    //                             ">
    //                                 Thank you for your payment and welcome to 
    //                                 <strong>MJESHTER FITNESS GYM</strong>.
    //                                 We are excited to have you as part of our growing fitness family.
    //                             </p>

    //                             <p style="
    //                                 font-size:15px;
    //                                 line-height:1.8;
    //                             ">
    //                                 Your membership subscription has been successfully activated.
    //                                 Below are your membership details:
    //                             </p>

    //                             <!-- MEMBERSHIP DETAILS -->
    //                             <div style="
    //                                 background:#f9fafb;
    //                                 border:1px solid #e5e7eb;
    //                                 border-radius:10px;
    //                                 padding:25px;
    //                                 margin-top:25px;
    //                             ">

    //                                 <table width="100%" cellpadding="8" cellspacing="0">

    //                                     <tr>
    //                                         <td style="font-weight:bold;">Membership Plan:</td>
    //                                         <td>'.$plan.'</td>
    //                                     </tr>

    //                                     <tr>
    //                                         <td style="font-weight:bold;">Start Date:</td>
    //                                         <td>'.$start_date.'</td>
    //                                     </tr>

    //                                     <tr>
    //                                         <td style="font-weight:bold;">End Date:</td>
    //                                         <td>'.$end_date.'</td>
    //                                     </tr>

    //                                     <tr>
    //                                         <td style="font-weight:bold;">Payment Status:</td>
    //                                         <td style="color:green; font-weight:bold;">
    //                                             PAID
    //                                         </td>
    //                                     </tr>

    //                                 </table>

    //                             </div>

    //                             <!-- WELCOME MESSAGE -->
    //                             <div style="
    //                                 margin-top:30px;
    //                                 padding:20px;
    //                                 background:#eff6ff;
    //                                 border-left:5px solid #2563eb;
    //                                 border-radius:8px;
    //                             ">

    //                                 <p style="
    //                                     margin:0;
    //                                     font-size:15px;
    //                                     line-height:1.7;
    //                                 ">
    //                                     We are committed to helping you achieve your fitness goals.
    //                                     Stay consistent, stay motivated, and let us grow stronger together.
    //                                 </p>

    //                             </div>

    //                             <p style="
    //                                 margin-top:35px;
    //                                 font-size:15px;
    //                                 line-height:1.8;
    //                             ">
    //                                 If you have any questions regarding your membership,
    //                                 feel free to contact our staff anytime.
    //                             </p>

    //                             <p style="
    //                                 margin-top:35px;
    //                                 font-size:15px;
    //                                 line-height:1.8;
    //                             ">
    //                                 Welcome again and see you at the gym!
    //                             </p>

    //                             <p style="
    //                                 margin-top:40px;
    //                                 font-size:15px;
    //                             ">
    //                                 Best Regards,<br>
    //                                 <strong>MJESHTER FITNESS GYM Team</strong>
    //                             </p>

    //                         </div>

    //                         <!-- FOOTER -->
    //                         <div style="
    //                             background:#111827;
    //                             color:#9ca3af;
    //                             text-align:center;
    //                             padding:20px;
    //                             font-size:13px;
    //                         ">
    //                             © '.date('Y').' MJESHTER FITNESS GYM. All Rights Reserved.
    //                         </div>

    //                     </div>

    //                 </div>
    //                 ';

    //                 $email->setMessage($message);

    //                 if ($email->send()) {

    //                     echo 'EMAIL SENT SUCCESSFULLY';

    //                 } else {

    //                     echo $email->printDebugger(['headers']);
    //                 }
                    
    //             }elseif($first_word == 'Walk-In'){
    //                 $walkin_name = explode('-', $item_name)[2];
    //                 $query = $this->db->query("
    //                     INSERT INTO `tbl_walkin_checkin_history`(
    //                         `walkin_name`,
    //                         `checkin_time`,
    //                         `checkout_time`
    //                     )
    //                     VALUES (?, NOW(), DATE_ADD(NOW(), INTERVAL 4 HOUR))", 
    //                     [
    //                         $walkin_name
    //                     ]
    //                 );

    //             }elseif ($first_word == 'Zumba') {
    //                 $zumba_name = explode('-', $item_name)[1];
    //                 $query = $this->db->query("
    //                     INSERT INTO `tbl_zumba_checkin_history`(
    //                         `zumba_name`,
    //                         `checkin_time`,
    //                         `checkout_time`
    //                     )
    //                     VALUES (?, NOW(), DATE_ADD(NOW(), INTERVAL 4 HOUR))", 
    //                     [
    //                         $zumba_name
    //                     ]
    //                 );
    //             }elseif ($first_word == 'Crossfit') {
    //                 $crossfit_name = explode('-', $item_name)[1];
    //                 $query = $this->db->query("
    //                     INSERT INTO `tbl_crossfit_checkin_history`(
    //                         `crossfit_name`,
    //                         `checkin_time`,
    //                         `checkout_time`
    //                     )
    //                     VALUES (?, NOW(), DATE_ADD(NOW(), INTERVAL 4 HOUR))", 
    //                     [
    //                         $crossfit_name
    //                     ]
    //                 );
    //             }elseif ($first_word == 'Yoga') {
    //                 $yoga_name = explode('-', $item_name)[1];
    //                 $query = $this->db->query("
    //                     INSERT INTO `tbl_yoga_checkin_history`(
    //                         `yoga_name`,
    //                         `checkin_time`,
    //                         `checkout_time`
    //                     )
    //                     VALUES (?, NOW(), DATE_ADD(NOW(), INTERVAL 4 HOUR))", 
    //                     [
    //                         $yoga_name
    //                     ]
    //                 );
    //             }else{
    //                 //MATIK ETO YUNG SA INVENTORY
    //                 $query = $this->db->query("
    //                 UPDATE
    //                     `tbl_products`
    //                 SET
    //                     `stock_qty` = `stock_qty` - ?,
    //                     `updated_at` = NOW()
    //                 WHERE product_name = ?
    //                 ", [
    //                     $item_qty, $item_name
    //                 ]);

    //                 $query = $this->db->query("
    //                     SELECT `product_id` FROM `tbl_products` WHERE `product_name` = '$item_name'
    //                 ");
    //                 $rw = $query->getRowArray();
    //                 $product_id = $rw['product_id'];

    //                 $query = $this->db->query("
    //                     INSERT INTO `tbl_inventory_movements`(
    //                         `product_id`,
    //                         `product_name`,
    //                         `movement_type`,
    //                         `quantity`,
    //                         `reference_type`,
    //                         `reference_no`,
    //                         `remarks`,
    //                         `created_by`
    //                     )
    //                     VALUES (?, ?, ?, ?, ?, ?, ?, ?)", 
    //                     [
    //                         $product_id,
    //                         $item_name,
    //                         'OUT',
    //                         -abs($item_qty),
    //                         'POS',
    //                         $cseqn,
    //                         'POS TRANSACTION',
    //                         $this->cuser
    //                     ]
    //                 );
    //             }
     
    //         }
    //     }

    //     //KAHIT ANONG CONDITION KAILANGAN PASOK DITO SA POS SALES TRANSACTION
    //     $query = $this->db->query("
    //         INSERT INTO `tbl_pos_payment`(
    //             `postrxno`,
    //             `payment_method`,
    //             `amount_tendered`,
    //             `change_amount`,
    //             `grand_total`,
    //             `created_by`
    //         )
    //         VALUES (?, ?, ?, ?, ?, ?)", 
    //         [
    //             $cseqn,
    //             $payment_method,
    //             $amount_tendered,
    //             $change_amount,
    //             $grand_total,
    //             $this->cuser
    //         ]
    //     );

    //     if ($query) {
    //         // Echo JavaScript to show the toast and then redirect - EXACTLY like your budget code
    //         echo "
    //         <script>
    //             toastr.success('POS Transaction Saved Successfully!', 'Well Done!', {
    //                 progressBar: true,
    //                 closeButton: true,
    //                 timeOut: 1500,
    //             });
    //             setTimeout(function() {
    //                 window.location.href = 'pos?meaction=MAIN';
    //             }, 1500);
    //         </script>
    //         ";
    //         exit;
    //     } else {
    //         echo "<script>alert('An error occurred while saving.');</script>";
    //         exit;
    //     }
    
    // }

    public function savePOS() { 
        $cartdata = $this->request->getPostGet('cartdata');
        $payment_method = $this->request->getPostGet('payment_method');
        $amount_tendered = $this->request->getPostGet('amount_tendered');
        $change_amount = $this->request->getPostGet('change_amount');
        $grand_total = $this->request->getPostGet('grand_total');

        //MEMBERSHIP VARIABLES
        $member_id = $this->request->getPostGet('member_id');
        $plan = $this->request->getPostGet('plan');
        $membership_start_date = $this->request->getPostGet('membership_start_date');
        $membership_end_date = $this->request->getPostGet('membership_end_date');
        $membership_status = $this->request->getPostGet('membership_status');   

        //WALKING
        $walkin_name = $this->request->getPostGet('walkin_name');
        $email = \Config\Services::email();

        $cseqn =  $this->get_ctr_pos('POS','CTRL_NO01');//TRANSACTION NO

        if (!empty($cartdata)) {
            for($aa = 0; $aa < count($cartdata); $aa++){
                $medata = explode("x|x",$cartdata[$aa]);
                $item_name = $medata[0]; 
                $item_type = $medata[1]; 
                $item_qty = $medata[2]; 
                $item_amount = $medata[3];

                $first_word = explode(' ', $item_name)[0];
                
                $query = $this->db->query("
                    INSERT INTO `tbl_pos_dt`(
                        `postrxno`,
                        `item_name`,
                        `item_type`,
                        `item_qty`,
                        `item_amount`,
                        `created_by`
                    )
                    VALUES (?, ?, ?, ?, ?, ?)", 
                    [
                        $cseqn,
                        $item_name,
                        $item_type,
                        $item_qty,
                        $item_amount,
                        $this->cuser
                    ]
                );

                //KINUKUHA YUNG 1ST WORD NG ITEMS SA CART PARA MA TRANSACT SA INVENTORY, ATTENDANCE O MEMBERSHIP YUNG CART
                if ($first_word == 'Membership') {
                    $query = $this->db->query("
                    UPDATE
                        `tbl_members`
                    SET
                        `membership_plan` = ?,
                        `membership_start_date` = ?,
                        `membership_end_date` = ?,
                        `membership_status` = ?,
                        `updated_at` = NOW()
                    WHERE member_id = ?
                    ", [
                        $plan, $membership_start_date, $membership_end_date, $membership_status, $member_id
                    ]);

                    $queryy = $this->db->query("SELECT rfid_uid,email,membership_start_date,membership_end_date,first_name FROM tbl_members WHERE member_id = ?", [$member_id]);
                    $data = $queryy->getRowArray();
                    $rfid_uid = $data['rfid_uid'];
                    $from = $data['email'];
                    $membership_end_date = $data['membership_end_date'];
                    $membership_start_date = $data['membership_start_date'];
                    $first_name = $data['first_name'];

                    $this->db->query("
                        INSERT INTO tbl_checkin_history
                        (member_id, rfid_uid, checkin_time, checkin_method, status)
                        VALUES (?, ?, NOW(), 'RFID', 'Active')
                    ", [$member_id, $rfid_uid]);

                    $this->db->query("
                        UPDATE tbl_members
                        SET is_loggedin = 1
                        WHERE member_id = ?
                    ", [$member_id]);

                    // IMPORTANT
                    // FORMAT DATES
                    $start_date = date('F d, Y', strtotime($membership_start_date));
                    $end_date   = date('F d, Y', strtotime($membership_end_date));

                    $email->setFrom(
                        'kylealino@gmail.com',
                        'MJESHTER FITNESS GYM'
                    );

                    $email->setTo($from);

                    $email->setSubject('Welcome to MJESHTER FITNESS GYM - Membership Activated');

                    // ============================
                    // PROFESSIONAL HTML EMAIL
                    // ============================
                    $message = '
                    <div style="font-family: Arial, sans-serif; background:#f4f4f4; padding:30px;">

                        <div style="
                            max-width:700px;
                            margin:auto;
                            background:#ffffff;
                            border-radius:12px;
                            overflow:hidden;
                            box-shadow:0 5px 15px rgba(0,0,0,0.08);
                        ">

                            <!-- HEADER -->
                            <div style="
                                background:#111827;
                                color:#ffffff;
                                padding:30px;
                                text-align:center;
                            ">
                                <h1 style="margin:0; font-size:30px;">
                                    MJESHTER FITNESS GYM
                                </h1>

                                <p style="
                                    margin-top:10px;
                                    font-size:16px;
                                    color:#d1d5db;
                                ">
                                    Membership Payment Successfully Received
                                </p>
                            </div>

                            <!-- BODY -->
                            <div style="padding:40px; color:#374151;">

                                <h2 style="margin-top:0;">
                                    Welcome to the MJESHTER Fitness Community! 💪
                                </h2>

                                <p style="
                                    font-size:15px;
                                    line-height:1.8;
                                ">
                                    Hi <strong>'.$first_name.'</strong>,
                                </p>

                                <p style="
                                    font-size:15px;
                                    line-height:1.8;
                                ">
                                    Thank you for your payment and welcome to 
                                    <strong>MJESHTER FITNESS GYM</strong>.
                                    We are excited to have you as part of our growing fitness family.
                                </p>

                                <p style="
                                    font-size:15px;
                                    line-height:1.8;
                                ">
                                    Your membership subscription has been successfully activated.
                                    Below are your membership details:
                                </p>

                                <!-- MEMBERSHIP DETAILS -->
                                <div style="
                                    background:#f9fafb;
                                    border:1px solid #e5e7eb;
                                    border-radius:10px;
                                    padding:25px;
                                    margin-top:25px;
                                ">

                                    <table width="100%" cellpadding="8" cellspacing="0">

                                        <tr>
                                            <td style="font-weight:bold;">Membership Plan:</td>
                                            <td>'.$plan.'</td>
                                        </tr>

                                        <tr>
                                            <td style="font-weight:bold;">Start Date:</td>
                                            <td>'.$start_date.'</td>
                                        </tr>

                                        <tr>
                                            <td style="font-weight:bold;">End Date:</td>
                                            <td>'.$end_date.'</td>
                                        </tr>

                                        <tr>
                                            <td style="font-weight:bold;">Payment Status:</td>
                                            <td style="color:green; font-weight:bold;">
                                                PAID
                                            </td>
                                        </tr>

                                    </table>

                                </div>

                                <!-- WELCOME MESSAGE -->
                                <div style="
                                    margin-top:30px;
                                    padding:20px;
                                    background:#eff6ff;
                                    border-left:5px solid #2563eb;
                                    border-radius:8px;
                                ">

                                    <p style="
                                        margin:0;
                                        font-size:15px;
                                        line-height:1.7;
                                    ">
                                        We are committed to helping you achieve your fitness goals.
                                        Stay consistent, stay motivated, and let us grow stronger together.
                                    </p>

                                </div>

                                <p style="
                                    margin-top:35px;
                                    font-size:15px;
                                    line-height:1.8;
                                ">
                                    If you have any questions regarding your membership,
                                    feel free to contact our staff anytime.
                                </p>

                                <p style="
                                    margin-top:35px;
                                    font-size:15px;
                                    line-height:1.8;
                                ">
                                    Welcome again and see you at the gym!
                                </p>

                                <p style="
                                    margin-top:40px;
                                    font-size:15px;
                                ">
                                    Best Regards,<br>
                                    <strong>MJESHTER FITNESS GYM Team</strong>
                                </p>

                            </div>

                            <!-- FOOTER -->
                            <div style="
                                background:#111827;
                                color:#9ca3af;
                                text-align:center;
                                padding:20px;
                                font-size:13px;
                            ">
                                © '.date('Y').' MJESHTER FITNESS GYM. All Rights Reserved.
                            </div>

                        </div>

                    </div>
                    ';

                    $email->setMessage($message);

                    if ($email->send()) {

                        echo 'EMAIL SENT SUCCESSFULLY';

                    } else {

                        echo $email->printDebugger(['headers']);
                    }
                    
                } elseif($first_word == 'Walk-In'){
                    $walkin_name = explode('-', $item_name)[2];
                    $query = $this->db->query("
                        INSERT INTO `tbl_walkin_checkin_history`(
                            `walkin_name`,
                            `checkin_time`,
                            `checkout_time`
                        )
                        VALUES (?, NOW(), DATE_ADD(NOW(), INTERVAL 4 HOUR))", 
                        [
                            $walkin_name
                        ]
                    );

                } elseif ($first_word == 'Zumba') {
                    $zumba_name = explode('-', $item_name)[1];
                    $query = $this->db->query("
                        INSERT INTO `tbl_zumba_checkin_history`(
                            `zumba_name`,
                            `checkin_time`,
                            `checkout_time`
                        )
                        VALUES (?, NOW(), DATE_ADD(NOW(), INTERVAL 4 HOUR))", 
                        [
                            $zumba_name
                        ]
                    );
                } elseif ($first_word == 'Crossfit') {
                    $crossfit_name = explode('-', $item_name)[1];
                    $query = $this->db->query("
                        INSERT INTO `tbl_crossfit_checkin_history`(
                            `crossfit_name`,
                            `checkin_time`,
                            `checkout_time`
                        )
                        VALUES (?, NOW(), DATE_ADD(NOW(), INTERVAL 4 HOUR))", 
                        [
                            $crossfit_name
                        ]
                    );
                } elseif ($first_word == 'Yoga') {
                    $yoga_name = explode('-', $item_name)[1];
                    $query = $this->db->query("
                        INSERT INTO `tbl_yoga_checkin_history`(
                            `yoga_name`,
                            `checkin_time`,
                            `checkout_time`
                        )
                        VALUES (?, NOW(), DATE_ADD(NOW(), INTERVAL 4 HOUR))", 
                        [
                            $yoga_name
                        ]
                    );
                } else {
                    //MATIK ETO YUNG SA INVENTORY
                    $query = $this->db->query("
                    UPDATE
                        `tbl_products`
                    SET
                        `stock_qty` = `stock_qty` - ?,
                        `updated_at` = NOW()
                    WHERE product_name = ?
                    ", [
                        $item_qty, $item_name
                    ]);

                    $query = $this->db->query("
                        SELECT `product_id` FROM `tbl_products` WHERE `product_name` = '$item_name'
                    ");
                    $rw = $query->getRowArray();
                    $product_id = $rw['product_id'];

                    $query = $this->db->query("
                        INSERT INTO `tbl_inventory_movements`(
                            `product_id`,
                            `product_name`,
                            `movement_type`,
                            `quantity`,
                            `reference_type`,
                            `reference_no`,
                            `remarks`,
                            `created_by`
                        )
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?)", 
                        [
                            $product_id,
                            $item_name,
                            'OUT',
                            -abs($item_qty),
                            'POS',
                            $cseqn,
                            'POS TRANSACTION',
                            $this->cuser
                        ]
                    );
                }
            }
        }

        //KAHIT ANONG CONDITION KAILANGAN PASOK DITO SA POS SALES TRANSACTION
        $query = $this->db->query("
            INSERT INTO `tbl_pos_payment`(
                `postrxno`,
                `payment_method`,
                `amount_tendered`,
                `change_amount`,
                `grand_total`,
                `created_by`
            )
            VALUES (?, ?, ?, ?, ?, ?)", 
            [
                $cseqn,
                $payment_method,
                $amount_tendered,
                $change_amount,
                $grand_total,
                $this->cuser
            ]
        );

        // ==============================
        // INSERT INTO CASH RECEIPTS JOURNAL
        // ==============================
        // Determine account code based on items in cart
        $account_code = '4020-RETAIL'; // Default for retail
        
        // Check each item to determine account code
        foreach($cartdata as $item) {
            $medata = explode("x|x", $item);
            $item_name = $medata[0];
            $first_word = explode(' ', $item_name)[0];
            
            if ($first_word == 'Membership') {
                $account_code = '4010-MEMBERSHIP';
                break;
            } elseif ($first_word == 'Walk-In') {
                $account_code = '4030-WALKIN';
            } elseif ($first_word == 'Zumba') {
                $account_code = '4060-ZUMBA';
            } elseif ($first_word == 'Crossfit') {
                $account_code = '4040-CROSSFIT';
            } elseif ($first_word == 'Yoga') {
                $account_code = '4050-YOGA';
            }
        }

        // Insert into cash receipts journal
        $this->db->query("
            INSERT INTO `tbl_cash_receipts_journal`(
                `date`,
                `transaction_id`,
                `journal_id`,
                `account_code`,
                `amount`,
                `created_by`,
                `created_at`
            )
            VALUES (?, ?, NULL, ?, ?, ?, NOW())", 
            [
                date('Y-m-d'),
                $cseqn,
                $account_code,
                $grand_total,
                $this->cuser
            ]
        );

        if ($query) {
            // Echo JavaScript to show the toast and then redirect - EXACTLY like your budget code
            echo "
            <script>
                toastr.success('POS Transaction Saved Successfully!', 'Well Done!', {
                    progressBar: true,
                    closeButton: true,
                    timeOut: 1500,
                });
                setTimeout(function() {
                    window.location.href = 'pos?meaction=MAIN';
                }, 1500);
            </script>
            ";
            exit;
        } else {
            echo "<script>alert('An error occurred while saving.');</script>";
            exit;
        }
    }

    public function get_ctr_pos($tag,$mfld='') { 
		$accessquery = $this->db->query("
		CREATE TABLE if not exists `myctr_pos` (
		  `CTR_YEAR` varchar(4) DEFAULT '0000',
		  `CTR_MONTH` varchar(2) DEFAULT '00',
		  `CTR_DAY` varchar(2) DEFAULT '00',
		  `CTRL_NO01` varchar(15) DEFAULT '000',
		  `CTRL_NO02` varchar(15) DEFAULT '00000000',
		  `CTRL_NO03` varchar(15) DEFAULT '00000000',
		  `CTRL_NO04` varchar(15) DEFAULT '00000000',
		  `CTRL_NO05` varchar(15) DEFAULT '00000000',
		  `CTRL_NO06` varchar(15) DEFAULT '00000000',
		  `CTRL_NO07` varchar(15) DEFAULT '00000000',
		  `CTRL_NO08` varchar(15) DEFAULT '00000000',
		  `CTRL_NO09` varchar(15) DEFAULT '00000000',
		  `CTRL_NO10` varchar(15) DEFAULT '00000000',
		  `CTRL_NO11` varchar(15) DEFAULT '00000000',
		  `SS_CTR` varchar(15) DEFAULT '000000',
		  UNIQUE KEY `ctr01` (`CTR_YEAR`,`CTR_MONTH`,`CTR_DAY`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		");

		$xfield = (empty($mfld) ? 'CTRL_NO01' : $mfld);
		
		$q = $this->db->query("select date(now()) XSYSDATE");
		$rdate = $q->getRowArray();
		$xsysdate = $rdate['XSYSDATE'];
		$xsysdate_exp = explode('-', $xsysdate);
		$xsysyear =  $xsysdate_exp[0];
		$xsysmonth = $xsysdate_exp[1];
		$xsysday = $xsysdate_exp[2];
		
		$qctr = $this->db->query("select {$xfield} from myctr_pos WHERE CTR_YEAR = '$xsysyear' AND CTR_MONTH = '$xsysmonth' AND CTR_DAY = '$xsysday'  limit 1");
		if($qctr->getNumRows() == 0) {
			$xnumb = '001';
			$query = $this->db->query( "insert into myctr_pos (CTR_YEAR,CTR_MONTH,CTR_DAY,{$xfield}) values('$xsysyear','$xsysmonth','$xsysday','$xnumb')");
			$qctr->freeResult();
		} else {
			$qctr->freeResult();
			$qctr = $this->db->query( "select {$xfield} MYFIELD from myctr_pos WHERE CTR_YEAR = '$xsysyear' AND CTR_MONTH = '$xsysmonth' AND CTR_DAY = '$xsysday' limit 1");
			$rctr = $qctr->getRowArray();
			if(trim($rctr['MYFIELD'],' ') == '') { 
				$xnumb = '001';
			} else {
				$xnumb = $rctr['MYFIELD'];
				$qctr = $this->db->query("select ('{$xnumb}' + 1) XNUMB");
				$rctr = $qctr->getRowArray();
				$xnumb = trim($rctr['XNUMB'],' ');
				$xnumb = str_pad($xnumb + 0,3,"0",STR_PAD_LEFT);
				$query = $this->db->query("update myctr_pos set {$xfield} = '{$xnumb}'");
			}
		}
		return  $tag . $xsysmonth . $xsysday . $xsysyear. $xnumb;//.$supp
	} 
}