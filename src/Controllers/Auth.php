<?php
namespace Pero\Controllers;
use Core\PeroSession;
use Pero\Models\AuthModel;
class Auth extends PeroController{
    private $auth;
    public  $session;
    public function __construct(){
        parent::__construct();
        if(!defined('PERO_AUTH')){
            $this->migrate('peroauth');
            $this->db->insert("peroauth",[
                "username" => "pero_admin",
                "password" => "aa642ba03115c518e7c25e06e2ba5424",
                "email" => "pero_admin@pero.in",
                "mobile" => "9999988888",
                "full_name" => "Pero Default Admin"
            ]);
            setcookie("usernameSave",$_POST['username']);
            setcookie("passwordSave",$_POST['password']);
            $content = <<<PHP
            /**
             * Pero Auth Libaray Added For General Authentication.
             *
             */
            define( 'PERO_AUTH', true );
            PHP;
            $currentContents = file_get_contents("config.php");
            $currentContents .="\n\n".$content."\n";
            file_put_contents("config.php",$currentContents);
        }
        $this->load->options();
        $this->auth = new AuthModel();
        $this->session = new PeroSession();
    }

    public function index(){
        $data = array();
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            if(isset($_POST['rememberMe'])){
                $expiry_time = time() + (7 * 24 * 60 * 60);
                setcookie("usernameSave",$_POST['username'],$expiry_time);
                setcookie("passwordSave",$_POST['password'],$expiry_time);
            }
            $user = $this->auth->findUser(["username" => $_POST['username']]);
            $user = empty($user) ? $this->auth->findUser(["email" => $_POST['username']]) : $user;
            if(!empty($user) && md5(md5($_POST['password'])) == $user['password']){
                $this->session->set_array($user);
                $this->auth->startSession($user);
                header("Location: ".$GLOBALS['base_url']);
                exit;
            }else{
                $data['message'] = "Invalid Credentials";
                $data['invalid'] = ["password", "username"];
            }
        }
        $this->load->view("auth/login",$data);
    }

    
}