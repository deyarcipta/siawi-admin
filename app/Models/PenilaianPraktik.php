<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class PenilaianPraktik extends Model
{
    use HasFactory;

    protected $table = 'penilaian_praktik';

    protected $primaryKey = 'id_penilaian_praktik';

    protected $guarded = [];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas', 'id_kelas');
    }

    public function modul()
    {
        return $this->belongsTo(ModulPraktik::class, 'id_modul_praktik', 'id_modul_praktik');
    }

    public function kompetensi()
    {
        return $this->belongsTo(Kompetensi::class, 'id_kompetensi', 'id_kompetensi');
    }

    public function jadwal()
    {
        return $this->belongsTo(JadwalPraktik::class, 'id_jadwal_praktik', 'id_jadwal_praktik');
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'id_guru', 'id_guru');
    }

    public function nilaiSiswas()
    {
        return $this->hasMany(NilaiPraktikSiswa::class, 'id_penilaian_praktik', 'id_penilaian_praktik');
    }

    public function scopeForUserJurusan($query, $user = null)
    {
        $user = $user ?: Auth::user();
        if (!$user) {
            return $query->whereRaw('1 = 0');
        }

        if ($user->hasRole('admin')) {
            return $query;
        }

        $accessibleJurusanIds = Lab::getAccessibleJurusanIds($user);

        return $query->whereHas('kompetensi', function ($q) use ($accessibleJurusanIds) {
            $q->whereIn('id_jurusan', $accessibleJurusanIds);
        });
    }
}
