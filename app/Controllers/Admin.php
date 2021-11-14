<?php

namespace App\Controllers;

ini_set('display_errors', '1');

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\SubCategory;

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

    public function viewUsers(int $role = null)
    {
        $users = new User();

        $allUsers = $users->getUsers();

        if ($role !== false)
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

    public function newProduct(string $name, string $desc, int $sub_id, $price)
    {
        session();
        $prod = new Product();
        $date = date("Y/m/d H:i:s", 1);

        $details = [
            'product_name' => $name,
            'product_description' => $desc,
            'unit_price' => doubleval($price),
            'subcategory_id' => $sub_id,
            // 'created_at' => $date,
            'added_by' => $_SESSION['id']
        ];

        $check = $prod->checkProduct($name, $sub_id);

        if ($check) {
            if ($prod->insert($details) == true) {

                $string = ['message' => 3];
            } else

                $string = ['message' => 2];
        } else {

            $string = ['message' => 1];
        }

        return $this->response->setJSON($string);
    }
}
