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


