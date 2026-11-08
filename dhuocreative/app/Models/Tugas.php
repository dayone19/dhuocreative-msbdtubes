<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tugas extends Model
{
    protected $table = tugas;
    protected $primaryKey = id_tugas;

    public $timestamps = true;
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'id_tugas',
        'id_kelas',
        'id_tentor',
        'judul',
        'deskripsi',
        'deadline',
        'file_tugas',
        'tipe'
    ];

    public function pengumpulanTugas() {
        return $this->hasMany(PengumpulanTugas::class, 'id_tugas', 'id_tugas');
    }

    public function kelas() {
        return $this->belongsTo(Kelas::class, 'id_kelas','id_kelas');
    }

    public function tentor() {
        return $this->belongsTo(User::class, 'id_tentor', 'id_user');
    }
}
