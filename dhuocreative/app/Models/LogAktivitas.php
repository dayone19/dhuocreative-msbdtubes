<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogAktivitas extends Model
{
    protected $table = 'log_aktivitas';

    protected $primaryKey = 'id_log';

    public $timestamps = false;

    protected $fillable = [
        'id_user',
        'db_user',
        'aktivitas',
        'nama_tabel',
        'data_lama',
        'data_baru',
        'waktu_aktivitas',
    ];

    protected $casts = [
        'data_lama' => 'array',
        'data_baru' => 'array',
        'waktu_aktivitas' => 'datetime',
    ];
}
