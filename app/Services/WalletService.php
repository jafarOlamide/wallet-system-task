<?php 

namespace App\Services;

use App\Models\Wallet;
use Illuminate\Validation\Rules\In;

class WalletService 
{

    public static function validateTransactionAmount(Wallet $wallet, int $amount)
    {
        if ($wallet->walletType->minimum_balance < $wallet->balance - $amount) {
            // return false;
            return response(['res'=> false, 'message'=> 'Insufficient funds'], 400);
        }

    }


    public static function debitAccount(Wallet $wallet, int $amount){
        if (static::validateTransactionAmount($wallet, $amount)) {
            $wallet->balance = $wallet->balance - $amount;
            $wallet->save();
        }
    }

    public static function creditAccount(Wallet $wallet, int $amount)
    {
        $wallet->balance += $amount;
        $wallet->save();
    }
}