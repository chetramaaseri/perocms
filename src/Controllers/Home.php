<?php
namespace Pero\Controllers;
use Core\PeroLoad;
use Core\PeroSession;
use Pero\Models\AuthModel;
class Home {
    private $auth;
    public $load;
    public $session;
    public function __construct(){
        $this->auth = new AuthModel();
        $this->load = new PeroLoad();
        $this->session = new PeroSession();
        $this->load->options();
    }

    public function index(){
        $this->auth->checkSession();
        $this->load->view("dashboard");
    }
}