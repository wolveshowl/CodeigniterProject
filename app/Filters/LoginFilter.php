<?php

use Codeigniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;
use Firebase\JWT\ExpiredException;

class LoginFilter implements FilterInterface {

    public function before(RequestInterface $request, $arguments = null) {
        
        $jwtHelper = new JWT_helper();

        //헤더에 토큰이 없다면 로그인 페이지로 이동
        $authHeader = $request->getHeaderLine('Authorization');
        if (empty($authHeader)) {            
            return site_url('index');            
        }        

        try {
            $decoded = $jwtHelper->jwtDecodeData($authHeader);            
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