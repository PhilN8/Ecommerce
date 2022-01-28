<?php

namespace App\Controllers;

use App\Models\Product;
use CodeIgniter\HTTP\ResponseInterface;

class Products extends BaseController
{
    public function index() {
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
     * @return ResponseInterface
     */
    public function show($id)
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
     */

    public function search($category = null, string $subcategory = null) {
        $db = db_connect();

        if ($category === null)
            return $this->getResponse(
                ['message' => 'No Category chosen'],
                ResponseInterface::HTTP_BAD_REQUEST);

        $category = $this->sanitize($category);
        $subcategory = $this->sanitize($subcategory);

        if ($subcategory == null)
            $result = $db->query("SELECT * FROM `tbl_products` WHERE `subcategory_id` IN 
                                   (SELECT subcategory_id FROM tbl_subcategories where `category` = 
                                    (SELECT category_id FROM tbl_categories WHERE category_name = '$category'))");
        else
            $result = $db->query("SELECT * FROM `tbl_products` WHERE `subcategory_id` IN 
                                   (SELECT subcategory_id FROM tbl_subcategories where `subcategory_name` = '$subcategory'
                                     AND category = 
                                         (SELECT category_id FROM tbl_categories WHERE category_name = '$category'))");

        $rows = [];
        foreach ($result->getResult('array') as $row)
            $rows[] = $row;

        return $this->getResponse(
            [
                'message' => 'Products of category ' . $category . ' retrieved successfully',
                'products' => $rows
            ]
        );
//            array_push($rows, $row);
//            print_r($row);

//        return $this->response->setJSON($rows);
    }

    private function sanitize($data)
    {
        $data = trim($data);
        $data = strtolower($data);
        $data = ucfirst($data);
        return $data;
    }

    public function bySubcategory($subcategory = null)
    {
        $db = db_connect();

        if ($subcategory === null)
            return $this->getResponse(
                ['message' => 'No Category chosen'],
                ResponseInterface::HTTP_BAD_REQUEST);

        $subcategory = $this->sanitize($subcategory);
        $result = $db->query("SELECT * FROM `tbl_products` WHERE `subcategory_id` IN 
                                   (SELECT subcategory_id FROM tbl_subcategories where `subcategory_name` = '$subcategory'
                                    )");

        $rows = [];
        foreach ($result->getResult('array') as $row)
            $rows[] = $row;

        return $this->getResponse(
            [
                'message' => 'Products of category ' . $subcategory . ' retrieved successfully',
                'products' => $rows
            ]
        );
    }
}