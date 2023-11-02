<?php

namespace App\Controllers;

use App\Models\MemberModel;

class Member extends BaseController {

    protected $memberModel;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger) {
        parent::initController($request, $response, $logger);
        $this->memberModel = new MemberModel();
    }

    public function phpInfo() {
        return view("info");
    }
}
?>