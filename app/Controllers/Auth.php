<?php

namespace App\Controllers;

use App\Models\API_User;
use App\Models\User;
use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\ResponseInterface;
use Exception;
use ReflectionException;

class Auth extends BaseController
{
    /**
     * Register a new user
     * @return ResponseInterface
     * @throws ReflectionException
     */
    public function register()
    {
        $rules = [
            'username' => 'required|is_unique[tbl_apiusers.username]|min_length[8]|max_length[40]',
            'password' => 'required|min_length[8]|max_length[100]'
        ];

        $input = $this->getRequestInput($this->request);
        if (!$this->validateRequest($input, $rules)) {
            return $this
                ->getResponse(
                    $this->validator->getErrors(),
                    ResponseInterface::HTTP_BAD_REQUEST
                );
        }

        $apiUser = new API_User();
        $apiUser->save($input);


        return $this
            ->getJWTForUser(
                $input['username'],
                ResponseInterface::HTTP_CREATED
            );

    }

    /**
     * Authenticate Existing User
     * @return ResponseInterface
     */
    public function login()
    {
        $rules = [
            'username' => 'required|min_length[8]|max_length[40]',
            'password' => 'required|min_length[8]|max_length[100]|validateUser[username, password]'
        ];

        $errors = [
            'password' => [
                'validateUser' => 'Invalid login credentials provided'
            ]
        ];

        $input = $this->getRequestInput($this->request);


        if (!$this->validateRequest($input, $rules, $errors)) {
            return $this
                ->getResponse(
                    $this->validator->getErrors(),
                    ResponseInterface::HTTP_BAD_REQUEST
                );
        }
        return $this->getJWTForUser($input['username']);


    }

    private function getJWTForUser(
        string $username,
        int $responseCode = ResponseInterface::HTTP_OK
    )
    {
        try {
            $model = new API_User();
            $user = $model->findUser($username);
            unset($user['password']);

            helper('jwt');

            return $this
                ->getResponse(
                    [
                        'message' => 'User authenticated successfully',
                        'user' => $user,
                        'access_token' => getSignedJWTForUser($username)
                    ]
                );
        } catch (Exception $exception) {
            return $this
                ->getResponse(
                    [
                        'error' => $exception->getMessage(),
                    ],
                    $responseCode
                );
        }
    }
}
