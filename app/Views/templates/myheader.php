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
?>
<!DOCTYPE html>
<html lang="en" dir="ltr" data-bs-theme="light" data-color-theme="dark_theme" data-layout="vertical">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="shortcut icon" type="image/png" href="<?=base_url('assets/images/logos/gym-logo.png')?>" />
  <link rel="stylesheet" href="<?=base_url('assets/css/styles.css')?>" />
  <title>MJESHTER FITNESS GYM | Management System</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <!-- Tabler Icons (Free, no CDN issues) -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@2.30.0/tabler-icons.min.css">

  <!-- Bootstrap Icons (for bi-xxx icons) -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<!-- Bootstrap CSS (for grid, cards, buttons) -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <!-- Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    /* ===================== */
    /* MJESHTER FITNESS GYM - MINIMALIST BLACK/RED/WHITE THEME */
    /* ===================== */
    :root {
      --gym-red: #dc2626;
      --gym-red-dark: #b91c1c;
      --gym-red-light: #ef4444;
      --gym-black: #0a0a0a;
      --gym-black-light: #111111;
      --gym-black-soft: #1a1a1a;
      --gym-white: #ffffff;
      --gym-gray: #9ca3af;
      --gym-gray-dark: #6b7280;
      --gym-border: rgba(255, 255, 255, 0.06);
      --gym-hover: rgba(220, 38, 38, 0.1);
      --gym-card-border: rgba(0, 0, 0, 0.05);
      --gym-success: #10b981;
      --gym-warning: #f59e0b;
      --gym-danger: #ef4444;
      --gym-info: #3b82f6;
    }

    body {
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
      background: #f8f9fa;
    }

    /* ===================== */
    /* SIDEBAR - BLACK MINIMALIST */
    /* ===================== */
    .left-sidebar {
      background: var(--gym-black);
      box-shadow: 4px 0 20px rgba(0,0,0,0.3);
      border-right: 1px solid var(--gym-border);
    }

    .brand-logo {
      padding: 16px 20px;
      border-bottom: 1px solid var(--gym-border);
      background: var(--gym-black);
    }

    .brand-logo a {
      color: var(--gym-white);
      font-weight: 700;
      font-size: 1rem;
      display: flex;
      align-items: center;
      gap: 10px;
      text-decoration: none;
    }

    .brand-logo a img {
      width: 32px;
      height: auto;
    }

    .brand-text {
      font-size: 1rem;
      letter-spacing: 0.5px;
    }

    .brand-text span {
      color: var(--gym-red);
    }

    /* Sidebar Navigation */
    .sidebar-nav {
      padding: 10px 0;
    }

    .nav-small-cap {
      padding: 12px 20px 8px;
    }

    .nav-small-cap span {
      color: var(--gym-gray);
      font-size: 0.65rem;
      letter-spacing: 1px;
      text-transform: uppercase;
      font-weight: 600;
    }

    .sidebar-link {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 10px 18px;
      color: var(--gym-gray);
      border-radius: 8px;
      margin: 4px 12px;
      transition: all 0.2s ease;
      text-decoration: none;
    }

    .sidebar-link:hover {
      background: var(--gym-hover);
      color: var(--gym-white);
    }

    .sidebar-item.active .sidebar-link {
      background: var(--gym-red);
      color: var(--gym-white);
      box-shadow: 0 2px 8px rgba(220, 38, 38, 0.3);
    }

    .sidebar-link i, .sidebar-link .ti {
      font-size: 1.1rem;
      width: 24px;
    }

    .left-sidebar.with-vertical {

      scrollbar-color: var(--gym-red) var(--gym-black-light);
    }

    .left-sidebar.with-vertical::-webkit-scrollbar {
      width: 4px;
    }

    .left-sidebar.with-vertical::-webkit-scrollbar-track {
      background: var(--gym-black-light);
    }

    .left-sidebar.with-vertical::-webkit-scrollbar-thumb {
      background-color: var(--gym-red);
      border-radius: 4px;
    }

    /* ===================== */
    /* TOPBAR - WHITE MINIMALIST */
    /* ===================== */
    .topbar {
      background: var(--gym-white);
      border-bottom: 1px solid #e5e7eb;
      box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }

    .navbar {
      padding: 10px 24px;
    }

    #headerCollapse i {
      font-size: 1.3rem;
      color: var(--gym-black);
      transition: 0.2s;
      cursor: pointer;
    }

    #headerCollapse:hover i {
      color: var(--gym-red);
    }

    /* User Profile */
    .user-profile-img img {
      border: 2px solid var(--gym-red);
      transition: 0.2s;
      border-radius: 50%;
      width: 35px;
      height: 35px;
      object-fit: cover;
    }

    .dropdown-menu {
      border-radius: 12px;
      border: 1px solid #e5e7eb;
      box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }

    .profile-dropdown h5 {
      color: var(--gym-black);
    }

    .profile-dropdown span {
      color: var(--gym-gray-dark);
    }

    .btn-outline-primary {
      border-radius: 8px;
      border-color: var(--gym-red);
      color: var(--gym-red);
      background: transparent;
      padding: 8px 16px;
      font-weight: 500;
      transition: 0.2s;
    }

    .btn-outline-primary:hover {
      background: var(--gym-red);
      color: var(--gym-white);
      border-color: var(--gym-red);
    }

    /* Dashboard Cards */
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

    .trend-up {
      color: var(--gym-success);
      font-size: 0.7rem;
    }

    .trend-down {
      color: var(--gym-danger);
      font-size: 0.7rem;
    }

    /* Section Titles */
    .section-title {
      font-size: 1rem;
      font-weight: 600;
      color: var(--gym-black);
      margin-bottom: 1rem;
      padding-bottom: 0.5rem;
      border-bottom: 2px solid var(--gym-red);
      display: inline-block;
    }

    /* Tables */
    .table-custom {
      background: var(--gym-white);
      border-radius: 16px;
      overflow: hidden;
      width: 100%;
    }

    .table-custom thead th {
      background: var(--gym-black);
      color: var(--gym-white);
      border: none;
      padding: 12px 16px;
      font-size: 0.7rem;
      font-weight: 600;
      text-transform: uppercase;
    }

    .table-custom tbody td {
      padding: 12px 16px;
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

    .badge-danger {
      background: rgba(239, 68, 68, 0.1);
      color: var(--gym-danger);
      padding: 4px 8px;
      border-radius: 20px;
      font-size: 0.65rem;
    }

    .badge-warning {
      background: rgba(245, 158, 11, 0.1);
      color: var(--gym-warning);
      padding: 4px 8px;
      border-radius: 20px;
      font-size: 0.65rem;
    }

    .badge-info {
      background: rgba(59, 130, 246, 0.1);
      color: var(--gym-info);
      padding: 4px 8px;
      border-radius: 20px;
      font-size: 0.65rem;
    }

    /* Buttons */
    .btn-gym {
      background: var(--gym-red);
      color: var(--gym-white);
      border: none;
      border-radius: 8px;
      padding: 8px 16px;
      font-size: 0.75rem;
      font-weight: 500;
      transition: 0.2s;
    }

    .btn-gym:hover {
      background: var(--gym-red-dark);
      color: var(--gym-white);
    }

    .btn-gym-outline {
      background: transparent;
      border: 1px solid var(--gym-red);
      color: var(--gym-red);
      border-radius: 8px;
      padding: 6px 12px;
      font-size: 0.7rem;
      font-weight: 500;
      transition: 0.2s;
    }

    .btn-gym-outline:hover {
      background: var(--gym-red);
      color: var(--gym-white);
    }

    /* Urgent Alert */
    .urgent-alert {
      background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
      border-left: 4px solid var(--gym-red);
      border-radius: 12px;
      padding: 1rem;
      margin-bottom: 1rem;
    }

    .footer {
      background: var(--gym-white);
      border-top: 1px solid #e5e7eb;
      color: var(--gym-gray-dark);
      font-size: 0.75rem;
      padding: 1rem;
      text-align: center;
    }

    .body-wrapper {
      background: #f8f9fa;
      min-height: calc(100vh - 60px);
      padding: 1.5rem;
    }

    /* Responsive */
    @media (max-width: 768px) {
      .body-wrapper {
        padding: 1rem;
      }
    }
  </style>
</head>
<body>
  <div id="main-wrapper">
    <!-- Sidebar Start -->
    <aside class="left-sidebar with-vertical">
      <div>
        <div class="brand-logo d-flex align-items-center justify-content-between">
          <a href="<?=site_url();?>mydashboard" class="text-nowrap logo-img">
            <span class="brand-text">MJESHTER <span>FITNESS GYM</span></span>
          </a> 
          <a href="javascript:void(0)" class="sidebartoggler ms-auto text-decoration-none fs-5 d-block d-xl-none">
            <i class="ti ti-x"></i>
          </a>
        </div>

        <nav class="sidebar-nav scroll-sidebar" style="height: 100vh !important;">
          <ul id="sidebarnav" style="list-style: none; padding-left: 0;">
            <!-- DASHBOARD -->
            <li class="nav-small-cap">
              <i class="ti ti-dots"></i>
              <span>MAIN</span>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="<?=site_url();?>mydashboard">
                <span><i class="ti ti-dashboard"></i></span>
                <span>Dashboard</span>
              </a>
            </li>

            <!-- MEMBERS MANAGEMENT -->
            <li class="nav-small-cap">
              <i class="ti ti-dots"></i>
              <span>MEMBERS</span>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="<?=site_url();?>members">
                <span><i class="ti ti-users"></i></span>
                <span>All Members</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="<?=site_url();?>membership">
                <span><i class="ti ti-id"></i></span>
                <span>Membership Types</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="<?=site_url();?>attendance">
                <span><i class="ti ti-calendar-check"></i></span>
                <span>Attendance</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="<?=site_url();?>expirations">
                <span><i class="ti ti-alert-triangle"></i></span>
                <span>Expirations</span>
              </a>
            </li>

            <!-- PAYMENTS & BILLING -->
            <li class="nav-small-cap">
              <i class="ti ti-dots"></i>
              <span>FINANCE</span>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="<?=site_url();?>payments">
                <span><i class="ti ti-cash"></i></span>
                <span>Payments</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="<?=site_url();?>invoices">
                <span><i class="ti ti-receipt"></i></span>
                <span>Invoices</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="<?=site_url();?>financial-reports">
                <span><i class="ti ti-chart-bar"></i></span>
                <span>Financial Reports</span>
              </a>
            </li>

            <!-- CLASSES & TRAINERS -->
            <li class="nav-small-cap">
              <i class="ti ti-dots"></i>
              <span>CLASSES</span>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="<?=site_url();?>classes">
                <span><i class="ti ti-dumbbell"></i></span>
                <span>Classes</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="<?=site_url();?>trainers">
                <span><i class="ti ti-users"></i></span>
                <span>Trainers</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="<?=site_url();?>schedule">
                <span><i class="ti ti-calendar"></i></span>
                <span>Class Schedule</span>
              </a>
            </li>

            <!-- EQUIPMENT -->
            <li class="nav-small-cap">
              <i class="ti ti-dots"></i>
              <span>EQUIPMENT</span>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="<?=site_url();?>equipment">
                <span><i class="ti ti-tools"></i></span>
                <span>Equipment List</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="<?=site_url();?>maintenance">
                <span><i class="ti ti-wrench"></i></span>
                <span>Maintenance</span>
              </a>
            </li>

            <!-- REPORTS -->
            <li class="nav-small-cap">
              <i class="ti ti-dots"></i>
              <span>REPORTS</span>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="<?=site_url();?>member-reports">
                <span><i class="ti ti-file-report"></i></span>
                <span>Member Reports</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="<?=site_url();?>attendance-reports">
                <span><i class="ti ti-calendar-stats"></i></span>
                <span>Attendance Reports</span>
              </a>
            </li>

            <!-- SYSTEM -->
            <li class="nav-small-cap">
              <i class="ti ti-dots"></i>
              <span>SYSTEM</span>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="<?=site_url();?>users">
                <span><i class="ti ti-user"></i></span>
                <span>User Management</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="<?=site_url();?>settings">
                <span><i class="ti ti-settings"></i></span>
                <span>Settings</span>
              </a>
            </li>
          </ul>
        </nav>
      </div>
    </aside>
    <!-- Sidebar End -->

    <div class="page-wrapper">
      <!-- Header Start -->
      <header class="topbar">
        <div class="with-vertical">
          <nav class="navbar navbar-expand-lg p-0 d-flex justify-content-between">
            <ul class="navbar-nav">
              <li class="nav-item">
                <a class="nav-link sidebartoggler" id="headerCollapse" href="javascript:void(0)">
                  <i class="ti ti-menu-2"></i>
                </a>
              </li>
            </ul>

            <ul class="navbar-nav flex-row align-items-center">
              <li class="nav-item dropdown">
                <a class="nav-link pe-0" href="javascript:void(0)" id="drop1" data-bs-toggle="dropdown" aria-expanded="false">
                  <div class="d-flex align-items-center">
                    <div class="user-profile-img">
                      <img src="<?=base_url('assets/images/profile/user-1.jpg')?>" class="rounded-circle" width="35" height="35" alt="profile" />
                    </div>
                  </div>
                </a>
                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="drop1">
                  <div class="profile-dropdown">
                    <div class="py-3 px-4">
                      <h5 class="mb-0 fs-5 fw-semibold">User Profile</h5>
                    </div>
                    <div class="d-flex align-items-center py-3 px-4 border-bottom">
                      <img src="<?=base_url('assets/images/profile/user-1.jpg')?>" class="rounded-circle" width="50" height="50" alt="profile" />
                      <div class="ms-3">
                        <h6 class="mb-0"><?=$full_name;?></h6>
                        <span class="d-block fs-7 text-muted"><?=$position;?></span>
                        <span class="d-block fs-8 text-muted"><?=$division . ' - ' . $section;?></span>
                      </div>
                    </div>
                    <div class="d-grid py-3 px-4">
                      <form action="<?= site_url('mylogout'); ?>" method="post">
                        <?= csrf_field(); ?>
                        <button type="submit" class="btn btn-outline-primary w-100">Log Out</button>
                      </form>
                    </div>
                  </div>
                </div>
              </li>
            </ul>
          </nav>
        </div>
      </header>
      <!-- Header End -->

      <div class="body-wrapper">