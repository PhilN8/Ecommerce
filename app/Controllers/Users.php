<?php

namespace App\Controllers;

use App\Models\API_User;
use App\Models\User;
use CodeIgniter\HTTP\ResponseInterface;
use Exception;
use phpDocumentor\Reflection\Types\This;

class Users extends BaseController
{
    /**
     * Returns all users
     * @return ResponseInterface
     */
    public function index() {

        $model = new User();
        $all = $model->select('user_id, first_name, last_name, email, gender, role')->get()->getResultArray();

        return $this->getResponse(
                [
                    'message' => 'Users retrieved successfully',
                    'users'   => $all,
                ]
        );

    }


    /**
     * Get a single user by ID
     */
    public function show(int $id)
    {
        try {

            $model = new User();
            $user = $model->get()->getResultArray()[0];
            unset($user['password']);

//            print_r($user);
//            var_dump($user);

            return $this->getResponse(
                [
                    'message' => 'User retrieved successfully',
                    'user' => $user
                ]
            );
        } catch (Exception $e) {
            return $this->getResponse(
                [
                    'message' => 'Could not find user for specified ID',
                    'additional info' => $e->getMessage()
                ],
                ResponseInterface::HTTP_NOT_FOUND
            );
        }
    }

    /**
     * Searches for user via email
     * @return ResponseInterface
     */
    public function byEmail()
    {
        $rules = [
          'email' => 'required|valid_email'
        ];

        $input = $this->getRequestInput($this->request);
        if (!$this->validateRequest($input, $rules))
            return $this->getResponse(
                $this->validator->getErrors(),
                ResponseInterface::HTTP_BAD_REQUEST
            );


        try{
            $user = (new User())->findUserByEmailAddress($input['email']);
            unset($user['password']);

            return $this->getResponse([
                'message' => 'User Found',
                'user' => $user,
            ]);

        } catch (Exception $e) {
            return $this->getResponse(
                [
                    'message' => $e->getMessage()
                ],
                ResponseInterface::HTTP_NOT_FOUND
            );
        }

    }

    /**
     * Gets all male users
     * @return ResponseInterface
     */
    public function male()
    {
        $model = new User();
        $males = $model->select('user_id, first_name, last_name, email, role')
            ->where('gender', 'male')->get()->getResultArray();

        return $this->getResponse(
            [
                'message' => 'Male users retrieved successfully',
                'users'   => $males,
            ]
        );
    }

    /**
     * Gets all female users
     * @return ResponseInterface
     */
    public function female()
    {
        $model = new User();
        $females = $model->select('user_id, first_name, last_name, email, role')
            ->where('gender', 'female')->get()->getResultArray();

        return $this->getResponse(
            [
                'message' => 'Female users retrieved successfully',
                'users'   => $females,
            ]
        );
    }
}