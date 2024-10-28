<?php

use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\Api\AccountController;
use App\Http\Controllers\API\ColorController;
use App\Http\Controllers\API\SizeController;
use App\Http\Controllers\API\VoucherController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\PayController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\PromotionController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('reviews', ReviewController::class);
Route::apiResource('orders', OrderController::class);
Route::apiResource('payments', PayController::class);
Route::apiResource('carts', CartController::class);
Route::apiResource('products', ProductController::class);
Route::resource('promotions', PromotionController::class);

Route::apiResource('categories', CategoryController::class);


Route::controller(AccountController::class)->group(function () {
    // Đăng nhập
    Route::post('login', 'login')->name('login');
    //lay user
    Route::get('/users/{id}',  'show')->name('show');;
});


Route::apiResource('vouchers', VoucherController::class);
Route::apiResource('users', UserController::class);


