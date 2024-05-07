<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TransactionController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


// Routes for creating a new user and user authentication
Route::post('/users', [UserController::class, 'store']);
Route::post('/login', [AuthController::class, 'login']);

// Routes for transactions
Route::get('/allTransaction', [TransactionController::class, 'index']);
Route::get('/allDeposit', [TransactionController::class, 'deposits']);
Route::post('/makeDeposit', [TransactionController::class, 'deposit']);
Route::get('/allWithdrawal', [TransactionController::class, 'withdrawals']);
Route::post('/makeWithdrawal', [TransactionController::class, 'withdraw']);
