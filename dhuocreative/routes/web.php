<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Tentor;

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
    Route::get('/dashboard', [Tentor\DashboardController::class, 'index'])->name('dashboard'); 

    Route::get('/kelas', [Tentor\KelasController::class, 'index'])->name('kelas');

   Route::get('/kelas/absensi/{kelas}', [Tentor\KelasController::class, 'absensi'])
        ->whereNumber('kelas')->name('absensi');

    Route::post('/kelas/absensi/{kelas}', [Tentor\KelasController::class, 'simpanAbsensi'])
        ->whereNumber('kelas')->name('absensi.simpan');

    Route::get('/materi', [Tentor\MateriController::class, 'index'])    ->name('materi');
    
    Route::post('/materi', [Tentor\MateriController::class, 'simpan'])  ->name('materi.simpan');
    
    Route::get('/materi/{kelas}/{token}', [Tentor\MateriController::class, 'file'])
        ->whereNumber('kelas')->name('materi.file');

    Route::put('/materi/{kelas}/{token}', [Tentor\MateriController::class, 'update'])
        ->whereNumber('kelas')->name('materi.update');
    Route::delete('/materi/{kelas}/{token}', [Tentor\MateriController::class, 'hapus'])
        ->whereNumber('kelas')->name('materi.hapus');


    Route::get('/nilai/{tugas}', [Tentor\NilaiController::class, 'show'])
        ->whereNumber('tugas')->name('nilai');

    Route::post('/nilai/{pengumpulan}', [Tentor\NilaiController::class, 'simpan'])
        ->whereNumber('pengumpulan')->name('nilai.simpan');

    Route::get('/tugas', [Tentor\TugasController::class, 'index'])->name('tugas');
    
    Route::post('/tugas', [Tentor\TugasController::class, 'simpan'])->name('tugas.simpan');
    
    Route::put('/tugas/{tugas}', [Tentor\TugasController::class, 'update'])
        ->whereNumber('tugas')->name('tugas.update');
    
    Route::get('/tugas/{tugas}/file', [Tentor\TugasController::class, 'file'])
        ->whereNumber('tugas')->name('tugas.file');

    Route::get('/sertifikat', [Tentor\SertifikatController::class, 'index'])->name('sertifikat');

    Route::post('/sertifikat', [Tentor\SertifikatController::class, 'simpan'])->name('sertifikat.simpan');

    Route::get('/sertifikat/{sertifikat}/file', [Tentor\SertifikatController::class, 'file'])
        ->whereNumber('sertifikat')->name('sertifikat.file');
        
    Route::get('/pengumuman', [Tentor\PengumumanController::class, 'index'])->name('pengumuman');
    
    Route::post('/pengumuman', [Tentor\PengumumanController::class, 'simpan'])->name('pengumuman.simpan');

    Route::put('/pengumuman/{pengumuman}', [Tentor\PengumumanController::class, 'update'])
        ->whereNumber('pengumuman')->name('pengumuman.update');

    Route::delete('/pengumuman/{pengumuman}', [Tentor\PengumumanController::class, 'hapus'])
        ->whereNumber('pengumuman')->name('pengumuman.hapus');

    // Route::get('/kelas', function () {
    //     return view('tentor.kelas');
    // })->name('kelas');

    // Route::get('/kelas/absensi/{kelas}', function ($kelas) {
    //     return view('tentor.absensi', compact('kelas'));
    // })->name('absensi');

    // Route::get('/materi', function () {
    //     return view('tentor.materi');
    // })->name('materi');

    //     Route::get('/nilai', function () {
    //     return view('tentor.nilai');
    // })->name('nilai');

    // Route::get('/tugas', function () {
    //     return view('tentor.tugas');
    // })->name('tugas');  

    // Route::get('/sertifikat', function () {
    //     return view('tentor.sertifikat');
    // })->name('sertifikat');

    // Route::get('/pengumuman', function () {
    //     return view('tentor.pengumuman');
    // })->name('pengumuman'); 
});

Route::get('dashboard', function () {
    return redirect()->route('tentor.dashboard');
})->middleware('auth')->name('dashboard');

// operator
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
});

// siswa
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