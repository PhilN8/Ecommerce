<?php

namespace App\Controllers;

use App\Models\Product;
use CodeIgniter\HTTP\ResponseInterface;

class Products extends BaseController
{
    /**
     * Get all products
     * @return ResponseInterface
     */
    public function index(): ResponseInterface
    {
        $model = new Product();
        return $this->getResponse(
            [
                'message' => 'Products retrieved successfully',
                'products' => $model->findAll()
            ]
        );
    }

    /**
     * Searches for a product via id
     * @param $id
     * @return ResponseInterface
     */
    public function show($id): ResponseInterface
    {
        $model = new Product();
        $product = $model->find($id);

        if (!$product)
            return $this->getResponse(
                ['message' => 'Product does not exist for specified ID'],
                ResponseInterface::HTTP_BAD_REQUEST
            );

        return $this->getResponse(
            [
                'message' => 'Product ID ' . $id . ' retrieved successfully',
                'product' => $product
            ]
        );
    }

    /**
     * Displays products by category and/or subcategory
     * @param string|null $category - required
     * @param string|null $subcategory - optional
     *
     * @return ResponseInterface
     */
    public function search(string $category = null, string $subcategory = null): ResponseInterface
    {
        $db = db_connect();

        if ($category === null)
            return $this->getResponse(
                ['message' => 'No Category chosen'],
                ResponseInterface::HTTP_BAD_REQUEST);

        $category = $this->sanitize($category);

        if ($subcategory == null) {
            $result = $db->query(
                "SELECT * FROM `tbl_products` WHERE `subcategory_id` IN 
                    (SELECT subcategory_id FROM tbl_subcategories where `category` = 
                    (SELECT category_id FROM tbl_categories WHERE category_name = '$category'))");

            $message = 'Products of Category: ' . $category . ' retrieved successfully';
        } else {
            $subcategory = $this->sanitize($subcategory);

            $result = $db->query(
                "SELECT * FROM `tbl_products` WHERE `subcategory_id` IN 
                    (SELECT subcategory_id FROM tbl_subcategories where `subcategory_name` = '$subcategory'
                    AND category = (SELECT category_id FROM tbl_categories WHERE category_name = '$category'))");

            $message = 'Products of Category: ' . $category . ', Subcategory: ' . $subcategory . ' retrieved successfully';
        }

        $rows = [];
        foreach ($result->getResult('array') as $row)
            $rows[] = $row;

        if ($result->getNumRows() < 1)
            return $this->getResponse(
                ['message' => 'No Records found'],
                ResponseInterface::HTTP_NOT_FOUND);

        return $this->getResponse(
            [
                'message' => $message,
                'products' => $rows
            ]
        );
    }

    /**
     * Searches for all products with the pattern/search term provided
     * @param string $pattern - required
     * @return ResponseInterface
     */
    public function deepSearch(string $pattern): ResponseInterface
    {
        $pattern = trim($pattern);
        $input = ['pattern' => $pattern];
        $rules = [
            'pattern' => [
                'rules' => 'required',
                'label' => 'Search Pattern',
                'errors' => [
                    'required' => 'The search pattern is required'
                ]
            ]
        ];
        $db = db_connect();

        if (!$this->validateRequest($input, $rules))
            return $this->getResponse(
                $this->validator->getErrors(),
                ResponseInterface::HTTP_BAD_REQUEST
            );

        $result = $db->query("SELECT * FROM `tbl_products` WHERE `product_name` LIKE '%$pattern%'");

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
                'message' => "Products with the pattern {" . $pattern . "} : ",
                'products' => $rows
            ]
        );
    }

    /**
     * Returns all products that have a sales volume greater than or equals to the value provided
     * @param $value - amount to filter
     * @return ResponseInterface
     */
    public function sales($value) : ResponseInterface
    {
        $input = ['value' => $value];
        $rules = [
            'value' => [
                'rules' => 'required|decimal|greater_than_equal_to[500]',
                'label' => 'Value',
                'errors' => [
                    'required' => 'Value is required for search',
                    'decimal' => 'Only numbers are allowed',
                    'greater_than_equal_to[500]' => 'Value must be greater than, or equals to 500'
                ]
            ]
        ];

        $this->db = db_connect();

        if (!$this->validateRequest($input, $rules))
            return $this->getResponse(
                $this->validator->getErrors(),
                ResponseInterface::HTTP_BAD_REQUEST
            );

        $sql = "SELECT `product_id`, SUM(`orderdetails_total`) as sum FROM `tbl_orderdetails` GROUP BY `product_id`";

        $result = $this->db->query($sql);
        $rows = [];
        foreach ($result->getResult('array') as $row)
            if($row['sum'] > $value) {
                $model = new Product();
                $product = $model->find($row['product_id']);

                $rows[] = [
                    'product_id' => $row['product_id'],
                    'product_name' => $product['product_name'],
                    'product_description' => $product['product_description'],
                    'unit_price' => $product['unit_price'],
                    'sales_total' => $row['sum']
                ];
            }

        return $this->getResponse(
            [
                'message' => "Products with a sales volume >= ". $value. ": ",
                'products' => $rows
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