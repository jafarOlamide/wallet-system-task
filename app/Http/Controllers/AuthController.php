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

       $token = $fields['role'] == 1 ? $user->createToken(env('ADMIN_TOKEN_AUTHENTICATION')) : $user->createToken(env('TOKEN_AUTHENTICATION'));

       return response(["user"=> $user, "token"=>$token->plainTextToken]);
    }
   
    public function login(Request $request ){
       $fields = $request->validate([
           'email' => ['required', 'string', 'email'],
           'password'=> ['required', 'string']
       ]);

       $user = User::where('email', $fields['email'])->first();

       if (!$user || !Hash::check($fields['password'], $user->password)) {
           return response(['success'=>false, 'message'=> 'Invalid username or password'], 400);
       }

       $token = $user->createToken(env('TOKEN_AUTHENTICATION'))->plainTextToken;

       return response(['success'=> true, 'user'=> $user, 'token'=> $token], 200);
    }
}
