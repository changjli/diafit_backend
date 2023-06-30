<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\FoodController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\TransactionHeaderController;
use App\Models\TransactionHeader;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

// forgot password 
// halaman request email reset password
Route::get('/forgot-password', [PasswordResetController::class, 'index']);

// kirim email reset password
Route::post('/forgot-password', [PasswordResetController::class, 'mailComposer']);

// halaman reset password
Route::get('reset-password', [PasswordResetController::class, 'edit']);

// reset password
Route::post('reset-password', [PasswordResetController::class, 'update']);

// admin 

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'index'])->name('login');

    Route::post('/login', [LoginController::class, 'adminLogin']);
});

Route::middleware('auth')->group(function () {
    Route::get('/', [AdminController::class, 'index']);

    Route::post('/logout', [AdminController::class, 'logout']);

    Route::resource('/food', FoodController::class);

    Route::get('/order/{location}', [OrderController::class, 'index']);

    Route::put('/order/{transactionHeader:id}', [OrderController::class, 'update']);

    Route::get('/transaction/{location}', [TransactionHeaderController::class, 'index']);

    Route::get('/transaction/detail/{transactionHeader:id}', [TransactionHeaderController::class, 'show']);

    Route::put('/transaction/{transactionHeader:id}', [TransactionHeaderController::class, 'update']);
});
