<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kursus extends Model
{
    protected $table = kursus;
    protected $primaryKey = id_kurus;
    protected $fillable = [
        'id_kursus',
        'nama_kursus',
        'deksripsi',
        'durasi',
        'status'
    ];

    public function siswaKursus() {
        return $this->hasMany(TentorKursus::class, 'id_kursus', 'id_kursus');
    }

    public function tentorKursus() {
        return $this->hasMany(TentorKursus::class, 'id_kursus', 'id_kursus');
    }

    public function kelas() {
        return $this->hasMany(Kelas::class, 'id_kursus', 'id_kursus');
    }
}
