<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbsensiPkl extends Model
{
    use HasFactory;

    protected $table = 'absensi_pkl';
    protected $primaryKey = 'id_absensi_pkl';
    protected $guarded = [];

    protected $appends = ['foto_masuk_url', 'foto_pulang_url'];

    public function siswaPkl()
    {
        return $this->belongsTo(SiswaPkl::class, 'id_siswa_pkl', 'id_siswa_pkl');
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'id_siswa', 'id_siswa');
    }

    public function perusahaan()
    {
        return $this->belongsTo(Perusahaan::class, 'id_perusahaan', 'id_perusahaan');
    }

    public function getFotoMasukUrlAttribute(): ?string
    {
        return $this->foto_masuk ? asset('storage/absensi_pkl/' . $this->foto_masuk) : null;
    }

    public function getFotoPulangUrlAttribute(): ?string
    {
        return $this->foto_pulang ? asset('storage/absensi_pkl/' . $this->foto_pulang) : null;
    }
}

