<?php
namespace Core;

class PeroSqlResult{

    private $mysqliObject;
    public function __construct($mysqliObject){
        $this->mysqliObject = $mysqliObject;
    }

    public function num_rows(){
        return $this->mysqliObject->num_rows;
    }

    public function result_array(){
        return $this->mysqliObject->fetch_all(MYSQLI_ASSOC);
    }

    public function row_array(){
        return $this->mysqliObject->fetch_array(MYSQLI_ASSOC);
    }

}
