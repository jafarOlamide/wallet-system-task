<?php

namespace App\Http\Controllers;

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
}
