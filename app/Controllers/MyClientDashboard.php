<?php

namespace App\Controllers;

class MyClientDashboard extends BaseController
{
    protected $request;
    protected $db;

    public function __construct()
    {
        $this->request = \Config\Services::request();
        $this->db      = \Config\Database::connect();
    }

    public function index()
    {
        // =========================
        // AJAX RFID VALIDATION
        // =========================
        if ($this->request->getPost('meaction') === 'VALIDATE-RFID') {
            return $this->validateRFID();
        }

        // =========================
        // PROCESS CHECK-IN
        // =========================
        if ($this->request->getPost('meaction') === 'PROCESS-CHECKIN') {
            return $this->processCheckin();
        }

        // =========================
        // AUTO LOGOUT / MANUAL LOGOUT
        // =========================
        if ($this->request->getPost('meaction') === 'AUTO-LOGOUT' ||
            $this->request->getGet('meaction') === 'LOGOUT') {
            return redirect()->to('myclientlogin');
        }

        // =========================
        // SHOW DASHBOARD AFTER REDIRECT
        // =========================
        $rfid_uid = $this->request->getGet('rfid_uid');

        if (!empty($rfid_uid)) {
            return $this->showDashboard($rfid_uid);
        }

        // =========================
        // DEFAULT: SCANNER PAGE
        // =========================
        return view('MyClientDashboard', [
            'showDashboard' => false
        ]);
    }

