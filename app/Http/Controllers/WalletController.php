<?php

namespace App\Http\Controllers;

use App\Http\Traits\UserRole;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    use UserRole;
    
    public function store(Request $request){
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

        return response(['res'=> 'success', "wallet" => $wallet], 200);

    }

    public function index(Request $request)
    {
        if (!$this->isAdmin($request->user())) {
            return response(['res'=> false, 'message'=> 'Unauthorised access'], 401);
        }

        $walllets =  Wallet::
        join('wallet_types', 'wallet_types.id', '=', 'wallets.wallet_type_id')
        ->join('users', 'users.id', '=', 'wallets.user_id')
        ->select('wallets.id as wallet_id', 'users.name as user_name', 'users.email as user_email', 'wallets.balance','wallets.wallet_type_id  as wallet_type_id', 'wallet_types.type_name as wallet_type','wallets.created_at', 'wallets.updated_at')
        ->get();
        
        return response(['res'=> 'success', $walllets], 200);
    }

    public function find($id)
    {
        
        $user_wallet = Wallet::
        join('wallet_types', 'wallet_types.id', '=', 'wallets.wallet_type_id')
        ->join('users', 'users.id', '=', 'wallets.user_id')
        ->select('users.name as owner', 'users.email as owner_email', 'wallets.balance','wallets.wallet_type_id  as wallet_type_id', 'wallet_types.type_name as wallet_type','wallets.created_at', 'wallets.updated_at as last_transaction_date')
        ->where('wallets.id', $id)
        ->get();
        
        if ($user_wallet->count() == 0 || !$user_wallet) {
            $user_wallet = 0;
        }

        $transactions = Wallet::find($id)->transactions;

        if ($transactions->count() == 0 || !$transactions) {
            $transactions = 0;
        }
        
        return ['res'=> 'success', "wallet"=>$user_wallet, "transaction_history"=>$transactions];
    }

    public function transferFund(Request $request)
    {
        if (!$this->isUser($request->user())) {
            return response(['res'=> false, 'message'=> 'Unauthorised access'], 401);
        }

        $fields = $request->validate([
            'amount'=> ['required'],
            'initiation_wallet'=> ['required'],
            'destination_wallet'=> ['required']
        ]);
        
        $initiation_wallet = Wallet::find($fields['initiation_wallet']); 

        //get minimum balance
        $minimum_balance = $initiation_wallet->walletType->minimum_balance;

        //subtract balance from amount
        $transaction_subtraction =  $initiation_wallet->balance - $fields['amount'];

        //compare transaction balance with minimum balance
        if ($transaction_subtraction  < $minimum_balance) {
            return response(['res'=> false, 'message'=> 'Insufficient funds'], 400);
        }

        //reduce balance from source account
        $initiation_wallet->balance = $transaction_subtraction;
        $initiation_wallet->save();

        //increment balance in destination account
        $destination_wallet = Wallet::find($fields['destination_wallet']);
        $destination_wallet->balance += $fields['amount'];
        $destination_wallet->save();
        
        //save transaction  
        Transaction::create([
            'user_id' => $destination_wallet->user_id,
            'wallet_id'=>$fields['destination_wallet'],
            'amount'=>$fields['amount']
        ]);

        return response(['res'=> 'success', 'message'=> 'A sum of ' . $fields['amount'] . ' was deducted from your account, your new balance is ' . $initiation_wallet->balance], 200);

    }
    
    // public function fundWallet(Request $request)
    // {
    //     $fields = $request->validate([
    //         'amount'=> ['required'],
    //         'wallet_id'=> ['required']
    //     ]);

    //     //check existence to get wallet info
    //     $wallet = Wallet::find($fields['wallet_id']);
    //     if (!$wallet) {
    //         return response(['res'=>false, 'message'=> 'Account not found'], 400);
    //     }

    //     $wallet->balance += $fields['amount'];
    //     $wallet->save();

    //     //save as a transaction
    //     $transaction = Transaction::create([
    //         'user_id' => $wallet->user_id,
    //         'wallet_id'=>$wallet->id,
    //         'amount'=>$fields['amount']
    //     ]);

    //     return response(['res'=> 'success', 'message'=> 'Your wallet has been funded with ' . $transaction->amount], 200);
    // }
}
