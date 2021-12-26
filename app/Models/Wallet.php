<?php

namespace App\Models;

use CodeIgniter\Model;

class Wallet extends Model
{

    protected $table = 'tbl_wallet';
    protected $primaryKey = 'wallet_id';

    protected $allowedFields = [
        'customer_id',
        'amount_available',
        'created_at',
        'updated_at',
        'is_deleted'
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

    public function getAmount(int $id): mixed
    {
        $amount = $this->select('amount_available')->where('customer_id', $id)->first()['amount_available'] ?? null;

        if ($amount !== null)
            return $amount;
        else
            return null;
    }

    public function updateWallet(int $id, int $money)
    {

        $amount = $this->select('amount_available')->where('customer_id', $id)->first()['amount_available'] ?? null;

        if ($amount !== null)
            $this->whereIn('customer_id', [$id])
                ->set('amount_available', $money + $amount)
                ->update();
        else
            $this->newWallet($id, $money);
    }
}
