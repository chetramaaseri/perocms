<?php
namespace Core;
use MessagePack\MessagePack;
use PhpMyAdmin\SqlParser\Parser;
use PhpMyAdmin\SqlParser\Utils\Query;
class PeroDatabase{
    private $conn;
    private $hostName;
    private $userName;
    private $password;
    private $dbName;

    public $query;
    private $select;
    private $where;
    private $from;
    private $update;
    private $insert;
    private $delete;
    public function __construct(String $hostName,String $userName,String $password,String $dbName){
        if(!$this->conn){
            $this->hostName = $hostName;
            $this->userName = $userName;
            $this->password = $password;
            $this->dbName = $dbName;
            $this->conn = new \mysqli($hostName,$userName,$password,$dbName);
        }
    }

    public function has_error($query){
        try {
            $errors = [];
            $parser = new Parser($query);
            if ($parser->errors) {
                foreach ($parser->errors as $error) {
                    $errors[] = $error->getMessage() . "\n";
                }
            }
            return $errors;
        } catch (Exception $e) {
            echo "PeroDatabase\validate: ".$e->getMessage() . "\n";
            return [];
        }
    }

    public function validate($query){
        try {
            $parser = new Parser($query);
            if ($parser->errors) {
                return false;
            }
            return true;
        } catch (Exception $e) {
            echo "PeroDatabase\validate: ".$e->getMessage() . "\n";
            return false;
        }
    }

    public function query($query){
        try{
            $this->query = $query;
            $result = $this->conn->query($query);
            return new PeroSqlResult($result);
        } catch (Exception $e) {
            echo "PeroDatabase\query: ".$e->getMessage() . "\n";
            return false;
        }
    }


    public function table_exists($table){
        try{
            $table = table_prefix.$table;
            $sql = "SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES 
                        WHERE TABLE_SCHEMA = '{$this->dbName}' 
                        AND TABLE_NAME = '$table';";
    
            $query = $this->query($sql);
            return $query->num_rows() > 0; 
        } catch (Exception $e) {
            echo "PeroDatabase\query: ".$e->getMessage() . "\n";
            return false;
        }
    }

    public function list_columns($table){
        try{
            $table = table_prefix.$table;
            $sql = "SHOW COLUMNS FROM $table";
            $query = $this->query($sql);
            return $query->result_array();
        } catch (Exception $e) {
            echo "PeroDatabase\query: ".$e->getMessage() . "\n";
            return false;
        }
    }
    public function last_query(){
        return !empty($this->query) ? $this->query : "Cant Find Last Query";
    }

    public function drop($table){
        $table = table_prefix.$table;
        $sql = "DROP TABLE $table";
        return $this->query($sql);
    }

    public function backup($table){
        $table = table_prefix.$table;
        // Backup file path
        $timestamp = date('Y_m_d_His');
        $backupFile = "backup/sql/{$timestamp}_{$table}.gz";

        $this->select("*");
        $this->from($table);
        $query = $this->get();
        $data = $query->result_array();

        $packed = MessagePack::pack($data);
        $gzencoded = gzencode($packed,9);
        file_put_contents($backupFile, $gzencoded);
        return true;

    }

    public function get(){
        $sql = $this->select. $this->from . $this->where;
        return $this->query($sql);
    }

    public function from($table){
        $table = table_prefix.$table;
        $this->from = " FROM ".$table;
    }

    public function select($select){
        $this->select = "SELECT ".$select;
    }
    
    public function where(){
        $args = func_get_args();
        if(is_array($args[0])){
            foreach($args[0] as $key => $value){
                $this->createWhere([
                    $key,$value
                ]);
            }
        }else{
            $this->createWhere($args);
        }
            
    }
        
    private function createWhere($args){
        $args[1] = $this->conn->real_escape_string($args[1]);
        if(empty($this->where)){
            $this->where .= " WHERE `".$args[0]."` = '".$args[1]."' ";
        }else{
            $this->where .= "AND `".$args[0]."` = '".$args[1]."' ";
        }
    }

    public function update($table,$data){
        $table = table_prefix.$table;
        $string = "UPDATE `$table` SET ";
        foreach($data as $col => $value){
            // $string.= "`".$col."` = '".$value."'";
            $string.= "`$col` = '$value' ,";
        }
        $string = rtrim($string,",");
        $this->update = $string;
        return $this->query($this->update . $this->where);
    }

    public function delete($table){
        $table = table_prefix.$table;
        $this->delete = "DELETE FROM $table ";
        return $this->query($this->delete . $this->where);
    }

    public function insert($table,$data){
        $table = table_prefix.$table;
        $this->insert = "INSERT INTO $table (";
        $fieldNames = array_keys($data); 
        $this->insert .= "`".implode("`,`",$fieldNames)."`) VALUES(";
        $fieldData = array_values($data); 
        // escape strings here using real escape string
        $this->insert .= "'".implode("','",$fieldData)."') ";
        $this->query($this->insert);
        return $this->conn->insert_id;
    }

    public function __destruct(){
        $this->conn->close();
    }
}
