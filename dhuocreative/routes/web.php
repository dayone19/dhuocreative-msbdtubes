<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Operator\JadwalController;
use App\Http\Controllers\Tentor\DashboardController as TentorDashboardController;
use App\Http\Controllers\Tentor\KursusController as TentorKursusController;
use App\Http\Controllers\Tentor\AbsensiController as TentorAbsensiController;
use App\Http\Controllers\Tentor\SertifikatController as TentorSertifikatController;
use App\Http\Controllers\Tentor\TugasController as TentorTugasController;
use App\Http\Controllers\Tentor\PengumumanController as TentorPengumumanController;
use App\Http\Controllers\Tentor\JadwalController as TentorJadwalController;
use App\Http\Controllers\Siswa\DashboardController as SiswaDashboardController;
use App\Http\Controllers\Siswa\SertifikatController as SertifikatController;

// controller admin
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ManajemenAkunController;
use App\Http\Controllers\Admin\DataPenggunaController;
use App\Http\Controllers\Admin\KursusController;
use App\Http\Controllers\Admin\JadwalController as AdminJadwalController;
use App\Http\Controllers\Admin\RiwayatAbsensiController;
use App\Http\Controllers\Admin\TugasDanNilaiController;
use App\Http\Controllers\Admin\SertifikatController as AdminSertifikatController;
use App\Http\Controllers\Admin\PengumumanController as AdminPengumumanController;

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
    Route::get('/dashboard', [TentorDashboardController::class, 'index'])->name('dashboard'); 
    Route::get('/jadwal', [TentorJadwalController::class, 'index'])->name('jadwal');
    Route::post('/jadwal/ajukan', [TentorJadwalController::class, 'ajukan'])->name('jadwal.ajukan');
    Route::get('/kursus', [TentorKursusController::class, 'index'])->name('kursus');
    Route::get('/kursus/absensi/{kursus}', [TentorAbsensiController::class, 'show'])
        ->whereNumber('kursus')->name('absensi');
    Route::post('/kursus/absensi/jadwal/{jadwal}', [TentorAbsensiController::class, 'updateStatus'])
        ->whereNumber('jadwal')->name('absensi.status');
    Route::get('/tugas', [App\Http\Controllers\Tentor\TugasController::class, 'index'])->name('tugas');
    Route::post('/tugas', [App\Http\Controllers\Tentor\TugasController::class, 'simpan'])->name('tugas.simpan');
    Route::put('/tugas/{tugas}', [App\Http\Controllers\Tentor\TugasController::class, 'update'])
        ->whereNumber('tugas')->name('tugas.update');
    Route::post('/tugas/pengumpulan/{pengumpulan}/nilai', [TentorTugasController::class, 'nilai'])
        ->whereNumber('pengumpulan')
        ->name('tugas.nilai');
    Route::get('/tugas/pengumpulan/{pengumpulan}/file', [TentorTugasController::class, 'fileJawaban'])
        ->whereNumber('pengumpulan')
        ->name('tugas.file');
    Route::get('/sertifikat', [TentorSertifikatController::class, 'index'])->name('sertifikat');
    Route::post('/sertifikat', [TentorSertifikatController::class, 'simpan'])->name('sertifikat.simpan');
    Route::put('/sertifikat/{sertifikat}', [TentorSertifikatController::class, 'update'])
        ->whereNumber('sertifikat')->name('sertifikat.update');
    Route::get('/sertifikat/file/{sertifikat}', [TentorSertifikatController::class, 'file'])
        ->whereNumber('sertifikat')->name('sertifikat.file');
    Route::get('/pengumuman', [TentorPengumumanController::class, 'index'])->name('pengumuman');
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

    Route::get('/jadwal', [JadwalController::class, 'index'])
    ->name('jadwal'); 

    Route::post('/jadwal', [JadwalController::class, 'store'])
    ->name('jadwal.store'); 

    Route::post('/jadwal/persetujuan', [JadwalController::class, 'approveReschedule'])
    ->name('jadwal.approve');
  


    Route::get('/absensi', function () {
        return view('operator.absensi');
    })->name('absensi'); 

    Route::get('/absensiSiswa', function () {
        return view('operator.absensiSiswa');
    })->name('absensiSiswa'); 

    Route::get('/sertifikat', function () {
        return view('operator.sertifikat');
    })->name('sertifikat');

    Route::get('/pengumuman', function () {
        return view('operator.pengumuman');
    })->name('pengumuman'); 
});

