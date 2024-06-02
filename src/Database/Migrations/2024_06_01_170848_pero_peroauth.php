<?php
namespace Pero\Database\Migrations;

use Core\PeroMigration;
use Core\PeroTableSchema;

class Pero_peroauth extends PeroMigration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $schema = PeroTableSchema::create('pero_peroauth', [
            'puser_id' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'auto_increment' => true,
                'key'            => 'primary'
            ],
            'username' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'key'        => 'unique'
            ],
            'password' => [
                'type'       => 'TEXT',
            ],
            'full_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'mobile' => [
                'type' => 'VARCHAR',
                'constraint' => '15',
            ],
            'email' => [
                'type' => 'TEXT',
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

        $this->run('pero_peroauth',$schema);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->dropIfExists('pero_peroauth');
    }
}