<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;


class User extends Authenticatable
{

    use HasFactory;

    protected $table = 'users';
    protected $primaryKey = 'id_user';
    public $incrementing = false;
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'update_at';
    protected $fillable = [
        'id_user',
        'nama',
        'email',
        'no_telp',
        'pasword',
        'role',
        'tahun_masuk',
        'status',
    ];

    protected $hidden = [
        'password',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function siswaKursus() {
        return $this->hasMany(SiswaKursus::class, 'id_siswa', 'id_user');
    }

    public function tentorKursus() {
        return $this->hasMany(TentorKursus::class, 'id_tentor', 'id_user');
    }

    public function siswaKelas() {
        return $this->hasMany(Kelas::class, 'id_siswa', 'id_user');
    }

    public function pengumpulanTugas() {
        return $this->hasMany(PengumpulanTugas::class, 'id_siswa', 'id_user');
    }

    public function jadwal() {
        return $this->hasMany(Jadwal::class, 'id_tentor', 'id_user');
    }

    public function absensiSiswa() {
        return $this->hasMany(AbsensiSiswa::class, 'id_siswa', 'id_user');
    }

    public function tugas() {
        return $this->hasMany(Tugas::class, 'id_tentor', 'id_user');
    }

    public function nilai() {
        return $this->hasMany(Nilai::class, 'id_user', 'id_tentor');
    }

    public function siswaSertifikat() {
        return $this->hasMany(Sertifikat::class, 'id_user', 'id_siswa');
    }

    public function tentorSertifikat() {
        return $this->hasMany(Sertifikat::class, 'id_user', 'id_tentor');
    }

    public function pengumuman() {
        return $this->hasMany(Pengumuman::class, 'id_user', 'id_user');
    }

    public function kelas() {
        return $this->hasMany(Kelas::class, 'id_user', 'id_siswa');
    }
}
