<?php

namespace App\Models;

use CodeIgniter\Model;

class Wallet extends Model
{

    protected $table = 'tbl_wallet';

    protected $allowedFields = [
        'customer_id',
        'amount_available',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'is_deleted';

    public function newWallet(int $id, int $money = null)
    {
        $new_wallet = [
            'customer_id' => $id,
            'amount_available' => $money ?? 0,
        ];

        $this->insert($new_wallet);
    }

    public function getAmount(int $id)
    {
        return $this->select('amount_available')
            ->where('customer_id', $id)
            ->first()['amount_available'];
    }

    public function updateWallet(int $id, int $money)
    {

        $amount = $this->select('amount_available')->where('customer_id', $id)->first()['amount_available'];

        if ($amount != null) {
            $this->whereIn('customer_id', [$id])
                ->set('amount_available', $money + $amount)
                ->update();
            echo "DONE!" . $money + $amount;
        } else {
            $this->newWallet($id, $money);
            echo "NEW Wallet!";
        }
    }
}
