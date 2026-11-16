<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kursus extends Model
{
    protected $table = 'kursus';
    protected $primaryKey = 'id_kursus';

    public $timestamps = false;
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    protected $fillable = [
        'nama_kursus',
        'deskripsi',
        'durasi',
        'status',
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
