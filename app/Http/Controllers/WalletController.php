<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    
    public function create(Request $request){
        $fields = $request->validate([
            'balance'=> ['required'],
            'wallet_type_id'=> ['required'],
            'user_id'=> ['required']
        ]);

        $wallet = Wallet::create([
            'balance'=> $fields['balance'],
            'wallet_type_id'=> $fields['wallet_type_id'],
            'user_id'=> $fields['user_id'],
        ]);

        return ["wallet" => $wallet];

    }

    public function getWallets()
    {
        return Wallet::
        join('wallet_types', 'wallet_types.id', '=', 'wallets.wallet_type_id')
        ->join('users', 'users.id', '=', 'wallets.user_id')
        ->select('wallets.id as wallet_id', 'users.name as user_name', 'users.email as user_email', 'wallets.balance','wallets.wallet_type_id  as wallet_type_id', 'wallet_types.type_name as wallet_type','wallets.created_at', 'wallets.updated_at')
        ->get();
    }

    public function showWallet($id)
    {
        $user_wallet = Wallet::
        join('wallet_types', 'wallet_types.id', '=', 'wallets.wallet_type_id')
        ->join('users', 'users.id', '=', 'wallets.user_id')
        ->select('users.name as owner', 'users.email as owner_email', 'wallets.balance','wallets.wallet_type_id  as wallet_type_id', 'wallet_types.type_name as wallet_type','wallets.created_at', 'wallets.updated_at as last_transaction_date')
        ->where('wallets.id', $id)
        ->get();

        $transactions = Transaction::
        select('amount', 'created_at')
        ->where('wallet_id', $id)
        ->get();

        return ["wallet"=>$user_wallet, "transaction_history"=>$transactions];
    }
}
