<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\isAdmin;
use App\Http\Middleware\isUser;
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
    return view('welcome');
});

Route::controller(AccountController::class)->group(function () {
    // Đăng ký
    Route::get('register', 'register')->name('register.form');
    Route::post('register', 'register_')->name('register');

    // Đăng nhập
    Route::get('login', 'login')->name('login.form');
    Route::post('login', 'login_')->name('login');

    // Quên mật khẩu
    Route::get('password/forgot', 'rspassword')->name('password.forgot.form');
    Route::post('password/forgot', 'rspassword_')->name('password.forgot');

    // Đặt lại mật khẩu
    Route::get('password/reset/{token}', 'updatepassword')->name('password.reset.form');
    Route::post('password/reset', 'updatepassword_')->name('password.reset');

    //cập nhật tài khoản
    Route::get('/edit', 'edit')->name('edit')->middleware('auth');
    Route::post('/update', 'update')->name('update')->middleware('auth');

    //changepass
    Route::get('/change-password','changepass')->name('changepass.form')->middleware('auth');;
    Route::post('/change-password','changepass_')->name('password.change')->middleware('auth');;

    //Xác thực email
    Route::get('/verify', 'verify')->name('verify')->middleware('auth');
    Route::get('/verify/{id}/{hash}', 'verifydone')->name('verification.verify');

    // Đăng xuất
    Route::post('logout', 'logout')->name('logout');
});

Route::get('/admin', [AdminController::class, 'admin'])->name('admin.dashboard')
->middleware(['auth', isAdmin::class]);

Route::get('/user', [UserController::class, 'user'])->name('user.dashboard')
->middleware(['auth', isUser::class]);

