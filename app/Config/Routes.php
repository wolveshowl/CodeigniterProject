<?php

use CodeIgniter\Router\RouteCollection;
use App\Controllers\Auth;

/**
 * @var RouteCollection $routes
 */

$routes->group('/', ['filter' => 'Authentication'], function ($routes) {
    // 라우트 설정 추가    
    $routes->post('api/update', 'Auth::accessTokenUpdate');  
    $routes->get('api/member-view', 'Member::memberView');
    $routes->get('api/admin-view', 'Admin::adminView');
});

// 토큰이 필요하지 않은 엔드포인트 엑세스 허용
$routes->get('/info', 'Auth::phpInfo');
$routes->post('/login', 'Auth::login'); 
$routes->post('/signup', 'Register::index');


