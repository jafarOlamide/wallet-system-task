<?php

namespace App\Http\Controllers;

use App\Http\Traits\UserRole;
use App\Models\WalletType;
use Illuminate\Http\Request;

class WalletTypeController extends Controller
{
    use UserRole;

    public function create(Request $request){
        //verify authorisation
        if (!$this->isAdmin($request->user())) {
            return response(['res'=> false, 'message'=> 'Unauthorised access'], 401);
        }

        $fields = $request->validate([
            'type_name'=> ['required', 'string', 'unique:wallet_types,type_name'],
            'minimum_balance'=> ['required'],
            'monthly_interest'=> ['required']
        ]);

        $wallet = WalletType::create([
            'type_name'=> $fields['type_name'],
            'minimum_balance'=> $fields['minimum_balance'],
            'monthly_interest'=> $fields['monthly_interest'],
        ]);

        return response(['res'=> 'success', "wallet" => $wallet], 200);
    }
}

