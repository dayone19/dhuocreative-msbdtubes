<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;

// login
Route::get('/', function () {
    return redirect()->route('login');
});
Route::get('/login', [LoginController::class, 'showLogin'])
    ->middleware(['guest', 'prevent.back'])
    ->name('login');
Route::post('/login', [LoginController::class, 'login'])
    ->middleware('guest')
    ->name('login.process');
Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware(['auth', 'prevent.back'])
    ->name('logout');

// forgot password
Route::get('/forgotPass', [ForgotPasswordController::class, 'showForgotPassword'])
    ->name('password.request');
Route::post('/forgotPass/send-otp', [ForgotPasswordController::class, 'sendOtp'])
    ->name('password.email');
Route::post('/forgotPass/verify-otp', [ForgotPasswordController::class, 'verifyOtp'])
    ->name('password.verify');
Route::post('/forgotPass/reset-password', [ForgotPasswordController::class, 'resetPassword'])
    ->name('password.reset');    

Route::get('/tentor/dashboard', function () {
    return view('tentor.dashboard');
})->name('dashboard');

Route::post('/admin/users', [UserController::class, 'store'])
   ->middleware('auth')
   ->name('admin.users.store');

Route::middleware(['auth', 'prevent-back-history'])->group(function () {
    // admin
    // operator
    // tentor
    // siswa
});