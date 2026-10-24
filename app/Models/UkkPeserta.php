<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UkkPeserta extends Model
{
    use HasFactory;

    protected $table = 'ukk_peserta';
    protected $primaryKey = 'id_ukk_peserta';
    protected $guarded = [];

    protected $casts = [
        'nilai_akhir' => 'float',
    ];

    public function kegiatan()
    {
        return $this->belongsTo(UkkKegiatan::class, 'id_ukk_kegiatan', 'id_ukk_kegiatan');
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'id_siswa', 'id_siswa');
    }

    public function nilais()
    {
        return $this->hasMany(UkkNilaiSiswa::class, 'id_ukk_peserta', 'id_ukk_peserta');
    }
}
