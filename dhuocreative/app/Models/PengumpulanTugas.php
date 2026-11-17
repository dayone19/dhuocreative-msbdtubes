<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengumpulanTugas extends Model
{
    protected $table = 'pengumpulan_tugas';
    protected $primaryKey = 'id_pengumpulan';

    public $timestamps = false;

    protected $fillable = [
        'id_pengumpulan',
        'id_tugas',
        'id_siswa',
        'file_jawaban',
        'waktu_pengumpulan',
        'status'
    ];

    public function siswa() {
        return $this->belongsTo(User::class, 'id_siswa', 'id_user');
    }

    public function tugas() {
        return $this->belongsTo(Tugas::class, 'id_tugas', 'id_tugas');
    }

    public function nilai() {
        return $this->hasOne(Nilai::class, 'id_pengumpulan', 'id_pengumpulan');
    }
}
