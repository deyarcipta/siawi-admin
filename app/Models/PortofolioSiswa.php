<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PortofolioSiswa extends Model
{
    use HasFactory;

    protected $table = 'portofolio_siswa';
    protected $primaryKey = 'id_portofolio';
    protected $guarded = [];

    protected $casts = [
        'tanggal_kegiatan' => 'date',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'id_siswa', 'id_siswa');
    }

    public function kompetensi()
    {
        return $this->belongsTo(Kompetensi::class, 'id_kompetensi', 'id_kompetensi');
    }

    public function verifikator()
    {
        return $this->belongsTo(Guru::class, 'id_verifikator', 'id_guru');
    }

    /**
     * Scope query portofolio siswa sesuai hak akses jurusan user.
     */
    public function scopeForUserJurusan($query, $user = null)
    {
        $user = $user ?: \Illuminate\Support\Facades\Auth::user();
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
