<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kursus extends Model
{
    protected $table = 'kursus';
    protected $primaryKey = 'id_kursus';
    public $incrementing = false;

    public $timestamps = true;
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    protected $fillable = [
        'id_kursus',
        'nama_kursus',
        'deskripsi',
        'durasi',
        'jumlah_pertemuan',
        'harga',
        'status',
        'created_at',
        'updated_at',
    ];

    public function kelas()
    {
        return $this->hasMany(Kelas::class, 'id_kursus', 'id_kursus');
    }

    public function tentorKursus()
    {
        return $this->hasMany(TentorKursus::class, 'id_kursus', 'id_kursus');
    }
}
