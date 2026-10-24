<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class PerawatanAsetLab extends Model
{
    use HasFactory;

    protected $table = 'perawatan_aset_lab';

    protected $primaryKey = 'id_perawatan';

    protected $guarded = [];

    protected $casts = [
        'tanggal_jadwal' => 'date',
        'tanggal_selesai' => 'date',
        'biaya' => 'float',
    ];

    public function aset()
    {
        return $this->belongsTo(AsetLab::class, 'id_aset', 'id_aset');
    }

    public function pelapor()
    {
        return $this->belongsTo(Guru::class, 'id_pelapor', 'id_guru');
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
        return $query->whereIn('status', ['terjadwal', 'dalam_pengerjaan']);
    }
}
