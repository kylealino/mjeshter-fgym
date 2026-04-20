<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Login - MJESHTER FITNESS GYM</title>

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<!-- Google Fonts -->
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
  background: #0a0a0a;
}

/* ============================================ */
/* LEFT SIDE - BRAND SECTION (Premium) */
/* ============================================ */
.left {
  flex: 1;
  background: linear-gradient(135deg, #0a0a0a 0%, #111111 50%, #1a1a1a 100%);
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 3rem;
  position: relative;
  overflow: hidden;
}

/* Animated Background Effect */
.left::before {
  content: '';
  position: absolute;
  top: -50%;
  left: -50%;
  width: 200%;
  height: 200%;
  background: radial-gradient(circle at 30% 50%, rgba(220, 38, 38, 0.08) 0%, transparent 50%);
  animation: slowDrift 20s ease-in-out infinite;
}

@keyframes slowDrift {
  0%, 100% { transform: translate(0, 0); }
  50% { transform: translate(-2%, -2%); }
}

.left-content {
  max-width: 480px;
  position: relative;
  z-index: 1;
}

/* Brand Logo Area */
.brand {
  display: flex;
  align-items: center;
  gap: 16px;
  margin-bottom: 32px;
}

.brand-box {
  width: 56px;
  height: 56px;
  background: linear-gradient(135deg, #dc2626, #b91c1c);
  border-radius: 16px;
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0 10px 25px -5px rgba(220, 38, 38, 0.3);
}

.brand-box i {
  font-size: 28px;
  color: white;
}

.brand h1 {
  font-size: 28px;
  font-weight: 700;
  color: white;
  letter-spacing: -0.5px;
}

.brand h1 span {
  color: #dc2626;
}

/* Description */
.desc {
  color: #9ca3af;
  font-size: 15px;
  line-height: 1.6;
  margin-bottom: 48px;
}

/* Stats Cards */
.stats {
  display: flex;
  gap: 48px;
  margin-bottom: 48px;
}

.stat-item {
  background: rgba(255, 255, 255, 0.03);
  border-radius: 20px;
  padding: 20px 24px;
  text-align: center;
  border: 1px solid rgba(255, 255, 255, 0.06);
  transition: all 0.3s ease;
  flex: 1;
}

.stat-item:hover {
  background: rgba(255, 255, 255, 0.05);
  border-color: rgba(220, 38, 38, 0.3);
  transform: translateY(-2px);
}

.stat-value {
  font-size: 28px;
  font-weight: 800;
  color: white;
  margin-bottom: 6px;
}

.stat-value i {
  font-size: 20px;
  color: #dc2626;
  margin-right: 6px;
}

.stat-label {
  font-size: 11px;
  font-weight: 600;
  color: #6b7280;
  text-transform: uppercase;
  letter-spacing: 1px;
}

/* Features List */
.features {
  display: flex;
  flex-direction: column;
  gap: 16px;
  margin-top: 40px;
  padding-top: 32px;
  border-top: 1px solid rgba(255, 255, 255, 0.06);
}

.feature-item {
  display: flex;
  align-items: center;
  gap: 12px;
  color: #9ca3af;
  font-size: 13px;
}

.feature-item i {
  font-size: 18px;
  color: #dc2626;
}

/* ============================================ */
/* RIGHT SIDE - LOGIN FORM (Premium) */
/* ============================================ */
.right {
  width: 480px;
  background: white;
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: -5px 0 30px rgba(0, 0, 0, 0.1);
}

.login-wrap {
  width: 100%;
  max-width: 360px;
  padding: 2rem;
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
  margin-bottom: 32px;
}

.header h3 {
  font-size: 24px;
  font-weight: 700;
  color: #111;
  margin-bottom: 8px;
}

.header p {
  font-size: 13px;
  color: #6b7280;
}

/* Form Elements */
.form-group {
  margin-bottom: 20px;
}

.form-group label {
  font-size: 12px;
  font-weight: 600;
  color: #374151;
  margin-bottom: 8px;
  display: block;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.input-wrapper {
  position: relative;
}

.input-wrapper i {
  position: absolute;
  left: 14px;
  top: 50%;
  transform: translateY(-50%);
  color: #9ca3af;
  font-size: 16px;
  transition: color 0.2s ease;
}

.form-control {
  width: 100%;
  padding: 12px 12px 12px 42px;
  border: 1.5px solid #e5e7eb;
  border-radius: 12px;
  font-size: 14px;
  outline: none;
  transition: all 0.2s ease;
  background: white;
}

.form-control:focus {
  border-color: #dc2626;
  box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
}

.form-control:focus + i {
  color: #dc2626;
}

/* Options Row */
.options {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 24px;
  font-size: 13px;
}

.checkbox-label {
  display: flex;
  align-items: center;
  gap: 8px;
  cursor: pointer;
  color: #4b5563;
}

.checkbox-label input {
  width: 16px;
  height: 16px;
  accent-color: #dc2626;
  cursor: pointer;
}

.forgot-link {
  color: #dc2626;
  text-decoration: none;
  font-weight: 500;
  transition: color 0.2s;
}

.forgot-link:hover {
  color: #b91c1c;
  text-decoration: underline;
}

/* Login Button */
.btn-login {
  width: 100%;
  padding: 14px;
  background: #111;
  color: white;
  border: none;
  border-radius: 12px;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
}

.btn-login:hover {
  background: #dc2626;
  transform: translateY(-1px);
  box-shadow: 0 5px 15px -3px rgba(220, 38, 38, 0.4);
}

.btn-login:active {
  transform: translateY(0);
}

/* Footer */
.footer {
  text-align: center;
  margin-top: 32px;
  padding-top: 24px;
  border-top: 1px solid #e5e7eb;
  font-size: 11px;
  color: #9ca3af;
}

.footer p {
  line-height: 1.5;
}

/* ============================================ */
/* RESPONSIVE DESIGN */
/* ============================================ */
@media (max-width: 1024px) {
  .stats {
    gap: 24px;
  }
  .stat-item {
    padding: 16px;
  }
}

@media (max-width: 900px) {
  .left {
    display: none;
  }
  .right {
    width: 100%;
  }
  .login-wrap {
    max-width: 400px;
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
    font-size: 22px;
  }
}
</style>
</head>

<body>

<!-- LEFT SIDE - Brand Section -->
<div class="left">
  <div class="left-content">
    <div class="brand">
      <h1>MJESHTER <span>FITNESS GYM</span></h1>
    </div>

    <div class="desc">
      Enterprise gym management system. Complete control over members, payments, classes, and operations — all in one platform.
    </div>

    <div class="stats">
      <div class="stat-item">
        <div class="stat-value">
          <i class="bi bi-people-fill"></i> 1,200+
        </div>
        <div class="stat-label">ACTIVE MEMBERS</div>
      </div>
      <div class="stat-item">
        <div class="stat-value">
          <i class="bi bi-graph-up"></i> 98%
        </div>
        <div class="stat-label">RETENTION RATE</div>
      </div>
      <div class="stat-item">
        <div class="stat-value">
          <i class="bi bi-clock-fill"></i> 24/7
        </div>
        <div class="stat-label">SYSTEM ACCESS</div>
      </div>
    </div>

    <div class="features">
      <div class="feature-item">
        <i class="bi bi-check-circle-fill"></i>
        <span>Real-time member tracking & check-ins</span>
      </div>
      <div class="feature-item">
        <i class="bi bi-check-circle-fill"></i>
        <span>Automated billing & payment processing</span>
      </div>
      <div class="feature-item">
        <i class="bi bi-check-circle-fill"></i>
        <span>Class scheduling & trainer management</span>
      </div>
      <div class="feature-item">
        <i class="bi bi-check-circle-fill"></i>
        <span>Comprehensive financial reporting</span>
      </div>
    </div>
  </div>
</div>

<!-- RIGHT SIDE - Login Form -->
<div class="right">
  <div class="login-wrap">
    <div class="logo-area">
      <div class="logo-icon">
        <i class="bi bi-shield-lock-fill"></i>
      </div>
      <h2>ADMIN PANEL</h2>
      <p>Secure access for authorized personnel only</p>
    </div>

    <div class="header">
      <h3>Welcome Back</h3>
      <p>Sign in to continue</p>
    </div>

    <form action="<?=site_url();?>mylogin-auth" method="post" novalidate>
      <div class="form-group">
        <label>Username / Email</label>
        <div class="input-wrapper">
          <i class="bi bi-person"></i>
          <input type="text" id="MyUsername" name="MyUsername" class="form-control" placeholder="Enter your username" autocomplete="off">
        </div>
      </div>

      <div class="form-group">
        <label>Password</label>
        <div class="input-wrapper">
          <i class="bi bi-lock"></i>
          <input type="password" id="MyPassword" name="MyPassword" class="form-control" placeholder="Enter your password">
        </div>
      </div>

      <div class="options">
        <label class="checkbox-label">
          <input type="checkbox" name="remember"> Remember me
        </label>
        <a href="#" class="forgot-link">Forgot password?</a>
      </div>

      <button type="submit" class="btn-login">
        <i class="bi bi-box-arrow-in-right"></i> Sign In
      </button>

      <div class="footer">
        <p>MJESHTER FITNESS GYM<br>Enterprise Management System v2.0</p>
        <p style="margin-top: 8px;">© 2026 All Rights Reserved</p>
      </div>
    </form>
  </div>
</div>

<!-- Toastr Notifications (for login errors) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

<script>
// Toastr Configuration
toastr.options = {
  closeButton: true,
  progressBar: true,
  positionClass: "toast-top-right",
  timeOut: 4000,
  showDuration: 300,
  hideDuration: 1000
};

// Login Form Handler
$('form').on('submit', function(e) {
  const username = $('#MyUsername').val().trim();
  const password = $('#MyPassword').val();
  
  if (!username) {
    e.preventDefault();
    toastr.warning('Please enter your username', 'Missing Information');
    $('#MyUsername').focus();
  } else if (!password) {
    e.preventDefault();
    toastr.warning('Please enter your password', 'Missing Information');
    $('#MyPassword').focus();
  }
});

// PHP Flashdata for login errors (uncomment when integrated)
<?php if(session()->getFlashdata('login_error')): ?>
  toastr.error('<?= session()->getFlashdata('login_error') ?>', 'Login Failed');
<?php endif; ?>
</script>

</body>
</html>