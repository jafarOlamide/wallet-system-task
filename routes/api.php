<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\WalletTypeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/register', [AuthController::class, 'register']);



Route::post('/create_wallet', [WalletController::class, 'create']);

Route::post('/create_wallet_type', [WalletTypeController::class, 'create']);




Route::get('/get_user/{id}', [UserController::class, 'getUserDetails']);
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});





