<?php

namespace App\Controllers;

use App\Models\UserModel;

class Login extends BaseController
{
    public function index()
    {
        echo view('frontend/login');
    }


    public function logout()
    {
        session();
        session_destroy();
        $data['logout'] = 1;
        echo view('/frontend/login', $data);
    }

    public function loginCheck($email = null, $password = null)
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $userModel = new UserModel();
            $data = $userModel->where('email', $email)->first();

            if ($data) {

                $pass = $data['password'];

                $authenticatePassword = ($password == $pass) ? true : false;

                if ($authenticatePassword) {
                    $ses_data = [
                        'id' => $data['user_id'],
                        'name' => $data['first_name'],
                        'email' => $data['email'],
                        'isLoggedIn' => TRUE
                    ];

                    $session = session();
                    $session->set($ses_data);

                    $string = [
                        'message' => "Login Successful",
                        'role' => $data['role']
                    ];
                } else {

                    $string = ["message" => "Invalid Credentials"];
                }
            } else {

                $string = ["message" => "No such Record"];
            }
        } else {
            $string = ["message" => "Not a valid email"];
        }

        return $this->response->setJSON($string);
    }
}
