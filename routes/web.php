<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('/', function () {
    return redirect('product');
});
Route::get('login', function () {
    return view('page.login');
})->name('login');

Route::post('login', [AuthController::class, 'login']);
Route::get('logout', [AuthController::class, 'logout']);

Route::get('product', [ProductController::class, 'index']);
Route::get('product/{id}', [ProductController::class, 'show']);

Route::middleware('auth')->group(function(){
    Route::resource('cart', CartController::class)->except('show');

    Route::get('/transaction/confirm', [TransactionController::class, 'confirmCheckout']);
    Route::post('/transaction/create', [TransactionController::class, 'store']);
    Route::get('/transaction/index', [TransactionController::class, 'index']);
    Route::get('/transaction/detail/{id}', [TransactionController::class, 'detail']);
    Route::delete('/transaction/delete/{id}', [TransactionController::class, 'destroy']);

    Route::get('/transaction/check-status/{id}', [TransactionController::class, 'check']);
});

if (strtolower(env('APP_ENV')) != 'production') {
    Route::get('/snap', function () {
        return view('midtrans');
    });
}
