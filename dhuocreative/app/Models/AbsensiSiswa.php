<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AbsensiSiswa extends Model
{
    protected $table = absensi_siswa;
    protected $primaryKey = id_absensi;
    protected $fillable = [
        'id_absensi',
        'id_jadwal',
        'id_siswa',
        'waktu_absen',
        'status'
    ];

    public function jadwal() {
        return $this->belongsTo(Jadwal::class, 'id_jadwal', 'id_jadwal');
    }

    public function siswa() {
        return $this->belongsTo(User::class, 'id_siswa', 'id_user');
    }
}
