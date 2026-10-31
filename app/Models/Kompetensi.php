<?php

namespace App\Models;

use App\Traits\FilterByJurusan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kompetensi extends Model
{
    use HasFactory, FilterByJurusan;

    protected $table = 'kompetensi';

    protected $primaryKey = 'id_kompetensi';

    protected $casts = [
        'kkm' => 'float',
    ];

    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class, 'id_jurusan', 'id_jurusan');
    }

    public function modulPraktiks()
    {
        return $this->hasMany(ModulPraktik::class, 'id_kompetensi', 'id_kompetensi');
    }

    public function moduls()
    {
        return $this->belongsToMany(ModulPraktik::class, 'modul_praktik_kompetensi', 'id_kompetensi', 'id_modul_praktik')->withTimestamps();
    }

    public function rubriks()
    {
        return $this->hasMany(RubrikPenilaian::class, 'id_kompetensi', 'id_kompetensi')->orderBy('urutan', 'asc');
    }

    public function capaianSiswas()
    {
        return $this->hasMany(CapaianSiswa::class, 'id_kompetensi', 'id_kompetensi');
    }
}
