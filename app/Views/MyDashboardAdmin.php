<?php
$this->request = \Config\Services::request();
$this->db = \Config\Database::connect();
$this->session = session();
$this->cuser = $this->session->get('__xsys_myuserzicas__');

  $query = $this->db->query("
  SELECT 
      `full_name`, 
      `division`,
      `section`, 
      `position`,
      `username`, 
      `hash_password`,
      `hash_value`
  FROM 
      `myua_user` 
  WHERE 
      `username` = '$this->cuser'"
  );

  $data = $query->getRowArray();
  $full_name = $data['full_name'];
  $position = $data['position'];
  $section = $data['section'];
  $division = $data['division'];

  // =============================================
  // MOCKUP DATA - NO DATABASE RETRIEVAL
  // =============================================

  // FINANCIAL STATS (EMPHASIZED)
  $monthlyRevenue = 342800;
  $monthlyRevenueTarget = 400000;
  $monthlyRevenueGrowth = 8.3;
  $monthlyRevenuePercent = round(($monthlyRevenue / $monthlyRevenueTarget) * 100);
  
  $pendingPayments = 23450;
  $overduePayments = 8750;
  $collectedPayments = 318350;
  
  $ytdRevenue = 1842000;
  $ytdRevenueLastYear = 1650000;
  $ytdGrowth = 11.6;
  
  $averageTicketSize = 2850;
  $activeSubscriptions = 1124;
  
  // MEMBERSHIP & ATTENDANCE
  $totalMembers = 1284;
  $newMembersThisMonth = 87;
  $todayCheckins = 412;
  $todayCheckouts = 389;
  $currentlyInGym = 156;
  
  // URGENT
  $expiringToday = 8;
  $expiringThisWeek = 34;
  $expiredMembers = 12;
  $activeTrainers = 12;
  $todayClasses = 8;

  // MEMBERSHIP DISTRIBUTION
  $membershipMonthly = 682;
  $membershipQuarterly = 445;
  $membershipAnnual = 298;
  $membershipDayPass = 122;

  // RECENT CHECK-INS
  $recentCheckins = [
    ['name' => 'John M. Reyes', 'time' => '6:45 PM', 'member_id' => 'M-1001'],
    ['name' => 'Maria S. Santos', 'time' => '6:30 PM', 'member_id' => 'M-1002'],
    ['name' => 'Mike T. Cruz', 'time' => '6:15 PM', 'member_id' => 'M-1003'],
    ['name' => 'Sarah L. Gomez', 'time' => '5:55 PM', 'member_id' => 'M-1004'],
    ['name' => 'James D. Lee', 'time' => '5:30 PM', 'member_id' => 'M-1005'],
  ];

  $recentCheckouts = [
    ['name' => 'Anna K. Reyes', 'time' => '6:40 PM', 'duration' => '1.5 hrs'],
    ['name' => 'Paul C. Santos', 'time' => '6:20 PM', 'duration' => '2 hrs'],
    ['name' => 'Lisa M. Gomez', 'time' => '6:00 PM', 'duration' => '1 hr'],
  ];

  // EXPIRING MEMBERSHIPS
  $expiringTodayList = [
    ['name' => 'Robert Johnson', 'plan' => 'Annual', 'expiry' => '2026-04-20', 'phone' => '0912-345-6789'],
    ['name' => 'Emily Davis', 'plan' => 'Quarterly', 'expiry' => '2026-04-20', 'phone' => '0923-456-7890'],
  ];

  $expiringThisWeekList = [
    ['name' => 'Michael Brown', 'plan' => 'Monthly', 'expiry' => '2026-04-22', 'days_left' => 2],
    ['name' => 'Jessica Wilson', 'plan' => 'Annual', 'expiry' => '2026-04-25', 'days_left' => 5],
    ['name' => 'David Martinez', 'plan' => 'Monthly', 'expiry' => '2026-04-27', 'days_left' => 7],
  ];

  $expiredMembersList = [
    ['name' => 'William Taylor', 'plan' => 'Monthly', 'expired' => '2026-04-15', 'overdue' => '5 days'],
    ['name' => 'Olivia Anderson', 'plan' => 'Quarterly', 'expired' => '2026-04-10', 'overdue' => '10 days'],
  ];

  // TODAY'S CLASSES
  $todaysClasses = [
    ['class' => 'HIIT Training', 'trainer' => 'Mike Castillo', 'time' => '6:00 PM', 'capacity' => '18/20', 'status' => 'ongoing'],
    ['class' => 'Yoga Flow', 'trainer' => 'Sarah Lopez', 'time' => '7:00 PM', 'capacity' => '15/25', 'status' => 'upcoming'],
    ['class' => 'Boxing', 'trainer' => 'Alex Rivera', 'time' => '8:00 PM', 'capacity' => '10/15', 'status' => 'upcoming'],
    ['class' => 'Strength Training', 'trainer' => 'James Tan', 'time' => '9:00 PM', 'capacity' => '8/12', 'status' => 'upcoming'],
  ];

  // RECENT PAYMENTS
  $recentPayments = [
    ['name' => 'John M. Reyes', 'plan' => 'Annual Premium', 'amount' => 12000, 'date' => '2026-04-20', 'status' => 'paid'],
    ['name' => 'Maria S. Santos', 'plan' => 'Monthly', 'amount' => 1500, 'date' => '2026-04-19', 'status' => 'paid'],
    ['name' => 'Mike T. Cruz', 'plan' => 'Quarterly', 'amount' => 4200, 'date' => '2026-04-18', 'status' => 'paid'],
    ['name' => 'Sarah L. Gomez', 'plan' => 'Annual Premium', 'amount' => 12000, 'date' => '2026-04-17', 'status' => 'paid'],
    ['name' => 'James D. Lee', 'plan' => 'Monthly', 'amount' => 1500, 'date' => '2026-04-16', 'status' => 'pending'],
  ];

  $overduePaymentsList = [
    ['name' => 'Robert Johnson', 'amount' => 12000, 'due_date' => '2026-04-10', 'overdue' => '10 days'],
    ['name' => 'Emily Davis', 'amount' => 4200, 'due_date' => '2026-04-15', 'overdue' => '5 days'],
  ];

