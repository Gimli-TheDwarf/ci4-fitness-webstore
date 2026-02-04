<?php

namespace App\Models;

use CodeIgniter\Model;

class UserFavoriteModel extends Model
{
    protected $table            = 'user_wishlist';
    protected $primaryKey       = 'person_id';  
    protected $useAutoIncrement = false;  
    protected $allowedFields    = ['product_id','person_id'];
}
