<?php

namespace App\Controllers;

use App\Models\User;

class Registration extends BaseController
{
    public function index()
    {
        echo view('frontend/register');
    }

    public function regCheck($email = null, $fname = null, $lname = null, $pass = null, $gender = null, $role = null)
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {

            $userModel = new User();

            $check = $userModel->where('email', $email)->first();

            if ($check) {
                $string = ["message" => "Email already exists"];
            } else {

                $newUser = [
                    'first_name' => $fname,
                    'last_name' => $lname,
                    'email' => $email,
                    '`password`' => password_hash($pass, PASSWORD_DEFAULT),
                    'gender' => $gender,
                ];

                $newAdmin = [
                    'first_name' => $fname,
                    'last_name' => $lname,
                    'email' => $email,
                    '`password`' => password_hash($pass, PASSWORD_DEFAULT),
                    'gender' => $gender,
                    'role' => $role
                ];

                if ($role === null)
                    $id = $userModel->createUser($newUser);
                else
                    $id = $userModel->createUser($newAdmin);

                if (is_int($id)) {

                    $ses_data = [
                        'id' => $id,
                        'name' => $fname,
                        'email' => $email,
                        'isLoggedIn' => TRUE
                    ];

                    $session = session();
                    $session->set($ses_data);
                    $string = ["message" => "Registration Successful"];
                } else {
                    $string = ["message" => "Registration Failed"];
                }
            }
        } else {
            $string = ["message" => "Not a valid email"];
        }
        return $this->response->setJSON($string);
    }
}
