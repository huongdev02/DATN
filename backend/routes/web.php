<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Api\AccountController as ApiAccountController;
use App\Http\Controllers\ColorController;
use App\Http\Controllers\SizeController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ManagerUserController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\ThongkeController;
use App\Http\Controllers\VoucherController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Đây là nơi bạn có thể đăng ký các route web cho ứng dụng của bạn.
| Các route này được tải bởi RouteServiceProvider và tất cả chúng
| sẽ được gán vào nhóm "web" middleware. Hãy tạo nên điều gì đó tuyệt vời!
|
*/
Route::get('/', function () {
    return view('welcome');
});

// Các route cho AccountController
Route::controller(ApiAccountController::class)->group(function () {
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
    Route::get('password/reset/{token}', 'updatepassword')->name('password.reset');
    Route::post('password/reset', 'updatepassword_')->name('password.update');

    // Xác thực email
    Route::get('/verify', 'verify')->name('verify')->middleware('auth');
    Route::get('/verify/{id}/{hash}', 'verifydone')->name('verification.verify');

    // Đăng xuất
    Route::post('logout', 'logout')->name('logout');
});

// Route cho Admin
Route::controller(AdminController::class)->group(function () {
    Route::get('/admin/dashboard',  'admin')->name('admin.dashboard')
    ->middleware(['auth', 'admin']);

      // Đổi mật khẩu
    Route::get('/admin/change-password', 'changepass')->name('admin.changepass.form')->middleware('auth');
    Route::post('/admin/change-password', 'changepass_')->name('admin.password.change')->middleware('auth');

    // Cập nhật tài khoản
    Route::get('/admin/edit', 'edit')->name('admin.edit')->middleware('auth');
    Route::post('/admin/update', 'update')->name('admin.update')->middleware('auth');
});

// Route cho User
Route::controller(UserController::class)->group(function () {
    Route::get('/user/dashboard', 'user')->name('user.dashboard')
    ->middleware(['auth', 'user']);

       // Đổi mật khẩu
    Route::get('/user/change-password', 'changepass')->name('user.changepass.form')->middleware('auth');
    Route::post('/user/change-password', 'changepass_')->name('user.password.change')->middleware('auth');

    // Cập nhật tài khoản
    Route::get('/user/edit', 'edit')->name('user.edit')->middleware('auth');
    Route::post('/user/update', 'update')->name('user.update')->middleware('auth');

    //địa chỉ
    Route::resource('address', AddressController::class);
    Route::patch('ship-addresses/{id}/set-default',  [AddressController::class, 'setDefault'])->name('address.set-default');
});

Route::resource('products', ProductController::class);


Route::resource('sizes', SizeController::class);
Route::resource('colors', ColorController::class);
Route::resource('categories', CategoryController::class);

Route::resource('vouchers', VoucherController::class);

Route::resource('orders', OrderController::class);
Route::resource('promotion', PromotionController::class);
Route::resource('users', ManagerUserController::class);
