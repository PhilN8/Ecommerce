<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\CategoryModel;
use App\Models\SubCategoryModel;

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
        $users = new UserModel();

        $allUsers = $users->getUsers();

        if ($role !== false)
            $allUsers = $users->getUsers($role);


        return $this->response->setJSON($allUsers);
    }

    # CATEGORIES

    public function newCategory(string $name)
    {

        $category = new CategoryModel();

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
        $category = new CategoryModel();

        $categories = $category->getCategories();

        return $this->response->setJSON($categories);
    }

    # SUB-CATEGORIES

    public function newSub(string $name, int $id)
    {
        $sub = new SubCategoryModel();

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
        $subcategory = new SubCategoryModel();

        $subs = $subcategory->getSubs($cat);

        return $this->response->setJSON($subs);
    }
}
