<?php

namespace App\Models;

use CodeIgniter\Model;

class Product extends Model
{
    protected $table = 'tbl_products';
    protected $primaryKey = 'product_id';

    protected $allowedFields = [
        'product_name',
        'product_description',
        'unit-price',
        'available_quantity',
        'subcategory_id',
        'created_at',
        'added_by'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'is_deleted';

    public function checkProduct(string $product, int $sub_id)
    {
        if ($this->getWhere([
            'product_name' => $product,
            'subcategory_id' => $sub_id
        ])->getNumRows() > 0)
            return false;
        else
            return true;
    }
}
