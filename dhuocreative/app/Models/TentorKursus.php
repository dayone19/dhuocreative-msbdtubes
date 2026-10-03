<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TentorKursus extends Model
{
    protected $table = tentor_kursus;
    protected $primaryKey = id_tentor_kursus;
    protected $fillable = [
        'id_tentor_kursus',
        'id_tentor',
        'id_kursus',
        'tanggal_daftar'
    ];

    public function tentor() {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function Kursus() {
        return $this->belongsTo(Kursus::class, 'id_kursus', 'id_kursus');
    }
}
