<?php
namespace Pero\Controllers;
use Core\PeroLoad;
use Pero\Models\AuthModel;
class Home extends PeroController{
    private $auth;
    public function __construct(){
        parent::__construct();
        $this->load->options();
        $this->auth = new AuthModel();
    }

    public function index(){
        $this->auth->checkSession();
    }
}