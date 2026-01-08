<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class CreateUsers extends Migration
{
    public function up()
    {
        $this->forge->addField(
        [

            'id' => 
            [
                'type'           => 'BIGINT',
                'constraint'     => 20,
                'unsigned'       => true,
                'auto_increment' => true,
            ],

            'username' => 
            [
                'type' => 'TEXT',
                'null' => false,
            ],

            'email' => 
            [
                'type' => 'TEXT',
                'null' => false,
            ],

            'password' => 
            [
                'type' => 'TEXT',
                'null' => false,
            ],

            'phone' => 
            [
                'type' => 'TEXT',
                'null' => true,
            ],

            'address' => 
            [
                'type' => 'TEXT',
                'null' => true,
            ],

            'full_name' => 
            [
                'type' => 'TEXT',
                'null' => true,
            ],

            'role' => 
            [
                'type'       => 'ENUM',
                'constraint' => ['user', 'administrator'],
                'null'       => false,
                'default'    => 'user',
            ],

            'email_verified' => 
            [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'null'       => true,
                'default'    => 0,
            ],
            'is_active' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'null'       => true,
                'default'    => 1,
            ],

            'created_at' => 
            [
                'type'    => 'TIMESTAMP',
                'null'    => false,
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
            
            'updated_at' => 
            [
                'type'    => 'TIMESTAMP',
                'null'    => false,
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
        ]);

        $this->forge->addKey('id', true);

        $attributes = [
            'ENGINE'         => 'InnoDB',
            'DEFAULT CHARSET'=> 'utf8mb4',
            'COLLATE'        => 'utf8mb4_latvian_ci',
        ];

        $this->forge->createTable('users', true, $attributes);
    }

    public function down()
    {
        $this->forge->dropTable('users', true);
    }
}
