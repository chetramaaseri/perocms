<?php

namespace Core;


class PeroMigration extends PeroCore{

    public function run($schema){
        // run migration here after validating it 
        echo $schema;
    }

    public function dropIfExists($table){
        // drop table if exists
    }
}