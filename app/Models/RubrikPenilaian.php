<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RubrikPenilaian extends Model
{
    use HasFactory;

    protected $table = 'rubrik_penilaian';

    protected $primaryKey = 'id_rubrik';

    protected $guarded = [];

    public function modul()
    {
        return $this->belongsTo(ModulPraktik::class, 'id_modul_praktik', 'id_modul_praktik');
    }

    public function kompetensi()
    {
        return $this->belongsTo(Kompetensi::class, 'id_kompetensi', 'id_kompetensi');
    }
}
