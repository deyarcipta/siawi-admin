<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    protected $table = 'kelas';

    protected $guarded = [];

    protected $primaryKey = 'id_kelas';

    public function absensis()
    {
        return $this->belongsTo('App\Models\Absensi', 'id_kelas', 'id_kelas');
    }

    public function siswa()
    {
        return $this->hasMany(Siswa::class, 'id_kelas')->orderBy('nama_siswa', 'asc');
    }

    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class, 'kode_jurusan', 'kode_jurusan'); 
    }

    public function level()
    {
        return $this->belongsTo(Level::class, 'kode_level', 'kode_level'); 
    }

    public function jurnalMengajar()
    {
        return $this->hasMany(JurnalMengajar::class, 'id_kelas', 'id_kelas');
    }

    public function waliKelas()
    {
        return $this->belongsTo(Guru::class, 'id_guru', 'id_guru');
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'id_guru', 'id_guru');
    }

    /**
     * Scope query kelas sesuai hak akses jurusan user.
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

        return $query->whereHas('jurusan', function ($q) use ($accessibleJurusanIds) {
            $q->whereIn('id_jurusan', $accessibleJurusanIds);
        });
    }
}
