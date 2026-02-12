<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class CreateProductTags extends Migration
{
    public function up()
    {
        $this->forge->addField(
        [
            'item_id' => 
            [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
                'null'       => false,
            ],

            'tag_id' => 
            [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
                'null'       => false,
            ],
        ]);

        $this->forge->addKey(['item_id', 'tag_id'], true);
        $this->forge->addKey('tag_id');

        $attributes = 
        [
            'ENGINE'         => 'InnoDB',
            'DEFAULT CHARSET'=> 'utf8mb4',
            'COLLATE'        => 'utf8mb4_latvian_ci',
        ];

        $this->forge->createTable('product_tags', true, $attributes);
        $this->db->query('ALTER TABLE `product_tags`
            ADD CONSTRAINT `product_tags_ibfk_1`
            FOREIGN KEY (`item_id`) REFERENCES `products` (`id`)
            ON DELETE CASCADE');

        $this->db->query('ALTER TABLE `product_tags`
            ADD CONSTRAINT `product_tags_ibfk_2`
            FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`)
            ON DELETE CASCADE');
    }

    public function down()
    {
        $this->forge->dropTable('product_tags', true);
    }
}
