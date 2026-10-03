<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nilai extends Model
{
    protected $table = nilai;
    protected $primaryKey = id_nilai;
    protected $fillable = [
        'id_nilai',
        'id_pengumpulan',
        'id_tentor',
        'nilai',
        'feedback',
        'tanggal_nilai'
    ];

    public function pengumpulanTugas() {
        return $this->belongsTo(PengumpulanTugas::class, 'id_pengumpulan', 'id_tugas');
    }

    public function tentor() {
        return $this->belongsTo(User::class, 'id_tentor', 'id_user');
    }
}
