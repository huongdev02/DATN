<?php

use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\Api\AccountController;
use App\Http\Controllers\API\ColorController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\API\VoucherController;
use App\Http\Controllers\API\SizeController;
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

Route::apiResource('products', ProductController::class);
Route::apiResource('vouchers', VoucherController::class);

Route::apiResource('categories', CategoryController::class);


Route::controller(AccountController::class)->group(function () {
    // Đăng ký
    Route::post('register', 'register')->name('register');

    // Đăng nhập
    Route::post('login', 'login')->name('login');

    // Quên mật khẩu
    Route::post('password/forgot', 'rspassword')->name('password.forgot');

    // Đặt lại mật khẩu
    Route::post('password/reset', 'updatepassword')->name('password.update');

    // Cập nhật tài khoản
    Route::post('/update', 'update')->name('update')->middleware('auth');

    // Đổi mật khẩu
    Route::post('/change-password', 'changepass')->name('password.change')->middleware('auth');
});

Route::apiResource('sizes', SizeController::class);
Route::apiResource('colors', ColorController::class);
