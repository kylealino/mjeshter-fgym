<?php
$this->request = \Config\Services::request();
$this->db = \Config\Database::connect();
$this->session = session();
$this->cuser = $this->session->get('__xsys_myuserzicas__');

// =============================================
// CLIENT MOCKUP DATA - SPECIFIC MEMBER
// =============================================

// Current Logged-in Member (Client)
$member = [
    'id' => 'M-1001',
    'full_name' => 'John Michael Reyes',
    'email' => 'john.reyes@email.com',
    'phone' => '0912-345-6789',
    'address' => 'Blk 5 Lot 14, Bacoor, Cavite',
    'birthdate' => '1990-05-15',
    'gender' => 'Male',
    'member_since' => '2023-01-15',
    'status' => 'active'
];

// Current Active Subscription
$currentSubscription = [
    'plan' => 'Annual Premium',
    'price' => 12000,
    'start_date' => '2026-01-15',
    'end_date' => '2027-01-15',
    'days_left' => 270,
    'auto_renew' => true,
    'features' => [
        '24/7 Gym Access',
        'Unlimited Group Classes',
        '1 Free PT Session/month',
        'Locker Room Access',
        'Guest Pass (2x/month)'
    ]
];

// Subscription History
$subscriptionHistory = [
    ['plan' => 'Monthly', 'price' => 1500, 'start_date' => '2023-01-15', 'end_date' => '2023-04-15', 'status' => 'expired'],
    ['plan' => 'Quarterly', 'price' => 4200, 'start_date' => '2023-04-15', 'end_date' => '2023-10-15', 'status' => 'expired'],
    ['plan' => 'Annual Basic', 'price' => 9500, 'start_date' => '2023-10-15', 'end_date' => '2024-10-15', 'status' => 'expired'],
    ['plan' => 'Annual Premium', 'price' => 12000, 'start_date' => '2024-10-15', 'end_date' => '2025-10-15', 'status' => 'expired'],
    ['plan' => 'Annual Premium', 'price' => 12000, 'start_date' => '2026-01-15', 'end_date' => '2027-01-15', 'status' => 'active']
];

// Payment History
$paymentHistory = [
    ['date' => '2026-01-15', 'description' => 'Annual Premium Subscription', 'amount' => 12000, 'method' => 'Credit Card', 'receipt' => 'INV-2026-001', 'status' => 'paid'],
    ['date' => '2025-10-15', 'description' => 'Annual Premium Renewal', 'amount' => 12000, 'method' => 'Bank Transfer', 'receipt' => 'INV-2025-089', 'status' => 'paid'],
    ['date' => '2024-10-15', 'description' => 'Annual Basic to Premium Upgrade', 'amount' => 2500, 'method' => 'Cash', 'receipt' => 'INV-2024-234', 'status' => 'paid'],
    ['date' => '2024-10-15', 'description' => 'Annual Basic Subscription', 'amount' => 9500, 'method' => 'Credit Card', 'receipt' => 'INV-2024-233', 'status' => 'paid'],
    ['date' => '2023-10-15', 'description' => 'Annual Basic Subscription', 'amount' => 9500, 'method' => 'Bank Transfer', 'receipt' => 'INV-2023-156', 'status' => 'paid'],
];

// Calculate Total Spent
$totalSpent = array_sum(array_column($paymentHistory, 'amount'));

// =============================================
// CHECK-IN DATA (Last 365 days)
// =============================================
$selectedYear = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');

// Only show recent years (2020-2026)
$availableYears = [2026, 2025, 2024, 2023, 2022, 2021, 2020];
if (!in_array($selectedYear, $availableYears)) {
    $selectedYear = 2026;
}

// Generate check-in data for selected year
$checkinData = [];
$yearlyCheckins = 0;

$startDate = new DateTime("{$selectedYear}-01-01");
$endDate = new DateTime("{$selectedYear}-12-31");

while ($startDate <= $endDate) {
    $dateStr = $startDate->format('Y-m-d');
    $dayOfWeek = $startDate->format('N');
    $isWeekend = ($dayOfWeek >= 6);
    $random = rand(1, 100);
    
    if ($isWeekend) {
        $checked = ($random > 70);
    } else {
        $checked = ($random > 35);
    }
    
    if ($selectedYear < 2023) {
        $checked = false;
    }
    
    $checkinData[$dateStr] = $checked;
    if ($checked) {
        $yearlyCheckins++;
    }
    $startDate->modify('+1 day');
}

