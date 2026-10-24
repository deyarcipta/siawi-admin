<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UkkUnitKompetensi extends Model
{
    use HasFactory;

    protected $table = 'ukk_unit_kompetensi';
    protected $primaryKey = 'id_ukk_unit';
    protected $guarded = [];

    protected $casts = [
        'bobot' => 'float',
        'kkm' => 'float',
    ];

    public function kegiatan()
    {
        return $this->belongsTo(UkkKegiatan::class, 'id_ukk_kegiatan', 'id_ukk_kegiatan');
    }

    public function kompetensi()
    {
        return $this->belongsTo(Kompetensi::class, 'id_kompetensi', 'id_kompetensi');
    }

    public function nilaiSiswas()
    {
        return $this->hasMany(UkkNilaiSiswa::class, 'id_ukk_unit', 'id_ukk_unit');
    }
}
