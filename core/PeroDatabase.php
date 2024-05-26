<?php
namespace Core;
class PeroDatabase{
    private $conn;
    public function __construct(String $hostName,String $userName,String $password,String $dbName){
        $this->conn = new \mysqli($hostName,$userName,$password,$dbName);
    }
}
