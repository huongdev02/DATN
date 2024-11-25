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
use App\Http\Controllers\Api\ShipAddressController;
use App\Http\Controllers\UserController;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\Sanctum;

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

// Sanctum::routes();
Route::get('/sanctum/csrf-cookie', function () {
    return response()->json(['message' => 'CSRF cookie set']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/cart/{user_id}', [CartController::class, 'getCartByUserId']);
Route::put('/cart/{cartId}/update/{productId}', [CartController::class, 'updateCartItem']);
Route::apiResource('carts', CartController::class)->middleware('auth:sanctum');
Route::apiResource('orders', OrderController::class)->middleware('auth:sanctum');
Route::get('categories/{category}/products', [ProductController::class, 'getProductsByCategory']);
Route::apiResource('products', ProductController::class);
Route::apiResource('reviews', ReviewController::class);
Route::apiResource('vouchers', VoucherController::class);
Route::apiResource('payments', PayController::class);
Route::resource('promotions', PromotionController::class);
Route::apiResource('categories', CategoryController::class);
Route::get('products/category/{categoryId}', [CategoryController::class, 'productsByCategory']); 
Route::controller(AccountController::class)->group(function () {
    Route::post('login', 'login')->name('login');
    Route::post('register', 'register')->name('register');
    Route::post('/logout',  'logout')->middleware('auth:sanctum');
    //lay user
    Route::get('/users/{id}',  'show')->name('show');
});

Route::get('/auth/check', [AccountController::class, 'checkAuth']);

Route::post('ship_addresses', [ShipAddressController::class, 'store']); 