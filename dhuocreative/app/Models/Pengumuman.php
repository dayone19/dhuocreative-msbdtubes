<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengumuman extends Model
{
    protected $table = pengumuman;
    protected $primaryKey = id_pengumuman;
    protected $fillable = [
        'id_pengumuman',
        'id_user',
        'judul',
        'isi',
        'tanggal',
        'status'
    ];

    public function operator() {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}
