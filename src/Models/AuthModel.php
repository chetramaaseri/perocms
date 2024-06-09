<?php
namespace Pero\Models;
class AuthModel extends PeroModel{
    public function __construct(){
        parent::__construct();

    }

    public function findUser(array $credentials):array
    {
        $this->db->select("*");
        $this->db->from("peroauth");
        $this->db->where($credentials);
        $query = $this->db->get();
        return $query->row_array();
    }


    public function startSession(array $user):bool
    {
        $expiry_time = time() + (4 * 60 * 60);
        setcookie("authorised",$user['puser_id'],$expiry_time,"/");
        setcookie("uid",$user['puser_id'],$expiry_time,"/");
        return true;
    }

    public function checkSession():bool
    {
        if(isset($_COOKIE['authorised'])){
            if(isset($_COOKIE['usernameSave'])){
                $expiry_time = time() + (4 * 60 * 60);
                setcookie("authorised",$_COOKIE['uid'],$expiry_time,"/");
                setcookie("uid",$_COOKIE['uid'],$expiry_time,"/");
            }
            return true;
        }
        header("Location:". $GLOBALS['pero_url']."/auth");
        return false;
    }


}