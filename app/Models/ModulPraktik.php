<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ModulPraktik extends Model
{
    use HasFactory;

    protected $table = 'modul_praktik';

    protected $primaryKey = 'id_modul_praktik';

    protected $guarded = [];

    public function kompetensi()
    {
        return $this->belongsTo(Kompetensi::class, 'id_kompetensi', 'id_kompetensi');
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'id_guru', 'id_guru');
    }

    public function pembuat()
    {
        return $this->belongsTo(Guru::class, 'id_guru', 'id_guru');
    }

    public function versis()
    {
        return $this->hasMany(ModulPraktikVersi::class, 'id_modul_praktik', 'id_modul_praktik')->orderBy('id_versi', 'desc');
    }

    public function rubriks()
    {
        return $this->hasMany(RubrikPenilaian::class, 'id_modul_praktik', 'id_modul_praktik')->orderBy('urutan', 'asc');
    }

    public function penilaians()
    {
        return $this->hasMany(PenilaianPraktik::class, 'id_modul_praktik', 'id_modul_praktik');
    }

    public function jurnals()
    {
        return $this->hasMany(JurnalPraktik::class, 'id_modul_praktik', 'id_modul_praktik');
    }

    /**
     * Scope modul praktik berdasarkan hak akses jurusan user.
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

        return $query->whereHas('kompetensi', function ($q) use ($accessibleJurusanIds) {
            $q->whereIn('id_jurusan', $accessibleJurusanIds);
        });
    }

    /**
     * Scope hanya modul yang berstatus terbit dan siap digunakan pada jurnal / penilaian.
     */
    public function scopeTerbit($query)
    {
        return $query->where('status', 'terbit');
    }
}
