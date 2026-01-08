<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';

    protected $allowedFields = 
    [
        'username',
        'email',
        'password',
        'phone',
        'address',
        'full_name',
        'role',
        'email_verified',
        'is_active',
        'cart',
        'created_at',
        'updated_at',
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
}
