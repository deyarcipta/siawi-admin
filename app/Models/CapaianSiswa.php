<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class CapaianSiswa extends Model
{
    use HasFactory;

    protected $table = 'capaian_siswa';

    protected $primaryKey = 'id_capaian';

    protected $guarded = [];

    protected $casts = [
        'nilai' => 'float',
        'kkm' => 'float',
        'tanggal' => 'date',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'id_siswa', 'id_siswa');
    }

    public function kompetensi()
    {
        return $this->belongsTo(Kompetensi::class, 'id_kompetensi', 'id_kompetensi');
    }

    public function penilai()
    {
        return $this->belongsTo(Guru::class, 'id_penilai', 'id_guru');
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

        $accessibleJurusanIds = Jurusan::getAccessibleJurusanIds($user);
        if (empty($accessibleJurusanIds)) {
            return $query->whereRaw('1 = 0');
        }

        return $query->whereHas('siswa', function ($sub) use ($accessibleJurusanIds) {
            $sub->whereIn('id_jurusan', $accessibleJurusanIds)
                ->orWhereHas('kelas.jurusan', function ($k) use ($accessibleJurusanIds) {
                    $k->whereIn('id_jurusan', $accessibleJurusanIds);
                });
        });
    }
}