// Recent Check-ins
$recentCheckins = [
    ['date' => '2026-04-20', 'time_in' => '06:45 AM', 'time_out' => '08:30 AM', 'duration' => '1h 45m'],
    ['date' => '2026-04-19', 'time_in' => '05:30 PM', 'time_out' => '07:15 PM', 'duration' => '1h 45m'],
    ['date' => '2026-04-18', 'time_in' => '06:30 AM', 'time_out' => '08:00 AM', 'duration' => '1h 30m'],
    ['date' => '2026-04-17', 'time_in' => '05:45 PM', 'time_out' => '07:30 PM', 'duration' => '1h 45m'],
    ['date' => '2026-04-16', 'time_in' => '06:15 AM', 'time_out' => '08:00 AM', 'duration' => '1h 45m'],
];

// DO NOT INCLUDE HEADER AND FOOTER - CLIENT-ONLY PAGE
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Dashboard | MJESHTER FITNESS GYM</title>
    <link rel="shortcut icon" type="image/png" href="<?=base_url('assets/images/logos/gym-logo.png')?>">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        :root {
            --gym-red: #dc2626;
            --gym-red-dark: #b91c1c;
            --gym-black: #0a0a0a;
            --gym-black-light: #111111;
            --gym-white: #ffffff;
            --gym-gray: #9ca3af;
            --gym-gray-dark: #6b7280;
            --gym-border: rgba(0, 0, 0, 0.05);
            --gym-success: #10b981;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #f8f9fa;
        }

        /* Client-Only Navbar (Minimal, No Modules) */
        .client-navbar {
            background: var(--gym-white);
            border-bottom: 1px solid #e5e7eb;
            padding: 12px 24px;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }

        .navbar-brand img {
            width: 35px;
            height: auto;
        }

        .navbar-brand span {
            font-weight: 700;
            font-size: 1.1rem;
            color: var(--gym-black);
        }

        .navbar-brand span span {
            color: var(--gym-red);
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--gym-red), var(--gym-red-dark));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
        }

        .logout-btn {
            background: transparent;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 8px 16px;
            font-size: 0.8rem;
            font-weight: 500;
            color: var(--gym-gray-dark);
            text-decoration: none;
            transition: all 0.2s;
        }

        .logout-btn:hover {
            background: var(--gym-red);
            border-color: var(--gym-red);
            color: white;
        }

        /* Main Content */
        .main-content {
            padding: 24px;
            max-width: 1400px;
            margin: 0 auto;
        }

        /* Stat Cards */
        .stat-card {
            background: var(--gym-white);
            border: none;
            border-radius: 16px;
            padding: 1.25rem;
            transition: all 0.2s ease;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            height: 100%;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1);
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            background: rgba(220, 38, 38, 0.1);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .stat-icon i {
            font-size: 1.5rem;
            color: var(--gym-red);
        }

        .stat-value {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--gym-black);
        }

        .stat-label {
            font-size: 0.7rem;
            color: var(--gym-gray-dark);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .section-title {
            font-size: 1rem;
            font-weight: 600;
            color: var(--gym-black);
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--gym-red);
            display: inline-block;
        }

        /* Table Styles */
        .table-custom {
            width: 100%;
        }

        .table-custom thead th {
            background: #f8f9fa;
            color: var(--gym-gray-dark);
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            padding: 10px 12px;
        }

        .table-custom tbody td {
            padding: 10px 12px;
            font-size: 0.8rem;
            border-bottom: 1px solid #e5e7eb;
        }

        /* Badges */
        .badge-success {
            background: rgba(16, 185, 129, 0.1);
            color: var(--gym-success);
            padding: 4px 8px;
            border-radius: 20px;
            font-size: 0.65rem;
        }

        .badge-secondary {
            background: rgba(107, 114, 128, 0.1);
            color: var(--gym-gray-dark);
            padding: 4px 8px;
            border-radius: 20px;
            font-size: 0.65rem;
        }

        /* Footer */
        .client-footer {
            background: var(--gym-white);
            border-top: 1px solid #e5e7eb;
            padding: 16px 24px;
            text-align: center;
            font-size: 0.7rem;
            color: var(--gym-gray-dark);
        }

        /* Contribution Heatmap */
        .contribution-graph {
            overflow-x: auto;
        }
        
        #contributionCells {
            display: flex;
            gap: 3px;
            min-width: 750px;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .main-content {
                padding: 16px;
            }
            .stat-value {
                font-size: 1.25rem;
            }
        }
    </style>
