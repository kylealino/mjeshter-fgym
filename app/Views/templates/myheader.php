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
  
  // Get current URL for active menu highlighting
  $current_url = current_url();
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
  <link rel="shortcut icon" type="image/png" href="<?=base_url('assets/images/logos/gym-logo.png')?>" />
  <title>MJESHTER FITNESS GYM | Management System</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@2.30.0/tabler-icons.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  
  <style>
    /* ===================== */
    /* MJESHTER FITNESS GYM - PROFESSIONAL BLACK/RED/WHITE THEME */
    /* ===================== */
    :root {
      --gym-red: #dc2626;
      --gym-red-dark: #b91c1c;
      --gym-red-light: #ef4444;
      --gym-black: #0a0a0a;
      --gym-black-light: #111111;
      --gym-white: #ffffff;
      --gym-gray: #9ca3af;
      --gym-gray-dark: #6b7280;
      --gym-border: rgba(255, 255, 255, 0.06);
      --gym-hover: rgba(220, 38, 38, 0.1);
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
      background: #f8f9fa;
      overflow-x: hidden;
    }

    /* ===================== */
    /* SIDEBAR */
    /* ===================== */
    .left-sidebar {
      background: var(--gym-black);
      box-shadow: 4px 0 20px rgba(0,0,0,0.3);
      border-right: 1px solid var(--gym-border);
      position: fixed;
      top: 0;
      left: 0;
      height: 100vh;
      width: 280px;
      z-index: 1000;
      transition: transform 0.3s ease, width 0.3s ease;
      overflow-y: auto;
      overflow-x: hidden;
      display: flex;
      flex-direction: column;
    }

    /* Collapsed Sidebar */
    .left-sidebar.collapsed {
      width: 80px;
    }

    .left-sidebar.collapsed .brand-text,
    .left-sidebar.collapsed .sidebar-link span:not(.ti),
    .left-sidebar.collapsed .nav-small-cap span,
    .left-sidebar.collapsed .logout-link span {
      display: none;
    }

    .left-sidebar.collapsed .sidebar-link {
      justify-content: center;
      padding: 10px;
    }

    .left-sidebar.collapsed .sidebar-link i,
    .left-sidebar.collapsed .sidebar-link .ti {
      margin: 0;
    }

    .left-sidebar.collapsed .brand-logo a {
      justify-content: center;
    }

    /* Scrollbar */
    .left-sidebar::-webkit-scrollbar {
      width: 3px;
    }

    .left-sidebar::-webkit-scrollbar-track {
      background: transparent;
    }

    .left-sidebar::-webkit-scrollbar-thumb {
      background: var(--gym-red);
      border-radius: 3px;
    }

    /* Mobile Sidebar */
    @media (max-width: 768px) {
      .left-sidebar {
        transform: translateX(-100%);
        width: 260px;
      }
      .left-sidebar.open {
        transform: translateX(0);
      }
      .left-sidebar.collapsed {
        width: 260px;
      }
      .left-sidebar.collapsed .brand-text,
      .left-sidebar.collapsed .sidebar-link span:not(.ti),
      .left-sidebar.collapsed .nav-small-cap span,
      .left-sidebar.collapsed .logout-link span {
        display: inline;
      }
    }

    .brand-logo {
      padding: 20px 24px;
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

    .brand-text {
      font-size: 0.95rem;
      letter-spacing: 0.5px;
      font-weight: 700;
    }

    .brand-text span {
      color: var(--gym-red);
    }

    /* Sidebar Navigation */
    .sidebar-nav {
      padding: 16px 0 0 0;
      flex: 1;
    }

    .nav-small-cap {
      padding: 8px 24px 4px 24px;
    }

    .nav-small-cap span {
      color: var(--gym-gray);
      font-size: 0.6rem;
      letter-spacing: 1px;
      text-transform: uppercase;
      font-weight: 600;
    }

    .sidebar-item {
      list-style: none;
    }

    .sidebar-link {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 10px 20px;
      margin: 2px 12px;
      color: var(--gym-gray);
      border-radius: 10px;
      transition: all 0.2s ease;
      text-decoration: none;
      font-size: 0.85rem;
      font-weight: 500;
    }

    .sidebar-link:hover {
      background: var(--gym-hover);
      color: var(--gym-white);
    }

    .sidebar-item.active .sidebar-link {
      background: var(--gym-red);
      color: var(--gym-white);
    }

    .sidebar-link i, .sidebar-link .ti {
      font-size: 1.2rem;
      width: 24px;
    }

    /* Sidebar Footer - Logout */
    .sidebar-footer {
      padding: 20px 20px 30px 20px;
      border-top: 1px solid var(--gym-border);
      margin-top: auto;
    }

    .logout-link {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 10px 16px;
      color: var(--gym-gray);
      border-radius: 10px;
      transition: all 0.2s ease;
      text-decoration: none;
      font-size: 0.85rem;
      font-weight: 500;
      background: rgba(220, 38, 38, 0.08);
    }

    .logout-link:hover {
      background: var(--gym-red);
      color: var(--gym-white);
    }

    .logout-link i {
      font-size: 1.2rem;
      width: 24px;
    }

    /* ===================== */
    /* PAGE WRAPPER */
    /* ===================== */
    .page-wrapper {
      margin-left: 280px;
      transition: margin-left 0.3s ease;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    .page-wrapper.expanded {
      margin-left: 80px;
    }

    @media (max-width: 768px) {
      .page-wrapper {
        margin-left: 0;
      }
      .page-wrapper.expanded {
        margin-left: 0;
      }
    }

    /* ===================== */
    /* TOPBAR */
    /* ===================== */
    .topbar {
      background: var(--gym-white);
      border-bottom: 1px solid #e5e7eb;
      box-shadow: 0 1px 3px rgba(0,0,0,0.05);
      position: sticky;
      top: 0;
      z-index: 999;
      width: 100%;
    }

    .navbar {
      padding: 10px 24px;
    }

    .navbar-nav {
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .nav-item {
      list-style: none;
    }

    #headerCollapse, .mobile-menu-toggle {
      background: transparent;
      border: none;
      cursor: pointer;
      padding: 8px;
      border-radius: 8px;
      transition: all 0.2s;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    #headerCollapse i, .mobile-menu-toggle i {
      font-size: 1.4rem;
      color: var(--gym-black);
    }

    #headerCollapse:hover, .mobile-menu-toggle:hover {
      background: rgba(220, 38, 38, 0.1);
    }

    #headerCollapse:hover i, .mobile-menu-toggle:hover i {
      color: var(--gym-red);
    }

    .mobile-menu-toggle {
      display: none;
    }

    @media (max-width: 768px) {
      .mobile-menu-toggle {
        display: flex;
      }
    }

    /* User Profile */
    .user-profile-img img {
      border: none;
      transition: 0.2s;
      border-radius: 50%;
      width: 38px;
      height: 38px;
      object-fit: cover;
    }

    .user-profile-img img:hover {
      transform: scale(1.05);
      opacity: 0.9;
    }

    .dropdown-menu {
      border-radius: 12px;
      border: 1px solid #e5e7eb;
      box-shadow: 0 10px 25px rgba(0,0,0,0.1);
      min-width: 260px;
      padding: 0;
    }

    .profile-dropdown {
      background: var(--gym-white);
      border-radius: 12px;
      overflow: hidden;
    }

    .profile-dropdown .dropdown-header {
      background: linear-gradient(135deg, var(--gym-red) 0%, var(--gym-red-dark) 100%);
      color: var(--gym-white);
      padding: 14px 18px;
    }

    .profile-dropdown .dropdown-header h5 {
      margin: 0;
      font-size: 0.9rem;
      font-weight: 600;
    }

    .profile-info {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 14px 18px;
      border-bottom: 1px solid #e5e7eb;
    }

    .profile-info h6 {
      margin: 0;
      font-size: 0.85rem;
      font-weight: 600;
      color: var(--gym-black);
    }

    .profile-info span {
      font-size: 0.65rem;
      color: var(--gym-gray-dark);
    }

    .btn-outline-primary {
      border-radius: 8px;
      border: 1px solid var(--gym-red);
      color: var(--gym-red);
      background: transparent;
      padding: 8px 16px;
      font-weight: 600;
      font-size: 0.75rem;
      transition: 0.2s;
      width: 100%;
    }

    .btn-outline-primary:hover {
      background: var(--gym-red);
      color: var(--gym-white);
    }

    /* Sidebar Overlay */
    .sidebar-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0,0,0,0.5);
      z-index: 998;
      display: none;
    }

    .sidebar-overlay.active {
      display: block;
    }

    /* Body Wrapper */
    .body-wrapper {
      background: #f8f9fa;
      flex: 1;
      padding: 24px;
    }

    @media (max-width: 768px) {
      .body-wrapper {
        padding: 16px;
      }
    }

    /* Footer */
    .footer {
      background: var(--gym-white);
      border-top: 1px solid #e5e7eb;
      color: var(--gym-gray-dark);
      font-size: 0.7rem;
      padding: 12px 24px;
      text-align: center;
    }
  </style>
