<?php
namespace Core;

class PeroLoad extends PeroCore{

    private $directory;
    private $session;
    public function __construct($directory = "src/Views"){
        parent::__construct();
        $this->directory = $directory;
        $this->session = new PeroSession();
    }
    
    public function options(){
        $this->db->select("`key`,`value`");
        $this->db->from("options");
        $query = $this->db->get();
        $result = $query->result_array();
        foreach($result as $item) {
            if (isset($item['key']) && isset($item['value'])) {
                $GLOBALS[$item['key']] = $item['value'];
            }
        }
    }

    public static function option($key){
        return $GLOBALS[$key] ?? false;
    }


    public function view($view,$data = []){
        extract($GLOBALS);
        extract($data);
        include realpath($this->directory."/".$view.".php");
    }
    public function render($view,$data = []){
        extract($GLOBALS);
        extract($data);
        include realpath($this->directory."/".$view.".php");
    }

}
