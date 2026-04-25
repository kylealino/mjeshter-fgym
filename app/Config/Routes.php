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


