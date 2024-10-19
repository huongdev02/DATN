<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AdminController;

use App\Http\Controllers\API\VoucherController;

use App\Http\Controllers\ColorController;
use App\Http\Controllers\SizeController;
use App\Http\Controllers\CategoryController;

use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ThongkeController;
use App\Http\Controllers\VoucherController;
use App\Http\Middleware\isAdmin;
use App\Http\Middleware\isUser;
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
    Route::get('password/reset/{token}', 'updatepassword')->name('password.reset');
    Route::post('password/reset', 'updatepassword_')->name('password.update');

    // Cập nhật tài khoản
    Route::get('/edit', 'edit')->name('edit')->middleware('auth');
    Route::post('/update', 'update')->name('update')->middleware('auth');

    // Đổi mật khẩu
    Route::get('/change-password', 'changepass')->name('changepass.form')->middleware('auth');
    Route::post('/change-password', 'changepass_')->name('password.change')->middleware('auth');

    // Xác thực email
    Route::get('/verify', 'verify')->name('verify')->middleware('auth');
    Route::get('/verify/{id}/{hash}', 'verifydone')->name('verification.verify');

    // Đăng xuất
    Route::post('logout', 'logout')->name('logout');
});

// Route cho Admin
Route::controller(AdminController::class)->group(function () {
    Route::get('/admin',  'admin')->name('admin.dashboard')
    ->middleware(['auth', 'admin']);

      // Đổi mật khẩu
    Route::get('/admin/change-password', 'changepass')->name('admin.changepass.form')->middleware('auth');
    Route::post('/admin/change-password', 'changepass_')->name('admin.password.change')->middleware('auth');

    // Cập nhật tài khoản
    Route::get('/admin/edit', 'edit')->name('admin.edit')->middleware('auth');
    Route::post('/admin/update', 'update')->name('admin.update')->middleware('auth');
});

// Route cho User
Route::get('/user', [UserController::class, 'user'])->name('user.dashboard')
    ->middleware(['auth', isUser::class]);

Route::resource('products', ProductController::class);
Route::resource('dashboard', ProductController::class);



Route::resource('sizes', SizeController::class);
Route::resource('colors', ColorController::class);
Route::resource('categories', CategoryController::class);


Route::resource('vouchers', VoucherController::class);


