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

    Route::get('/jadwal', function () {
        return view('tentor.jadwal');
    })->name('jadwal');

    Route::get('/kursus', function () {
        return view('tentor.kursus');
    })->name('kursus');

    Route::get('/kursus/absensi/{kursus}', function ($kursus) {
        return view('tentor.absensi', compact('kursus'));
    })->name('absensi');

    Route::get('/tugas', function () {
        return view('tentor.tugas');
    })->name('tugas'); 

    Route::get('/nilai', function () {
        return view('tentor.nilai');
    })->name('nilai'); 

    Route::get('/sertifikat', function () {
        return view('tentor.sertifikat');
    })->name('sertifikat');

    Route::get('/pengumuman', function () {
        return view('tentor.pengumuman');
    })->name('pengumuman'); 
});

// operator
Route::middleware(['auth', 'prevent.back'])->prefix('operator')->name('operator.')->group(function () {
    Route::get('/dashboard', function () {
        return view('operator.dashboard');
    })->name('dashboard'); 

    Route::get('/siswa', function () {
        return view('operator.siswa');
    })->name('siswa'); 

    Route::get('/tugas', function () {
        return view('operator.tugas');
    })->name('tugas');

    Route::get('/kursus', function () {
        return view('operator.kursus');
    })->name('kursus'); 

    Route::get('/jadwal', function () {
        return view('operator.jadwal');
    })->name('jadwal'); 

    Route::get('/absensi', function () {
        return view('operator.absensi');
    })->name('absensi'); 

    Route::get('/pengumuman', function () {
        return view('operator.pengumuman');
    })->name('pengumuman'); 
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
    Route::get('/kursus', function () {
        return view('siswa.kursus');
    })->name('kursus');
    Route::get('/aktivitas', function () {
        return view('siswa.aktivitas');
    })->name('aktivitas');
    Route::get('/sertifikat', function () {
        return view('siswa.sertifikat');
    })->name('sertifikat');
    Route::get('/pengumpulanTugas', function () {
        return view('siswa.pengumpulanTugas');
    })->name('pengumpulanTugas');
    Route::get('/nilai', function () {
        return view('siswa.nilai');
    })->name('nilai');
    Route::get('/absensi', function () {
        return view('siswa.absensi');
    })->name('absensi');
});

Route::middleware(['auth', 'prevent.back'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');
    Route::get('/manajemenAkun', function () {
        return view('admin.manajemenAkun');
    })->name('manajemenAkun');
    Route::get('/dataPengguna', function () {
        return view('admin.dataPengguna');
    })->name('dataPengguna');
    Route::get('/manajemenKursus', function () {
        return view('admin.manajemenKursus');
    })->name('manajemenKursus');
    Route::get('/jadwalAbsensi', function () {
        return view('admin.jadwalAbsensi');
    })->name('jadwalAbsensi');
    Route::get('/tugas', function () {
        return view('admin.tugas');
    })->name('tugas');
    Route::get('/nilaiSertifikat', function () {
        return view('admin.nilaiSertifikat');
    })->name('nilaiSertifikat');
    Route::get('/pengumuman', function () {
        return view('admin.pengumuman');
    })->name('pengumuman');
    Route::get('/buatAkun', function () {
        return view('admin.buatAkun');
    })->name('buatAkun');
});