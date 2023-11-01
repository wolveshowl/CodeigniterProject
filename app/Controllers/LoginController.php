<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\MemberModel;
use CodeIgniter\API\ResponseTrait;

use JWT_helper;

helper('jwt');
helper('cookie');

class LoginController extends BaseController {
    
    use ResponseTrait;

    public function index() {

        $memberModel = new MemberModel();

        $id = $this->request->getVar('id');
        $password = $this->request->getVar('password');

        $member = $memberModel->getMemberOne($id);

        if(is_null($member)) {
            return $this->respond(['error' => '로그인 안됨'], 401);
        }

        $pwdVerity = password_verify($password, $member->mem_password);
                
        if(!$pwdVerity) {
            return $this->respond(['error' => '비밀번호 아님'], 401);
        }

        $jwtHelper = new JWT_helper();
        $accessToken = $jwtHelper->jwtEncodeData($id);
        $refreshToken = $jwtHelper->jwtCreateRefreshToken();

        set_cookie('refreshToken', $refreshToken, 3600);        
        
        $tokenData = [
            'mem_no' => $member->mem_no,
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken
        ];
        
        $response = [
            'message' => '로그인 성공!',
            'accessToken' => $accessToken,
            'refreshToken' => $refreshToken
        ];

        $memberModel->updateAccessAndRefreshToken($tokenData);

        return $this->respond($response, 200);

    }

    public function decode() {

        // 헤더로 요청받은 엑세스 토큰
        $header = array(
            'Authorization' => $_SERVER['HTTP_AUTHORIZATION']
        );
        
        // 엑세스 토큰 디코딩 후 해당하는 회원의 데이터(계정 ID)를 가져옴
        $jwtHelper = new JWT_helper();
        $data = $jwtHelper->jwtDecodeData($header['Authorization'])->id;        
        
        return $data;
    }

    public function accessTokenUpdate() {
        
        $jwtHelper = new JWT_helper();
        $memberModel = new MemberModel();
        
        $getToken = get_cookie('refreshToken');
        $findMember = $memberModel->selectAccessTokenByRefreshToken($getToken);
        $accessToken = $jwtHelper->jwtEncodeData($findMember->mem_id);
        $refreshToken = $jwtHelper->jwtCreateRefreshToken();

        $tokenData = [
            'mem_no' => $findMember->mem_no,
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken
        ];
        

        $memberModel->updateAccessAndRefreshToken($tokenData);

        set_cookie('refreshToken', $refreshToken, 3600);

        $response = [
            'message' => '토큰 재발급.',
            'accessToken' => $accessToken,
            'refreshToken' => $refreshToken
        ];
        
        return $this->respond($response, 200);
                            
    }

    public function set_cookie($name, $value, $exp) {
        setcookie(md5($name), base64_encode($value), time()+$exp);
    }

    public function get_cookie($name) {
        $cookie = md5($name);
        if (array_key_exists($cookie, $_COOKIE)) {
            return base64_decode($_COOKIE[$cookie]);
        } else {
            return null;
        }
    }
}
?>