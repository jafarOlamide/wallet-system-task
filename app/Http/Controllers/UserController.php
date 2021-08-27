<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Wallet;
use App\Http\Traits\UserRole;
use App\Models\Deposit;
use App\Models\Withdrawal;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use UserRole;

    public function find($id){
        $user_details = User::select('name', 'email')->where('id', $id)->get();

        if (!$user_details) {
            return response(['res'=> false, 'message'=> 'User not found'], 404);
        }
        
        $wallet_details = Wallet::
        join('wallet_types', 'wallet_types.id', '=', 'wallets.wallet_type_id')
        ->select('wallets.id as wallet_id', 'wallets.balance','wallets.created_at', 'wallets.updated_at as last_transaction_date', 'wallet_types.type_name')
        ->where('wallets.user_id', $id)
        ->get();

        if ($wallet_details->count() == 0 || !$wallet_details) {
            $wallet_details = 0;
        }

        $credit_transaction_history  = Deposit::
        join('wallets', 'wallets.id', '=', 'deposits.wallet_id')
        ->select('deposits.wallet_id', 'deposits.transaction_reference','deposits.amount','wallets.created_at as deposit_date')
        ->where('deposits.user_id', $id)
        ->get();

        $debit_transaction_history = Withdrawal::
        join('wallets', 'wallets.id', '=', 'withdrawals.wallet_id')
        ->select('withdrawals.wallet_id', 'withdrawals.transaction_reference','withdrawals.amount','wallets.created_at as withdrawal_date')
        ->where('withdrawals.user_id', $id)
        ->get();

        if ($debit_transaction_history->count() == 0 || !$debit_transaction_history) {
            $debit_transaction_history = 0;
        }

        if ($credit_transaction_history->count() == 0 || !$credit_transaction_history) {
            $credit_transaction_history = 0;
        }

        return response(["res"=> "success", "user_details"=>$user_details, "wallets"=>$wallet_details, "debit_transactions"=>$debit_transaction_history, "credit_transactions"=>$credit_transaction_history], 200);

    }


    public function index(Request $request)
    {
        if (!$this->isAdmin($request->user())) {
            return response(['res'=> false, 'message'=> 'Unauthorised access'], 401);
        }

        $users = User::select('name', 'email', 'role')->get();
        return response(["res"=> "success",  "users"=>$users], 200);
    }
}
