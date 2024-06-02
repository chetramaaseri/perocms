<?php
namespace Pero\Database\Migrations;

use Core\PeroMigration;
use Core\PeroTableSchema;

class Pero_options extends PeroMigration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $schema = PeroTableSchema::create('pero_options', [
            'rid' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'auto_increment' => true,
                'key'            => 'primary'
            ],
            'key' => [
                'type' => 'text',
                'null' => true,
            ],
            'type' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'value' => [
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

        $this->run('pero_options',$schema);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->dropIfExists('pero_options');
    }
}