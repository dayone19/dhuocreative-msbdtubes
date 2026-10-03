<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sertifikat extends Model
{
    protected $table = sertifikat;
    protected $primaryKey = id_sertifikat;
    protected $fillable = [
        'id_sertifikat',
        'id_siswa',
        'id_tentor',
        'nama_ujian',
        'tanggal_ujian',
        'file_sertifikat',
        'tanggal_upload'
    ];

    public function siswa() {
        return $this->belongsTo(User::class, 'id_siswa', 'id_user');
    }

    public function tentor() {
        return $this->belongsTo(User::class, 'id_tentor', 'id_user');
    }

}
