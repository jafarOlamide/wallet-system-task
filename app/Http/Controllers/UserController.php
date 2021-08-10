<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getUserDetails($id){
        $user_details = User::select('name', 'email')->where('id', $id)->get();
        
        $wallet_details = Wallet::
        join('wallet_types', 'wallet_types.id', '=', 'wallets.wallet_type_id')
        ->select('wallets.id', 'wallets.balance','wallets.created_at', 'wallets.updated_at', 'wallet_types.type_name')
        
        ->get();

        $transaction_history = Transaction::
        join('wallets', 'wallets.id', '=', 'transactions.wallet_id')
        ->select('transactions.id', 'transactions.wallet_id', 'transactions.amount','wallets.created_at', 'wallets.updated_at')
        ->where('transactions.user_id', $id)
        ->get();

        return ["user_details"=>$user_details, "wallets"=>$wallet_details, "transactions"=>$transaction_history];

    }


    public function getUsers()
    {
        $users = User::select('name', 'email', 'role')->get();
        return $users;
    }
}
