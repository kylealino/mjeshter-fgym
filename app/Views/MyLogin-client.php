<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
<title>Client Check-In | MJESHTER FITNESS GYM</title>

<!-- Bootstrap Icons & Google Fonts -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

<style>
  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
  }

  body {
    font-family: 'Inter', sans-serif;
    min-height: 100vh;
    display: flex;
    background: #0f0f10;
  }

  /* ============================================ */
  /* LEFT SIDE - ORIGINAL DESIGN (centered, like before) */
  /* ============================================ */
  .left {
    flex: 1;
    background: linear-gradient(135deg, #0f0f10, #1a1a1c);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 60px;
    color: #fff;
  }

  .left-content {
    max-width: 500px;
  }

  .brand {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 25px;
  }

  .brand-box {
    width: 45px;
    height: 45px;
    background: #c41e3a;
    border-radius: 10px;
  }

  .brand h1 {
    font-size: 28px;
    font-weight: 700;
  }

  .brand span {
    color: #c41e3a;
  }

  .desc {
    color: #9ca3af;
    font-size: 14px;
    margin-bottom: 40px;
  }

  .stats {
    display: flex;
    gap: 40px;
  }

  .stats strong {
    font-size: 20px;
  }

  .stats small {
    font-size: 11px;
    color: #6b7280;
  }

  /* ============================================ */
  /* RIGHT SIDE - CHECK-IN FORM */
  /* ============================================ */
  .right {
    width: 420px;
    background: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: -5px 0 30px rgba(0, 0, 0, 0.1);
  }

  .login-wrap {
    width: 100%;
    max-width: 320px;
    text-align: center;
  }

  /* Logo Area */
  .logo-area {
    text-align: center;
    margin-bottom: 32px;
  }

  .logo-icon {
    width: 70px;
    height: 70px;
    background: linear-gradient(135deg, #dc2626, #b91c1c);
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 16px;
    box-shadow: 0 10px 25px -5px rgba(220, 38, 38, 0.3);
  }

  .logo-icon i {
    font-size: 32px;
    color: white;
  }

  .logo-area h2 {
    font-size: 20px;
    font-weight: 700;
    color: #111;
    letter-spacing: 1px;
  }

  .logo-area p {
    font-size: 12px;
    color: #6b7280;
    margin-top: 4px;
  }

  /* Header */
  .header {
    text-align: center;
    margin-bottom: 20px;
  }

  .header h3 {
    font-size: 20px;
    font-weight: 600;
  }

  .header p {
    font-size: 13px;
    color: #6b7280;
  }

  /* Hidden RFID input */
  #rfid_input {
    opacity: 0;
    position: absolute;
    height: 1px;
    width: 1px;
    pointer-events: auto;
    z-index: -1;
  }

  /* Scan Card Panel - Heartbeat Button */
  .scan-card-panel {
    background: #f9fafb;
    border-radius: 24px;
    padding: 28px 20px;
    text-align: center;
    border: 2px solid #eef2f6;
    transition: all 0.25s cubic-bezier(0.2, 0.9, 0.4, 1.1);
    margin-bottom: 15px;
    cursor: pointer;
    position: relative;
    overflow: hidden;
    width: 100%;
    font-family: inherit;
  }

  .scan-card-panel:focus-visible {
    outline: 3px solid #dc2626;
    outline-offset: 2px;
  }

  /* IDLE HEARTBEAT - gentle pulsing like before */
  .scan-card-panel.idle-heartbeat {
    animation: heartbeatIdle 1.8s ease-in-out infinite;
    border-color: rgba(220, 38, 38, 0.35);
    background: linear-gradient(145deg, #ffffff, #fefafb);
  }

  @keyframes heartbeatIdle {
    0% {
      transform: scale(1);
      box-shadow: 0 0 0 0 rgba(220, 38, 38, 0.1);
      border-color: rgba(220, 38, 38, 0.25);
    }
    50% {
      transform: scale(1.01);
      box-shadow: 0 8px 20px -6px rgba(220, 38, 38, 0.25);
      border-color: rgba(220, 38, 38, 0.55);
    }
    100% {
      transform: scale(1);
      box-shadow: 0 0 0 0 rgba(220, 38, 38, 0.05);
      border-color: rgba(220, 38, 38, 0.25);
    }
  }

  /* Active scanning - faster pulse */
  .scan-card-panel.active-scan {
    border-color: #dc2626;
    background: linear-gradient(145deg, #ffffff, #fff5f5);
    box-shadow: 0 12px 28px -8px rgba(220, 38, 38, 0.4);
    animation: scanPulse 0.7s ease-in-out infinite;
    cursor: wait;
  }

  @keyframes scanPulse {
    0% {
      transform: scale(1);
      box-shadow: 0 0 0 0 rgba(220, 38, 38, 0.4);
    }
    50% {
      transform: scale(1.02);
      box-shadow: 0 0 0 8px rgba(220, 38, 38, 0.15);
    }
    100% {
      transform: scale(1);
      box-shadow: 0 0 0 0 rgba(220, 38, 38, 0);
    }
  }

  /* Success state */
  .scan-card-panel.success-scan {
    border-color: #16a34a;
    background: #f0fdf4;
    box-shadow: 0 12px 20px -10px rgba(22, 163, 74, 0.3);
    animation: successBouncePanel 0.6s cubic-bezier(0.34, 1.2, 0.64, 1);
    cursor: default;
  }

  @keyframes successBouncePanel {
    0% { transform: scale(1); }
    50% { transform: scale(1.03); background: #e8f5e9; }
    100% { transform: scale(1); }
  }

  /* Icon animations */
  .scan-card-panel.idle-heartbeat .scan-icon {
    animation: iconHeartbeat 1.8s ease-in-out infinite;
  }

  @keyframes iconHeartbeat {
    0% { transform: scale(1); opacity: 0.85; }
    50% { transform: scale(1.12); opacity: 1; text-shadow: 0 0 5px rgba(220,38,38,0.3); }
    100% { transform: scale(1); opacity: 0.85; }
  }

  .scan-card-panel.active-scan .scan-icon {
    animation: iconFastBeat 0.7s ease-in-out infinite;
  }

  @keyframes iconFastBeat {
    0% { transform: scale(1); }
    50% { transform: scale(1.18); color: #b91c1c; }
    100% { transform: scale(1); }
  }

  .scan-card-panel.success-scan .scan-icon {
    animation: successIconPop 0.5s ease-out;
    color: #16a34a;
  }

  @keyframes successIconPop {
    0% { transform: scale(1); }
    50% { transform: scale(1.25); }
    100% { transform: scale(1); }
  }

  .scan-icon {
    font-size: 48px;
    color: #dc2626;
    margin-bottom: 12px;
    display: inline-block;
    transition: all 0.2s;
  }

  .scan-text {
    font-size: 15px;
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 6px;
    transition: all 0.2s;
  }

  .scan-status {
    font-size: 12px;
    font-weight: 500;
    color: #6b7280;
    transition: color 0.2s, font-weight 0.2s;
    letter-spacing: 0.3px;
  }

  .scan-status.success-status {
    color: #16a34a !important;
    font-weight: 600;
  }

  .scan-hint {
    font-size: 11px;
    color: #9ca3af;
    margin-top: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
  }

  .scan-hint i {
    font-size: 12px;
    color: #dc2626;
    transition: transform 0.2s;
  }

  .scan-card-panel:hover .scan-hint i {
    transform: translateX(3px);
  }

  /* Footer */
  .footer {
    font-size: 11px;
    color: #9ca3af;
    margin-top: 20px;
  }

  /* Responsive */
  @media (max-width: 900px) {
    .left {
      display: none;
    }
    .right {
      width: 100%;
    }
    .login-wrap {
      max-width: 350px;
    }
  }

  @media (max-width: 480px) {
    .right {
      padding: 1rem;
    }
    .login-wrap {
      padding: 1rem;
    }
    .header h3 {
      font-size: 20px;
    }
    .scan-card-panel {
      padding: 20px 16px;
    }
    .scan-icon {
      font-size: 42px;
    }
  }

  .scan-card-panel:active {
    transform: scale(0.98);
    transition: transform 0.08s;
  }
</style>
</head>
<body>

<!-- LEFT SIDE - Original Design (centered, like before) -->
<div class="left">
  <div class="left-content">
    <div class="brand">
      <h1>MJESHTER <span>FITNESS GYM</span></h1>
    </div>
    <div class="desc">
      Gym check-in system. Tap your RFID card to record attendance instantly.
    </div>
    <div class="stats">
      <div>
        <strong>1,200+</strong>
        <small>MEMBERS</small>
      </div>
      <div>
        <strong>98%</strong>
        <small>ACTIVE</small>
      </div>
      <div>
        <strong>OPEN</strong>
        <small>DAILY</small>
      </div>
    </div>
  </div>
</div>

<!-- RIGHT SIDE - Check-in Form -->
<div class="right">
  <div class="login-wrap">
    <div class="logo-area">
      <div class="logo-icon">
        <i class="bi bi-upc-scan"></i>
      </div>
      <h2>MEMBER CHECK-IN</h2>
      <p>Tap or click to check in</p>
    </div>

    <div class="header">
      <h3>Tap to Check In</h3>
      <p>Scan your RFID card</p>
    </div>

    <!-- Form that submits to myclientdashboard -->
    <form action="<?=site_url();?>myclientdashboard" method="post" novalidate id="checkinForm">
      <input type="hidden" name="rfid_uid" id="rfid_uid_hidden" value="">
      <input type="hidden" name="checkin_source" value="button_rfid">
      
      <!-- Hidden RFID input for physical scanners -->
      <input type="text" id="rfid_input" autocomplete="off" aria-label="RFID Scanner">
      
      <!-- Heartbeat Button Scanner -->
      <button type="submit" class="scan-card-panel idle-heartbeat" id="scanPanel">
        <div class="scan-icon">
          <i class="bi bi-credit-card-2-front"></i>
        </div>
        <div class="scan-text" id="scanText">Ready to scan</div>
        <div class="scan-status" id="scanStatus">Waiting for card</div>
        <div class="scan-hint">
          <i class="bi bi-tap"></i> Tap RFID card or click here
        </div>
      </button>
    </form>

    <div class="footer">
      MJESHTER FITNESS GYM<br>
      © 2026
    </div>
  </div>
</div>

<script>
  (function() {
    // DOM elements
    const form = document.getElementById('checkinForm');
    const submitButton = document.getElementById('scanPanel');
    const hiddenUidInput = document.getElementById('rfid_uid_hidden');
    const rfidScannerInput = document.getElementById('rfid_input');
    const scanTextEl = document.getElementById('scanText');
    const scanStatusEl = document.getElementById('scanStatus');
    
    // State management
    let isProcessing = false;
    let resetTimer = null;
    let pendingSubmission = false;

    // Helper: clear any pending reset timer
    function clearResetTimer() {
      if (resetTimer) {
        clearTimeout(resetTimer);
        resetTimer = null;
      }
    }

    // Reset to idle state with heartbeat animation
    function resetToIdle() {
      if (!submitButton) return;
      submitButton.classList.remove('active-scan');
      submitButton.classList.remove('success-scan');
      submitButton.classList.add('idle-heartbeat');
      scanStatusEl.classList.remove('success-status');
      
      scanTextEl.innerText = 'Ready to scan';
      scanStatusEl.innerText = 'Waiting for card';
      
      const iconSpan = submitButton.querySelector('.scan-icon i');
      if (iconSpan) {
        iconSpan.className = 'bi bi-credit-card-2-front';
      }
      
      isProcessing = false;
      pendingSubmission = false;
      if (rfidScannerInput) rfidScannerInput.value = '';
      if (rfidScannerInput) rfidScannerInput.focus();
    }

    // Show processing / scanning state
    function setProcessingState() {
      clearResetTimer();
      submitButton.classList.remove('idle-heartbeat');
      submitButton.classList.remove('success-scan');
      submitButton.classList.add('active-scan');
      scanStatusEl.classList.remove('success-status');
      
      scanTextEl.innerText = 'Processing...';
      scanStatusEl.innerText = 'Reading card...';
      
      const iconSpan = submitButton.querySelector('.scan-icon i');
      if (iconSpan) {
        iconSpan.className = 'bi bi-upc-scan';
      }
    }

    // Show success state before form submission
    function setSuccessState(uidValue) {
      submitButton.classList.remove('active-scan');
      submitButton.classList.remove('idle-heartbeat');
      submitButton.classList.add('success-scan');
      scanTextEl.innerText = 'Check-in Successful';
      scanStatusEl.innerText = `UID: ${uidValue}`;
      scanStatusEl.classList.add('success-status');
      
      const iconSpan = submitButton.querySelector('.scan-icon i');
      if (iconSpan) {
        iconSpan.className = 'bi bi-check-circle-fill';
      }
      
      submitButton.style.transform = 'scale(1.01)';
      setTimeout(() => {
        if (submitButton) submitButton.style.transform = '';
      }, 200);
    }

    // Actual form submission function
    function submitFormWithUid(uid) {
      if (!form) return;
      if (hiddenUidInput) {
        hiddenUidInput.value = uid;
      }
      form.submit();
    }

    // Core check-in logic
    function performCheckIn(uid) {
      if (isProcessing) return;
      if (!uid || uid.trim() === '') {
        uid = 'MEMBER-' + Math.floor(Math.random() * 100000);
      }
      
      isProcessing = true;
      pendingSubmission = true;
      setProcessingState();
      
      // Simulate backend validation delay
      setTimeout(() => {
        setSuccessState(uid);
        clearResetTimer();
        resetTimer = setTimeout(() => {
          submitFormWithUid(uid);
        }, 800);
      }, 500);
    }

    // Button click event
    if (submitButton) {
      submitButton.addEventListener('click', function(e) {
        e.preventDefault();
        if (isProcessing) return;
        if (submitButton.classList.contains('success-scan')) return;
        
        let uid = '';
        if (rfidScannerInput && rfidScannerInput.value.trim() !== '') {
          uid = rfidScannerInput.value.trim();
        } else if (hiddenUidInput && hiddenUidInput.value.trim() !== '') {
          uid = hiddenUidInput.value.trim();
        } else {
          uid = 'CLICK-' + Date.now();
        }
        
        performCheckIn(uid);
        if (rfidScannerInput) rfidScannerInput.value = '';
      });
    }

    // RFID input event
    if (rfidScannerInput) {
      rfidScannerInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
          const uid = this.value.trim();
          if (uid !== '') {
            if (isProcessing) {
              this.value = '';
              return;
            }
            performCheckIn(uid);
          }
          this.value = '';
        }
      });
    }
    
    // Maintain focus for physical RFID readers
    function maintainFocus() {
      if (rfidScannerInput && document.activeElement !== rfidScannerInput && !isProcessing) {
        rfidScannerInput.focus();
      }
    }
    
    // Click on right area focuses hidden input
    const rightSide = document.querySelector('.right');
    if (rightSide) {
      rightSide.addEventListener('click', function(e) {
        if (e.target.closest('#scanPanel')) return;
        if (rfidScannerInput) rfidScannerInput.focus();
      });
    }
    
    // Blur event: bring focus back
    if (rfidScannerInput) {
      rfidScannerInput.addEventListener('blur', function() {
        setTimeout(() => {
          if (document.activeElement !== rfidScannerInput && !isProcessing && !pendingSubmission) {
            rfidScannerInput.focus();
          }
        }, 50);
      });
    }
    
    // Initial focus
    if (rfidScannerInput) rfidScannerInput.focus();
    
    // Form submission validation
    if (form) {
      form.addEventListener('submit', function(e) {
        if (!hiddenUidInput.value || hiddenUidInput.value === '') {
          e.preventDefault();
          if (!isProcessing) {
            scanTextEl.innerText = 'Error: No RFID detected';
            scanStatusEl.innerText = 'Please tap your card again';
            setTimeout(() => {
              if (!isProcessing) resetToIdle();
            }, 1500);
          }
        }
      });
    }
    
    console.log("Heartbeat Scanner Active — Left side original design restored.");
  })();
</script>
</body>
</html>