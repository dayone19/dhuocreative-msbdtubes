<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;

// login
Route::get('/', function () {
    if (auth()->check()) {
        $user = auth()->user();
        switch ($user->role) {
            case 'admin': return redirect('/admin/dashboard');
            case 'operator': return redirect('/operator/dashboard');
            case 'tentor': return redirect('/tentor/dashboard');
            case 'siswa': return redirect('/siswa/dashboard');
            default: return redirect('/login');
        }
    }
    
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

// tentor
Route::middleware(['auth', 'prevent.back'])->prefix('tentor')->name('tentor.')->group(function () {
    Route::get('/dashboard', function () {
        return view('tentor.dashboard');
    })->name('dashboard'); 

    Route::get('/kelas', function () {
        return view('tentor.kelas');
    })->name('kelas');

    Route::get('/kelas/absensi/{kelas}', function ($kelas) {
        return view('tentor.absensi', compact('kelas'));
    })->name('absensi');

    Route::get('/materi', function () {
        return view('tentor.materi');
    })->name('materi');

    Route::get('/tugas', function () {
        return view('tentor.tugas');
    })->name('tugas'); 

    Route::get('/nilai', function () {
        return view('tentor.nilai');
    })->name('nilai'); 
});


Route::middleware(['auth', 'prevent.back'])->prefix('operator')->name('operator.')->group(function () {
    Route::get('/dashboard', function () {
        return view('operator.dashboard');
    })->name('dashboard'); 

    Route::get('/siswa', function () {
        return view('operator.siswa');
    })->name('siswa'); 

    Route::get('/kursus', function () {
        return view('operator.kursus');
    })->name('kursus'); 

    Route::get('/jadwal', function () {
        return view('operator.jadwal');
    })->name('jadwal'); 
});


Route::middleware(['auth', 'prevent-back-history'])->group(function () {
    Route::post('/admin/users', [UserController::class, 'store'])
   ->name('admin.users.store');
    // admin
    // operator
    // tentor
    // siswa
});

Route::middleware(['auth', 'prevent.back'])->prefix('siswa')->name('siswa.')->group(function () {
    Route::get('/dashboard', function () {
        return view('siswa.dashboard');
    })->name('dashboard');
});