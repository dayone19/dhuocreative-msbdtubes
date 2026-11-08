<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengajuanJadwal extends Model
{
    protected $table = 'pengajuan_jadwal';
    protected $primaryKey = 'id_pengajuan';

    public $timestamps = true;
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'id_pengajuan',
        'id_jadwal_lama',
        'id_kelas',
        'id_tentor',
        'tanggal_baru',
        'jam_mulai',
        'jam_selesai',
        'alasan',
        'status',
    ];

    public function jadwalLama()
    {
        return $this->belongsTo(Jadwal::class, 'id_jadwal_lama', 'id_jadwal');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas', 'id_kelas');
    }

    public function tentor()
    {
        return $this->belongsTo(User::class, 'id_tentor', 'id_user');
    }
}