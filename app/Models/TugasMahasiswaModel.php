<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TugasMahasiswaModel extends Model
{
    use HasFactory;

    protected $table = 'tugas_mahasiswa';
    protected $primaryKey = "id_tugasmhs";
    protected $fillable = [
        'id_tugas',
        'id_mahasiswa',
        'status'
    ];

    const STATUS_BELUM = 'belum_dikumpulkan';
    const STATUS_SUDAH = 'sudah_dikumpulkan';
    const STATUS_TELAT = 'telat';

    public static function getStatusOptions()
    {
        return [
            self::STATUS_BELUM => 'Belum Dikumpulkan',
            self::STATUS_SUDAH => 'Sudah Dikumpulkan',
            self::STATUS_TELAT => 'Telat',
        ];
    }

    public function mahasiswa()
    {
        return $this->belongsTo(MahasiswaModel::class, 'id_mahasiswa', 'id_mahasiswa');
    }

    public function tugas()
    {
        return $this->belongsTo(TugasModel::class, 'id_tugas', 'id_tugas');
    }
}
