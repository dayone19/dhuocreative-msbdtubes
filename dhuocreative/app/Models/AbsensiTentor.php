<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AbsensiTentor extends Model
{
    protected $table = absensi_tentor;
    protected $primaryKey = id_absensi_tentor;
    protected $fillable = [
        'id_absensi_tentor',
        'id_jadwal',
        'waktu_absen',
        'status'
    ];

    public function jadwal() {
        return $this->belongsTo(Jadwal::class, 'id_jadwal', 'id_jadwal');
    }
}
