<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class CreateProducts extends Migration
{
    public function up()
    {
        $this->forge->addField(
            [
            'id' => 
            [
                'type'           => 'INT',
                'constraint'     => 10,
                'unsigned'       => true,
                'auto_increment' => true,
            ],

            'name' => 
            [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => false,
            ],

            'description' => 
            [
                'type' => 'LONGTEXT',
                'null' => false,
            ],

            'price' => 
            [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'null'       => false,
                'default'    => 0.00,
            ],

            'status' => 
            [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'unsigned'   => true,
                'null'       => false,
                'default'    => 0,
            ],

            'discount_percentage' => 
            [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => false,
                'default'    => 0,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('products', true);
    }

    public function down()
    {
        $this->forge->dropTable('products', true);
    }
}
