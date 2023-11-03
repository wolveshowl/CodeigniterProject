<?php

namespace App\Models;

use CodeIgniter\Model;

class MemberModel extends Model {
    protected $DBGroup              = 'default';

    protected $protectFields        = false;
    protected $table                = "member";
    protected $primaryKey           = 'mem_no';
    protected $useAutoIncrement     = true;
    protected $insertID             = 0;
    protected $returnType           = 'array';
    protected $useSoftDeletes       = false;    
    protected $allowedFields        = ['access_token', 'refresh_token'];


    // SELECT -----------------------------------------------

    public function getMemberInfo() {        
        return $this->findAll();
    }

    public function getMemberOne($id) {
        return $this->db->table("member")->where("mem_id", $id)->get()->getRow();            
    }
    
    public function selectAccessTokenByRefreshToken($data) {
        return $this->db->table("member")->where("refresh_token", $data)->get()->getRow();
    }

    // ------------------------------------------------------



    // INSERT -----------------------------------------------

    public function insertMember($data) {
        return $this->insert($data);
    }

    // ------------------------------------------------------



    // UPDATE -----------------------------------------------

    public function updateAccessAndRefreshToken($data) {
        $this->db->table("member")->where("mem_no", $data['mem_no'])->update($data);        
    }

    // ------------------------------------------------------
        
}
?>