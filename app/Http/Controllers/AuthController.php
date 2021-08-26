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
           'role'=> ['required']
        ]);


        $user = User::create([
           'email'=> $fields['email'],
           'password'=> bcrypt($fields['password']),
           'name'=> $fields['name'],
           'phone'=> $fields['phone_number'],
           'role'=> $fields['role']
        ]);

        $role = $fields['role'] === 1 ? "admin" : "user";

        $token = $user->createToken(env('TOKEN_AUTHENTICATION'), [$role]);

       return response(['res'=> 'success',, "user"=> $user, "token"=>$token->plainTextToken]);
    }
   
    public function login(Request $request ){
       $fields = $request->validate([
           'email' => ['required', 'string', 'email'],
           'password'=> ['required', 'string']
       ]);

       $user = User::where('email', $fields['email'])->first();

       if (!$user || !Hash::check($fields['password'], $user->password)) {
           return response(['res'=> 'success',, 'message'=> 'Invalid username or password'], 400);
       }

       $user_role = $user->role === 1 ? "admin" : "user";

       $token = $user->createToken(env('TOKEN_AUTHENTICATION'), [$user_role])->plainTextToken;

       return response(['res'=> 'success',, 'user'=> $user, 'token'=> $token, 'token_expiration'=>'60 mins'], 200);
    }
}
