<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasswordResertCode extends Model
{
    protected $table = password_reset_code;
    protected $primaryKey = email;
    protected $fillable = [
        'code_hash',
        'expires_at',
        'attempts',
        'tanggal',
        'created_at'
    ];

}
