<?php

namespace Core;


class PeroMigration extends PeroCore{

    public function run($table,$schema){
        // run migration here after validating it 
        if($this->db->validate($schema)){
            if($this->db->table_exists($table)){
                $this->db->backup($table);
                $this->db->query($schema);
            }else{
                $this->db->query($schema);
            }
        }
    }

    public function dropIfExists($table){
        // drop table if exists
        if($this->db->table_exists($table)){
            $this->db->drop($table);
            echo "Migration removed successfully";
        }else{
            echo "Migration does not exists in database";
        }
    }
}