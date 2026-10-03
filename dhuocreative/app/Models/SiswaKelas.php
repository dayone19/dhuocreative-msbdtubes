<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiswaKelas extends Model
{
    protected $table = siswa_kelas;
    protected $primaryKey = id_siswa_kelas;
    protected $fillable = [
        'id_siswa_kelas',
        'id_siswa',
        'id_kelas',
        'tanggal_daftar',
        'status'
    ];

    public function siswa() {
        return $this->belongsTo(User::class, 'id_siswa', 'id_user');
    }

    public function kursus() {
        return $this->belongsto(kursus::class, 'id_kelas', 'id_kelas');
    }
}
