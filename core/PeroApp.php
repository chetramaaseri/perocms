<?php
namespace Core;

require_once realpath("config.php");

class PeroApp{

    public $db;
    public function __construct(){
        $this->db = new PeroDatabase(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
    }
}