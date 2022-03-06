<?php

namespace App\Controllers;

use App\Models\User;
use CodeIgniter\HTTP\ResponseInterface;
use Exception;

class Users extends BaseController
{
    private $db = null;

    /**
     * Returns all users
     * @return ResponseInterface
     */
    public function index() : ResponseInterface
    {

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
     * @param int $id - ID to search by
     *
     * @return ResponseInterface
     */
    public function show(int $id) : ResponseInterface
    {
        try {

            $model = new User();
            $user = $model->where('user_id', $id)->get()->getResultArray()[0];
            unset($user['password']);

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
    public function email(string $email): ResponseInterface
    {
        $email = trim($email);
        $input = ['email' => $email];
        $rules = [
          'email' => 'required|valid_email'
        ];

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
    public function male() : ResponseInterface
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
    public function female() : ResponseInterface
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

    public function last_login($date): ResponseInterface
    {
        $date = trim($date);
        $this->db = db_connect();

        $input = ['date' => $date];
        $rules = [
            'date' => [
                'rules' => 'required|check_date|valid_date[Y-m-d]',
                'label' => 'Date',
                'errors' => [
                    'check_date' => 'You cannot add a Start Date after today',
                    'valid_date' => 'Start Date must of the format yyyy-mm-dd e.g. ' . date('Y-m-d')
                ]
            ],

        ];

        if (!$this->validateRequest($input, $rules))
            return $this->getResponse(
                $this->validator->getErrors(),
                ResponseInterface::HTTP_BAD_REQUEST
            );

        $sql = "SELECT * FROM tbl_userlogins WHERE `login_time` > '$date' GROUP BY `user_id`";
        $result = $this->db->query($sql);

        $rows = [];
        foreach ($result->getResult('array') as $row) {
            $user = (new User())->where('user_id', $row['user_id'])->get()->getResultArray()[0];

            $rows[] = [
                'userlogin_id' => $row['userlogin_id'],
                'user_id' => $user['user_id'],
                'first_name' => $user['first_name'],
                'last_name' => $user['last_name'],
                'last_login_time' => $row['login_time'],
                'ip_address_used' => $row['user_ip']
            ];
        }

        if ($result->getNumRows() < 1)
            return $this->getResponse(
                [
                    'message' => 'No Records found'
                ],
                ResponseInterface::HTTP_NOT_FOUND
            );

        return $this->getResponse(
            [
                'message' => "Users that logged in after $date: ",
                'users' => $rows
            ]
        );
    }

    public function purchaseByID(int $id, string $gender = null)
    {
        $this->db = db_connect();

        $sql = "SELECT * FROM `tbl_users` WHERE `user_id` IN
            (SELECT customer_id FROM `tbl_order` WHERE order_id IN
            (SELECT order_id FROM tbl_orderdetails WHERE product_id = $id))";
        $message = 'Users that bought Product ID ' . $id;

        if ($gender != null) {
            $gender = strtolower(trim($gender));
            $sql = "SELECT * FROM `tbl_users` WHERE `user_id` IN
            (SELECT customer_id FROM `tbl_order` WHERE order_id IN
            (SELECT order_id FROM tbl_orderdetails WHERE product_id = $id))
            AND `gender` = '$gender'";
            $message = 'Users that bought Product ID ' . $id . ' and are ' . $gender;
        }

        $result = $this->db->query($sql);
        $rows = [];
        foreach ($result->getResult('array') as $row) {
            unset($row['password']);
            $rows[] = $row;
        }

        if ($result->getNumRows() < 1)
            return $this->getResponse(
                [
                    'message' => 'No Records found',
                    'sql' => $sql
                ],
                ResponseInterface::HTTP_NOT_FOUND
            );

        return $this->getResponse(
            [
                'message' => $message,
                'users' => $rows
            ]
        );

    }

    public function purchase(string $category, string $parameter = null, string $gender = null)
    {
        $this->db = db_connect();
        $parameter = strtolower(trim($parameter));

        $category = $this->sanitize($category);

        $sql = "SELECT * FROM `tbl_users` WHERE `user_id` IN 
                (SELECT customer_id FROM `tbl_order` WHERE order_id IN
                (SELECT order_id FROM tbl_orderdetails WHERE product_id IN
                (SELECT product_id FROM tbl_products WHERE `subcategory_id` IN 
                (SELECT subcategory_id FROM tbl_subcategories WHERE `category` =
                (SELECT category_id FROM tbl_categories WHERE category_name = '$category')))))";
        $message = 'Users that bought products of Category: ' . $category;

        if ($parameter != null)
            if ($parameter == 'male' || $parameter == 'female') {
                $sql = "SELECT * FROM `tbl_users` WHERE `user_id` IN 
                        (SELECT customer_id FROM `tbl_order` WHERE order_id IN
                        (SELECT order_id FROM tbl_orderdetails WHERE product_id IN 
                        (SELECT product_id FROM tbl_products WHERE `subcategory_id` IN 
                        (SELECT subcategory_id FROM tbl_subcategories WHERE `category` =
                        (SELECT category_id FROM tbl_categories WHERE category_name = '$category')))))
                        AND `gender` = '$parameter'";

                $message = 'Users that bought products of Category: ' . $category . ' and are ' . $parameter;
            } else {
                $parameter = $this->sanitize($parameter);
                $gender = strtolower(trim($gender));

                $sql = "SELECT * FROM `tbl_users` WHERE `user_id` IN 
                        (SELECT customer_id FROM `tbl_order` WHERE order_id IN
                        (SELECT order_id FROM tbl_orderdetails WHERE product_id IN
                        (SELECT product_id FROM tbl_products WHERE `subcategory_id` IN 
                        (SELECT subcategory_id FROM tbl_subcategories WHERE  `subcategory_name` = '$parameter'
                            AND `category` =
                        (SELECT category_id FROM tbl_categories WHERE category_name = '$category')))))";

                $message = 'Users that bought products of Category: ' . $category . ', Subcategory: ' . $parameter;
            }

        if ($gender != null) {
            $parameter = $this->sanitize($parameter);
            $sql = "SELECT * FROM `tbl_users` WHERE `user_id` IN 
                    (SELECT customer_id FROM `tbl_order` WHERE order_id IN
                    (SELECT order_id FROM tbl_orderdetails WHERE product_id IN
                    (SELECT product_id FROM tbl_products WHERE `subcategory_id` IN 
                    (SELECT subcategory_id FROM tbl_subcategories WHERE  `subcategory_name` = '$parameter'
                        AND `category` =
                    (SELECT category_id FROM tbl_categories WHERE category_name = '$category')))))
                    AND `gender` = '$gender'";

            $message = 'Users that bought products of Category: ' . $category .  ', Subcategory: ' . $parameter.
                ' and are ' . $gender;
        }

        $result = $this->db->query($sql);
        $rows = [];
        foreach ($result->getResult('array') as $row) {
            unset($row['password']);
            $rows[] = $row;
        }

        if ($result->getNumRows() < 1)
            return $this->getResponse(
                [
                    'message' => 'No Records found',
                ],
                ResponseInterface::HTTP_NOT_FOUND
            );

        return $this->getResponse(
            [
                'message' => $message,
                'users' => $rows
            ]
        );
    }

    /**
     * Validates string for better results
     * @param string $data
     * @return string
     */
    private function sanitize(string $data) : string
    {
        return ucfirst(strtolower(trim($data)));
    }
}