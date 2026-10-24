<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UkkNilaiSiswa extends Model
{
    use HasFactory;

    protected $table = 'ukk_nilai_siswa';
    protected $primaryKey = 'id_ukk_nilai';
    protected $guarded = [];

    protected $casts = [
        'nilai' => 'float',
        'nilai_remedial' => 'float',
        'jadwal_remedial' => 'date',
    ];

    public function peserta()
    {
        return $this->belongsTo(UkkPeserta::class, 'id_ukk_peserta', 'id_ukk_peserta');
    }

    public function unit()
    {
        return $this->belongsTo(UkkUnitKompetensi::class, 'id_ukk_unit', 'id_ukk_unit');
    }

    public function penguji()
    {
        return $this->belongsTo(UkkPenguji::class, 'id_ukk_penguji', 'id_ukk_penguji');
    }
}
