<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;



class AuthController extends Controller
{
    public function register(Request $request){
        $fields = $request->validate([
           'email' => ['required', 'string', 'email', 'unique:users,email'],
           'password'=> ['required', 'string'],
           'name'=> ['required', 'string', 'max:255'],
           'phone_number'=> ['required', 'string', 'max:255'],
           'balance'=> ['required'],
           'wallet_type_id'=> ['required']
        ]);

        $user = User::create([
           'email'=> $fields['email'],
           'password'=> bcrypt($fields['password']),
           'name'=> $fields['name'],
           'phone'=> $fields['phone_number'],
        ]);

        $role = "user";

        //create a wallet for the user
        $wallet = $user->wallet()->create([
            'balance'=> $request['balance'],
            'wallet_type_id'=> $request['wallet_type_id'],
            'user_id'=> $user->id
        ]);
        
        $token = $user->createToken(env('TOKEN_AUTHENTICATION'), [$role]);

        return response(['res'=> 'success', "user"=> $user, "wallet" => $wallet, "token"=>$token->plainTextToken, 'token_expiration'=>'60 mins']);
    }


    public function registerAdmin(Request $request){
        $fields = $request->validate([
           'email' => ['required', 'string', 'email', 'unique:users,email'],
           'password'=> ['required', 'string'],
           'name'=> ['required', 'string', 'max:255'],
           'phone_number'=> ['required', 'string', 'max:255']
        ]);

        $user = User::create([
           'email'=> $fields['email'],
           'password'=> bcrypt($fields['password']),
           'name'=> $fields['name'],
           'phone'=> $fields['phone_number'],
           'role'=> $fields['role']
        ]);

        $role = "admin";
        
        $token = $user->createToken(env('TOKEN_AUTHENTICATION'), [$role]);

        return response(['res'=> 'success', "user"=> $user, "token"=>$token->plainTextToken, 'token_expiration'=>'60 mins']);
    }
   
    public function login(Request $request ){
       $fields = $request->validate([
           'email' => ['required', 'string', 'email'],
           'password'=> ['required', 'string']
       ]);

       $user = User::where('email', $fields['email'])->first();

       if (!$user || !Hash::check($fields['password'], $user->password)) {
           return response(['res'=> 'success', 'message'=> 'Invalid username or password'], 400);
       }

       $user_role = $user->role;

       $token = $user->createToken(env('TOKEN_AUTHENTICATION'), [$user_role])->plainTextToken;

       return response(['res'=> 'success', 'user'=> $user, 'token'=> $token, 'token_expiration'=>'60 mins'], 200);
    }
}
