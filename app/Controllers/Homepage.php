<?php

namespace App\Controllers;

use App\Models\SubCategory;
use App\Models\Wallet;
use App\Models\Category;
use App\Models\Product;

class Homepage extends BaseController
{
    public function index()
    {
        session();
        $notThere['num'] = 1;

        if (isset($_SESSION['name']))
            echo view('/frontend/homepage', $_SESSION);
        else
            echo view('frontend/login', $notThere);
    }

    # WALLET

    public function wallet(int $id, int $money)
    {
        $wallet = new Wallet();

        $wallet->updateWallet($id, $money);
    }

    public function getWallet(int $id)
    {
        session();
        $wallet = new Wallet();

        $amount = $wallet->getAmount($id);

        if ($amount != null) {
            $_SESSION['wallet'] = $amount;
            return $this->response->setJSON(['amount' =>  $amount]);
        }

        return null;
    }

    # CATEGORIES

    public function getCategories()
    {
        $category = new Category();

        $categories = $category->getCategories();

        return $this->response->setJSON($categories);
    }

    # SUB-CATEGORIES

    public function getSubs($cat)
    {
        $subcategory = new SubCategory();

        $subs = $subcategory->getSubs($cat);

        return $this->response->setJSON($subs);
    }

    # PRODUCTS

    public function getProducts(int $sub_id)
    {
        $product = new Product();

        $prod = $product->getProducts($sub_id);

        return $this->response->setJSON($prod);
    }

    public function editInfo()
    {
    }
}
