<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class JurnalPraktik extends Model
{
    use HasFactory;

    protected $table = 'jurnal_praktik';

    protected $primaryKey = 'id_jurnal_praktik';

    protected $guarded = [];

    protected $casts = [
        'tanggal' => 'date',
        'approved_at' => 'datetime',
    ];

    public function jadwalPraktik()
    {
        return $this->belongsTo(JadwalPraktik::class, 'id_jadwal_praktik', 'id_jadwal_praktik');
    }

    public function approver()
    {
        return $this->belongsTo(Guru::class, 'approved_by', 'id_guru');
    }

    public function modulPraktik()
    {
        return $this->belongsTo(ModulPraktik::class, 'id_modul_praktik', 'id_modul_praktik');
    }

    public function presensis()
    {
        return $this->hasMany(JurnalPraktikPresensi::class, 'id_jurnal_praktik', 'id_jurnal_praktik');
    }

    /**
     * Scope jurnal praktik berdasarkan hak akses user.
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
            return $query->whereHas('jadwalPraktik.lab', function ($q) use ($accessibleJurusanIds) {
                $q->whereIn('id_jurusan', $accessibleJurusanIds);
            });
        }

        // Guru hanya melihat jurnal praktik miliknya
        return $query->whereHas('jadwalPraktik', function ($q) use ($user) {
            $q->where('id_guru', $user->id_guru);
        });
    }
}

