<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModulPraktikVersi extends Model
{
    use HasFactory;

    protected $table = 'modul_praktik_versi';

    protected $primaryKey = 'id_versi';

    protected $guarded = [];

    public function modul()
    {
        return $this->belongsTo(ModulPraktik::class, 'id_modul_praktik', 'id_modul_praktik');
    }

    public function pengubah()
    {
        return $this->belongsTo(Guru::class, 'diubah_oleh', 'id_guru');
    }
}
