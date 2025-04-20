<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KelasMahasiswaModel extends Model
{
    protected $table = 'kelas_mahasiswa';
    protected $primaryKey = 'id_kelasmhs';
    public $timestamps = false;

    protected $fillable = ['id_kelas', 'id_mahasiswa'];

    public function kelas()
    {
        return $this->belongsTo(KelasModel::class, 'id_kelas');
    }

    public function mahasiswa()
    {
        return $this->belongsTo(MahasiswaModel::class, 'id_mahasiswa');
    }
}

