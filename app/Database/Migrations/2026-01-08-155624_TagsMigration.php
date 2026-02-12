<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class CreateTags extends Migration
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
                'constraint' => 50,
                'null'       => false,
            ],
            
        ]);

        $this->forge->addKey('id', true);

        $attributes = [
            'ENGINE'  
                   => 'InnoDB',
            'DEFAULT CHARSET'=> 'utf8mb4',
            'COLLATE'        => 'utf8mb4_latvian_ci',
        ];

        $this->forge->createTable('tags', true, $attributes);

        $this->db->query('CREATE UNIQUE INDEX `NAME` ON `tags` (`name`) USING BTREE');
    }

    public function down()
    {
        $this->forge->dropTable('tags', true);
    }
}
