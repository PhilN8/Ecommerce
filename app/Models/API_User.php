<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Models\User;

class API_User extends Model
{
    protected $table = 'tbl_apiusers';
    protected $primaryKey = 'apiuser_id';

    protected $allowedFields = [
        'username',
        'key'.
        'password'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'is_deleted';

    protected $beforeInsert = ['beforeInsert'];
    protected $beforeUpdate = ['beforeUpdate'];

    protected function beforeInsert(array $data): array
    {
        return $this->getUpdatedDataWithHashedPassword($data);
    }

    protected function beforeUpdate(array $data): array
    {
        return $this->getUpdatedDataWithHashedPassword($data);
    }

    private function getUpdatedDataWithHashedPassword(array $data): array
    {
        if (isset($data['data']['password'])) {
            $plaintextPassword = $data['data']['password'];
            $data['data']['password'] = $this->hashPassword($plaintextPassword);
        }
        return $data;
    }

    private function hashPassword(string $plaintextPassword): string
    {
        return password_hash($plaintextPassword, PASSWORD_BCRYPT);
    }

    public function findUser(string $username)
    {
        $user = $this
            ->asArray()
            ->where(['username' => $username])
            ->first();

        if (!$user)
            throw new \Exception('User does not exist for specified username');

        return $user;
    }
}