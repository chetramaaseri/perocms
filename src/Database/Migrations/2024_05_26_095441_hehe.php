<?php
namespace Pero\Database\Migrations;

use Core\PeroMigration;
use Core\PeroTableSchema;

class Hehe extends PeroMigration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $schema = PeroTableSchema::create('hehe', [
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

        $this->run($schema);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->dropIfExists('users');
    }
}