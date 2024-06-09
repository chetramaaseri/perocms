<?php
namespace Core;

class PeroSession{

    public function __construct(){
        if(!session_id()){
            session_start();
        }
        $this->load();
    }

    private function load(){
        array_walk($_SESSION, function($value, $key) {
            $this->$key = $value;
        }, $this);
    }

    public function set($key,$value){
        $_SESSION[$key] = $value;
        $this->$key = $value;
    }

    public function set_array($array){
        array_walk($array, function($value, $key) {
            $this->$key = $value;
            $_SESSION[$key] = $value;
        }, $this);
    }

}
