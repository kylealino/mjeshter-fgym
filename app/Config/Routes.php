<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Home::index');
$routes->get('myclientlogin', 'ClientHome::index');
$routes->add('mylogin-auth', 'MyLogIn::auth');
$routes->add('mylogout', 'MyLogIn::logout');
$routes->get('myadmindashboard', 'MyAdminDashboard::index',['filter' => 'myauthuser']);
$routes->add('myclientdashboard', 'MyClientDashboard::index');
$routes->add('myclientlogout', 'MyClientDashboard::logout');

//Members Management - Members module
$routes->get('mymembers', 'MembersManagementController::index',['filter' => 'myauthuser']);
$routes->post('mymembers', 'MembersManagementController::index',['filter' => 'myauthuser']);

//User Account - User module
$routes->get('myaccount', 'AccountSettingsController::index',['filter' => 'myauthuser']);
$routes->post('myaccount', 'AccountSettingsController::index',['filter' => 'myauthuser']);

//LOAN AVAILMENT - Loan availment module
$routes->get('myloanavailment', 'LoanAvailmentController::index',['filter' => 'myauthuser']);
$routes->post('myloanavailment', 'LoanAvailmentController::index',['filter' => 'myauthuser']);

//LOAN PROFILE - Loan profile module
$routes->get('myloanprofile', 'LoanProfileController::index',['filter' => 'myauthuser']);
$routes->post('myloanprofile', 'LoanProfileController::index',['filter' => 'myauthuser']);

//COA PROFILE - Coa profile module
$routes->get('mycoa', 'COAController::index',['filter' => 'myauthuser']);
$routes->post('mycoa', 'COAController::index',['filter' => 'myauthuser']);

//JOURNAL PROFILE - Journal entry module
$routes->get('myjournalentry', 'JournalEntryController::index',['filter' => 'myauthuser']);
$routes->post('myjournalentry', 'JournalEntryController::index',['filter' => 'myauthuser']);

//ACCOUNTING REPORT - Accounting report module
$routes->get('myaccountingreport', 'AccountingReportController::index',['filter' => 'myauthuser']);
$routes->post('myaccountingreport', 'AccountingReportController::index',['filter' => 'myauthuser']);

