<?php
namespace Core;

class PeroTableSchema{
    public static function create($tableName, $columns){
        $schema = "CREATE TABLE IF NOT EXISTS `$tableName` (";

        // Add 'created_at', 'updated_at', and 'deleted_at' columns
        // $columns['created_at'] = ['type' => 'TIMESTAMP', 'not_null' => true, 'default' => 'CURRENT_TIMESTAMP'];
        // $columns['updated_at'] = ['type' => 'TIMESTAMP', 'not_null' => true, 'default' => 'CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'];
        // $columns['deleted_at'] = ['type' => 'TIMESTAMP', 'null' => true, 'default' => null];

        foreach ($columns as $columnName => $columnDefinition) {
            $type = $columnDefinition['type'];
            $constraint = isset($columnDefinition['constraint']) ? $columnDefinition['constraint'] : '';
            $null = isset($columnDefinition['null']) && $columnDefinition['null'] ? 'NULL' : 'NOT NULL';
            $default = isset($columnDefinition['default']) ? $columnDefinition['default'] : '';
            $default = ($type == 'TIMESTAMP') ? "DEFAULT $default" : ($default ? "DEFAULT '$default'" : '');
            $extra = isset($columnDefinition['extra']) ? $columnDefinition['extra'] : '';
    
            $schema .= "`$columnName` $type";
    
            if ($constraint) {
                $schema .= "($constraint)";
            }
    
            $schema .= " $null";
    
            // Add AUTO_INCREMENT here if it's an INT column and marked as auto_increment
            if ($type === 'INT' && isset($columnDefinition['auto_increment']) && $columnDefinition['auto_increment']) {
                $schema .= " AUTO_INCREMENT";
            }
    
            $schema .= " $default $extra,"; // Add column definition
    
            // Check for keys
            if (isset($columnDefinition['key'])) {
                switch ($columnDefinition['key']) {
                    case 'primary':
                        $schema .= "PRIMARY KEY (`$columnName`),";
                        break;
                    case 'unique':
                        $schema .= "UNIQUE KEY `$columnName` (`$columnName`),";
                        break;
                    // Add other key types if needed
                }
            }
        }

        // Remove the trailing comma and close the query
        $schema = rtrim($schema, ',') . ')';

        return $schema;
    }
}