<?php

namespace App\Models;

use CodeIgniter\Model;

class PaymentType extends Model
{

    protected $table = 'tbl_paymenttypes';
    protected $primaryKey = 'paymenttype_id';

    protected $allowedFields = [
        'paymenttype_name',
        'description'
    ];

    protected $deletedField = 'is_deleted';

    public function newPayment(array $payment): bool
    {
        if ($this->search($payment['paymenttype_name']) == false)
            return false;

        $this->insert($payment);
        return true;
    }

    public function search(string $name): bool
    {
        if ($this->where('paymenttype_name', $name)->first() == [])
            return true;

        return false;
    }
}
