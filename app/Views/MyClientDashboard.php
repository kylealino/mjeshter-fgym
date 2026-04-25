<?php
$showDashboard = $showDashboard ?? false;
$rfid_uid = $rfid_uid ?? '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
<title><?= $showDashboard ? 'My Dashboard' : 'Client Check-In' ?> | MJESHTER FITNESS GYM</title>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<style>
  * { margin: 0; padding: 0; box-sizing: border-box; }

  body {
    font-family: 'Inter', sans-serif;
    min-height: 100vh;
    background: #f8f9fa;
  }

  /* Check-in Page Styles */
  .checkin-wrapper {
    display: flex;
    min-height: 100vh;
  }

  .left {
    flex: 1;
    background: linear-gradient(135deg, #0f0f10, #1a1a1c);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 60px;
    color: #fff;
  }

  .left-content { max-width: 500px; }

  .brand { display: flex; align-items: center; gap: 15px; margin-bottom: 25px; }

  .brand h1 { font-size: 28px; font-weight: 700; }

  .brand span { color: #c41e3a; }

  .desc { color: #9ca3af; font-size: 14px; margin-bottom: 40px; }

  .stats { display: flex; gap: 40px; }

  .stats strong { font-size: 20px; }

  .stats small { font-size: 11px; color: #6b7280; }

  .right {
    width: 420px;
    background: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: -5px 0 30px rgba(0,0,0,0.1);
  }

  .login-wrap { width: 100%; max-width: 320px; text-align: center; }

  .logo-area { text-align: center; margin-bottom: 32px; }

  .logo-icon {
    width: 70px; height: 70px;
    background: linear-gradient(135deg,#dc2626,#b91c1c);
    border-radius: 20px;
    display:flex;
    align-items:center;
    justify-content:center;
    margin:0 auto 16px;
  }

  .logo-icon i { font-size: 32px; color:#fff; }

  .header { text-align:center; margin-bottom:20px; }

  #rfid_input {
    opacity:0;
    position:absolute;
    height:1px;
    width:1px;
  }

  .scan-card-panel {
    background:#f9fafb;
    border-radius:24px;
    padding:28px 20px;
    border:2px solid #eef2f6;
    cursor:pointer;
    transition:0.3s;
  }

  .scan-icon { font-size:48px; color:#dc2626; margin-bottom:10px; }

  .scan-text { font-size:15px; font-weight:600; }

  .scan-status { font-size:12px; color:#6b7280; margin-top:5px; }

  .footer { font-size:11px; color:#9ca3af; margin-top:20px; }

  /* Dashboard Styles */
  .dashboard-navbar {
    background: #fff;
    border-bottom: 1px solid #e5e7eb;
    padding: 12px 24px;
    width: 100%;
  }
  
  .dashboard-container {
    padding: 24px;
    max-width: 1400px;
    margin: 0 auto;
  }
  
  .stat-card {
    background: #fff;
    border-radius: 16px;
    padding: 20px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    height: 100%;
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
  
  .stat-icon i { font-size: 24px; color: #dc2626; }
  .stat-value { font-size: 28px; font-weight: 700; color: #1a1a1a; }
  .stat-label { font-size: 12px; color: #6c757d; }
  
  .section-title {
    font-size: 16px;
    font-weight: 600;
    margin-bottom: 16px;
    padding-bottom: 8px;
    border-bottom: 2px solid #dc2626;
    display: inline-block;
  }
  
  .timer-alert {
    position: fixed;
    bottom: 20px;
    right: 20px;
    background: #fee2e2;
    border-radius: 12px;
    padding: 12px 20px;
    color: #dc2626;
    z-index: 999;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    font-size: 14px;
  }
  
  .btn-checkout {
    background: #dc2626;
    color: #fff;
    border: none;
    padding: 14px;
    border-radius: 12px;
    font-weight: 600;
    width: 100%;
    transition: all 0.3s;
  }
  
  .btn-checkout:hover {
    background: #b91c1c;
  }
  
  .info-row {
    display: flex;
    padding: 10px 0;
    border-bottom: 1px solid #e5e7eb;
  }
  
  .info-label {
    width: 140px;
    font-weight: 500;
    color: #6c757d;
  }
  
  .info-value {
    flex: 1;
    color: #1a1a1a;
  }
  
  .membership-card {
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
    border-radius: 16px;
    padding: 24px;
    color: #fff;
    margin-bottom: 24px;
  }
  
  @media (max-width: 768px) {
    .checkin-wrapper { flex-direction: column; }
    .right { width: 100%; padding: 40px; }
    .left { display: none; }
    .dashboard-container { padding: 16px; }
    .info-row { flex-direction: column; }
    .info-label { width: 100%; margin-bottom: 4px; }
  }
</style>
</head>
<body>

<?php if(!$showDashboard): ?>
<!-- CHECK-IN PAGE -->
<div class="checkin-wrapper">
  <div class="left">
    <div class="left-content">
      <div class="brand">
        <h1>MJESHTER <span>FITNESS GYM</span></h1>
      </div>
      <div class="desc">RFID Check-in system for members.</div>
      <div class="stats">
        <div><strong>1200+</strong><small>MEMBERS</small></div>
        <div><strong>98%</strong><small>ACTIVE</small></div>
        <div><strong>OPEN</strong><small>DAILY</small></div>
      </div>
    </div>
  </div>

  <div class="right">
    <div class="login-wrap">
      <div class="logo-area">
        <div class="logo-icon"><i class="bi bi-upc-scan"></i></div>
        <h2>MEMBER CHECK-IN</h2>
        <p>Tap RFID card</p>
      </div>
      <div class="header"><h3>Scan Card</h3></div>

      <form action="<?=site_url();?>myclientdashboard" method="post" id="checkinForm">
        <input type="hidden" name="rfid_uid" id="rfid_uid_hidden">
        <input type="hidden" name="meaction" value="PROCESS-CHECKIN">
        <input type="text" id="rfid_input" autocomplete="off">

        <div class="scan-card-panel" id="scanPanel">
          <div class="scan-icon"><i class="bi bi-credit-card-2-front"></i></div>
          <div class="scan-text" id="scanText">Ready</div>
          <div class="scan-status" id="scanStatus">Waiting RFID...</div>
        </div>
      </form>
      <div class="footer">MJESHTER FITNESS GYM © 2026</div>
    </div>
  </div>
</div>

<audio id="beepSound">
  <source src="https://actions.google.com/sounds/v1/alarms/beep_short.ogg" type="audio/ogg">
</audio>

<script>
toastr.options = { closeButton: true, progressBar: true, positionClass: "toast-top-right", timeOut: 3000 };

(function(){
  const form = document.getElementById('checkinForm');
  const input = document.getElementById('rfid_input');
  const hidden = document.getElementById('rfid_uid_hidden');
  const status = document.getElementById('scanStatus');
  const text = document.getElementById('scanText');
  const beep = document.getElementById('beepSound');
  const scanPanel = document.getElementById('scanPanel');

  let processing = false;

  function playBeep(){
    try { beep.currentTime = 0; beep.play(); } catch(e){}
  }

  function validateRFID(uid){
    if(processing) return;
    processing = true;

    playBeep();
    text.innerText = "Validating...";
    status.innerText = "Checking RFID card...";

    $.ajax({
      type: "POST",
      url: "<?=site_url();?>myclientdashboard",
      data: { rfid_uid: uid, meaction: 'VALIDATE-RFID' },
      dataType: "json",
      success: function(response){
        if(response.status === 'allowed') {
          text.innerText = "Access Granted";
          status.innerText = "Welcome " + response.member_name + "!";
          scanPanel.style.borderColor = "#28a745";
          hidden.value = uid;
          setTimeout(() => { form.submit(); }, 1000);
        } else {
          toastr.error(response.message, 'Access Denied');
          text.innerText = "Access Denied";
          status.innerText = response.message;
          scanPanel.style.borderColor = "#dc3545";
          processing = false;
          setTimeout(() => {
            text.innerText = "Ready";
            status.innerText = "Waiting RFID...";
            scanPanel.style.borderColor = "#eef2f6";
          }, 2000);
        }
      },
      error: function(){ toastr.error('Server error', 'Error'); processing = false; }
    });
  }

  input.addEventListener('keypress', function(e){
    if(e.key === 'Enter'){
      e.preventDefault();
      const uid = this.value.trim();
      this.value = '';
      if(uid !== '') validateRFID(uid);
    }
  });

  setInterval(() => { if(document.activeElement !== input && !processing) input.focus(); }, 500);
})();
</script>

<?php else: ?>
<!-- DASHBOARD PAGE -->
<nav class="dashboard-navbar">
  <div class="d-flex justify-content-between align-items-center">
    <div>
      <h4 class="mb-0" style="font-weight: 700;">MJESHTER <span style="color: #dc2626;">FITNESS GYM</span></h4>
    </div>
    <div class="d-flex align-items-center gap-3">
      <span class="text-muted"><?= $member['first_name'] . ' ' . $member['last_name']; ?></span>
      <a href="<?=site_url();?>myclientdashboard?meaction=LOGOUT" class="btn btn-sm btn-outline-danger">
        <i class="bi bi-box-arrow-right"></i> Logout
      </a>
    </div>
  </div>
</nav>

<div class="timer-alert" id="timerAlert">
  <i class="bi bi-clock-history me-2"></i> Auto logout in <strong id="timerCount">15</strong> seconds
</div>

<div class="dashboard-container">
  <!-- Welcome Section -->
  <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
    <div>
      <h4 class="fw-bold mb-1">Welcome back, <?= $member['first_name']; ?>!</h4>
      <p class="text-muted mb-0">Member since <?= date('F d, Y', strtotime($member['membership_start_date'])); ?></p>
    </div>
    <span class="badge bg-success px-3 py-2"><i class="bi bi-shield-check"></i> <?= $member['membership_status']; ?> Member</span>
  </div>

  <!-- Membership Card -->
  <div class="membership-card">
    <div class="row align-items-center">
      <div class="col-md-8">
        <div class="d-flex align-items-center gap-3">
          <div class="stat-icon" style="background: rgba(255,255,255,0.1);">
            <i class="bi bi-star-fill text-white"></i>
          </div>
          <div>
            <h5 class="text-white mb-0"><?= $member['membership_plan']; ?> Plan</h5>
            <p class="text-white-50 mb-0">Valid until <?= date('F d, Y', strtotime($member['membership_end_date'])); ?></p>
          </div>
        </div>
      </div>
      <div class="col-md-4 text-md-end mt-3 mt-md-0">
        <div class="small text-white-50">Today's Check-in</div>
        <span class="h3 text-white fw-bold"><?= $checkin_time; ?></span>
      </div>
    </div>
  </div>

  <!-- Stats Row -->
  <div class="row g-4 mb-4">
    <div class="col-md-4">
      <div class="stat-card text-center">
        <div class="stat-icon mx-auto mb-3"><i class="bi bi-calendar-check"></i></div>
        <div class="stat-value"><?= $yearlyCheckins; ?></div>
        <div class="stat-label">Check-ins in <?= $selectedYear; ?></div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="stat-card text-center">
        <div class="stat-icon mx-auto mb-3"><i class="bi bi-credit-card"></i></div>
        <div class="stat-value"><?= $member['membership_plan']; ?></div>
        <div class="stat-label">Current Plan</div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="stat-card text-center">
        <div class="stat-icon mx-auto mb-3"><i class="bi bi-person-badge"></i></div>
        <div class="stat-value"><?= $member['member_no']; ?></div>
        <div class="stat-label">Member ID</div>
      </div>
    </div>
  </div>

  <!-- Recent Check-ins -->
  <div class="stat-card mb-4">
    <h6 class="section-title"><i class="bi bi-clock-history me-2 text-danger"></i> Recent Check-ins</h6>
    <div class="table-responsive">
      <table class="table table-hover">
        <thead class="table-light">
          <tr><th>Date</th><th>Time In</th><th>Time Out</th><th>Duration</th></tr>
        </thead>
        <tbody>
          <?php foreach($recentCheckins as $check): ?>
          <tr>
            <td><?= date('M d, Y', strtotime($check['date'])); ?></td>
            <td><?= $check['time_in']; ?></td>
            <td><?= $check['time_out']; ?></td>
            <td><span class="badge bg-success bg-opacity-10 text-success px-3 py-2"><?= $check['duration']; ?></span></td>
          </tr>
          <?php endforeach; ?>
          <?php if(empty($recentCheckins)): ?>
          <tr><td colspan="4" class="text-center py-4">No check-ins yet</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Personal Information -->
  <div class="stat-card mb-4">
    <h6 class="section-title"><i class="bi bi-person-badge me-2 text-danger"></i> Personal Information</h6>
    <div class="info-row">
      <div class="info-label">Full Name</div>
      <div class="info-value"><?= $member['first_name'] . ' ' . $member['last_name']; ?></div>
    </div>
    <div class="info-row">
      <div class="info-label">Email Address</div>
      <div class="info-value"><?= $member['email']; ?></div>
    </div>
    <div class="info-row">
      <div class="info-label">Mobile Number</div>
      <div class="info-value"><?= $member['mobile_number']; ?></div>
    </div>
    <div class="info-row">
      <div class="info-label">Address</div>
      <div class="info-value"><?= $member['address'] ?? 'Not provided'; ?></div>
    </div>
  </div>

  <!-- Checkout Button -->
  <?php if($hasActiveSession ?? false): ?>
  <form action="<?=site_url();?>myclientdashboard" method="post">
    <input type="hidden" name="rfid_uid" value="<?= $rfid_uid; ?>">
    <input type="hidden" name="meaction" value="CHECKOUT">
    <button type="submit" class="btn-checkout">
      <i class="bi bi-box-arrow-right me-2"></i> Checkout Now
    </button>
  </form>
  <?php endif; ?>
</div>

<form id="autoLogoutForm" action="<?=site_url();?>myclientdashboard" method="post" style="display: none;">
  <input type="hidden" name="rfid_uid" value="<?= $rfid_uid; ?>">
  <input type="hidden" name="meaction" value="AUTO-LOGOUT">
</form>

<script>
let timer = 15;
const timerDisplay = document.getElementById('timerCount');
const timerAlert = document.getElementById('timerAlert');
let autoLogoutTriggered = false;

const countdown = setInterval(function() {
  timer--;
  timerDisplay.textContent = timer;
  
  if(timer <= 5 && timer > 0) {
    timerAlert.style.background = '#ffcccc';
    timerAlert.style.color = '#990000';
  }
  
  if(timer <= 0 && !autoLogoutTriggered) {
    clearInterval(countdown);
    autoLogoutTriggered = true;
    toastr.info('Auto logging out due to inactivity...', 'Session Expired');
    setTimeout(function() {
      document.getElementById('autoLogoutForm').submit();
    }, 1000);
  }
}, 1000);

function resetTimer() {
  if(autoLogoutTriggered) return;
  timer = 15;
  timerDisplay.textContent = timer;
  timerAlert.style.background = '#fee2e2';
  timerAlert.style.color = '#dc2626';
}

document.onmousemove = resetTimer;
document.onkeypress = resetTimer;
document.onclick = resetTimer;
document.onscroll = resetTimer;
</script>

<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>