echo view('templates/myheader.php');
?>

<!-- Dashboard Content -->
<div class="container-fluid px-0">
  
  <!-- Welcome Section -->
  <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
    <div>
      <h4 class="fw-semibold mb-1" style="color: var(--gym-black);">Welcome back, <?= $full_name ?>!</h4>
      <p class="text-muted mb-0" style="font-size: 0.85rem;">Here's what's happening at MJESHTER FITNESS GYM today.</p>
    </div>
    <div>
      <span class="text-muted me-2"><i class="bi bi-calendar3"></i> <?= date('F d, Y') ?></span>
      <span class="text-muted"><i class="bi bi-clock"></i> <?= date('h:i A') ?></span>
    </div>
  </div>

  <!-- ============================================ -->
  <!-- FINANCIAL SECTION - EMPHASIZED (FULL WIDTH) -->
  <!-- ============================================ -->
  <div class="stat-card mb-4" style="background: linear-gradient(135deg, #6e6e6e 0%, #ffb8b8 100%); border: 1px solid rgba(220,38,38,0.3);">
    <div class="d-flex align-items-center justify-content-between mb-3">
      <h5 class="text-white mb-0"><i class="bi bi-graph-up me-2 text-danger"></i> FINANCIAL OVERVIEW</h5>
      <span class="badge bg-success">Live Data</span>
    </div>
    
    <div class="row g-4">
      <!-- Monthly Revenue Card -->
      <div class="col-xl-3 col-md-6">
        <div class="text-center text-md-start">
          <div class="text-muted small text-uppercase mb-1">Monthly Revenue</div>
          <div class="d-flex align-items-center justify-content-center justify-content-md-start gap-3">
            <h2 class="text-white mb-0 fw-bold">₱<?= number_format($monthlyRevenue) ?></h2>
            <span class="badge bg-success"><i class="bi bi-arrow-up"></i> <?= $monthlyRevenueGrowth ?>%</span>
          </div>
          <div class="mt-2">
            <div class="d-flex justify-content-between small text-muted mb-1">
              <span>Target: ₱<?= number_format($monthlyRevenueTarget) ?></span>
              <span><?= $monthlyRevenuePercent ?>% achieved</span>
            </div>
            <div class="progress" style="height: 4px;">
              <div class="progress-bar bg-danger" style="width: <?= $monthlyRevenuePercent ?>%"></div>
            </div>
          </div>
        </div>
      </div>
      
      <!-- YTD Revenue Card -->
      <div class="col-xl-3 col-md-6">
        <div class="text-center text-md-start">
          <div class="text-muted small text-uppercase mb-1">Year-to-Date Revenue</div>
          <h3 class="text-white mb-0 fw-bold">₱<?= number_format($ytdRevenue) ?></h3>
          <div class="mt-1">
            <span class="text-success small"><i class="bi bi-arrow-up"></i> <?= $ytdGrowth ?>% vs last year</span>
            <span class="text-muted small ms-2">(₱<?= number_format($ytdRevenueLastYear) ?>)</span>
          </div>
        </div>
      </div>
      
      <!-- Collection Status Card -->
      <div class="col-xl-3 col-md-6">
        <div class="text-center text-md-start">
          <div class="text-muted small text-uppercase mb-1">Collection Status</div>
          <div class="row g-2">
            <div class="col-6">
              <div class="small text-muted">Collected</div>
              <span class="text-success fw-bold">₱<?= number_format($collectedPayments) ?></span>
            </div>
            <div class="col-6">
              <div class="small text-muted">Pending</div>
              <span class="text-warning fw-bold">₱<?= number_format($pendingPayments) ?></span>
            </div>
            <div class="col-6">
              <div class="small text-muted">Overdue</div>
              <span class="text-danger fw-bold">₱<?= number_format($overduePayments) ?></span>
            </div>
            <div class="col-6">
              <div class="small text-muted">Collection Rate</div>
              <span class="text-white fw-bold"><?= round(($collectedPayments / ($collectedPayments + $pendingPayments + $overduePayments)) * 100) ?>%</span>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Key Metrics Card -->
      <div class="col-xl-3 col-md-6">
        <div class="text-center text-md-start">
          <div class="text-muted small text-uppercase mb-1">Key Metrics</div>
          <div class="row g-2">
            <div class="col-6">
              <div class="small text-muted">Avg Ticket</div>
              <span class="text-white fw-bold">₱<?= number_format($averageTicketSize) ?></span>
            </div>
            <div class="col-6">
              <div class="small text-muted">Active Subs</div>
              <span class="text-white fw-bold"><?= number_format($activeSubscriptions) ?></span>
            </div>
            <div class="col-12 mt-2">
              <div class="small text-muted">Projected Monthly</div>
              <span class="text-info fw-bold">₱<?= number_format($activeSubscriptions * $averageTicketSize) ?></span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>


  <!-- STATS CARDS ROW - Quick Metrics -->
  <div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6">
      <div class="stat-card">
        <div class="d-flex align-items-center justify-content-between">
          <div class="stat-icon"><i class="bi bi-people-fill"></i></div>
          <span class="trend-up text-success"><i class="bi bi-arrow-up"></i> 12.5%</span>
        </div>
        <div class="stat-value mt-2"><?= number_format($totalMembers) ?></div>
        <div class="stat-label">Total Members</div>
        <div class="mt-1"><small class="text-muted">+<?= $newMembersThisMonth ?> this month</small></div>
      </div>
    </div>
    <div class="col-xl-3 col-md-6">
      <div class="stat-card">
        <div class="d-flex align-items-center justify-content-between">
          <div class="stat-icon"><i class="bi bi-box-arrow-in-right"></i></div>
          <span class="trend-up text-success"><i class="bi bi-arrow-up"></i> 5.2%</span>
        </div>
        <div class="stat-value mt-2"><?= number_format($todayCheckins) ?></div>
        <div class="stat-label">Today's Check-ins</div>
        <div class="mt-1"><small class="text-success"><?= $currentlyInGym ?> currently inside</small></div>
      </div>
    </div>
    <div class="col-xl-3 col-md-6">
      <div class="stat-card">
        <div class="d-flex align-items-center justify-content-between">
          <div class="stat-icon"><i class="bi bi-box-arrow-right"></i></div>
          <span class="trend-down text-muted"><i class="bi bi-arrow-down"></i> 2.1%</span>
        </div>
        <div class="stat-value mt-2"><?= number_format($todayCheckouts) ?></div>
        <div class="stat-label">Today's Check-outs</div>
        <div class="mt-1"><small class="text-muted">Avg stay: 1.5 hrs</small></div>
      </div>
    </div>
    <div class="col-xl-3 col-md-6">
      <div class="stat-card">
        <div class="d-flex align-items-center justify-content-between">
          <div class="stat-icon"><i class="bi bi-exclamation-triangle-fill"></i></div>
          <span class="trend-down text-danger">Urgent</span>
        </div>
        <div class="stat-value mt-2 text-danger"><?= $expiringToday + $expiringThisWeek ?></div>
        <div class="stat-label">Expiring Soon</div>
        <div class="mt-1"><small class="text-danger"><?= $expiringToday ?> expire TODAY!</small></div>
      </div>
    </div>
  </div>

  <!-- SIMPLE COMPACT CHARTS ROW (Small, Business-Friendly) -->
  <div class="row g-4 mb-4">
    <div class="col-xl-5">
      <div class="stat-card">
        <div class="d-flex align-items-center justify-content-between mb-3">
          <h6 class="section-title mb-0"><i class="bi bi-graph-up me-1 text-danger"></i> Revenue Trend</h6>
          <select class="form-select form-select-sm" style="width: auto; font-size: 0.7rem;">
            <option>Last 6 Months</option>
            <option>This Year</option>
          </select>
        </div>
        <canvas id="revenueChart" style="height: 180px;"></canvas>
        <div class="text-center mt-2">
          <small class="text-muted">↑ 8.3% growth from last month</small>
        </div>
      </div>
    </div>
    <div class="col-xl-3">
      <div class="stat-card">
        <h6 class="section-title mb-3"><i class="bi bi-pie-chart-fill me-1 text-danger"></i> Membership</h6>
        <canvas id="membershipChart" style="height: 120px;"></canvas>
        <div class="row mt-2 text-center small">
          <div class="col-3"><span class="text-danger">●</span> <?= $membershipMonthly ?></div>
          <div class="col-3"><span class="text-danger">●</span> <?= $membershipQuarterly ?></div>
          <div class="col-3"><span class="text-danger">●</span> <?= $membershipAnnual ?></div>
          <div class="col-3"><span class="text-danger">●</span> <?= $membershipDayPass ?></div>
        </div>
      </div>
    </div>
    <div class="col-xl-4">
      <div class="stat-card">
        <h6 class="section-title mb-3"><i class="bi bi-cash-stack me-1 text-danger"></i> Financial Summary</h6>
        <div class="mb-2">
          <div class="d-flex justify-content-between small mb-1">
            <span>Collection Rate</span>
            <span class="fw-bold">92%</span>
          </div>
          <div class="progress" style="height: 4px;"><div class="progress-bar bg-success" style="width: 92%"></div></div>
        </div>
        <div class="mb-2">
          <div class="d-flex justify-content-between small mb-1">
            <span>Revenue vs Target</span>
            <span class="fw-bold"><?= $monthlyRevenuePercent ?>%</span>
          </div>
          <div class="progress" style="height: 4px;"><div class="progress-bar bg-danger" style="width: <?= $monthlyRevenuePercent ?>%"></div></div>
        </div>
        <div class="mt-3 pt-2 border-top">
          <div class="d-flex justify-content-between small">
            <span>Pending Collection</span>
            <span class="text-warning">₱<?= number_format($pendingPayments) ?></span>
          </div>
          <div class="d-flex justify-content-between small">
            <span>Overdue</span>
            <span class="text-danger">₱<?= number_format($overduePayments) ?></span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- RECENT CHECK-INS & CHECK-OUTS -->
  <div class="row g-4 mb-4">
    <div class="col-xl-6">
      <div class="stat-card">
        <h6 class="section-title mb-3"><i class="bi bi-box-arrow-in-right text-success me-1"></i> Recent Check-ins</h6>
        <div class="table-responsive">
          <table class="table table-sm table-borderless">
            <tbody>
              <?php foreach($recentCheckins as $ci): ?>
              <tr>
                <td><i class="bi bi-person-circle text-muted"></i> <?= $ci['name'] ?></td>
                <td><?= $ci['time'] ?></td>
                <td><span class="badge bg-success bg-opacity-10 text-success">Checked In</span></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        <div class="text-center mt-2"><a href="#" class="btn btn-outline-danger btn-sm">View All <i class="bi bi-arrow-right"></i></a></div>
      </div>
    </div>
    <div class="col-xl-6">
      <div class="stat-card">
        <h6 class="section-title mb-3"><i class="bi bi-box-arrow-right text-warning me-1"></i> Recent Check-outs</h6>
        <div class="table-responsive">
          <table class="table table-sm table-borderless">
            <tbody>
              <?php foreach($recentCheckouts as $co): ?>
              <tr>
                <td><i class="bi bi-person-circle text-muted"></i> <?= $co['name'] ?></td>
                <td><?= $co['time'] ?></td>
                <td><span class="badge bg-info bg-opacity-10 text-info">Checked Out</span></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        <div class="text-center mt-2"><a href="#" class="btn btn-outline-danger btn-sm">View All <i class="bi bi-arrow-right"></i></a></div>
      </div>
    </div>
  </div>

  <!-- URGENT: EXPIRING MEMBERSHIPS -->
  <div class="row g-4 mb-4">
    <div class="col-12">
      <div class="stat-card">
        <h6 class="section-title mb-3"><i class="bi bi-exclamation-triangle-fill text-danger me-1"></i> URGENT: Expiring Memberships</h6>
        
        <?php if(!empty($expiringTodayList)): ?>
        <div class="mb-3 p-3" style="background: #fee2e2; border-radius: 12px;">
          <strong class="text-danger">⚠️ EXPIRING TODAY</strong>
          <div class="table-responsive mt-2">
            <table class="table table-sm">
              <thead><tr><th>Member</th><th>Plan</th><th>Contact</th><th>Action</th></tr></thead>
              <tbody>
                <?php foreach($expiringTodayList as $exp): ?>
                <tr>
                  <td><?= $exp['name'] ?></td>
                  <td><?= $exp['plan'] ?></td>
                  <td><?= $exp['phone'] ?></td>
                  <td><button class="btn btn-danger btn-sm">Renew Now</button></td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
        <?php endif; ?>

        <div class="mb-3">
          <strong class="text-warning">⚠️ Expiring This Week (<?= count($expiringThisWeekList) ?> members)</strong>
          <div class="table-responsive mt-2">
            <table class="table table-sm">
              <thead><tr><th>Member</th><th>Plan</th><th>Days Left</th></tr></thead>
              <tbody>
                <?php foreach($expiringThisWeekList as $exp): ?>
                <tr>
                  <td><?= $exp['name'] ?></td>
                  <td><?= $exp['plan'] ?></td>
                  <td class="text-warning fw-bold"><?= $exp['days_left'] ?> days</td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- TODAY'S CLASSES & RECENT PAYMENTS -->
  <div class="row g-4 mb-4">
    <div class="col-xl-6">
      <div class="stat-card">
        <h6 class="section-title mb-3"><i class="bi bi-calendar-event me-1 text-danger"></i> Today's Classes</h6>
        <div class="table-responsive">
          <table class="table table-sm">
            <thead>
              <tr><th>Class</th><th>Trainer</th><th>Time</th><th>Status</th></tr>
            </thead>
            <tbody>
              <?php foreach($todaysClasses as $class): ?>
              <tr>
                <td><i class="bi bi-dumbbell text-danger me-1"></i> <?= $class['class'] ?></td>
                <td><?= $class['trainer'] ?></td>
                <td><?= $class['time'] ?></td>
                <td><span class="badge bg-<?= $class['status'] == 'ongoing' ? 'success' : 'warning' ?> bg-opacity-10 text-<?= $class['status'] == 'ongoing' ? 'success' : 'warning' ?>"><?= ucfirst($class['status']) ?></span></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <div class="col-xl-6">
      <div class="stat-card">
        <h6 class="section-title mb-3"><i class="bi bi-cash-stack me-1 text-danger"></i> Recent Payments</h6>
        <div class="table-responsive">
          <table class="table table-sm">
            <thead>
              <tr><th>Member</th><th>Amount</th><th>Date</th><th>Status</th></tr>
            </thead>
            <tbody>
              <?php foreach($recentPayments as $pay): ?>
              <tr>
                <td><?= $pay['name'] ?></td>
                <td>₱<?= number_format($pay['amount']) ?></td>
                <td><?= date('M d', strtotime($pay['date'])) ?></td>
                <td><span class="badge bg-<?= $pay['status'] == 'paid' ? 'success' : 'warning' ?> bg-opacity-10 text-<?= $pay['status'] == 'paid' ? 'success' : 'warning' ?>"><?= ucfirst($pay['status']) ?></span></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        <?php if(!empty($overduePaymentsList)): ?>
        <div class="mt-2 p-2 bg-light rounded">
          <small class="text-danger"><i class="bi bi-alarm"></i> Overdue: <?= count($overduePaymentsList) ?> payments</small>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </div>

