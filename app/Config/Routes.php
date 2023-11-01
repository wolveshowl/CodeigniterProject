<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// $routes->get('/', 'Home::index');
$routes->get('/member', 'MemberController::index');
$routes->get('/', 'MemberController::phpInfo');
$routes->post('/login', 'LoginController::index');
$routes->post('/signup', 'RegisterController::index');
$routes->post('/decode', 'LoginController::decode');
$routes->post('/update', 'LoginController::accessTokenUpdate');

