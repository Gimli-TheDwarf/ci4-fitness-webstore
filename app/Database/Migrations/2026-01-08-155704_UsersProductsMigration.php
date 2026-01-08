<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class CreateUsersProducts extends Migration
{
    public function up()
    {
        $this->forge->addField(

        [
            'person_id' => 
            [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => true,
                'null'       => false,
            ],

            'product_id' => 
            [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
                'null'       => false,
            ],

            'quantity' => 
            [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
                'null'       => false,
            ],
        ]);

        $this->forge->addKey(['person_id', 'product_id'], true);
        $this->forge->addKey('product_id');

        $attributes = 
        [
            'ENGINE'         => 'InnoDB',
            'DEFAULT CHARSET'=> 'utf8mb4',
            'COLLATE'        => 'utf8mb4_latvian_ci',
        ];

        $this->forge->createTable('users_products', true, $attributes);

        $this->db->query(
            'ALTER TABLE `users_products`
            ADD CONSTRAINT `users_products_ibfk_1`
            FOREIGN KEY (`person_id`) REFERENCES `users` (`id`)
            ON DELETE CASCADE'
            );

        $this->db->query(
            'ALTER TABLE `users_products`
            ADD CONSTRAINT `users_products_ibfk_2`
            FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
            ON DELETE CASCADE'
            );
    }

    public function down()
    {
        $this->forge->dropTable('users_products', true);
    }
}
