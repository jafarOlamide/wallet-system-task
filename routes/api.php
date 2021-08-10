<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SummaryController;
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
Route::post('/fund_wallet', [WalletController::class, 'fundWallet']);
Route::post('/transfer', [WalletController::class, 'transferFund']);
Route::get('/wallets', [WalletController::class, 'getWallets']);
Route::get('/wallets/{id}', [WalletController::class, 'showWallet']);


Route::post('/create_wallet_type', [WalletTypeController::class, 'create']);


Route::get('/summary', [SummaryController::class, 'summary']);


Route::get('/users/{id}', [UserController::class, 'getUserDetails']);
Route::get('/users', [UserController::class, 'getUsers']);

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});





