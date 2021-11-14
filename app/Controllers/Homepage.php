<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\Wallet;

class Homepage extends BaseController
{
    public function index()
    {
        session();
        $notThere['num'] = 1;

        if (isset($_SESSION['name']))
            echo view('/frontend/homepage', $_SESSION);
        else
            echo view('frontend/login', $notThere);
    }

    public function wallet(int $id, int $money)
    {
        $wallet = new Wallet();

        $wallet->updateWallet($id, $money);
    }

    public function getWallet(int $id)
    {
        session();
        $wallet = new Wallet();

        $amount = $wallet->getAmount($id);
        $_SESSION['wallet'] = $amount;
    }

    public function editInfo()
    {
    }
}
