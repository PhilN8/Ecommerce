<?php

namespace App\Models;

use CodeIgniter\Model;

class SubCategoryModel extends Model
{
    protected $table = 'tbl_subcategories';

    protected $allowedFields = [
        'subcategory_name',
        'category'
    ];

    public function checkSub(string $sub, int $cat_id)
    {
        if (
            $this->getWhere([
                'subcategory_name' => $sub,
                'category' => $cat_id
            ])
            ->getNumRows() > 0
        )
            return false;
        else
            return true;
    }

    public function getSubs($category = null)
    {
        // $this === \Config\Database::connect()->table('tbl_categories')
        $result = $this->select('subcategory_id, subcategory_name')
            ->where(['category' => $category])
            ->get()->getResultArray();
        $subs = [];
        foreach ($result as $row) {
            array_push($subs, $row);
        }

        return json_encode($subs);
    }
}
