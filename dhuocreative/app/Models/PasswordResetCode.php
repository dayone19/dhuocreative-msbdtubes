<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasswordResertCode extends Model
{
    protected $table = password_reset_code;
    protected $primaryKey = email;

    public $incrementing = false;
    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'email',
        'code_hash',
        'expires_at',
        'attempts',
        'created_at'
    ];

}
