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
        'payment_type',
        'order_status'
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

    public function history(int $id = null)
    {
        if ($id != null)
            return $this->select('order_id, order_amount, order_status, created_at')->where('customer_id', $id)->findAll();

        return $this->select('order_id, order_amount, order_status, created_at')->findAll();
    }

    public function updateOrder(int $id, string $update)
    {
        $this->update($id, ['order_status' => $update]);
    }

    public function receipt(int $order_id)
    {
        return $this->find($order_id);
    }
}
