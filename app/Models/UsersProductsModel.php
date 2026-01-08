<?php

namespace App\Models;

use CodeIgniter\Model;

class UsersProductsModel extends Model
{
    protected $table = 'users_products';
    protected $useAutoIncrement = false;
    protected $useTimestamps = false;
    protected $primaryKey = 'person_id';

    protected $allowedFields = 
    [
        'person_id',
        'product_id',
        'quantity',
    ];

    public function getCartCount(int $userId): int
    {
        return $this->where('person_id', $userId)->countAllResults();
    }

    public function updatePersonProduct(int $person_id, int $product_id, int $quantity): void
    {
        $builder = $this->db->table('users_products');

        $builder->set('quantity', "quantity + $quantity", false)
        ->where('person_id', $person_id)
        ->where('product_id', $product_id)
        ->update();
    }
}