// siswa
Route::middleware(['auth', 'prevent.back'])->prefix('siswa')->name('siswa.')->group(function () {
    Route::get('/dashboard', [SiswaDashboardController::class, 'index'])->name('dashboard');
    Route::get('/kursus', [App\Http\Controllers\Siswa\KursusController::class, 'index'])->name('kursus');
    Route::get('/absensi', [App\Http\Controllers\Siswa\AbsensiController::class, 'index'])->name('absensi');

    Route::post('/absensi', [App\Http\Controllers\Siswa\AbsensiController::class, 'absen'])->name('absensi.absen');


    Route::get('/pengumpulanTugas', [App\Http\Controllers\Siswa\PengumpulanTugasController::class, 'index'])->name('pengumpulanTugas');
    Route::post('/pengumpulanTugas', [App\Http\Controllers\Siswa\PengumpulanTugasController::class, 'kumpulkan'])->name('pengumpulanTugas.kumpulkan');
    Route::get('/nilai', [App\Http\Controllers\Siswa\NilaiController::class, 'index'])->name('nilai');
    Route::get('/sertifikat', [App\Http\Controllers\Siswa\SertifikatController::class, 'index'])->name('sertifikat');
    Route::get('/sertifikat/{sertifikat}/file', [App\Http\Controllers\Siswa\SertifikatController::class, 'file'])
        ->whereNumber('sertifikat')
        ->name('sertifikat.file');
    Route::get('/pengumuman', [App\Http\Controllers\Siswa\PengumumanController::class, 'index'])->name('pengumuman');
});

// admin
Route::middleware(['auth', 'prevent.back'])->prefix('admin')->name('admin.')->group(function () {
    // dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // manajemen akun
    Route::get('/manajemenAkun', [ManajemenAkunController::class, 'index'])->name('manajemenAkun');
    Route::put('/manajemenAkun/{id}', [ManajemenAkunController::class, 'update'])->name('manajemenAkun.update');
    Route::patch('/manajemenAkun/{id}/nonaktifkan', [ManajemenAkunController::class, 'nonaktifkan'])->name('manajemenAkun.nonaktifkan');
    Route::post('/buatAkun', [ManajemenAkunController::class, 'store'])->name('buatAkun.store');
    
    // data pengguna
    Route::get('/dataPengguna', [DataPenggunaController::class, 'index'])->name('dataPengguna');
    
    // kursus
    Route::get('/manajemenKursus', [KursusController::class, 'index'])->name('manajemenKursus');
    Route::post('/manajemenKursus', [KursusController::class, 'store'])->name('manajemenKursus.store');
    Route::put('/manajemenKursus/{id}', [KursusController::class, 'update'])->name('manajemenKursus.update');
    Route::patch('/manajemenKursus/{id}/nonaktifkan', [KursusController::class, 'nonaktifkan'])->name('manajemenKursus.nonaktifkan');
    Route::patch('/manajemenKursus/{id}/aktifkan', [KursusController::class, 'aktifkan'])->name('manajemenKursus.aktifkan');
    
    // jadwal
    Route::get('/jadwal', [AdminJadwalController::class, 'index'])->name('jadwal');
    Route::get('/jadwalAbsensi', function () {return view('admin.jadwalAbsensi');})->name('jadwalAbsensi');
    
    // riwayat absensi
    Route::get('/riwayatAbsen', [RiwayatAbsensiController::class, 'index'])->name('riwayatAbsen');
    Route::get('/riwayatPresensi/{id_kelas}/{id_siswa}', [RiwayatAbsensiController::class, 'detail'])->name('riwayatPresensi');
    Route::patch('/riwayatPresensi/{id_kelas}/{id_siswa}/{id_jadwal}',[RiwayatAbsensiController::class, 'updateStatus'])->name('riwayatPresensi.updateStatus');
    
    // tugas dan nilai
    Route::get('/tugasDanNilai', [TugasDanNilaiController::class, 'index'])->name('tugasDanNilai');
    
    // sertifikat
    Route::get('/nilaiSertifikat', [AdminSertifikatController::class, 'index'])->name('nilaiSertifikat');

    // pengumuman
    Route::get('/pengumuman', [AdminPengumumanController::class, 'index'])->name('pengumuman');
    Route::post('/pengumuman', [AdminPengumumanController::class, 'store'])->name('pengumuman.store');
    Route::put('/pengumuman/{id}', [AdminPengumumanController::class, 'update'])->name('pengumuman.update');
    Route::delete('/pengumuman/{id}', [AdminPengumumanController::class, 'destroy'])->name('pengumuman.destroy');
});