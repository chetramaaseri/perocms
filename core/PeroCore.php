<?php
namespace Core;

use Core\PeroApp;

class PeroCore extends PeroApp{

    private $directory = 'src/Database/Migrations';
    public function __construct(){
        parent::__construct();
    }

    public function migration($command,$name){
        switch ($command) {
            case 'create':
                if ($name) {
                    $this->createMigrationFile($name);
                } else {
                    echo "Please provide a migration name.\n Example Use : php pero migration create users";
                }
                break;
            case 'remove':
                if ($name) {
                    $this->removeMigration($name);
                } else {
                    echo "Please provide a migration name.\n Example Use : php pero migration create users";
                }
                break;
            default:
                echo "Invalid command. Available commands: create\n";
                break;
        }
    }

    public function migrate($tableName){
        $directory = $this->directory;
        $files = scandir($directory);

        $phpFiles = array_filter($files, function($file) {
            return pathinfo($file, PATHINFO_EXTENSION) === 'php';
        });

        $phpFiles = array_filter($phpFiles, function($file) use ($tableName) {
            return preg_match('/\d{4}_\d{2}_\d{2}_\d{6}_'.$tableName.'\.php$/', $file);
        });
        usort($phpFiles, function($a, $b) use ($directory) {
            $timeA = filemtime("$directory/$a");
            $timeB = filemtime("$directory/$b");
            return $timeB - $timeA;
        });

        // Select the latest file
        if (!empty($phpFiles)) {
            $latestFile = $phpFiles[0];
            $this->runMigrationFile($tableName,$latestFile);
        } else {
            echo "No Migration found of name $tableName.\n";
        }
    }

    public function backup($command, $name){
        switch ($command) {
            case 'db':
                if ($name && $this->db->table_exists($name) && $this->db->backup($name)) {
                    echo "Database Table Backup Sucessfull";
                } else {
                    echo "Please provide a existing migration name.\n Example Use : php pero migration create users";
                }
                break;
            default:
                echo "Invalid command. Available commands: create\n";
                break;
        }
    }

    private function createMigrationFile($migrationName){
        $timestamp = date('Y_m_d_His');
        $className = ucfirst($migrationName);
        $filename = "src/Database/migrations/{$timestamp}_{$migrationName}.php";
        $content = <<<PHP
        <?php
        namespace Pero\Database\Migrations;
        
        use Core\PeroMigration;
        use Core\PeroTableSchema;
        
        class $className extends PeroMigration
        {
            /**
             * Run the migrations.
             *
             * @return void
             */
            public function up()
            {
                \$schema = PeroTableSchema::create('$migrationName', [
                    'rid' => [
                        'type'           => 'INT',
                        'constraint'     => 5,
                        'auto_increment' => true,
                        'key'            => 'primary'
                    ],
                    'col1' => [
                        'type'       => 'VARCHAR',
                        'constraint' => '100',
                        'key'        => 'unique'
                    ],
                    'col2' => [
                        'type' => 'TEXT',
                        'null' => true,
                        'default' => 'lorem lorem'
                    ],
                    'col3' => [
                        'type' => 'TEXT',
                        'null' => true,
                    ],
                    'created_at' => [
                        'type'    => 'TIMESTAMP',
                        'default' => 'current_timestamp'
                    ],
                    'updated_at' => [
                        'type'    => 'TIMESTAMP',
                        'default' => 'current_timestamp on update current_timestamp'
                    ],
                    'deleted_at' => [
                        'type'    => 'TIMESTAMP',
                        'default' => 'current_timestamp on update current_timestamp'
                    ]
                ]);
        
                \$this->run('$migrationName',\$schema);
            }
        
            /**
             * Reverse the migrations.
             *
             * @return void
             */
            public function down()
            {
                \$this->dropIfExists('\$migrationName');
            }
        }
        PHP;
        // Create the migration file
        file_put_contents($filename, $content);
        echo "Created migration: $filename\n";
    }

    private function runMigrationFile($table,$file){
        require_once realpath($this->directory."/". $file);
        $className = "Pero\\Database\\Migrations\\" . ucfirst($table);
        if (class_exists($className)) {
            $migration = new $className();
            if (method_exists($migration, 'up')) {
                $migration->up();
            } else {
                echo $table." Migration File Corrupted";
            }
        } else {
            echo "Class '$className' not found in file '$file'.";
        }
    }
    private function removeMigration($tableName){
        $directory = $this->directory;
        $files = scandir($directory);

        $phpFiles = array_filter($files, function($file) {
            return pathinfo($file, PATHINFO_EXTENSION) === 'php';
        });

        $phpFiles = array_filter($phpFiles, function($file) use ($tableName) {
            return preg_match('/\d{4}_\d{2}_\d{2}_\d{6}_'.$tableName.'\.php$/', $file);
        });
        usort($phpFiles, function($a, $b) use ($directory) {
            $timeA = filemtime("$directory/$a");
            $timeB = filemtime("$directory/$b");
            return $timeB - $timeA;
        });

        // Select the latest file
        if (!empty($phpFiles)) {
            $latestFile = $phpFiles[0];
            require_once realpath($this->directory."/". $latestFile);
            $className = "Pero\\Database\\Migrations\\" . ucfirst($tableName);
            if (class_exists($className)) {
                $migration = new $className();
                if (method_exists($migration, 'down')) {
                    $migration->down();
                } else {
                    echo $table." Migration File Corrupted";
                }
            } else {
                echo "Class '$className' not found in file '$file'.";
            }
        } else {
            echo "No Migration found of name $tableName.\n";
        }
    }
}