<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>MJESHTER FITNESS GYM</title>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: 'Inter', sans-serif;
}

body {
  display: flex;
  min-height: 100vh;
  background: #0f0f10;
}

/* LEFT */
.left {
  flex: 1;
  background: linear-gradient(135deg, #0f0f10, #1a1a1c);
  display: flex;
  align-items: center;
  padding: 60px;
  color: #fff;
}

.left-content { max-width: 500px; }

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

.brand span { color: #c41e3a; }

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

/* RIGHT */
.right {
  width: 420px;
  background: #fff;
  display: flex;
  align-items: center;
  justify-content: center;
}

.login-wrap {
  width: 100%;
  max-width: 320px;
  text-align: center;
}

/* LOGO */
.logo img {
  width: 60px;
  margin-bottom: 10px;
}

.logo h2 {
  font-size: 18px;
  font-weight: 700;
}

/* HEADER */
.header {
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

/* RFID INPUT */
#rfid_input {
  opacity: 0;
  position: absolute;
}

/* SCAN BOX */
.scan-box {
  border: 2px dashed #e5e7eb;
  padding: 30px 20px;
  border-radius: 12px;
  margin-bottom: 15px;
  transition: 0.3s;
  position: relative;
}

/* Glow effect */
.scan-box.active {
  border-color: #c41e3a;
  box-shadow: 0 0 15px rgba(196,30,58,0.25);
}

/* ICON */
.scan-icon {
  font-size: 36px;
  color: #c41e3a;
  margin-bottom: 10px;
  animation: pulse 1.5s infinite;
}

/* TEXT */
.scan-text {
  font-size: 13px;
  color: #6b7280;
}

.scan-status {
  font-size: 12px;
  margin-top: 8px;
  color: #9ca3af;
}

/* SUCCESS STATE */
.success {
  color: #16a34a !important;
}

/* ANIMATION */
@keyframes pulse {
  0% { transform: scale(1); opacity: 0.8; }
  50% { transform: scale(1.15); opacity: 1; }
  100% { transform: scale(1); opacity: 0.8; }
}

/* FOOTER */
.footer {
  font-size: 11px;
  color: #9ca3af;
  margin-top: 20px;
}

@media(max-width: 900px) {
  .left { display: none; }
  .right { width: 100%; }
}
</style>
</head>

<body>

<!-- LEFT -->
<div class="left">
  <div class="left-content">
    <div class="brand">
      <div class="brand-box"></div>
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

<!-- RIGHT -->
<div class="right">
  <div class="login-wrap">

    <div class="logo">
      <img src="<?=site_url('assets/images/logos/barbell.png')?>">
    </div>

    <div class="header">
      <h3>Tap to Check In</h3>
      <p>Scan your RFID card</p>
    </div>

    <!-- INPUT -->
    <input type="text" id="rfid_input" autofocus>

    <!-- SCAN BOX -->
    <div class="scan-box" id="scanBox">
      <div class="scan-icon"><i class="bi bi-credit-card"></i></div>
      <div class="scan-text" id="scanText">Waiting for card...</div>
      <div class="scan-status" id="scanStatus">Idle</div>
    </div>

    <div class="footer">
      MJESHTER FITNESS GYM<br>
      © 2026
    </div>

  </div>
</div>

<script>
const input = document.getElementById('rfid_input');
const box = document.getElementById('scanBox');
const text = document.getElementById('scanText');
const status = document.getElementById('scanStatus');

input.focus();

input.addEventListener('keypress', function(e) {
  if (e.key === 'Enter') {
    let uid = this.value.trim();

    if(uid !== '') {

      // ACTIVE STATE
      box.classList.add('active');
      text.innerHTML = "Processing...";
      status.innerHTML = "Reading card...";

      console.log("UID:", uid);

      // SIMULATE SUCCESS
      setTimeout(() => {
        text.innerHTML = "Check-in Successful";
        status.innerHTML = "UID: " + uid;
        status.classList.add('success');

        box.classList.remove('active');

        // RESET AFTER 2s
        setTimeout(() => {
          text.innerHTML = "Waiting for card...";
          status.innerHTML = "Idle";
          status.classList.remove('success');
        }, 2000);

      }, 500);

    }

    this.value = '';
  }
});
</script>

</body>
</html>