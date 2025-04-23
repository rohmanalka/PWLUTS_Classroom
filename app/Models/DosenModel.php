<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class DosenModel extends Authenticatable
{
    use HasFactory;

    protected $table = "dosen";
    protected $primaryKey = "id_dosen";
    protected $fillable = ['nip', 'nama', 'username', 'password'];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}
