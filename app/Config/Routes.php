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
$routes->add('myclientlogout', 'MyClientDashboard::logout');

//CLIENT DASHBOARD
$routes->get('myclientdashboard', 'MyClientDashboard::index');
$routes->post('myclientdashboard', 'MyClientDashboard::index');

//MEMBERS MANAGEMENT
$routes->get('membersmanagement', 'MembersManagementController::index',['filter' => 'myauthuser']);
$routes->post('membersmanagement', 'MembersManagementController::index',['filter' => 'myauthuser']);

//ATTENDANCE
$routes->get('attendance', 'AttendanceController::index',['filter' => 'myauthuser']);
$routes->post('attendance', 'AttendanceController::index',['filter' => 'myauthuser']);

//POS
$routes->get('pos', 'POSController::index',['filter' => 'myauthuser']);
$routes->post('pos', 'POSController::index',['filter' => 'myauthuser']);

//POS SALES
$routes->get('possales', 'POSSalesController::index',['filter' => 'myauthuser']);
$routes->post('possales', 'POSSalesController::index',['filter' => 'myauthuser']);

//INVENTORY
$routes->get('inventory', 'InventoryController::index',['filter' => 'myauthuser']);
$routes->post('inventory', 'InventoryController::index',['filter' => 'myauthuser']);


// EMAIL TEST
$routes->get('testemail', 'TestEmail::index');

//CASH RECEIPTS
$routes->get('cashreceipts', 'CashReceiptsController::index',['filter' => 'myauthuser']);
$routes->post('cashreceipts', 'CashReceiptsController::index',['filter' => 'myauthuser']);

//CHART OF ACCOUNTS
$routes->get('chartofaccounts', 'ChartOfAccountsController::index',['filter' => 'myauthuser']);
$routes->post('chartofaccounts', 'ChartOfAccountsController::index',['filter' => 'myauthuser']);

//GYM ASSETS
$routes->get('gym-assets', 'GymAssetsController::index',['filter' => 'myauthuser']);
$routes->post('gym-assets', 'GymAssetsController::index',['filter' => 'myauthuser']);

//CASH DISBURSEMENT
$routes->get('cash-disbursement-journal', 'CashDisbursementController::index',['filter' => 'myauthuser']);
$routes->post('cash-disbursement-journal', 'CashDisbursementController::index',['filter' => 'myauthuser']);

//GENERAL JOURNAL
$routes->get('general-journal', 'GeneralJournalController::index',['filter' => 'myauthuser']);
$routes->post('general-journal', 'GeneralJournalController::index',['filter' => 'myauthuser']);

//FINANCIAL REPORTS
$routes->get('financial-reports', 'FinancialReportsController::index',['filter' => 'myauthuser']);
$routes->post('financial-reports', 'FinancialReportsController::index',['filter' => 'myauthuser']);