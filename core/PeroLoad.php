<?php
namespace Core;

class PeroLoad extends PeroCore{

    private $directory;
    public function __construct($directory = "src/Views"){
        parent::__construct();
        $this->directory = $directory;
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
        require realpath($this->directory."/".$view.".php");
    }

}
