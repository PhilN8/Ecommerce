<?php

namespace App\Models;

use CodeIgniter\Model;


class CategoryModel extends Model
{

    protected $table = 'tbl_categories';

    protected $allowedFields = [
        'category_name'
    ];

    public function checkCategory(string $category)
    {
        if ($this->getWhere(['category_name' => $category])->getNumRows() > 0)
            return false;
        else
            return true;
    }

    public function getCategories()
    {
        // $this === \Config\Database::connect()->table('tbl_categories')
        $result = $this->select('category_id, category_name')->get()->getResultArray();
        $categories = [];
        foreach ($result as $row) {
            array_push($categories, $row);
        }

        return json_encode($categories);
    }
}
