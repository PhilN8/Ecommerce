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
            return $this->getResponse(['message' => 'No Records found']);

        return $this->getResponse(
            [
                'message' => $message,
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