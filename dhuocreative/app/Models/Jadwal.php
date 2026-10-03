<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    protected $table = jadwal;
    protected $primaryKey = id_jadwal;
    protected $fillable = [
        'id_jadwal',
        'id_kelas',
        'id_tentor',
        'tanggal',
        'jam_mulai',
        'jam_selesai',
        'status'
    ];

    public function kelas() {
        return $this->belongsTo(Kelas::class, 'id_kelas', 'id_kelas');
    }

    public function tentor() {
        return $this->belongsTo(User::class, 'id_tentor', 'id_user');
    }

    public function absensiSiswa() {
        return $this->hasMany(AbsensiSiswa::class, 'id_jadwal', 'id_jadwal');
    }

    public function absensiTentor() {
        return $this->hasOne(AbsensiTentor::class, 'id_jadwal', 'id_jadwal');
    }
}
