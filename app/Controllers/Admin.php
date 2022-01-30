<?php

namespace App\Controllers;

ini_set('display_errors', '1');

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\SubCategory;
use App\Models\ProductImage;
use App\Models\PaymentType;
use App\Models\Order;
use App\Models\API_User;

class Admin extends BaseController
{

    public function index()
    {
        session();
        $notThere['num'] = 1;
        if (isset($_SESSION['name']))
            echo view('/frontend/admin', $_SESSION);
        else
            echo view('frontend/login', $notThere);
    }

    # USERS

    public function viewUsers(int $role = 3)
    {
        $users = new User();

        $allUsers = $users->getUsers($role);

        return $this->response->setJSON($allUsers);
    }

    public function dynamicSearch(string $searchTerm)
    {
        $users = new User();
        $namesarray = $users->getUsers();
        $existingname = [];

        // print_r($namesarray);

        foreach ($namesarray as $name) {
            //Convert the name to be lowercase
            $Fname = strtolower($name['first_name']);

            //Get the length of the search term. You need to check a name e.g 
            //if I search Ti, then i want names starting from T then followed by i

            $searchLength = strlen($searchTerm);

            //Get substing of size n e.g. if To is the search term, then get all first letters. Eg in names array, Ti, Ti, Ma, and Ju, 
            $substringname = substr($Fname, 0, $searchLength);


            if (stristr($searchTerm, $substringname)) {
                //Create the Search hints
                if (count($existingname) == 0) {
                    array_push($existingname, $name);
                } else {
                    // $existingname .= ", ";
                    // $existingname .= ucfirst($Fname);
                    array_push($existingname, $name);
                }
            }
        }

        $string = ['message' => $existingname];

        return $this->response->setJSON($string);
    }

    public function editUser(string $option, int $id, string $val)
    {

        $user = new User();

        $new_val = [
            $option  => $val
        ];

        if ($option == 'email') {
            if (!filter_var($val, FILTER_VALIDATE_EMAIL)) {
                $string = ['message' => 3];
                return $this->response->setJSON($string);
            }

            $check = $user->where('email', $val)->first();
            if ($check) {
                $string = ['message' => 4];
                return $this->response->setJSON($string);
            }
        }



        $isDone = $user->editUser($new_val, $id);

        if ($isDone)
            $string = ['message' => 1];
        else
            $string = ['message' => 2];

        return $this->response->setJSON($string);
    }

    # CATEGORIES

    public function newCategory(string $name)
    {

        $category = new Category();

        $check = $category->checkCategory($name);

        if ($check == true) {

            if ($category->insert(["category_name" => $name]) == true) {

                $string = ['message' => 3];
            } else

                $string = ['message' => 2];
        } else {

            $string = ['message' => 1];
        }


        return $this->response->setJSON($string);
    }

    public function getCategories()
    {
        $category = new Category();

        $categories = $category->getCategories();

        return $this->response->setJSON($categories);
    }

    # SUB-CATEGORIES

    public function newSub(string $name, int $id)
    {
        $sub = new SubCategory();

        $check = $sub->checkSub($name, $id);

        if ($check) {
            if ($sub->insert(['subcategory_name' => $name, 'category' => $id]) == true) {

                $string = ['message' => 3];
            } else

                $string = ['message' => 2];
        } else {

            $string = ['message' => 1];
        }

        return $this->response->setJSON($string);
    }

    public function getSubs($cat)
    {
        $subcategory = new SubCategory();

        $subs = $subcategory->getSubs($cat);

        return $this->response->setJSON($subs);
    }

    # PRODUCTS

    public function newProduct(string $name = null, string $desc = null, int $sub_id = null, $price = null)
    {
        session();
        helper(['form']);

        if ($this->request->getMethod() == 'post') {

            $file = $this->request->getFile('productimage');

            $rules = [
                'categories' => [
                    'rules' => 'required',
                    'label' => 'Category'
                ],
                'subcategories' => [
                    'rules' => 'required',
                    'label' => 'Subcategory'
                ],
                'productname' => [
                    'rules' => 'required',
                    'label' => 'Product Name'
                ],
                'productimage' => [
                    'rules' => 'uploaded[productimage]|is_image[productimage]',
                    'label' => 'Product Image',
                    'errors' => [
                        'uploaded[productimage]' => 'Select an image for the product',
                        'is_image[productimage]' =>  'File uploaded is not a valid image'
                    ]
                ],
                'unitprice' => [
                    'rules' => 'required',
                    'label' => 'Price'
                ]
            ];

            if ($this->validate($rules)) {

                if ($file->isValid() && !$file->hasMoved()) {
                    $file->move('./images/database', $file->getRandomName());
                }
            } else {
                $data['validation'] = $this->validator;
                echo view('/frontend/admin', $data);
                exit();
            }

            $product = new Product();
            $image = new ProductImage();

            $name = $this->request->getVar('productname');
            $sub_id = $this->request->getVar('subcategories');
            $desc = $this->request->getVar('productdesc');
            $string_price = $this->request->getVar('unitprice');

            $price = intval($string_price);
            $details = [
                'product_name' => $name,
                'product_description' => $desc,
                'product_image' => $file->getName(),
                'unit_price' => $price,
                'subcategory_id' => $sub_id,
                'added_by' => $_SESSION['id']
            ];

            $check = $product->checkProduct($name, $sub_id);

            if ($check) {
                if ($product->insert($details) == true) {

                    $image_details = [
                        'product_image' => $file->getName(),
                        'product_id' => $product->getInsertID(),
                        'added_by' => $_SESSION['id']
                    ];

                    $image->newImage($image_details);
                    $data['string'] = 3;

                    $string = ['message' => 3];
                } else
                    $data['string'] = 2;
                $string = ['message' => 2];
            } else {
                $data['string'] = 1;
                $string = ['message' => 1];
            }

            echo view('frontend/admin', $data);
        }
    }

    # PAYMENT TYPES

    public function newPayment(string $payment, string $desc = null)
    {
        $payType = new PaymentType();

        $newType = [
            'paymenttype_name' => $payment,
            'description' => $desc
        ];

        $result = $payType->newPayment($newType);

        if ($result == true)
            $string = ['message' => 1];
        else
            $string = ['message' => 0];

        return $this->response->setJSON($string);
    }

    # ORDERS

    public function orders() {
        $order =  new Order();

        $all_orders = $order->history();

        return $this->response->setJSON($all_orders);
    }

    public function updateOrder(int $order_id) {
        $order = new Order();

        $order->updateOrder($order_id, 'pending payment');

        return $this->response->setJSON(["message" => "order updated"]);
    }

    /**
     * @throws \ReflectionException
     */
    public function updateCategory(int $categoryID, string $newName)
    {
        $category = new Category();

        $category
            ->whereIn('category_id', [$categoryID])
            ->set(['category_name' => $newName])
            ->update();
    }
}
