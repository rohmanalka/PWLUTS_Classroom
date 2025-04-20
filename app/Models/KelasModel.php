<?php

namespace App\Models;

use App\Models\DosenModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KelasModel extends Model
{
    use HasFactory;

    protected $table = "kelas";
    protected $primaryKey = "id_kelas";
    protected $fillable = ['id_dosen', 'nama_kelas'];

    public function dosen(): BelongsTo
    {
        return $this->belongsTo(DosenModel::class, 'id_dosen', 'id_dosen');
    }

    public function mahasiswas()
    {
        return $this->belongsToMany(MahasiswaModel::class, 'kelas_mahasiswa', 'id_kelas', 'id_mahasiswa')
            ->withPivot('id_kelasmhs');
    }
}
