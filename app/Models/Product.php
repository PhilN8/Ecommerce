<?php

namespace App\Models;

use CodeIgniter\Model;
use ReflectionException;

class Product extends Model
{
    protected $table = 'tbl_products';
    protected $primaryKey = 'product_id';

    protected $allowedFields = [
        'product_name',
        'product_description',
        'product_image',
        'unit_price',
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
        $result = $this->select('product_id, product_name, unit_price, product_image')
            ->where(['subcategory_id' => $sub_id])
            ->get()->getResultArray();
        $products = [];
        foreach ($result as $row) {
            array_push($products, $row);
        }

        return json_encode($products);
    }

    public function getPrice(int $id)
    {

        return $this->select('unit_price')->where(['product_id' => $id])->first()['unit_price'];
    }

    /**
     * Checks available quantity of products
     * @param int $product_id
     * @param int $request
     * @return bool
     */
    public function checkQuantity(int $product_id, int $request): bool
    {
        $actual = $this->select('available_quantity')
            ->where(['product_id' => $product_id])
            ->first()['available_quantity'];

        if ($actual < $request)
            return false;

        return true;
    }

    /**
     * Updates available quantity of product
     * @param int $product_id
     * @param int $deduction
     * @throws ReflectionException
     */
    public function updateQuantity(int $product_id, int $deduction) {
        $current = $this->select('available_quantity')
            ->where(['product_id' => $product_id])
            ->first()['available_quantity'];

        $new = $current + $deduction;

        $this->update($product_id, ['available_quantity' => $new]);
    }
}
