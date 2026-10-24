<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NilaiPraktikSiswa extends Model
{
    use HasFactory;

    protected $table = 'nilai_praktik_siswa';

    protected $primaryKey = 'id_nilai_praktik';

    protected $guarded = [];

    protected $casts = [
        'nilai_per_indikator' => 'array',
        'is_remedial' => 'boolean',
        'nilai_awal' => 'float',
        'nilai_remedial' => 'float',
        'nilai_akhir' => 'float',
    ];

    public function penilaian()
    {
        return $this->belongsTo(PenilaianPraktik::class, 'id_penilaian_praktik', 'id_penilaian_praktik');
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'id_siswa', 'id_siswa');
    }
}
