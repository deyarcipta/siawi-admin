<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JurnalPraktikPresensi extends Model
{
    use HasFactory;

    protected $table = 'jurnal_praktik_presensi';
    protected $primaryKey = 'id_presensi_praktik';
    protected $guarded = [];

    public function jurnalPraktik()
    {
        return $this->belongsTo(JurnalPraktik::class, 'id_jurnal_praktik', 'id_jurnal_praktik');
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'id_siswa', 'id_siswa');
    }
}
