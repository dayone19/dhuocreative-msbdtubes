<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;


class User extends Authenticatable
{
    protected $table = 'users';
    protected $primaryKey = 'id_user';
    const UPDATED_AT = 'update_at';
    protected $fillable = [
        'nama',
        'email',
        'no_telp',
        'password',
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
        return $this->hasMany(SiswaKursus::class, 'id_user', 'id_siswa');
    }

    public function tentorKursus() {
        return $this->hasMany(TentorKursus::class, 'id_user', 'id_tentor');
    }

    public function siswaKelas() {
        return $this->hasMany(SiswaKelas::class, 'id_user', 'id_siswa');
    }

    public function pengumpulanTugas() {
        return $this->hasaMany(PengumpulanTugas::class, 'id_user', 'id_siswa');
    }

    public function jadwal() {
        return $this->hasMany(Jadwal::class, 'id_user', 'id_tentor');
    }

    public function absensiSiswa() {
        return $this->hasMany(AbsensiSiswa::class, 'id_user', 'id_siswa');
    }

    public function tugas() {
        return $this->HasMany(Tugas::class, 'id_user', 'id_tentor');
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
}
