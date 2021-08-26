<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Wallet;
use App\Http\Traits\UserRole;
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
        ->select('wallets.id', 'wallets.balance','wallets.created_at', 'wallets.updated_at', 'wallet_types.type_name')
        ->where('wallets.user_id', $id)
        ->get();

        if ($wallet_details->count() == 0 || !$wallet_details) {
            $wallet_details = 0;
        }

        $transaction_history = Transaction::
        join('wallets', 'wallets.id', '=', 'transactions.wallet_id')
        ->select('transactions.id', 'transactions.wallet_id', 'transactions.amount','wallets.created_at', 'wallets.updated_at')
        ->where('transactions.user_id', $id)
        ->get();

        if ($transaction_history->count() == 0 || !$transaction_history) {
            $transaction_history = 0;
        }

        return response(["user_details"=>$user_details, "wallets"=>$wallet_details, "transactions"=>$transaction_history], 200);

    }


    public function index(Request $request)
    {
        if (!$this->isAdmin($request->user())) {
            return response(['res'=> false, 'message'=> 'Unauthorised access'], 401);
        }

        $users = User::select('name', 'email', 'role')->get();
        return response($users, 200);
    }
}
