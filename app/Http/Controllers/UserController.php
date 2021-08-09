<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getUserDetails($id){
        $user_details = User::join('wallets', 'users.id', '=', 'wallets.user_id');
        return $user_details;

    }
}
