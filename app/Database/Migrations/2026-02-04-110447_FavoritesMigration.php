<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUserWishlist extends Migration
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
        ]);

        $this->forge->addKey(['person_id', 'product_id'], true);

        $this->forge->addForeignKey('person_id', 'users', 'id', 'NO ACTION', 'CASCADE');
        $this->forge->addForeignKey('product_id', 'products', 'id', 'NO ACTION', 'CASCADE');

        $this->forge->createTable('user_wishlist', true, ['ENGINE' => 'InnoDB']);
    }

    public function down()
    {
        $this->forge->dropTable('user_wishlist', true);
    }
}
