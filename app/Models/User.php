<?php

namespace App\Models;

use CodeIgniter\Model;
use Exception;

class User extends Model
{

    protected $table = 'tbl_users';
    protected $primaryKey = 'user_id';

    protected $allowedFields = [
        'first_name',
        'last_name',
        'email',
        '`password`',
        'gender',
        'role'
    ];

    protected $useSoftDeletes = true;
    protected $deletedField = 'is_deleted';

    public function getUsers(int $role = null)
    {

        if ($role === null || $role == 0)
            return $this->select('user_id, first_name, last_name, email')
                ->findAll();


        return $this->select('user_id, first_name, last_name, email')
            ->where(['role' => $role])
            ->get()->getResultArray();
    }

    public function createUser(array $newUser): mixed
    {

        if ($this->insert($newUser, true) == true)
            return $this->getInsertID();
        else
            return false;
    }

    public function editUser(array $newValue, int $id): bool
    {
        if ($this->update($id, $newValue) == true)
            return true;

        return false;
    }

    public function deleteUser(int $id): bool
    {

        if ($this->delete(['user_id' => $id]) == true)
            return true;
        else
            return false;
    }

    public function findUserByEmailAddress(string $emailAddress)
    {
        $user = $this
            ->asArray()
            ->where(['email' => $emailAddress])
            ->first();

        if (!$user)
            throw new Exception('User does not exist for specified email address');

        return $user;
    }
}
