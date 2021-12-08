<?php

namespace App\Models;

use CodeIgniter\Model;

class Order extends Model
{

    protected $table = 'tbl_order';
    protected $primaryKey = 'order_id';

    protected $allowedFields = [
        'customer_id',
        'order_amount',
        'payment_type'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'is_deleted';

    public function newOrder(array $order): int
    {
        $this->insert($order);

        return $this->getInsertID();
    }

    public function updateTotal(int $id, int $total)
    {
        $this->update($id, ['order_amount' => $total]);
    }

    public function history(int $id)
    {
        return $this->select('order_id, order_amount, order_status, updated_at')->where('customer_id', $id)->findAll();
    }
}
