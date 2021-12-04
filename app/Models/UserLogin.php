<?php

namespace App\Models;

use CodeIgniter\Model;

class UserLogin extends Model
{

    protected $table = 'tbl_userlogins';
    protected $primaryKey = 'userlogin_id';

    protected $allowedFields = [
        'user_id',
        'user_ip',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'is_deleted';
}
