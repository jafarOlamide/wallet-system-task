<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Options\UserRoleTypes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;



class AuthController extends Controller
{
    public function welcome(){
        return response(['res'=> 'success', "message"=> "Welcome  to Wallet Task"]);
    }


    public function register(Request $request){
        
        $fields = $request->validate([
           'email' => ['required', 'string', 'email', 'unique:users,email'],
           'password'=> ['required', 'string'],
           'name'=> ['required', 'string', 'max:255'],
           'phone_number'=> ['required', 'string', 'max:255'],
           'wallet_type_id'=> ['required']
        ]);

        $new_user_resource = DB::transaction(function () use ($fields, $request){
            $user = User::create([
                'name'=> $fields['name'],
                'email'=> $fields['email'],
                'phone'=> $fields['phone_number'],
                'password'=> Hash::make($fields['password']),
                'role'=> UserRoleTypes::USER,
                'email_verified_at' => now()->format('Y-m-d H:i:s'),
            ]);

            //create a wallet for the user
            $wallet = $user->wallet()->create([
                'balance'=> '0',
                'wallet_type_id'=> $request['wallet_type_id'],
                'user_id'=> $user->id
            ]);

            return ["user"=> $user, "wallet" => $wallet];
        });

        $token = $new_user_resource['user']->createToken(env('TOKEN_AUTHENTICATION'), [UserRoleTypes::USER]);

        return response()->json(['res'=> 'success', "data"=> $new_user_resource, "token"=>$token->plainTextToken, 'expiration'=>'60'], 200);
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

        
        $token = $user->createToken(env('TOKEN_AUTHENTICATION'), [UserRoleTypes::ADMIN]);

        return response()->json(['res'=> 'success', "user"=> $user, "token"=>$token->plainTextToken, 'expiration'=>'60'], 200);
    }
   
    public function login(Request $request){
       $fields = $request->validate([
           'email' => ['required', 'string', 'email'],
           'password'=> ['required', 'string']
       ]);

       $user = User::where('email', $fields['email'])->first();

       if (!$user || !Hash::check($fields['password'], $user->password)) {
           return response(['res'=> 'success', 'message'=> 'Invalid username or password'], 400);
       }

       $token = $user->createToken(env('TOKEN_AUTHENTICATION'), [$user->role])->plainTextToken;

       return response()->json(['res'=> 'success', 'user'=> $user, 'token'=> $token, 'expiration'=>'60'], 200);
    }
}
