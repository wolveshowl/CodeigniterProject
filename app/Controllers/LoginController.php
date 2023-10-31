<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\MemberModel;
use CodeIgniter\API\ResponseTrait;
use Firebase\JWT\JWT;

class LoginController extends BaseController {
    
    use ResponseTrait;

    public function index() {

        $memberModel = new MemberModel();

        $id = $this->request->getVar('id');
        $password = $this->request->getVar('password');
        
        // $member = $memberModel->where('mem_id', $id)->find();
        $member = $memberModel->getMemberOne($id);        
        
        
        if(is_null($member)) {
            return $this->respond(['error' => '로그인 안됨', 401]);
        }

        $pwdVerity = password_verify($password, $member->mem_password);
                
        if(!$pwdVerity) {
            return $this->respond(['error' => '비밀번호 아님'], 401);
        }
        
        $iat = time();
        $exp = $iat + 3600;                

        $payload = array(
            "iss" => "Issuer of the JWT",
            "aud" => "Audience that the JWT",
            "sub" => "Subject of the JWT",
            "iat" => $iat, //Time the JWT issued at
            "exp" => $exp, // Expiration time of token
            "id" => $member->mem_id,
        );

        // .env 파일에서 환경 설정 가져오기
        $envFile = ROOTPATH . 'env';
        if (file_exists($envFile)) {
            $env = parse_ini_file($envFile);
        } else {
            die('.env 파일을 찾을 수 없습니다.');
        }

        // JWT_SECRET 값을 가져와서 사용
        $jwtSecret = $env['JWT_SECRET'];

        log_message('error', $jwtSecret);

        $token = JWT::encode($payload, $jwtSecret, 'HS256');

        $response = [
            'message' => 'Login Succesful',
            'token' => $token
        ];
        
        $accessData['mem_no'] = $member->mem_no;
        $accessData['access_token'] = $token;
        

        return $this->respond($response, 200);

    }
}
?>