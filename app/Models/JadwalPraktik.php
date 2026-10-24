<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class JadwalPraktik extends Model
{
    use HasFactory;

    protected $table = 'jadwal_praktik';

    protected $primaryKey = 'id_jadwal_praktik';

    protected $guarded = [];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas', 'id_kelas');
    }

    public function lab()
    {
        return $this->belongsTo(Lab::class, 'id_lab', 'id_lab');
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'id_guru', 'id_guru');
    }

    public function mapel()
    {
        return $this->belongsTo(Mapel::class, 'id_mapel', 'id_mapel');
    }

    public function jurnalPraktik()
    {
        return $this->hasMany(JurnalPraktik::class, 'id_jadwal_praktik', 'id_jadwal_praktik');
    }

    /**
     * Scope jadwal praktik sesuai hak akses user (Admin, Kaprog, Guru).
     */
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

        if ($user->hasRole('kaprog')) {
            return $query->whereHas('lab', function ($q) use ($accessibleJurusanIds) {
                $q->whereIn('id_jurusan', $accessibleJurusanIds);
            });
        }

        // Guru Mapel / Guru Jurusan melihat jadwal miliknya atau lab di jurusannya
        return $query->where(function ($q) use ($user, $accessibleJurusanIds) {
            $q->where('id_guru', $user->id_guru);
            if (!empty($accessibleJurusanIds)) {
                $q->orWhereHas('lab', function ($sub) use ($accessibleJurusanIds) {
                    $sub->whereIn('id_jurusan', $accessibleJurusanIds);
                });
            }
        });
    }
}