</div>

<!-- Charts Scripts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  // Revenue Chart - Small & Simple
  const revenueCtx = document.getElementById('revenueChart').getContext('2d');
  new Chart(revenueCtx, {
    type: 'line',
    data: {
      labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
      datasets: [{
        label: 'Revenue',
        data: [245, 268, 289, 312, 328, 342.8],
        borderColor: '#dc2626',
        backgroundColor: 'rgba(220, 38, 38, 0.05)',
        borderWidth: 2,
        pointRadius: 3,
        pointBackgroundColor: '#dc2626',
        pointBorderColor: '#fff',
        tension: 0.3,
        fill: true
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: true,
      plugins: { legend: { display: false }, tooltip: { callbacks: { label: (ctx) => '₱' + ctx.raw + 'K' } } },
      scales: { y: { grid: { color: '#e5e7eb' }, ticks: { callback: (v) => '₱' + v + 'K' } }, x: { grid: { display: false } } }
    }
  });

  // Membership Chart - Small & Simple
  const membershipCtx = document.getElementById('membershipChart').getContext('2d');
  new Chart(membershipCtx, {
    type: 'doughnut',
    data: {
      labels: ['Monthly', 'Quarterly', 'Annual', 'Day Pass'],
      datasets: [{ data: [682, 445, 298, 122], backgroundColor: ['#dc2626', '#ef4444', '#f87171', '#fca5a5'], borderWidth: 0 }]
    },
    options: { responsive: true, maintainAspectRatio: true, plugins: { legend: { display: false } } }
  });
</script>

<?php echo view('templates/myfooter.php'); ?>