<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderDetails extends Model
{

    protected $table = 'tbl_orderdetails';
    protected $primaryKey = 'orderdetails_id';

    protected $allowedFields = [
        'order_id',
        'product_id',
        'product_price',
        'order_quantity',
        'orderdetails_total'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'is_deleted';

    public function newOrderDetail(array $order_detail)
    {
        $this->insert($order_detail);

        // return $this->getInsertID();
    }

    public function receipt(int $order_id)
    {
        return $this->select('tbl_products.product_name, product_price, order_quantity, orderdetails_total')->join('tbl_products', 'tbl_products.product_id = tbl_orderdetails.product_id')->where('order_id', $order_id)->findAll();
    }
}
