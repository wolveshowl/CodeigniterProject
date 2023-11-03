<?php

namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;

use App\Models\MemberModel;
use JWT_helper;

helper('jwt_helper');
helper('cookie');

class Member extends BaseController {

    use ResponseTrait;

    protected $memberModel;        
    protected $memberInfo;
    protected $header;
    protected $jwtHelper;
    
    
    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger) {
        parent::initController($request, $response, $logger);
        $this->memberModel = new MemberModel();   
        $this->jwtHelper = new JWT_helper();

        
        // 헤더로 요청받은 엑세스 토큰
        $this->header = array(
            'Authorization' => $_SERVER['HTTP_AUTHORIZATION']
        );         
        $this->memberInfo = $this->jwtHelper->jwtDecodeData($this->header['Authorization']);                      
    }
    
    public function memberView() {
                                        
        $response = [
            "message" => "일반 회원",
            "data" => $this->memberInfo->id
        ];
        return $this->respond($response, 200);
    }
}
?>