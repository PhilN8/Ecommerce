<?php

namespace App\Controllers;

// namespace App\Libraries;

use App\Models\SubCategory;
use App\Models\Wallet;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\PaymentType;

use PDF;

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
                $pay = intval($this->request->getVar('pay-type'));

                $rules = [
                    'pay-type' => [
                        'rules' => 'required|integer',
                        'label' => 'Payment Type',
                        'errors' => [
                            'required' => 'The payment type is missing. Choose a method of payment',
                            'integer' => 'Invalid payment type chosen'
                        ]
                    ]
                ];

                if ($this->validate($rules)) {

                    $details = [
                        "customer_id" => $_SESSION['id'],
                        'payment_type' => $pay,
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
                } else {
                    $data['incomplete'] = 1;
                    $data['validation'] = $this->validator;
                    echo view('frontend/homepage', $data);
                }
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

    public function getPaymentTypes()
    {
        $payment = new PaymentType();
        return $this->response->setJSON($payment->paymentTypes());
    }

    public function receipt(int $order_id)
    {
        session();
        $this->response->setHeader('Content-Type', 'application/pdf');

        $order = new Order();
        $receipt = $order->receipt($order_id);

        $orderDetails = new OrderDetails();
        $details = $orderDetails->receipt($order_id);

        // return $this->response->setJSON($details);

        $pdf = new PDF();
        $pdf->AddPage();
        $pdf->AliasNbPages();
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(40, 10, 'User ID: ' . $_SESSION['id']);
        $pdf->Cell(40, 10, 'Name: ' . $_SESSION['name']);
        // $pdf->Cell(40, 10, 'ID: ' . $receipt['order_id']);
        // $pdf->Cell(40, 10, 'ID: ' . $receipt['order_id']);
        $pdf->Ln();
        $pdf->Ln();
        // $pdf->Cell(40, 10, 'Order');

        $pdf->Cell(40, 10, 'Order ID: ' . $receipt['order_id']);
        $pdf->Ln();
        $pdf->Cell(40, 10, 'Amount: ' . $receipt['order_amount']);
        $pdf->Ln();
        $pdf->Cell(40, 10, 'Date: ' . substr($receipt['created_at'], 0, 10));
        $pdf->Ln();
        $pdf->Ln();
        $pdf->Cell(40, 10, 'ORDER DETAILS');
        $pdf->Ln();

        $headings = ['Product', 'Unit Price', 'Quantity', 'Total Per Item'];

        foreach ($headings as $header => $value)
            $pdf->Cell(40, 10, $value, 1);
        //  Order details
        $pdf->SetFont('Arial', '', 12);

        foreach ($details as $key => $value) {
            $pdf->Ln();

            foreach ($value as $column) {
                // print_r($column);

                $pdf->Cell(40, 12, $column, 1);
            }
        }

        $filename = substr($receipt['created_at'], 0, 10) . "_" . $order_id;

        $pdf->Output('', $filename);
    }
}
