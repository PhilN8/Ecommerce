<?php

namespace App\Controllers;

use App\Models\Order;
use App\Models\OrderDetails;
use CodeIgniter\HTTP\ResponseInterface;
use Exception;

class Transactions extends BaseController
{

    private $db = null;

    /**
     * Returns all orders
     * @return ResponseInterface
     */
    public function index(): ResponseInterface
    {
        $model = new Order();
        return $this->getResponse(
            [
                'message' => 'All Orders retrieved successfully',
                'users'   => $model->findAll(),
            ]
        );
    }

    /**
     * Searches an order by id, and show its order details
     * @param $id
     * @return ResponseInterface
     */
    public function show($id): ResponseInterface
    {
        $model = new Order();
        $order = $model->find($id);

        $details = (new OrderDetails())->where('order_id', $id)->get()->getResultArray();

        if (!$order)
            return $this->getResponse(
                ['message' => 'Order does not exist for specified ID'],
                ResponseInterface::HTTP_BAD_REQUEST
            );

        return $this->getResponse(
            [
                'message' => 'Order ID ' . $id . ' retrieved successfully',
                'order' => $order,
                'order_details' => $details
            ]
        );
    }

    /**
     * Filters all orders by Category and/or SubCategory
     * @param string|null $category - required
     * @param string|null $subcategory - optional
     *
     * @return ResponseInterface
     */
    public function search(string $category = null, string $subcategory = null): ResponseInterface
    {
        $db = db_connect();

        if ($category == null)
            return $this->getResponse(
                ['message' => 'No Category chosen'],
                ResponseInterface::HTTP_BAD_REQUEST);

        $category = $this->sanitize($category);

        if ($subcategory == null) {
            $result = $db->query("
                SELECT * FROM tbl_orderdetails WHERE product_id IN 
                (SELECT `product_id` FROM `tbl_products` WHERE `subcategory_id` IN 
                (SELECT subcategory_id FROM tbl_subcategories where `category` = 
                (SELECT category_id FROM tbl_categories WHERE category_name = '$category')))");

            $message = 'Orders of category ' . $category . ' retrieved successfully';
        } else {
            $subcategory = $this->sanitize($subcategory);

            $result = $db->query("
                SELECT * FROM tbl_orderdetails WHERE product_id IN 
                (SELECT `product_id` FROM `tbl_products` WHERE `subcategory_id` IN 
                (SELECT subcategory_id FROM tbl_subcategories where `subcategory_name` = '$subcategory'
                AND category = 
                (SELECT category_id FROM tbl_categories WHERE category_name = '$category')))");

            $message = 'Orders of Category: ' . $category . ', Subcategory: ' . $subcategory . ' retrieved successfully';
        }

        $rows = [];
        foreach ($result->getResult('array') as $row)
            $rows[] = $row;

        if ($result->getNumRows() < 1)
            return $this->getResponse(
                ['message' => 'No Records found'],
                ResponseInterface::HTTP_NOT_FOUND
            );

        return $this->getResponse(
            [
                'message' => $message,
                'products' => $rows
            ]
        );
    }

    /**
     * Returns all transactions between two dates, $startDate and $endDate
     * @param $startDate - required
     * @param $endDate - optional, is equated to today if excluded
     * @return ResponseInterface
     * @throws Exception
     */
    public function dates($startDate, $endDate = null): ResponseInterface
    {
        $startDate = $this->sanitize($startDate);

        $db = db_connect();
        $input = ['startDate' => $startDate];
        $rules = [
            'startDate' => [
                'rules' => 'required|check_date|valid_date[Y-m-d]',
                'label' => 'Start Date',
                'errors' => [
                    'check_date' => 'You cannot add a Start Date after today',
                    'valid_date' => 'Start Date must of the format yyyy-mm-dd e.g. ' . date('Y-m-d')
                ]
            ],

        ];

        if (strlen($endDate) > 0) {
            $endDate = $this->sanitize($endDate);
            $input = [
                'startDate' => $startDate,
                'endDate' => $endDate
            ];
            $rules = [
                'startDate' => [
                    'rules' => 'required|check_date|valid_date[Y-m-d]',
                    'label' => 'Start Date',
                    'errors' => [
                        'check_date' => 'You cannot add a Start Date after today',
                        'valid_date' => 'Start Date must of the format yyyy-mm-dd e.g. ' . date('Y-m-d')
                    ]
                ],
                'endDate' => [
                    'rules' => 'valid_date[Y-m-d]',
                    'label' => 'End Date',
                    'errors' => [
                        'valid_date' => 'End Date must of the format yyyy-mm-dd e.g. ' . date('Y-m-d')
                    ]
                ]
            ];
        } else
            $endDate = (new \DateTime())->format('Y-m-d H:i:s');

        if (!$this->validateRequest($input, $rules))
            return $this->getResponse(
                $this->validator->getErrors(),
                ResponseInterface::HTTP_BAD_REQUEST
            );


        $startDate = (new \DateTime($startDate))->format('Y-m-d H:i:s');

        $result = $db->query("SELECT * FROM `tbl_order` WHERE `created_at` 
                BETWEEN '$startDate' AND '$endDate'");

        if ($result->getNumRows() < 1)
            return $this->getResponse(
                ['message' => 'No Records found'],
                ResponseInterface::HTTP_NOT_FOUND
            );

        $rows = [];
        foreach ($result->getResult('array') as $row)
            $rows[] = $row;

        return $this->getResponse([
            'message' => 'Records Found',
            'orders' => $rows
        ]);

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