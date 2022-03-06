<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\UserLogin;
use DateTime;
use DateTimeZone;

class Login extends BaseController
{
    public function index()
    {
        echo view('frontend/login');
    }


    public function logout()
    {
        session();

        if (isset($_SESSION['login_id'])) {
            $user = new UserLogin();
            $user->logout($_SESSION['login_id']);
        }
        session_destroy();
        $data['logout'] = 1;
        echo view('/frontend/login', $data);
    }

    public function loginCheck($email = null, $password = null)
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $userModel = new User();
            $data = $userModel->where('email', $email)->get()->getResultArray()[0];

            if ($data != null) {

                $pass = $data['password'];

                $authenticatePassword = password_verify($password, $pass);

                if ($authenticatePassword) {

                    $user = new UserLogin();
                    $timezone = new DateTimeZone('Africa/Nairobi');
                    $date = new DateTime('now', $timezone);

                    $login = [
                        'user_id' => $data['user_id'],
                        'user_ip' => $this->request->getIPAddress(),
                        'login_time' => $date->format('Y-m-d H:i:s')
                    ];

                    $login_id = $user->login($login);

                    $ses_data = [
                        'id' => $data['user_id'],
                        'name' => $data['first_name'],
                        'email' => $data['email'],
                        'isLoggedIn' => TRUE,
                        'orders' => [],
                        'login_id' => $login_id
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

    private function noData()
    {
        session()->set('logout', 'hello');
        session_destroy();
    }
}
