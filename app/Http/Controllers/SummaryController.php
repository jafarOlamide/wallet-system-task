<?php

namespace App\Http\Controllers;

use App\Http\Traits\UserRole;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;

class SummaryController extends Controller
{
    use UserRole;

    public function index(Request $request)
    {   
        if (!$this->isAdmin($request->user())) {
            return response(['res'=> false, 'message'=> 'Unauthorised access'], 401);
        }
        
        $users_count = User::count();
        $wallet_count = Wallet::count();
        $total_wallet_balance = Wallet::sum('balance');
        $total_transactions = Transaction::sum('amount');
        
        return response(['res'=> 'success',, 'total_users'=>$users_count, "total_wallets"=>$wallet_count, "total_balances"=>$total_wallet_balance, "transactions_volume"=>$total_transactions], 200);
    }

}

