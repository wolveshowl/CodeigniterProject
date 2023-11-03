<?php

namespace App\Filters;


use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;
use Firebase\JWT\ExpiredException;
use CodeIgniter\HTTP\URI;

use App\Models\MemberModel;

use JWT_helper;
helper('jwt_helper');
helper('cookie');

class AuthenticationFilter implements FilterInterface {

    public function before(RequestInterface $request, $arguments = null) {
        
        $uri = new URI(current_url());
        $jwtHelper = new JWT_helper();
        $memberModel = new MemberModel();
                    
    
        // 헤더에 토큰이 없다면 로그인 페이지로 이동
        $authHeader = $request->getHeaderLine('Authorization');
        
        if (empty($authHeader)) {                                   
            return site_url('info');               
        } 
        $token = get_cookie('accessToken');
        $_SERVER['HTTP_AUTHORIZATION'] = $token;
       
        try {
            $decoded = $jwtHelper->jwtDecodeData($_SERVER['HTTP_AUTHORIZATION']);
            $findMember = $memberModel->getMemberOne($decoded->id);             
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