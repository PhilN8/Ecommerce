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
        'product_image',
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

    public function checkProduct(string $product, int $sub_id): bool
    {
        if ($this->getWhere([
            'product_name' => $product,
            'subcategory_id' => $sub_id
        ])->getNumRows() > 0)
            return false;
        else
            return true;
    }

    public function updateProduct()
    {
        # code...
    }

    public function getProducts(int $sub_id)
    {
        $result = $this->select('product_id, product_name, unit_price')
            ->where(['subcategory_id' => $sub_id])
            ->get()->getResultArray();
        $products = [];
        foreach ($result as $row) {
            array_push($products, $row);
        }

        return json_encode($products);
    }
}
