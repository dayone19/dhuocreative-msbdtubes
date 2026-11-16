<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    protected $table = 'kelas';
    protected $primaryKey = 'id_kelas';
    public $incrementing = false;

    protected $fillable = [
        'id_kelas',
        'id_kursus',
        'status',
        'kelas',
        'id_siswa',
    ];

    // Relasi ke Kursus
    public function kursus()
    {
        return $this->belongsTo(Kursus::class, 'id_kursus', 'id_kursus');
    }

    // Relasi ke Siswa
    public function siswa()
    {
        return $this->belongsTo(User::class, 'id_siswa', 'id_user');
    }

    // Relasi ke Jadwal
    public function jadwal()
    {
        return $this->hasMany(Jadwal::class, 'id_kelas', 'id_kelas');
    }

    public function tugas()
    {
        return $this->hasMany(Tugas::class, 'id_kelas', 'id_kelas');
    }
}
