<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    protected $table = kelas;
    protected $primaryKey = id_kelas;
    protected $fillable = [
        'id_kelas',
        'id_kursus',
        'id_siswa',
        'kelas',
        'status'
    ];

    public function siswaKelas() {
        return $this->hasMany(SiswaKelas::class, 'id_kelas', 'id_kelas');
    }

    public function kursus() {
        return $this->belongsTo(Kursus::class, 'id_kursus', 'id_kursus');
    }

    public function siswa() {
        return $this->belongsTo(User::class, 'id_siswa', 'id_user');
    }

    public function jadwal() {
        return $this->hasMany(Jadwal::class, 'id_kelas', 'id_kelas');
    }

    public function tugas() {
        return $this->hasMany(Tugas::class, 'id_kelas', 'id_kelas');
    }
}
