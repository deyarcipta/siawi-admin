<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class PeminjamanAset extends Model
{
    use HasFactory;

    protected $table = 'peminjaman_aset';

    protected $primaryKey = 'id_peminjaman';

    protected $guarded = [];

    protected $casts = [
        'tanggal_pinjam' => 'date',
        'rencana_kembali' => 'date',
        'tanggal_kembali' => 'date',
        'jumlah' => 'integer',
    ];

    public function aset()
    {
        return $this->belongsTo(AsetLab::class, 'id_aset', 'id_aset');
    }

    public function peminjamGuru()
    {
        return $this->belongsTo(Guru::class, 'id_peminjam_guru', 'id_guru');
    }

    public function peminjamSiswa()
    {
        return $this->belongsTo(Siswa::class, 'id_peminjam_siswa', 'id_siswa');
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

    public function scopeAktif($query)
    {
        return $query->whereIn('status', ['dipinjam', 'terlambat']);
    }
}
