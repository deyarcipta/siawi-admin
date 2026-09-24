<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratPeringatan extends Model
{
    use HasFactory;

    protected $table = 'surat_peringatan';

    protected $primaryKey = 'id_sp';

    protected $guarded = [];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'id_siswa', 'id_siswa');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas', 'id_kelas');
    }

    public function getTanggalTerbitFormattedAttribute()
    {
        if (!$this->tanggal_terbit) return '-';
        try {
            return \Carbon\Carbon::parse($this->tanggal_terbit)->locale('id')->translatedFormat('d F Y');
        } catch (\Exception $e) {
            return $this->tanggal_terbit;
        }
    }
}
