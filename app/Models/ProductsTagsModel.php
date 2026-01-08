<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductsTagsModel extends Model
{
    protected $table            = 'product_tags';
    protected $primaryKey       = 'item_id';  
    protected $useAutoIncrement = false;  
    protected $allowedFields    = ['item_id','tag_id'];
}
