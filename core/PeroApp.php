<?php
namespace Core;

require_once realpath("config.php");

class PeroApp extends PeroCore{

    public $db;

    public $load;
    public $session;
    public function __construct(){
        $this->db = new PeroDatabase(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
        $this->load = new PeroLoad();

    }

}