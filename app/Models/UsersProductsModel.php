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
        $this->db->query(
            'UPDATE users_products SET quantity = GREATEST(quantity + ?, 0) WHERE person_id = ? AND product_id = ?',
            [$quantity, $person_id, $product_id]
        );
    }
}
