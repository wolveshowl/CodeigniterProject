<?php

namespace App\Filters;


use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;
use Firebase\JWT\ExpiredException;
use CodeIgniter\HTTP\URI;

use JWT_helper;
helper('jwt_helper');
helper('cookie');

class AuthenticationFilter implements FilterInterface {

    public function before(RequestInterface $request, $arguments = null) {
        
        $uri = new URI(current_url());
        $jwtHelper = new JWT_helper();
                    
    
        // 헤더에 토큰이 없다면 로그인 페이지로 이동
        // $authHeader = $request->getHeaderLine('Authorization');
        $token = get_cookie('accessToken');

        $_SERVER['HTTP_AUTHORIZATION'] = $token;
        
        if (empty($_SERVER['HTTP_AUTHORIZATION'])) {                       
            return site_url('info');               
        }        
    
        try {
            $decoded = $jwtHelper->jwtDecodeData($_SERVER['HTTP_AUTHORIZATION']);            
        } catch (ExpiredException $e) {
            return Services::response()->setStatusCode(401, '토큰 만료');
        } catch (\Exception $e) {
            return Services::response()->setStatusCode(401, '인증 오류');
        }

    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {

    }
}
?>