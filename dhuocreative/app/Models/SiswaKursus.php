<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiswaKursus extends Model
{
    protected $table = siswa_kursus;
    protected $primaryKey = id_siswa_kursus;
    protected $fillable = [
        'id_siswa_kursus',
        'id_siswa',
        'id_kursus',
        'tanggal_daftar',
        'status'
    ];

    public function siswa() {
        return $this->belongsTo(User::class, 'id_siswa', 'id_user');
    }

    public function kursus() {
        return $this->belongsTo(Kursus::class, 'id_kursus', 'id_kursus');
    }
}
