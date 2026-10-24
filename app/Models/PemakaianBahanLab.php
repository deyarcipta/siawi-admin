<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class PemakaianBahanLab extends Model
{
    use HasFactory;

    protected $table = 'pemakaian_bahan_lab';

    protected $primaryKey = 'id_pemakaian';

    protected $guarded = [];

    protected $casts = [
        'tanggal_pakai' => 'date',
        'jumlah_pakai' => 'integer',
        'sisa_stok_saat_itu' => 'integer',
    ];

    public function aset()
    {
        return $this->belongsTo(AsetLab::class, 'id_aset', 'id_aset');
    }

    public function jadwalPraktik()
    {
        return $this->belongsTo(JadwalPraktik::class, 'id_jadwal_praktik', 'id_jadwal_praktik');
    }

    public function modulPraktik()
    {
        return $this->belongsTo(ModulPraktik::class, 'id_modul_praktik', 'id_modul_praktik');
    }

    public function penggunaGuru()
    {
        return $this->belongsTo(Guru::class, 'id_pengguna_guru', 'id_guru');
    }

    public function petugas()
    {
        return $this->belongsTo(Guru::class, 'id_petugas', 'id_guru');
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

        return $query->whereHas('aset.lab', function ($q) use ($accessibleJurusanIds) {
            $q->whereIn('id_jurusan', $accessibleJurusanIds);
        });
    }
}