</head>
<body>
  <!-- Mobile Sidebar Overlay -->
  <div class="sidebar-overlay" id="sidebarOverlay"></div>

  <div id="main-wrapper">
    <!-- Sidebar Start -->
    <aside class="left-sidebar" id="sidebar">
      <div class="brand-logo d-flex align-items-center justify-content-between">
        <a href="<?=site_url();?>myadmindashboard" class="text-nowrap logo-img">
          <span class="brand-text">MJESHTER <span>FITNESS</span></span>
        </a> 
        <a href="javascript:void(0)" class="sidebartoggler ms-auto text-decoration-none d-block d-xl-none" id="closeSidebar">
          <i class="ti ti-x" style="color: var(--gym-gray); font-size: 1.2rem;"></i>
        </a>
      </div>

      <nav class="sidebar-nav">
        <ul id="sidebarnav" style="list-style: none; padding-left: 0;">
          <li class="sidebar-item <?= strpos($current_url, 'myadmindashboard') !== false ? 'active' : ''; ?>">
            <a class="sidebar-link" href="<?=site_url();?>myadmindashboard">
              <i class="ti ti-dashboard"></i>
              <span>Dashboard</span>
            </a>
          </li>

          <!-- MEMBERSHIP -->
          <li class="nav-small-cap">
            <span>MEMBERSHIP</span>
          </li>

          <li class="sidebar-item <?= strpos($current_url, 'membersmanagement') !== false ? 'active' : ''; ?>">
            <a class="sidebar-link" href="<?=site_url();?>membersmanagement?meaction=MAIN">
              <i class="ti ti-users"></i>
              <span>Members Management</span>
            </a>
          </li>
          <li class="sidebar-item <?= strpos($current_url, 'attendance') !== false ? 'active' : ''; ?>">
            <a class="sidebar-link" href="<?=site_url();?>attendance?meaction=MAIN">
              <i class="ti ti-clock-check"></i>
              <span>Today's Activity Log</span>
            </a>
          </li>

          <!-- POS & CASHIERING -->
          <li class="nav-small-cap">
            <span>POS & CASHIERING</span>
          </li>

          <li class="sidebar-item <?= strpos($current_url, 'pos?meaction=MAIN') !== false ? 'active' : ''; ?>">
            <a class="sidebar-link" href="<?=site_url();?>pos?meaction=MAIN">
              <i class="ti ti-user-plus"></i>
              <span>POS</span>
            </a>
          </li>

          <li class="sidebar-item <?= strpos($current_url, 'possales') !== false ? 'active' : ''; ?>">
            <a class="sidebar-link" href="<?=site_url();?>possales?meaction=MAIN">
              <i class="ti ti-receipt-2"></i>
              <span>POS Transaction</span>
            </a>
          </li>

          <!-- INVENTORY -->
          <li class="nav-small-cap">
            <span>INVENTORY</span>
          </li>

          <li class="sidebar-item <?= strpos($current_url, 'inventory') !== false ? 'active' : ''; ?>">
            <a class="sidebar-link" href="<?=site_url();?>inventory?meaction=MAIN">
              <i class="ti ti-package"></i>
              <span>Stock-in/Movement</span>
            </a>
          </li>

          <!-- ASSETS -->
          <li class="nav-small-cap">
            <span>ASSETS</span>
          </li>

          <li class="sidebar-item <?= strpos($current_url, 'gym-assets') !== false ? 'active' : ''; ?>">
            <a class="sidebar-link" href="<?=site_url();?>gym-assets?meaction=MAIN">
              <i class="ti ti-barbell"></i>
              <span>Gym Assets</span>
            </a>
          </li>

          <!-- ACCOUNTING -->
          <li class="nav-small-cap">
            <span>ACCOUNTING</span>
          </li>

          <li class="sidebar-item <?= strpos($current_url, 'chartofaccounts') !== false ? 'active' : ''; ?>">
            <a class="sidebar-link" href="<?=site_url();?>chartofaccounts?meaction=MAIN">
              <i class="ti ti-chart-pie"></i>
              <span>Chart of Accounts</span>
            </a>
          </li>

          <li class="sidebar-item <?= strpos($current_url, 'cashreceipts') !== false ? 'active' : ''; ?>">
            <a class="sidebar-link" href="<?=site_url();?>cashreceipts?meaction=MAIN">
              <i class="ti ti-receipt"></i>
              <span>Cash Receipts Journal</span>
            </a>
          </li>

          <li class="sidebar-item <?= strpos($current_url, 'cash-disbursement-journal') !== false ? 'active' : ''; ?>">
            <a class="sidebar-link" href="<?=site_url();?>cash-disbursement-journal">
              <i class="ti ti-cash-banknote"></i>
              <span>Cash Disbursement Journal</span>
            </a>
          </li>

          <li class="sidebar-item <?= strpos($current_url, 'general-journal') !== false ? 'active' : ''; ?>">
            <a class="sidebar-link" href="<?=site_url();?>general-journal">
              <i class="ti ti-book"></i>
              <span>General Journal</span>
            </a>
          </li>
        </ul>
      </nav>

      <!-- Logout Section at Bottom -->
      <div class="sidebar-footer">
        <a href="javascript:void(0)" class="logout-link" id="logoutBtn">
          <i class="ti ti-logout"></i>
          <span>Logout</span>
        </a>
      </div>
    </aside>
    <!-- Sidebar End -->

    <div class="page-wrapper" id="pageWrapper">
      <!-- Header Start -->
      <header class="topbar">
        <div class="with-vertical">
          <nav class="navbar navbar-expand-lg p-0 d-flex justify-content-between">
            <ul class="navbar-nav">
              <li class="nav-item d-block d-xl-none">
                <button class="mobile-menu-toggle" id="mobileMenuToggle">
                  <i class="ti ti-menu-2"></i>
                </button>
              </li>
              <li class="nav-item d-none d-xl-block">
                <button class="nav-link sidebartoggler" id="sidebarToggle">
                  <i class="ti ti-menu-2"></i>
                </button>
              </li>
            </ul>

            <ul class="navbar-nav flex-row align-items-center">
              <li class="nav-item dropdown">
                <a class="nav-link pe-0" href="javascript:void(0)" id="drop1" data-bs-toggle="dropdown" aria-expanded="false">
                  <div class="d-flex align-items-center">
                    <div class="user-profile-img">
                      <img src="<?=base_url('assets/images/profile/user-1.jpg')?>" class="rounded-circle" alt="profile" />
                    </div>
                  </div>
                </a>
                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="drop1">
                  <div class="profile-dropdown">
                    <div class="dropdown-header">
                      <h5>User Profile</h5>
                    </div>
                    <div class="profile-info">
                      <img src="<?=base_url('assets/images/profile/user-1.jpg')?>" class="rounded-circle" width="45" height="45" alt="profile" />
                      <div>
                        <h6><?=$full_name;?></h6>
                        <span><?=$position;?></span>
                        <span class="d-block"><?=$division . ' - ' . $section;?></span>
                      </div>
                    </div>
                    <div class="d-grid py-3 px-4">
                      <form action="<?= site_url('mylogout'); ?>" method="post" id="logoutForm">
                        <?= csrf_field(); ?>
                        <button type="submit" class="btn-outline-primary">Log Out</button>
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
        <!-- CONTENT STARTS HERE - YOUR MODULE CONTENT GOES INSIDE THIS DIV -->