<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TugasModel extends Model
{
    use HasFactory;

    protected $table = 'tugas';
    protected $primaryKey = 'id_tugas';
    protected $fillable = [
        'id_kelas',
        'judul',
        'deskripsi',
        'tanggal_diberikan',
        'deadline',
        'lampiran'
    ];

    // Relasi ke mahasiswa melalui tabel pivot
    public function mahasiswa()
    {
        return $this->belongsToMany(MahasiswaModel::class, 'tugas_mahasiswa', 'id_tugas', 'id_mahasiswa')
            ->withPivot('status')
            ->withTimestamps();
    }

    public function kelas()
    {
        return $this->belongsTo(KelasModel::class, 'id_kelas');
    }   
}
