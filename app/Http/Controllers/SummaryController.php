<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;

class SummaryController extends Controller
{
    public function summary()
    {
        $users_count = User::select()->count();
        $wallet_count = Wallet::select()->count();
        $total_wallet_balance = Wallet::sum('balance');
        $total_transactions = Transaction::sum('amount');
        
        return ['total_users'=>$users_count, "total_wallets"=>$wallet_count, "total_balances"=>$total_wallet_balance, "transactions_volume"=>$total_transactions];
    }

}

