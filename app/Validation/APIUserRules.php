<?php

namespace App\Validation;

use App\Models\API_User;
use Exception;

class APIUserRules
{
    public function validateUser(string $str, string $fields, array $data): bool
    {
        try {
            $model = new API_User();
            $user = $model->findUser($data['username']);
            return password_verify($data['password'], $user['password']);
        } catch (Exception $e) {
            return false;
        }
    }

    function check_date(string $str, string &$error = null)
    {
        if ($str > date("Y-m-d"))
            return false;

        return true;
    }
}

