<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use App\Models\MemberModel;

class Register extends BaseController {

    use ResponseTrait;

    public function index() {

        $memberModel = new MemberModel();

        $data = [
            'mem_id' => $this->request->getVar('id'),
            'mem_password' => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT),
            'mem_name' => $this->request->getVar('name'),
            'mem_company_no' => $this->request->getVar('companyNo'),
            'mem_role' => $this->request->getVar('role')
        ];
        
        $memberModel->insertMember($data);

        return $this->respond(['message' => '가입됨'], 200);
    }
    
}
?>