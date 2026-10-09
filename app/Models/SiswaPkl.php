<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiswaPkl extends Model
{
    use HasFactory;

    protected $table = 'siswa_pkl';

    protected $guarded = [];

    protected $primaryKey = 'id_siswa_pkl';


    public function siswa()
    {
        return $this->belongsTo('App\Models\Siswa', 'id_siswa', 'id_siswa');
    }

    public function kelas()
    {
        return $this->belongsTo('App\Models\Kelas', 'id_kelas', 'id_kelas');
    }

    public function perusahaan()
    {
        return $this->belongsTo('App\Models\Perusahaan', 'id_perusahaan', 'id_perusahaan');
    }

    /**
     * Dapatkan status operasional PKL secara dinamis berdasarkan tanggal hari ini.
     * Output: 'belum_mulai' | 'aktif' | 'selesai'
     */
    public function getStatusPklAttribute(): string
    {
        if (strtolower($this->status) === 'selesai') {
            return 'selesai';
        }

        $today = now()->format('Y-m-d');
        $tglMulai = $this->tanggal_mulai ? date('Y-m-d', strtotime($this->tanggal_mulai)) : null;
        $tglSelesai = $this->tanggal_selesai ? date('Y-m-d', strtotime($this->tanggal_selesai)) : null;

        if ($tglMulai && $today < $tglMulai) {
            return 'belum_mulai';
        }

        if ($tglSelesai && $today > $tglSelesai) {
            return 'selesai';
        }

        return 'aktif';
    }

    /**
     * Label teks deskriptif untuk status PKL.
     */
    public function getStatusLabelAttribute(): string
    {
        switch ($this->status_pkl) {
            case 'belum_mulai':
                return 'Sudah Ditempatkan';
            case 'aktif':
                return 'Sedang PKL';
            case 'selesai':
                return 'Selesai';
            default:
                return 'Sudah Ditempatkan';
        }
    }
}
