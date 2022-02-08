<?php

namespace App\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\SubCategory;

class Home extends BaseController
{
    public function index()
    {
        return view('frontend/index');
    }

    public function shop()
    {
        $db = db_connect();
        $result = $db->query("SELECT * FROM `tbl_products` WHERE subcategory_id IN 
                            ( SELECT subcategory_id FROM tbl_subcategories WHERE category = 
                            ( SELECT category_id FROM tbl_categories WHERE category_name = 'Pets'))");

        $pets = [];
        foreach ($result->getResult('array') as $row)
            $pets[] = $row;

        $data['pets'] = $pets;
        $data['products'] = (new Product())->findAll(8);

        return view('frontend/shop', $data);
    }
}
