<?php

namespace App\Controllers;

use App\Models\Order;
use App\Models\OrderDetails;
use CodeIgniter\HTTP\ResponseInterface;

class Transactions extends BaseController
{

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
            return $this->getResponse(['message' => 'No Records found']);

        return $this->getResponse(
            [
                'message' => $message,
                'products' => $rows
            ]
        );
    }

    private function sanitize($data)
    {
        $data = ucfirst(strtolower(trim($data)));
        return $data;
    }
}