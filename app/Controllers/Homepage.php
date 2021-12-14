<?php

namespace App\Controllers;

use App\Models\SubCategory;
use App\Models\Wallet;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderDetails;

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

    # MAKE ORDERS

    public function cart(int $product_id = null)
    {
        session();
        if (!in_array($product_id, $_SESSION['orders']) && $product_id != null) {
            array_push($_SESSION['orders'], $product_id);
            return $this->response->setJSON(["message" => 1, "count" => count($_SESSION['orders']), "orders" => $_SESSION['orders']]);
        }

        if (in_array($product_id, $_SESSION['orders']) && $product_id != null) {
            return $this->response->setJSON(["message" => 0]);
        }

        return $this->response->setJSON(["orders" => $_SESSION['orders'], "count" => count($_SESSION['orders'])]);
    }

    public function updateOrder()
    {
        session();
        helper(['form']);

        if ($this->request->getMethod() == 'post') {

            if ($this->request->getVar('complete-order') != null) {
                $order = new Order();
                $order_detail = new OrderDetails();
                $product = new Product();

                $count = count($_SESSION['orders']);

                $details = [
                    "customer_id" => $_SESSION['id'],
                    'payment_type' => 1,
                ];

                $total_cost = 0;

                $order_id = $order->newOrder($details);

                for ($i = 1; $i <= $count; $i++) {
                    $product_id = $_SESSION['orders'][$i - 1];
                    $price = $product->getPrice($_SESSION['orders'][$i - 1]);
                    $quantity = $_POST['order' . $i];
                    $cost = $price * $quantity;

                    $total_cost += $cost;

                    $new_order = [
                        'order_id' => $order_id,
                        'product_id' => $product_id,
                        'product_price' => $price,
                        'order_quantity' => $quantity,
                        'orderdetails_total' => $cost
                    ];

                    $order_detail->newOrderDetail($new_order);
                }

                $order->updateTotal($order_id, $total_cost);
                $_SESSION['orders'] = [];

                $data['complete'] = 1;
                echo view('frontend/homepage', $data);
            }

            if ($this->request->getVar('delete-order') != null) {

                $x = intval($this->request->getVar('delete-order'));

                array_splice($_SESSION['orders'], $x, 1);
                $data['delete'] = 1;
                echo view('frontend/homepage', $data);
            }
        }
    }

    public function orderHistory()
    {
        session();
        $order = new Order();

        $history = $order->history($_SESSION['id']);

        return $this->response->setJSON($history);
    }

    public function payOrder(int $order_id, int $total)
    {
        session();

        $wallet = new Wallet();
        $amount = $wallet->getAmount($_SESSION['id']);

        if ($amount < $total)
            return $this->response->setJSON(['message' => 1]);

        $wallet->updateWallet($_SESSION['id'], -$total);
        $order = new Order();
        $order->updateOrder($order_id, 'paid');

        return $this->response->setJSON(['message' => 2]);
    }
}