</head>
<body>

<!-- CLIENT-ONLY NAVBAR (NO MODULES ACCESS) -->
<nav class="client-navbar">
    <div class="d-flex justify-content-between align-items-center">
        <a href="#" class="navbar-brand">
            <span>MJESHTER <span>FITNESS GYM</span></span>
        </a>
        <div class="user-menu">
            <div class="user-avatar">
                <i class="bi bi-person-fill"></i>
            </div>
            <span class="text-muted small d-none d-sm-block"><?= $member['full_name']; ?></span>
            <a href="<?= site_url('myclientlogout'); ?>" class="logout-btn">
                <i class="bi bi-box-arrow-right"></i> Logout
            </a>
        </div>
    </div>
</nav>

<!-- MAIN CONTENT -->
<div class="main-content">
    
    <!-- Welcome Section -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h4 class="fw-semibold mb-1" style="color: var(--gym-black);">Welcome back, <?= $member['full_name']; ?>!</h4>
            <p class="text-muted mb-0" style="font-size: 0.85rem;">Member since <?= date('F d, Y', strtotime($member['member_since'])); ?></p>
        </div>
        <div>
            <span class="badge bg-success px-3 py-2"><i class="bi bi-shield-check"></i> Active Member</span>
        </div>
    </div>

    <!-- Membership Status Card -->
    <div class="stat-card mb-4" style="background: linear-gradient(135deg, #6e6e6e 0%, #ffb8b8 100%); border: 1px solid rgba(220,38,38,0.3);">
        <div class="row align-items-center">
            <div class="col-md-8">
                <div class="d-flex align-items-center gap-3 mb-3 mb-md-0">
                    <div class="stat-icon" style="background: rgba(220,38,38,0.15); width: 50px; height: 50px; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-star-fill text-danger fs-3"></i>
                    </div>
                    <div>
                        <h5 class="text-white mb-0"><?= $currentSubscription['plan']; ?> Plan</h5>
                        <p class="text-white mb-0 small">Valid until <?= date('F d, Y', strtotime($currentSubscription['end_date'])); ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 text-md-end">
                <div class="d-flex justify-content-md-end gap-3">
                    <div>
                        <div class="small text-muted">Days Remaining</div>
                        <span class="h4 text-white fw-bold"><?= $currentSubscription['days_left']; ?></span>
                    </div>
                    <div>
                        <div class="small text-muted">Auto-Renewal</div>
                        <span class="badge bg-success"><?= $currentSubscription['auto_renew'] ? 'ON' : 'OFF'; ?></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="progress mt-3" style="height: 4px;">
            <?php $progressPercent = round((365 - $currentSubscription['days_left']) / 365 * 100); ?>
            <div class="progress-bar bg-danger" style="width: <?= $progressPercent; ?>%"></div>
        </div>
        <div class="row mt-3">
            <?php foreach($currentSubscription['features'] as $feature): ?>
            <div class="col-md-4 col-6 mb-2">
                <small class="text-muted"><i class="bi bi-check-circle-fill text-success me-1"></i> <?= $feature; ?></small>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- GitHub-Style Check-in Heatmap -->
    <div class="stat-card mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-3">
            <div>
                <h6 class="section-title mb-0"><i class="bi bi-calendar-check-fill me-1 text-danger"></i> Your Gym Activity</h6>
                <p class="text-muted small mb-0 mt-1"><?= $yearlyCheckins; ?> check-ins in <?= $selectedYear; ?></p>
            </div>
            <div class="d-flex gap-2">
                <select class="form-select form-select-sm" style="width: auto;" id="yearSelect" onchange="window.location.href='?year='+this.value">
                    <?php foreach($availableYears as $year): ?>
                        <option value="<?= $year; ?>" <?= $selectedYear == $year ? 'selected' : ''; ?>><?= $year; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        
        <div class="contribution-graph mb-3">
            <div class="d-flex gap-1 flex-wrap" id="contributionCells"></div>
        </div>
        
        <div class="d-flex justify-content-between align-items-center mt-3">
            <div class="d-flex gap-2 align-items-center">
                <span class="text-muted small">Less</span>
                <div class="d-flex gap-1">
                    <div style="width: 12px; height: 12px; background: #ebedf0; border-radius: 2px;"></div>
                    <div style="width: 12px; height: 12px; background: #9be9a8; border-radius: 2px;"></div>
                    <div style="width: 12px; height: 12px; background: #40c463; border-radius: 2px;"></div>
                    <div style="width: 12px; height: 12px; background: #30a14e; border-radius: 2px;"></div>
                    <div style="width: 12px; height: 12px; background: #216e39; border-radius: 2px;"></div>
                </div>
                <span class="text-muted small">More</span>
            </div>
            <div class="text-muted small">
                <i class="bi bi-info-circle"></i> Each square represents one day
            </div>
        </div>
    </div>

    <!-- Recent Check-ins & Stats -->
    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="stat-card">
                <h6 class="section-title mb-3"><i class="bi bi-clock-history me-1 text-danger"></i> Recent Check-ins</h6>
                <div class="table-responsive">
                    <table class="table table-custom">
                        <thead>
                            <tr><th>Date</th><th>Time In</th><th>Time Out</th><th>Duration</th></tr>
                        </thead>
                        <tbody>
                            <?php foreach($recentCheckins as $check): ?>
                            <tr>
                                <td><?= date('M d', strtotime($check['date'])); ?></td>
                                <td><?= $check['time_in']; ?></td>
                                <td><?= $check['time_out']; ?></td>
                                <td><span class="badge-success"><?= $check['duration']; ?></span></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="stat-card">
                <h6 class="section-title mb-3"><i class="bi bi-graph-up me-1 text-danger"></i> Your Stats</h6>
                <div class="row text-center">
                    <div class="col-4">
                        <div class="p-3 border rounded">
                            <div class="h3 text-danger fw-bold mb-0"><?= $yearlyCheckins; ?></div>
                            <small class="text-muted">Check-ins<br><?= $selectedYear; ?></small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-3 border rounded">
                            <div class="h3 text-danger fw-bold mb-0"><?= round($yearlyCheckins / 52); ?></div>
                            <small class="text-muted">Avg per week<br><?= $selectedYear; ?></small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-3 border rounded">
                            <div class="h3 text-danger fw-bold mb-0"><?= round($yearlyCheckins / 12); ?></div>
                            <small class="text-muted">Avg per month<br><?= $selectedYear; ?></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Subscription History & Payments -->
    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="stat-card">
                <h6 class="section-title mb-3"><i class="bi bi-clock-history me-1 text-danger"></i> Subscription History</h6>
                <div class="table-responsive">
                    <table class="table table-custom">
                        <thead>
                            <tr><th>Plan</th><th>Period</th><th>Amount</th><th>Status</th></tr>
                        </thead>
                        <tbody>
                            <?php foreach($subscriptionHistory as $sub): ?>
                            <tr>
                                <td><?= $sub['plan']; ?></td>
                                <td><small><?= date('M Y', strtotime($sub['start_date'])); ?> - <?= date('M Y', strtotime($sub['end_date'])); ?></small></td>
                                <td>₱<?= number_format($sub['price']); ?></td>
                                <td><span class="badge-<?= $sub['status'] == 'active' ? 'success' : 'secondary'; ?>"><?= ucfirst($sub['status']); ?></span></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="section-title mb-0"><i class="bi bi-cash-stack me-1 text-danger"></i> Payment History</h6>
                    <div class="text-end">
                        <div class="small text-muted">Total Spent</div>
                        <span class="h5 text-danger fw-bold">₱<?= number_format($totalSpent); ?></span>
                    </div>
                </div>
                <div class="table-responsive" style="max-height: 250px; overflow-y: auto;">
                    <table class="table table-custom">
                        <thead>
                            <tr><th>Date</th><th>Description</th><th>Amount</th><th>Receipt</th></tr>
                        </thead>
                        <tbody>
                            <?php foreach($paymentHistory as $payment): ?>
                            <tr>
                                <td><small><?= date('M d, Y', strtotime($payment['date'])); ?></small></td>
                                <td><small><?= $payment['description']; ?></small></td>
                                <td><small class="fw-bold">₱<?= number_format($payment['amount']); ?></small></td>
                                <td><a href="#" class="text-danger small"><?= $payment['receipt']; ?></a></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Personal Information -->
    <div class="row g-4">
        <div class="col-12">
            <div class="stat-card">
                <h6 class="section-title mb-3"><i class="bi bi-person-badge me-1 text-danger"></i> Personal Information</h6>
                <div class="row">
                    <div class="col-md-3 col-6 mb-3">
                        <div class="small text-muted">Full Name</div>
                        <div class="fw-medium"><?= $member['full_name']; ?></div>
                    </div>
                    <div class="col-md-3 col-6 mb-3">
                        <div class="small text-muted">Member ID</div>
                        <div class="fw-medium"><?= $member['id']; ?></div>
                    </div>
                    <div class="col-md-3 col-6 mb-3">
                        <div class="small text-muted">Email Address</div>
                        <div class="fw-medium"><?= $member['email']; ?></div>
                    </div>
                    <div class="col-md-3 col-6 mb-3">
                        <div class="small text-muted">Phone Number</div>
                        <div class="fw-medium"><?= $member['phone']; ?></div>
                    </div>
                    <div class="col-md-3 col-6 mb-3">
                        <div class="small text-muted">Birthdate</div>
                        <div class="fw-medium"><?= date('F d, Y', strtotime($member['birthdate'])); ?></div>
                    </div>
                    <div class="col-md-3 col-6 mb-3">
                        <div class="small text-muted">Gender</div>
                        <div class="fw-medium"><?= $member['gender']; ?></div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="small text-muted">Address</div>
                        <div class="fw-medium"><?= $member['address']; ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CLIENT-ONLY FOOTER -->
