<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class CreateProductImages extends Migration
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

            'item_id' => 
            [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
                'null'       => false,
            ],

            'img' => 
            [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => false,
            ],

            'slot' => 
            [
                'type'       => 'TINYINT',
                'constraint' => 4,
                'null'       => false,
                'default'    => 1,
            ],
        ]);

        $this->forge->addKey('id', true);

        $attributes = 
        [
            'ENGINE'         => 'InnoDB',
            'DEFAULT CHARSET'=> 'utf8mb4',
            'COLLATE'        => 'utf8mb4_latvian_ci',
        ];

        $this->forge->createTable('product_images', true, $attributes);

        $this->db->query('CREATE UNIQUE INDEX `unique_item_slot` ON `product_images` (`item_id`, `slot`)');

        $this->db->query(
            'ALTER TABLE `product_images`
            ADD CONSTRAINT `product_images_ibfk_1`
            FOREIGN KEY (`item_id`) REFERENCES `products` (`id`)
            ON DELETE CASCADE'
            );

        $this->db->query(
            'ALTER TABLE `product_images`
            ADD CONSTRAINT `CONSTRAINT_1`
            CHECK (`slot` BETWEEN -6 AND 6)'
            );
    }

    public function down()
    {
        $this->forge->dropTable('product_images', true);
    }
}