    /**
     * =========================
     * SHOW DASHBOARD
     * =========================
     */
    private function showDashboard($rfid_uid)
    {
        $rfid_uid = trim((string)$rfid_uid);

        $query = $this->db->query("
            SELECT *
            FROM tbl_members
            WHERE CAST(rfid_uid AS CHAR) = ?
            LIMIT 1
        ", [$rfid_uid]);

        $member = $query->getRowArray();

        if (!$member) {
            return redirect()->to('myclientdashboard');
        }

        // ❌ not active
        if ($member['membership_status'] !== 'Active') {
            return redirect()->to('myclientdashboard');
        }

        // ❌ expired
        if (!empty($member['membership_end_date']) &&
            strtotime($member['membership_end_date']) < time()) {
            return redirect()->to('myclientdashboard');
        }

        // =========================
        // TODAY CHECK-IN
        // =========================
        $checkinQuery = $this->db->query("
            SELECT *
            FROM tbl_checkin_history
            WHERE member_id = ?
            AND DATE(checkin_time) = CURDATE()
            AND status = 'Active'
            ORDER BY checkin_id DESC
            LIMIT 1
        ", [$member['member_id']]);

        $checkin = $checkinQuery->getRowArray();

        $checkin_time = $checkin
            ? date('h:i A', strtotime($checkin['checkin_time']))
            : 'No check-in yet';

        $hasActiveSession = $checkin ? true : false;

        // =========================
        // YEARLY CHECKINS
        // =========================
        $selectedYear = date('Y');

        $yearlyQuery = $this->db->query("
            SELECT COUNT(*) as total
            FROM tbl_checkin_history
            WHERE member_id = ?
            AND YEAR(checkin_time) = ?
        ", [$member['member_id'], $selectedYear]);

        $yearlyCheckins = $yearlyQuery->getRow()->total ?? 0;

        // =========================
        // RECENT CHECKINS
        // =========================
        $recentQuery = $this->db->query("
            SELECT 
                DATE(checkin_time) as date,
                TIME(checkin_time) as time_in,
                TIME(checkout_time) as time_out
            FROM tbl_checkin_history
            WHERE member_id = ?
            ORDER BY checkin_time DESC
            LIMIT 5
        ", [$member['member_id']]);

        $recentRaw = $recentQuery->getResultArray();

        $recentCheckins = [];

        foreach ($recentRaw as $row) {
            $timeIn = $row['time_in'] ? date('h:i A', strtotime($row['time_in'])) : '-';
            $timeOut = $row['time_out'] ? date('h:i A', strtotime($row['time_out'])) : '-';

            $duration = '-';
            if ($row['time_in'] && $row['time_out']) {
                $duration = round((strtotime($row['time_out']) - strtotime($row['time_in'])) / 60) . ' mins';
            }

            $recentCheckins[] = [
                'date' => $row['date'],
                'time_in' => $timeIn,
                'time_out' => $timeOut,
                'duration' => $duration
            ];
        }

        // =========================
        // LOAD VIEW
        // =========================
        return view('MyClientDashboard', [
            'showDashboard'     => true, // ✅ IMPORTANT FIX
            'member'            => $member,
            'checkin_time'      => $checkin_time,
            'rfid_uid'          => $rfid_uid,
            'yearlyCheckins'    => $yearlyCheckins,
            'selectedYear'      => $selectedYear,
            'recentCheckins'    => $recentCheckins,
            'hasActiveSession'  => $hasActiveSession
        ]);
    }

    /**
     * =========================
     * VALIDATE RFID (AJAX)
     * =========================
     */
private function validateRFID()
{
    $rfid_uid = trim((string)$this->request->getPost('rfid_uid'));

    if (empty($rfid_uid)) {
        return $this->response->setJSON([
            'status' => 'denied',
            'message' => 'No RFID detected'
        ]);
    }

    // =========================
    // GET MEMBER
    // =========================
    $member = $this->db->query("
        SELECT 
            member_id,
            CONCAT(first_name, ' ', last_name) as member_name,
            membership_status,
            membership_end_date,
            is_loggedin
        FROM tbl_members
        WHERE CAST(rfid_uid AS CHAR) = ?
        LIMIT 1
    ", [$rfid_uid])->getRowArray();

    if (!$member) {
        return $this->response->setJSON([
            'status' => 'denied',
            'message' => 'RFID not registered'
        ]);
    }

    if ($member['membership_status'] !== 'Active') {
        return $this->response->setJSON([
            'status' => 'denied',
            'message' => 'Membership not active'
        ]);
    }

    if (!empty($member['membership_end_date']) &&
        strtotime($member['membership_end_date']) < time()) {
        return $this->response->setJSON([
            'status' => 'denied',
            'message' => 'Membership expired'
        ]);
    }

    // =========================
    // 🔴 CHECKOUT FLOW (SECOND TAP)
    // =========================
    if ($member['is_loggedin'] == 1) {

        // update session
        $session = $this->db->query("
            SELECT checkin_id
            FROM tbl_checkin_history
            WHERE member_id = ?
            AND status = 'Active'
            ORDER BY checkin_id DESC
            LIMIT 1
        ", [$member['member_id']])->getRowArray();

        if ($session) {
            $this->db->query("
                UPDATE tbl_checkin_history
                SET checkout_time = NOW(),
                    status = 'Completed'
                WHERE checkin_id = ?
            ", [$session['checkin_id']]);
        }

        // set logged out
        $this->db->query("
            UPDATE tbl_members
            SET is_loggedin = 0
            WHERE member_id = ?
        ", [$member['member_id']]);

        return $this->response->setJSON([
            'status' => 'checkout',
            'message' => 'Checkout successful. See you again!'
        ]);
    }

    // =========================
    // 🟢 CHECK-IN FLOW (FIRST TAP)
    // =========================
    return $this->response->setJSON([
        'status' => 'allowed',
        'member_name' => $member['member_name']
    ]);
}

    /**
     * =========================
     * PROCESS CHECK-IN
     * =========================
     */
private function processCheckin()
{
    $rfid_uid = trim((string)$this->request->getPost('rfid_uid'));

    if (empty($rfid_uid)) {
        return redirect()->to('myclientdashboard');
    }

    $member = $this->db->query("
        SELECT member_id
        FROM tbl_members
        WHERE CAST(rfid_uid AS CHAR) = ?
        LIMIT 1
    ", [$rfid_uid])->getRowArray();

    if (!$member) {
        return redirect()->to('myclientlogin');
    }

    $member_id = $member['member_id'];

    // =========================
    // INSERT CHECK-IN ONLY
    // =========================
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

    return redirect()->to('myclientdashboard?rfid_uid=' . urlencode($rfid_uid));
}
}