<footer class="client-footer">
    <p class="mb-0">MJESHTER FITNESS GYM — Member Portal | © <?= date('Y'); ?> All Rights Reserved</p>
</footer>

<!-- Heatmap Script -->
<script>
    const year = <?= json_encode($selectedYear); ?>;
    const checkinData = <?= json_encode($checkinData); ?>;

    function getContributionLevel(hasCheckin) {
        if (!hasCheckin) return 0;
        // Random intensity for visual variety
        const rand = Math.random();
        if (rand < 0.3) return 1;
        if (rand < 0.6) return 2;
        if (rand < 0.8) return 3;
        return 4;
    }

    function buildHeatmap() {
        const startDate = new Date(year, 0, 1);
        const endDate = new Date(year, 11, 31);
        const weeks = [];
        let currentWeek = [];
        
        let currentDate = new Date(startDate);
        const firstDayOfWeek = currentDate.getDay();
        
        for (let i = 0; i < firstDayOfWeek; i++) {
            currentWeek.push({ date: null, level: 0 });
        }
        
        while (currentDate <= endDate) {
            const dateStr = currentDate.toISOString().split('T')[0];
            const hasCheckin = checkinData[dateStr] || false;
            const level = getContributionLevel(hasCheckin);
            
            currentWeek.push({ date: dateStr, level: level });
            
            if (currentWeek.length === 7) {
                weeks.push(currentWeek);
                currentWeek = [];
            }
            
            currentDate.setDate(currentDate.getDate() + 1);
        }
        
        if (currentWeek.length > 0) {
            while (currentWeek.length < 7) {
                currentWeek.push({ date: null, level: 0 });
            }
            weeks.push(currentWeek);
        }
        
        const container = document.getElementById('contributionCells');
        if (!container) return;
        
        container.innerHTML = '';
        
        const dayLabels = document.createElement('div');
        dayLabels.className = 'd-flex flex-column me-2';
        dayLabels.style.fontSize = '10px';
        dayLabels.style.color = '#6c757d';
        ['Mon', 'Wed', 'Fri'].forEach(day => {
            const label = document.createElement('div');
            label.style.height = '14px';
            label.style.marginBottom = '2px';
            label.textContent = day;
            dayLabels.appendChild(label);
        });
        container.appendChild(dayLabels);
        
        const levelColors = ['#ebedf0', '#9be9a8', '#40c463', '#30a14e', '#216e39'];
        
        weeks.forEach(week => {
            const weekCol = document.createElement('div');
            weekCol.className = 'd-flex flex-column';
            weekCol.style.gap = '2px';
            
            week.forEach(day => {
                const cell = document.createElement('div');
                cell.style.width = '14px';
                cell.style.height = '14px';
                cell.style.borderRadius = '2px';
                
                if (day.date) {
                    cell.style.backgroundColor = levelColors[day.level];
                    cell.title = `${day.date}: ${day.level > 0 ? 'Checked in' : 'No check-in'}`;
                } else {
                    cell.style.backgroundColor = 'transparent';
                }
                
                weekCol.appendChild(cell);
            });
            
            container.appendChild(weekCol);
        });
    }

    buildHeatmap();
</script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>