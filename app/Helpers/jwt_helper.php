<?php

require "../vendor/autoload.php";

use App\Models\MemberModel;
use Firebase\JWT\JWT;
use CodeIgniter\API\ResponseTrait;
use Firebase\JWT\Key;

class JWT_helper {

    use ResponseTrait;

    protected $accessKey;
    protected $refreshKey;
    protected $accessToken;
    protected $refreshToken;
    protected $issuedAt;
    protected $expire;
    protected $jwt;

    public function __construct() {

        // 환경 변수 파일 env에 등록되어 있는 jwt의 키값을 가져온다.
        $envFile = ROOTPATH . 'env';
        if (file_exists($envFile)) {
            $env = parse_ini_file($envFile);
        } else {
            die('env 파일을 찾을 수 없습니다.');
        }

        // 토큰의 키로 환경변수 값을 가져와서 사용
        $this->accessKey = $env['ACCESS_KEY'];
        $this->refreshKey = $env['REFRESH_KEY'];

        $this->issuedAt = time();
        $this->expire = $this->issuedAt + 3600;
    }

    public function jwtEncodeData($data) {
                    
        $this->accessToken = array(
            "iss" => "Issuer of the JWT",
            "aud" => "Audience that the JWT",
            "sub" => "Subject of the JWT",
            "iat" => $this->issuedAt,
            "exp" => $this->expire,
            "id" => $data,
        );

        $this->jwt = JWT::encode($this->accessToken, $this->accessKey, 'HS256');
        return $this->jwt;
    }

    public function jwtDecodeData($encodeToken) {
        $replacedToken = str_replace("Bearer ", "", $encodeToken);
        
        try {
            $decode = JWT::decode($replacedToken, new Key($this->accessKey, 'HS256'));                        
            return $decode;
        } catch (Exception $e) {
            return $e ->getMessage();
        }
    }

    public function jwtCreateRefreshToken() {        
        $this->refreshToken = array(
            "iss" => "Issuer of the JWT",
            "aud" => "Audience that the JWT",
            "sub" => "Subject of the JWT",
            "iat" => $this->issuedAt,
            "exp" => $this->expire + 604800,                
        );

        $this->jwt = JWT::encode($this->refreshToken, $this->refreshKey, 'HS256');        
        return $this->jwt;
    }

    public function jwtRefreshTokenDecode($data) {

        $replacedToken = str_replace("Bearer ", "", $data);

        try {
            $decode = JWT::decode($replacedToken, new Key($this->refreshKey, 'HS256'));
            log_message('error', print_r($decode));                        
            return $decode;
        } catch (Exception $e) {
            return $e ->getMessage();
        }
    }

    public function jwtUpdateAccessToken($refreshToken) {
        $memberModel = new MemberModel();
        return $memberModel->selectAccessTokenByRefreshToken($refreshToken);
    }

    public function decode($token) {

        // 헤더로 요청받은 엑세스 토큰
        // $header = array(
        //     'Authorization' => $_SERVER['HTTP_AUTHORIZATION']
        // );
        
        // 엑세스 토큰 디코딩 후 해당하는 회원의 데이터(계정 ID)를 가져옴
        $jwtHelper = new JWT_helper();
        $data = $jwtHelper->jwtDecodeData($token);        

        $response = [
            'message' => '회원 조회 성공',
            'id' => $data->id,            
        ];
        
        return json_decode($data->id);
    }
}
?>