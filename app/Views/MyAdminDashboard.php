<?php
// =============================================
// MJESHTER FITNESS GYM - REAL-TIME DASHBOARD
// BASED ON ACTUAL DATABASE SCHEMA
// =============================================

$this->request = \Config\Services::request();
$this->db = \Config\Database::connect();
$this->session = session();
$this->cuser = $this->session->get('__xsys_myuserzicas__');

// Get current user info
$query = $this->db->query("
    SELECT 
        `full_name`, 
        `division`,
        `section`, 
        `position`,
        `username`
    FROM `myua_user` 
    WHERE `username` = '$this->cuser'
");
$data = $query->getRowArray();
$full_name = $data['full_name'] ?? 'User';
$position = $data['position'] ?? '';
$section = $data['section'] ?? '';
$division = $data['division'] ?? '';

// =============================================
// REVENUE & FINANCIAL STATS
// =============================================

// Monthly Revenue
$monthlyRevenueQuery = $this->db->query("
    SELECT COALESCE(SUM(grand_total), 0) as total 
    FROM tbl_pos_payment 
    WHERE MONTH(created_at) = MONTH(CURRENT_DATE()) 
    AND YEAR(created_at) = YEAR(CURRENT_DATE())
");
$monthlyRevenue = $monthlyRevenueQuery->getRow()->total ?? 0;

// Monthly Revenue Target
$monthlyRevenueTarget = 400000;
$monthlyRevenuePercent = $monthlyRevenueTarget > 0 ? round(($monthlyRevenue / $monthlyRevenueTarget) * 100) : 0;

// Previous Month Revenue
$prevMonthRevenueQuery = $this->db->query("
    SELECT COALESCE(SUM(grand_total), 0) as total 
    FROM tbl_pos_payment 
    WHERE MONTH(created_at) = MONTH(CURRENT_DATE() - INTERVAL 1 MONTH)
    AND YEAR(created_at) = YEAR(CURRENT_DATE() - INTERVAL 1 MONTH)
");
$prevMonthRevenue = $prevMonthRevenueQuery->getRow()->total ?? 0;
$monthlyRevenueGrowth = $prevMonthRevenue > 0 ? round((($monthlyRevenue - $prevMonthRevenue) / $prevMonthRevenue) * 100, 1) : 0;

// Year-to-Date Revenue
$ytdRevenueQuery = $this->db->query("
    SELECT COALESCE(SUM(grand_total), 0) as total 
    FROM tbl_pos_payment 
    WHERE YEAR(created_at) = YEAR(CURRENT_DATE())
");
$ytdRevenue = $ytdRevenueQuery->getRow()->total ?? 0;

// Last Year YTD Revenue
$ytdLastYearQuery = $this->db->query("
    SELECT COALESCE(SUM(grand_total), 0) as total 
    FROM tbl_pos_payment 
    WHERE YEAR(created_at) = YEAR(CURRENT_DATE() - INTERVAL 1 YEAR)
");
$ytdRevenueLastYear = $ytdLastYearQuery->getRow()->total ?? 0;
$ytdGrowth = $ytdRevenueLastYear > 0 ? round((($ytdRevenue - $ytdRevenueLastYear) / $ytdRevenueLastYear) * 100, 1) : 0;

// Pending Payments
$pendingPaymentsQuery = $this->db->query("
    SELECT COALESCE(SUM(grand_total), 0) as total 
    FROM tbl_pos_payment 
    WHERE payment_method = 'Pending'
");
$pendingPayments = $pendingPaymentsQuery->getRow()->total ?? 0;

$collectedPayments = $monthlyRevenue - $pendingPayments;
$collectionRate = $monthlyRevenue > 0 ? round(($collectedPayments / $monthlyRevenue) * 100) : 0;

// Average Ticket Size
$avgTicketQuery = $this->db->query("
    SELECT COALESCE(AVG(grand_total), 0) as avg_ticket 
    FROM tbl_pos_payment 
    WHERE MONTH(created_at) = MONTH(CURRENT_DATE())
");
$averageTicketSize = round($avgTicketQuery->getRow()->avg_ticket ?? 0);

// Active Subscriptions
$activeSubscriptionsQuery = $this->db->query("
    SELECT COUNT(*) as total 
    FROM tbl_members 
    WHERE membership_status = 'Active'
");
$activeSubscriptions = $activeSubscriptionsQuery->getRow()->total ?? 0;

// =============================================
// MEMBERSHIP & ATTENDANCE STATS
// =============================================

$totalMembersQuery = $this->db->query("SELECT COUNT(*) as total FROM tbl_members");
$totalMembers = $totalMembersQuery->getRow()->total ?? 0;

$newMembersThisMonthQuery = $this->db->query("
    SELECT COUNT(*) as total 
    FROM tbl_members 
    WHERE MONTH(created_at) = MONTH(CURRENT_DATE()) 
    AND YEAR(created_at) = YEAR(CURRENT_DATE())
");
$newMembersThisMonth = $newMembersThisMonthQuery->getRow()->total ?? 0;

$todayCheckinsQuery = $this->db->query("

    SELECT (

        -- MEMBERS
        (SELECT COUNT(*) 
        FROM tbl_checkin_history
        WHERE DATE(checkin_time) = CURDATE())

        +

        -- WALKIN
        (SELECT COUNT(*) 
        FROM tbl_walkin_checkin_history
        WHERE DATE(checkin_time) = CURDATE())

        +

        -- ZUMBA
        (SELECT COUNT(*) 
        FROM tbl_zumba_checkin_history
        WHERE DATE(checkin_time) = CURDATE())

        +

        -- YOGA
        (SELECT COUNT(*) 
        FROM tbl_yoga_checkin_history
        WHERE DATE(checkin_time) = CURDATE())

        +

        -- CROSSFIT
        (SELECT COUNT(*) 
        FROM tbl_crossfit_checkin_history
        WHERE DATE(checkin_time) = CURDATE())

    ) as total

");

$todayCheckins = $todayCheckinsQuery->getRow()->total ?? 0;


// =============================================
// TODAY CHECKOUTS
// =============================================

$todayCheckoutsQuery = $this->db->query("

    SELECT (

        -- MEMBERS
        (SELECT COUNT(*) 
        FROM tbl_checkin_history
        WHERE DATE(checkout_time) = CURDATE()
        AND checkout_time IS NOT NULL)

        +

        -- WALKIN
        (SELECT COUNT(*) 
        FROM tbl_walkin_checkin_history
        WHERE DATE(checkout_time) = CURDATE()
        AND checkout_time IS NOT NULL)

        +

        -- ZUMBA
        (SELECT COUNT(*) 
        FROM tbl_zumba_checkin_history
        WHERE DATE(checkout_time) = CURDATE()
        AND checkout_time IS NOT NULL)

        +

        -- YOGA
        (SELECT COUNT(*) 
        FROM tbl_yoga_checkin_history
        WHERE DATE(checkout_time) = CURDATE()
        AND checkout_time IS NOT NULL)

        +

        -- CROSSFIT
        (SELECT COUNT(*) 
        FROM tbl_crossfit_checkin_history
        WHERE DATE(checkout_time) = CURDATE()
        AND checkout_time IS NOT NULL)

    ) as total

");

$todayCheckouts = $todayCheckoutsQuery->getRow()->total ?? 0;

$currentlyInGymQuery = $this->db->query("

    SELECT (
    
        -- MEMBERS
        (SELECT COUNT(*) 
        FROM tbl_checkin_history
        WHERE DATE(checkin_time) = CURDATE()
        AND checkout_time IS NULL)

        +

        -- WALKIN
        (SELECT COUNT(*) 
        FROM tbl_walkin_checkin_history
        WHERE DATE(checkin_time) = CURDATE()
        AND checkout_time IS NULL)

        +

        -- ZUMBA
        (SELECT COUNT(*) 
        FROM tbl_zumba_checkin_history
        WHERE DATE(checkin_time) = CURDATE()
        AND checkout_time IS NULL)

        +

        -- YOGA
        (SELECT COUNT(*) 
        FROM tbl_yoga_checkin_history
        WHERE DATE(checkin_time) = CURDATE()
        AND checkout_time IS NULL)

        +

        -- CROSSFIT
        (SELECT COUNT(*) 
        FROM tbl_crossfit_checkin_history
        WHERE DATE(checkin_time) = CURDATE()
        AND checkout_time IS NULL)

    ) as total

");

$currentlyInGym = $currentlyInGymQuery->getRow()->total ?? 0;

// =============================================
// URGENT / EXPIRING MEMBERSHIPS
// =============================================

$expiringTodayQuery = $this->db->query("
    SELECT COUNT(*) as total 
    FROM tbl_members 
    WHERE membership_end_date = CURRENT_DATE() 
    AND membership_status = 'Active'
");
$expiringToday = $expiringTodayQuery->getRow()->total ?? 0;

$expiringThisWeekQuery = $this->db->query("
    SELECT COUNT(*) as total 
    FROM tbl_members 
    WHERE membership_end_date BETWEEN CURRENT_DATE() AND DATE_ADD(CURRENT_DATE(), INTERVAL 7 DAY)
    AND membership_end_date > CURRENT_DATE()
    AND membership_status = 'Active'
");
$expiringThisWeek = $expiringThisWeekQuery->getRow()->total ?? 0;

$expiredMembersQuery = $this->db->query("
    SELECT COUNT(*) as total 
    FROM tbl_members 
    WHERE membership_end_date < CURRENT_DATE() 
    AND membership_status = 'Expired'
");
$expiredMembers = $expiredMembersQuery->getRow()->total ?? 0;

$activeTrainersQuery = $this->db->query("
    SELECT COUNT(*) as total 
    FROM myua_user 
    WHERE cert_tag = 1 AND is_active = 1
");
$activeTrainers = $activeTrainersQuery->getRow()->total ?? 0;

// =============================================
// MEMBERSHIP PLAN DISTRIBUTION
// =============================================

$membershipMonthlyQuery = $this->db->query("
    SELECT COUNT(*) as total 
    FROM tbl_members 
    WHERE membership_plan = '1 Month' AND membership_status = 'Active'
");
$membershipMonthly = $membershipMonthlyQuery->getRow()->total ?? 0;

$membershipQuarterlyQuery = $this->db->query("
    SELECT COUNT(*) as total 
    FROM tbl_members 
    WHERE membership_plan = '3 Months' AND membership_status = 'Active'
");
$membershipQuarterly = $membershipQuarterlyQuery->getRow()->total ?? 0;

$membershipAnnualQuery = $this->db->query("
    SELECT COUNT(*) as total 
    FROM tbl_members 
    WHERE membership_plan = '6 Months' AND membership_status = 'Active'
");
$membershipAnnual = $membershipAnnualQuery->getRow()->total ?? 0;

$membershipDayPassQuery = $this->db->query("
    SELECT COUNT(*) as total 
    FROM tbl_members 
    WHERE membership_plan = 'Day Pass' AND membership_status = 'Active'
");
$membershipDayPass = $membershipDayPassQuery->getRow()->total ?? 0;

// =============================================
// MONTHLY REVENUE TREND (Last 6 Months)
// =============================================

$revenueTrend = [];
for ($i = 5; $i >= 0; $i--) {
    $month = date('n', strtotime("-$i months"));
    $year = date('Y', strtotime("-$i months"));
    $monthName = date('M', strtotime("-$i months"));
    
    $trendQuery = $this->db->query("
        SELECT COALESCE(SUM(grand_total), 0) as total 
        FROM tbl_pos_payment 
        WHERE MONTH(created_at) = $month AND YEAR(created_at) = $year
    ");
    $revenueTrend[] = ['month' => $monthName, 'revenue' => round(($trendQuery->getRow()->total ?? 0) / 1000, 1)];
}

// =============================================
// RECENT CHECK-INS (TODAY ONLY)
// =============================================

$recentCheckinsQuery = $this->db->query("

    SELECT * FROM (

        -- MEMBERS
        SELECT 
            CONCAT(m.first_name, ' ', m.last_name) as name,
            DATE_FORMAT(ch.checkin_time, '%M %d, %Y %h:%i %p') as datetime_display,
            DATE_FORMAT(ch.checkin_time, '%h:%i %p') as time,
            DATE(ch.checkin_time) as checkin_date,
            ch.member_id,
            'Member' as type,
            ch.checkin_time as raw_time
        FROM tbl_checkin_history ch
        LEFT JOIN tbl_members m ON m.member_id = ch.member_id
        WHERE DATE(ch.checkin_time) = CURDATE()

        UNION ALL

        -- WALKIN
        SELECT
            walkin_name as name,
            DATE_FORMAT(checkin_time, '%M %d, %Y %h:%i %p') as datetime_display,
            DATE_FORMAT(checkin_time, '%h:%i %p') as time,
            DATE(checkin_time) as checkin_date,
            NULL as member_id,
            'Walk-in' as type,
            checkin_time as raw_time
        FROM tbl_walkin_checkin_history
        WHERE DATE(checkin_time) = CURDATE()

        UNION ALL

        -- ZUMBA
        SELECT
            zumba_name as name,
            DATE_FORMAT(checkin_time, '%M %d, %Y %h:%i %p') as datetime_display,
            DATE_FORMAT(checkin_time, '%h:%i %p') as time,
            DATE(checkin_time) as checkin_date,
            NULL as member_id,
            'Zumba' as type,
            checkin_time as raw_time
        FROM tbl_zumba_checkin_history
        WHERE DATE(checkin_time) = CURDATE()

        UNION ALL

        -- YOGA
        SELECT
            yoga_name as name,
            DATE_FORMAT(checkin_time, '%M %d, %Y %h:%i %p') as datetime_display,
            DATE_FORMAT(checkin_time, '%h:%i %p') as time,
            DATE(checkin_time) as checkin_date,
            NULL as member_id,
            'Yoga' as type,
            checkin_time as raw_time
        FROM tbl_yoga_checkin_history
        WHERE DATE(checkin_time) = CURDATE()

        UNION ALL

        -- CROSSFIT
        SELECT
            crossfit_name as name,
            DATE_FORMAT(checkin_time, '%M %d, %Y %h:%i %p') as datetime_display,
            DATE_FORMAT(checkin_time, '%h:%i %p') as time,
            DATE(checkin_time) as checkin_date,
            NULL as member_id,
            'Crossfit' as type,
            checkin_time as raw_time
        FROM tbl_crossfit_checkin_history
        WHERE DATE(checkin_time) = CURDATE()

    ) x

    ORDER BY raw_time DESC
    LIMIT 5

");

$recentCheckins = $recentCheckinsQuery->getResultArray();


// =============================================
// RECENT CHECK-OUTS (TODAY ONLY)
// =============================================

$recentCheckoutsQuery = $this->db->query("

    SELECT * FROM (

        -- MEMBERS
        SELECT 
            CONCAT(m.first_name, ' ', m.last_name) as name,
            DATE_FORMAT(ch.checkout_time, '%M %d, %Y %h:%i %p') as datetime_display,
            DATE_FORMAT(ch.checkout_time, '%h:%i %p') as time,
            DATE(ch.checkout_time) as checkout_date,
            TIMESTAMPDIFF(MINUTE, ch.checkin_time, ch.checkout_time) as duration_minutes,
            'Member' as type,
            ch.checkout_time as raw_time
        FROM tbl_checkin_history ch
        LEFT JOIN tbl_members m ON m.member_id = ch.member_id
        WHERE DATE(ch.checkout_time) = CURDATE()
        AND ch.checkout_time IS NOT NULL

        UNION ALL

        -- WALKIN
        SELECT
            walkin_name as name,
            DATE_FORMAT(checkout_time, '%M %d, %Y %h:%i %p') as datetime_display,
            DATE_FORMAT(checkout_time, '%h:%i %p') as time,
            DATE(checkout_time) as checkout_date,
            TIMESTAMPDIFF(MINUTE, checkin_time, checkout_time) as duration_minutes,
            'Walk-in' as type,
            checkout_time as raw_time
        FROM tbl_walkin_checkin_history
        WHERE DATE(checkout_time) = CURDATE()
        AND checkout_time IS NOT NULL

        UNION ALL

        -- ZUMBA
        SELECT
            zumba_name as name,
            DATE_FORMAT(checkout_time, '%M %d, %Y %h:%i %p') as datetime_display,
            DATE_FORMAT(checkout_time, '%h:%i %p') as time,
            DATE(checkout_time) as checkout_date,
            TIMESTAMPDIFF(MINUTE, checkin_time, checkout_time) as duration_minutes,
            'Zumba' as type,
            checkout_time as raw_time
        FROM tbl_zumba_checkin_history
        WHERE DATE(checkout_time) = CURDATE()
        AND checkout_time IS NOT NULL

        UNION ALL

        -- YOGA
        SELECT
            yoga_name as name,
            DATE_FORMAT(checkout_time, '%M %d, %Y %h:%i %p') as datetime_display,
            DATE_FORMAT(checkout_time, '%h:%i %p') as time,
            DATE(checkout_time) as checkout_date,
            TIMESTAMPDIFF(MINUTE, checkin_time, checkout_time) as duration_minutes,
            'Yoga' as type,
            checkout_time as raw_time
        FROM tbl_yoga_checkin_history
        WHERE DATE(checkout_time) = CURDATE()
        AND checkout_time IS NOT NULL

        UNION ALL

        -- CROSSFIT
        SELECT
            crossfit_name as name,
            DATE_FORMAT(checkout_time, '%M %d, %Y %h:%i %p') as datetime_display,
            DATE_FORMAT(checkout_time, '%h:%i %p') as time,
            DATE(checkout_time) as checkout_date,
            TIMESTAMPDIFF(MINUTE, checkin_time, checkout_time) as duration_minutes,
            'Crossfit' as type,
            checkout_time as raw_time
        FROM tbl_crossfit_checkin_history
        WHERE DATE(checkout_time) = CURDATE()
        AND checkout_time IS NOT NULL

    ) x

    ORDER BY raw_time DESC
    LIMIT 5

");

$recentCheckouts = $recentCheckoutsQuery->getResultArray();

foreach ($recentCheckouts as &$co) {

    $mins = $co['duration_minutes'] ?? 0;

    if ($mins >= 60) {

        $hours = floor($mins / 60);
        $remainingMins = $mins % 60;

        $co['duration'] = $remainingMins > 0
            ? "{$hours}h {$remainingMins}m"
            : "{$hours}h";

    } else {

        $co['duration'] = "{$mins}m";
    }
}

// =============================================
// EXPIRING LISTS
// =============================================

$expiringTodayListQuery = $this->db->query("
    SELECT 
        CONCAT(first_name, ' ', last_name) as name,
        membership_plan as plan,
        contact_number as phone,
        member_id
    FROM tbl_members 
    WHERE membership_end_date = CURRENT_DATE() 
    AND membership_status = 'Active'
    LIMIT 5
");
$expiringTodayList = $expiringTodayListQuery->getResultArray();

$expiringThisWeekListQuery = $this->db->query("
    SELECT 
        CONCAT(first_name, ' ', last_name) as name,
        membership_plan as plan,
        DATEDIFF(membership_end_date, CURRENT_DATE()) as days_left,
        member_id
    FROM tbl_members 
    WHERE membership_end_date BETWEEN CURRENT_DATE() AND DATE_ADD(CURRENT_DATE(), INTERVAL 7 DAY)
    AND membership_end_date > CURRENT_DATE()
    AND membership_status = 'Active'
    ORDER BY days_left ASC
    LIMIT 5
");
$expiringThisWeekList = $expiringThisWeekListQuery->getResultArray();

$expiredMembersListQuery = $this->db->query("
    SELECT 
        CONCAT(first_name, ' ', last_name) as name,
        membership_plan as plan,
        membership_end_date as expired,
        DATEDIFF(CURRENT_DATE(), membership_end_date) as overdue_days,
        contact_number as phone
    FROM tbl_members 
    WHERE membership_end_date < CURRENT_DATE() 
    AND membership_status = 'Expired'
    ORDER BY membership_end_date ASC
    LIMIT 5
");
$expiredMembersList = $expiredMembersListQuery->getResultArray();

foreach ($expiredMembersList as &$exp) {
    $exp['overdue'] = $exp['overdue_days'] . ' days';
}

// =============================================
// RECENT PAYMENTS
// =============================================

$recentPaymentsQuery = $this->db->query("
    SELECT 
        pp.postrxno,
        pp.grand_total as amount,
        DATE_FORMAT(pp.created_at, '%Y-%m-%d') as date,
        pp.payment_method,
        pp.created_by
    FROM tbl_pos_payment pp
    ORDER BY pp.created_at DESC
    LIMIT 5
");
$recentPayments = $recentPaymentsQuery->getResultArray();

// =============================================
// OVERDUE PAYMENTS LIST
// =============================================

$overduePaymentsListQuery = $this->db->query("
    SELECT 
        CONCAT(first_name, ' ', last_name) as name,
        membership_plan as plan,
        membership_end_date as due_date,
        DATEDIFF(CURRENT_DATE(), membership_end_date) as overdue_days,
        contact_number as phone
    FROM tbl_members
    WHERE membership_end_date < CURRENT_DATE() 
    AND membership_status = 'Expired'
    ORDER BY membership_end_date ASC
    LIMIT 5
");
$overduePaymentsList = $overduePaymentsListQuery->getResultArray();

foreach ($overduePaymentsList as &$ovd) {
    $amount = 0;
    if ($ovd['plan'] == '1 Month') $amount = 1000;
    elseif ($ovd['plan'] == '3 Months') $amount = 3000;
    elseif ($ovd['plan'] == '6 Months') $amount = 6000;
    else $amount = 100;
    $ovd['amount'] = $amount;
    $ovd['overdue'] = $ovd['overdue_days'] . ' days';
}

// MEMBERS
$attendanceMembersQuery = $this->db->query("
    SELECT COUNT(*) as total
    FROM tbl_checkin_history
    WHERE DATE(checkin_time) = CURDATE()
");
$attendanceMembers = $attendanceMembersQuery->getRow()->total ?? 0;

// WALKIN
$attendanceWalkinQuery = $this->db->query("
    SELECT COUNT(*) as total
    FROM tbl_walkin_checkin_history
    WHERE DATE(checkin_time) = CURDATE()
");
$attendanceWalkin = $attendanceWalkinQuery->getRow()->total ?? 0;

// ZUMBA
$attendanceZumbaQuery = $this->db->query("
    SELECT COUNT(*) as total
    FROM tbl_zumba_checkin_history
    WHERE DATE(checkin_time) = CURDATE()
");
$attendanceZumba = $attendanceZumbaQuery->getRow()->total ?? 0;

// CROSSFIT
$attendanceCrossfitQuery = $this->db->query("
    SELECT COUNT(*) as total
    FROM tbl_crossfit_checkin_history
    WHERE DATE(checkin_time) = CURDATE()
");
$attendanceCrossfit = $attendanceCrossfitQuery->getRow()->total ?? 0;

// YOGA
$attendanceYogaQuery = $this->db->query("
    SELECT COUNT(*) as total
    FROM tbl_yoga_checkin_history
    WHERE DATE(checkin_time) = CURDATE()
");
$attendanceYoga = $attendanceYogaQuery->getRow()->total ?? 0;

// LATEST INVENTORY MOVEMENT
$inventoryMovementQuery = $this->db->query("
    SELECT
        product_name,
        movement_type,
        quantity,
        DATE_FORMAT(created_at, '%M %d, %Y %h:%i %p') as movement_date,
        created_by
    FROM tbl_inventory_movements
    ORDER BY created_at DESC
    LIMIT 5
");
$inventoryMovements = $inventoryMovementQuery->getResultArray();

echo view('templates/myheader.php');
?>

<style>
    :root {
        --gym-black: #0a0a0a;
        --gym-black-light: #111111;
        --gym-red: #dc2626;
        --gym-red-dark: #b91c1c;
        --gym-red-light: #fee2e2;
        --gym-white: #ffffff;
        --gym-gray: #6c757d;
        --gym-gray-dark: #6b7280;
        --gym-border: #e5e7eb;
    }
    
    /* Dashboard Cards - Matching Attendance Module */
    .attendance-card {
        background: var(--gym-white);
        border-radius: 20px;
        border: 1px solid var(--gym-border);
        transition: all 0.3s ease;
        margin-bottom: 0;
    }
    
    .attendance-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 20px -12px rgba(0,0,0,0.1);
        border-color: #d1d5db;
    }
    
    .attendance-card .card-body {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px;
    }
    
    .attendance-value {
        font-size: 32px;
        font-weight: 700;
        line-height: 1.2;
        color: var(--gym-black);
    }
    
    .attendance-icon {
        font-size: 42px;
        opacity: 0.12;
        color: var(--gym-red);
    }
    
    .attendance-label {
        font-size: 12px;
        font-weight: 600;
        color: var(--gym-gray);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 6px;
    }
    
    .attendance-sub {
        font-size: 11px;
        color: var(--gym-gray-dark);
        margin-top: 6px;
    }
    
    /* Financial Overview Card */
    .financial-card {
        background: linear-gradient(135deg, var(--gym-black) 0%, var(--gym-black-light) 100%);
        border-radius: 20px;
        border: none;
        padding: 24px;
        margin-bottom: 24px;
    }
    
    /* Section Title */
    .section-title {
        font-size: 14px;
        font-weight: 600;
        color: var(--gym-black);
        margin-bottom: 16px;
        padding-bottom: 8px;
        border-bottom: 2px solid var(--gym-red);
        display: inline-block;
    }
    
    /* Stat Cards */
    .stat-card {
        background: var(--gym-white);
        border-radius: 20px;
        padding: 20px;
        border: 1px solid var(--gym-border);
        transition: all 0.3s ease;
        height: 100%;
    }
    
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 20px -12px rgba(0,0,0,0.1);
    }
    
    .stat-icon {
        width: 48px;
        height: 48px;
        background: var(--gym-red-light);
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .stat-icon i {
        font-size: 1.5rem;
        color: var(--gym-red);
    }
    
    /* Progress Bars */
    .progress {
        background-color: #f3f4f6;
        border-radius: 10px;
        height: 6px;
    }
    
    .progress-bar {
        border-radius: 10px;
    }
    
    /* Badges */
    .badge {
        font-size: 10px;
        font-weight: 600;
        padding: 4px 10px;
        border-radius: 30px;
    }
    
    /* Tables */
    .table td, .table th {
        padding: 10px 8px;
        vertical-align: middle;
        font-size: 13px;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .attendance-value {
            font-size: 24px;
        }
        .attendance-icon {
            font-size: 34px;
        }
        .financial-card {
            padding: 16px;
        }
    }
</style>

<div class="container-fluid px-0">
    
    <!-- Header Welcome Section -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h4 class="fw-semibold mb-1" style="color: var(--gym-black);">Welcome back, <?= htmlspecialchars($full_name) ?>!</h4>
            <p class="text-muted mb-0" style="font-size: 0.85rem;">Real-time dashboard · <?= htmlspecialchars($position) ?>, <?= htmlspecialchars($division) ?></p>
        </div>
        <div class="text-end">
            <div class="mb-1">
                <span class="text-muted me-2"><i class="bi bi-calendar3"></i> <?= date('F d, Y') ?></span>
                <span class="text-muted"><i class="bi bi-clock"></i> <span id="liveClock"><?= date('h:i A') ?></span></span>
            </div>
        </div>
    </div>

    <!-- Financial Overview - Matching Theme -->
    <div class="financial-card mb-4">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h5 class="text-white mb-0"><i class="bi bi-graph-up me-2 text-danger"></i> FINANCIAL OVERVIEW</h5>
            <span class="badge" style="background: rgba(220,38,38,0.2); color: #fca5a5;"><i class="bi bi-database"></i> Live Data</span>
        </div>
        
        <div class="row g-4">
            <div class="col-xl-3 col-md-6">
                <div>
                    <div class="text-white-50 small text-uppercase mb-1">Monthly Revenue</div>
                    <div class="d-flex align-items-center gap-3 flex-wrap">
                        <h2 class="text-white mb-0 fw-bold">₱<?= number_format($monthlyRevenue) ?></h2>
                        <span class="badge <?= $monthlyRevenueGrowth >= 0 ? 'bg-success' : 'bg-danger' ?>">
                            <i class="bi bi-arrow-<?= $monthlyRevenueGrowth >= 0 ? 'up' : 'down' ?>"></i> <?= abs($monthlyRevenueGrowth) ?>%
                        </span>
                    </div>
                    <div class="mt-2">
                        <div class="d-flex justify-content-between small text-white-50 mb-1">
                            <span>Target: ₱<?= number_format($monthlyRevenueTarget) ?></span>
                            <span><?= $monthlyRevenuePercent ?>% achieved</span>
                        </div>
                        <div class="progress" style="background: rgba(255,255,255,0.2);">
                            <div class="progress-bar bg-danger" style="width: <?= $monthlyRevenuePercent ?>%"></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6">
                <div>
                    <div class="text-white-50 small text-uppercase mb-1">Year-to-Date Revenue</div>
                    <h3 class="text-white mb-0 fw-bold">₱<?= number_format($ytdRevenue) ?></h3>
                    <div class="mt-1">
                        <span class="text-success small"><i class="bi bi-arrow-up"></i> <?= $ytdGrowth ?>% vs last year</span>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6">
                <div>
                    <div class="text-white-50 small text-uppercase mb-1">Collection Status</div>
                    <div class="row g-2">
                        <div class="col-6">
                            <div class="small text-white-50">Collected</div>
                            <span class="text-success fw-bold">₱<?= number_format($collectedPayments) ?></span>
                        </div>
                        <div class="col-6">
                            <div class="small text-white-50">Pending</div>
                            <span class="text-warning fw-bold">₱<?= number_format($pendingPayments) ?></span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6">
                <div>
                    <div class="text-white-50 small text-uppercase mb-1">Key Metrics</div>
                    <div class="row g-2">
                        <div class="col-6">
                            <div class="small text-white-50">Avg Ticket</div>
                            <span class="text-white fw-bold">₱<?= number_format($averageTicketSize) ?></span>
                        </div>
                        <div class="col-6">
                            <div class="small text-white-50">Active Subs</div>
                            <span class="text-white fw-bold"><?= number_format($activeSubscriptions) ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats Cards - Matching Attendance Module -->
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="attendance-card">
                <div class="card-body">
                    <div>
                        <div class="attendance-label">Total Members</div>
                        <div class="attendance-value"><?= number_format($totalMembers) ?></div>
                        <div class="attendance-sub">+<?= $newMembersThisMonth ?> this month</div>
                    </div>
                    <i class="bi bi-people-fill attendance-icon"></i>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="attendance-card">
                <div class="card-body">
                    <div>
                        <div class="attendance-label">Today's Check-ins</div>
                        <div class="attendance-value"><?= number_format($todayCheckins) ?></div>
                        <div class="attendance-sub"><?= $currentlyInGym ?> currently inside</div>
                    </div>
                    <i class="bi bi-box-arrow-in-right attendance-icon"></i>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="attendance-card">
                <div class="card-body">
                    <div>
                        <div class="attendance-label">Today's Check-outs</div>
                        <div class="attendance-value"><?= number_format($todayCheckouts) ?></div>
                        <div class="attendance-sub">Total completed</div>
                    </div>
                    <i class="bi bi-box-arrow-right attendance-icon"></i>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="attendance-card">
                <div class="card-body">
                    <div>
                        <div class="attendance-label">Expiring Soon</div>
                        <div class="attendance-value text-danger"><?= $expiringToday + $expiringThisWeek ?></div>
                        <div class="attendance-sub text-danger"><?= $expiringToday ?> expire TODAY!</div>
                    </div>
                    <i class="bi bi-exclamation-triangle-fill attendance-icon"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row g-4 mb-4">
        <div class="col-xl-5">
            <div class="stat-card">
                <h6 class="section-title"><i class="bi bi-graph-up me-1 text-danger"></i> Revenue Trend (Last 6 Months)</h6>
                <canvas id="revenueChart" style="height: 200px; width: 100%;"></canvas>
            </div>
        </div>
        <div class="col-xl-3">
            <div class="stat-card">
                <h6 class="section-title"><i class="bi bi-pie-chart-fill me-1 text-danger"></i> Attendance Today</h6>
                <canvas id="membershipChart" style="height: 130px; width: 100%;"></canvas>
                <div class="row mt-2 text-center small g-1">
                    <div class="col-6"><span class="text-danger">●</span> Members: <?= $attendanceMembers ?></div>
                    <div class="col-6"><span class="text-primary">●</span> Walk-in: <?= $attendanceWalkin ?></div>
                    <div class="col-6"><span class="text-success">●</span> Zumba: <?= $attendanceZumba ?></div>
                    <div class="col-6"><span class="text-warning">●</span> Crossfit: <?= $attendanceCrossfit ?></div>
                    <div class="col-12 mt-1"><span class="text-info">●</span> Yoga: <?= $attendanceYoga ?></div>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="stat-card">
                <h6 class="section-title"><i class="bi bi-cash-stack me-1 text-danger"></i> Financial Summary</h6>
                <div class="mb-3">
                    <div class="d-flex justify-content-between small mb-1">
                        <span>Collection Rate</span>
                        <span class="fw-bold"><?= $collectionRate ?>%</span>
                    </div>
                    <div class="progress"><div class="progress-bar bg-success" style="width: <?= $collectionRate ?>%"></div></div>
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between small mb-1">
                        <span>Revenue vs Target</span>
                        <span class="fw-bold"><?= $monthlyRevenuePercent ?>%</span>
                    </div>
                    <div class="progress"><div class="progress-bar bg-danger" style="width: <?= $monthlyRevenuePercent ?>%"></div></div>
                </div>
                <div class="mt-3 pt-2 border-top">
                    <div class="d-flex justify-content-between small">
                        <span>Pending Collection</span>
                        <span class="text-warning fw-bold">₱<?= number_format($pendingPayments) ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Check-ins & Check-outs -->
    <div class="row g-4 mb-4">
        <div class="col-xl-6">
            <div class="stat-card">
                <h6 class="section-title"><i class="bi bi-box-arrow-in-right text-success me-1"></i> Recent Check-ins</h6>
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <tbody>
                            <?php foreach($recentCheckins as $ci): ?>
                            <tr>
                                <td><i class="bi bi-person-circle text-muted me-2"></i> <?= htmlspecialchars($ci['name'] ?? 'Walk-in') ?></td>
                                <td><?= $ci['time'] ?></td>
                                <td class="text-end"><span class="badge bg-success bg-opacity-10 text-success">Checked In</span></td>
                            </tr>
                            <?php endforeach; ?>
                            <?php if(empty($recentCheckins)): ?>
                            <tr><td colspan="3" class="text-center text-muted py-3">No check-ins recorded today</td><?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="stat-card">
                <h6 class="section-title"><i class="bi bi-box-arrow-right text-warning me-1"></i> Recent Check-outs</h6>
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <tbody>
                            <?php foreach($recentCheckouts as $co): ?>
                            <tr>
                                <td><i class="bi bi-person-circle text-muted me-2"></i> <?= htmlspecialchars($co['name'] ?? 'Walk-in') ?></td>
                                <td><?= $co['time'] ?></td>
                                <td class="text-end"><span class="badge bg-info bg-opacity-10 text-info"><?= $co['duration'] ?></span></td>
                            </tr>
                            <?php endforeach; ?>
                            <?php if(empty($recentCheckouts)): ?>
                            <tr><td colspan="3" class="text-center text-muted py-3">No check-outs recorded today</td><?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Expiring Memberships -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="stat-card">
                <h6 class="section-title"><i class="bi bi-exclamation-triangle-fill text-danger me-1"></i> URGENT: Expiring Memberships</h6>
                
                <?php if(!empty($expiringTodayList)): ?>
                <div class="mb-3 p-3" style="background: var(--gym-red-light); border-radius: 16px;">
                    <strong class="text-danger">⚠️ EXPIRING TODAY (<?= count($expiringTodayList) ?> members)</strong>
                    <div class="table-responsive mt-2">
                        <table class="table table-sm mb-0">
                            <thead><tr><th>Member</th><th>Plan</th><th>Contact</th></tr></thead>
                            <tbody>
                                <?php foreach($expiringTodayList as $exp): ?>
                                <tr><td><?= htmlspecialchars($exp['name']) ?></td><td><?= htmlspecialchars($exp['plan']) ?></td><td><?= htmlspecialchars($exp['phone'] ?? 'N/A') ?></td></tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php endif; ?>

                <?php if(!empty($expiringThisWeekList)): ?>
                <div>
                    <strong class="text-warning">⚠️ Expiring This Week (<?= count($expiringThisWeekList) ?> members)</strong>
                    <div class="table-responsive mt-2">
                        <table class="table table-sm mb-0">
                            <thead><tr><th>Member</th><th>Plan</th><th>Days Left</th></tr></thead>
                            <tbody>
                                <?php foreach($expiringThisWeekList as $exp): ?>
                                <tr><td><?= htmlspecialchars($exp['name']) ?></td><td><?= htmlspecialchars($exp['plan']) ?></td><td class="text-warning fw-bold"><?= $exp['days_left'] ?> days</td></tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php endif; ?>

                <?php if(empty($expiringTodayList) && empty($expiringThisWeekList)): ?>
                <div class="text-center text-success py-3"><i class="bi bi-check-circle-fill me-2"></i> No members expiring in the next 7 days</div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Recent Payments & Latest Inventory Movement -->
    <div class="row g-4 mb-4">
        <div class="col-xl-6">
            <div class="stat-card">
                <h6 class="section-title"><i class="bi bi-cash-stack me-1 text-danger"></i> Recent Payments</h6>
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead><tr><th>Transaction</th><th>Amount</th><th>Date</th><th>Method</th></tr></thead>
                        <tbody>
                            <?php foreach($recentPayments as $pay): ?>
                            <tr>
                                <td><?= htmlspecialchars($pay['postrxno']) ?></td>
                                <td>₱<?= number_format($pay['amount']) ?></td>
                                <td><?= date('M d', strtotime($pay['date'])) ?></td>
                                <td><span class="badge bg-success bg-opacity-10 text-success"><?= ucfirst($pay['payment_method']) ?></span></td>
                            </tr>
                            <?php endforeach; ?>
                            <?php if(empty($recentPayments)): ?>
                            <tr><td colspan="4" class="text-center text-muted py-3">No recent payments recorded</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-xl-6">
            <div class="stat-card">
                <h6 class="section-title"><i class="bi bi-box-seam me-1 text-danger"></i> Latest Inventory Movement</h6>
                <?php if(!empty($inventoryMovements)): ?>
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead><tr><th>Item</th><th>Type</th><th>Qty</th><th>Date</th></tr></thead>
                        <tbody>
                            <?php foreach($inventoryMovements as $mov): ?>
                            <tr>
                                <td><?= htmlspecialchars($mov['product_name']) ?></td>
                                <td>
                                    <?php
                                        $badgeClass = 'bg-secondary';
                                        if(strtolower($mov['movement_type']) == 'in') $badgeClass = 'bg-success';
                                        if(strtolower($mov['movement_type']) == 'out') $badgeClass = 'bg-danger';
                                        if(strtolower($mov['movement_type']) == 'adjustment') $badgeClass = 'bg-warning';
                                    ?>
                                    <span class="badge <?= $badgeClass ?>"><?= htmlspecialchars($mov['movement_type']) ?></span>
                                </td>
                                <td><?= number_format($mov['quantity']) ?></td>
                                <td style="font-size:11px;"><?= $mov['movement_date'] ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <div class="text-center text-muted py-4"><i class="bi bi-box-seam fs-1 d-block mb-2"></i>No inventory movement found</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    // Revenue Chart
    const revenueData = <?= json_encode($revenueTrend) ?>;
    new Chart(document.getElementById('revenueChart'), {
        type: 'line',
        data: {
            labels: revenueData.map(d => d.month),
            datasets: [{
                label: 'Revenue (₱K)',
                data: revenueData.map(d => d.revenue),
                borderColor: '#dc2626',
                backgroundColor: 'rgba(220, 38, 38, 0.05)',
                borderWidth: 2,
                pointRadius: 4,
                pointBackgroundColor: '#dc2626',
                pointBorderColor: '#fff',
                tension: 0.3,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: { legend: { display: false } },
            scales: { y: { ticks: { callback: (v) => '₱' + v + 'K' } } }
        }
    });

    // Attendance Chart
    new Chart(document.getElementById('membershipChart'), {
        type: 'doughnut',
        data: {
            labels: ['Monthly', 'Quarterly', 'Semi-Annual', 'Day Pass'],
            datasets: [{ 
                data: [<?= $membershipMonthly ?>, <?= $membershipQuarterly ?>, <?= $membershipAnnual ?>, <?= $membershipDayPass ?>], 
                backgroundColor: ['#dc2626', '#ef4444', '#f87171', '#fca5a5'],
                borderWidth: 0
            }]
        },
        options: { 
            responsive: true, 
            maintainAspectRatio: true, 
            cutout: '65%',
            plugins: { legend: { display: false } }
        }
    });

    // Live Clock
    function updateClock() {
        const now = new Date();
        document.getElementById('liveClock').textContent = now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
    }
    updateClock();
    setInterval(updateClock, 1000);
</script>

<?php echo view('templates/myfooter.php'); ?>