<?php

namespace App\Models;

use CodeIgniter\Model;

class API_Token extends Model
{
    protected $table = 'tbl_apitokens';
    protected $primaryKey = 'apitoken_id';

    protected $allowedFields = [
        'api_userid',
        'api_token',
        'expires_on'
    ];

//    protected $useTimestamps = true;
    protected $createdField = 'created_at';
}
