<?php

namespace App\Controllers;

use App\Models\MemberModel;

class MemberController extends BaseController {

    protected $memberModel;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger) {
        parent::initController($request, $response, $logger);
        $this->memberModel = new MemberModel();
    }

    public function index() {

        $companyNo = [];
        $data = [
            'memberInfo' => $this->memberModel->getMemberInfo()
        ];

        foreach($data['memberInfo'] as $value) {
            echo $value['mem_company_no'];
        }
                
        
        return view("member/member", $data);
    }

    public function phpInfo() {
        return view("info");
    }
}
?>