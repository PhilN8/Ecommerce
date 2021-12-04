<?php

namespace App\Models;

use CodeIgniter\Model;
use DateTime, DateTimeZone;

class UserLogin extends Model
{

    protected $table = 'tbl_userlogins';
    protected $primaryKey = 'userlogin_id';

    protected $allowedFields = [
        'user_id',
        'user_ip',
        'login_time',
        'logout_time'
    ];

    // protected $useTimestamps = true;
    // protected $createdField  = 'created_at';
    // protected $updatedField  = 'updated_at';
    protected $deletedField  = 'is_deleted';

    public function login(array $user): mixed
    {
        $this->insert($user);

        return $this->getInsertID() ?? null;
    }

    public function logout(int $id)
    {
        $timezone = new DateTimeZone('Africa/Nairobi');
        $date = new DateTime('now', $timezone);
        $logout = [
            'logout_time' => $date->format("Y-m-d H:i:s")
        ];

        $this->update($id, $logout);
    }
}
