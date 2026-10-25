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

    public function jadwalMapel()
    {
        return $this->belongsTo(JadwalMapel::class, 'id_jadwal_mapel', 'id_jadwal');
    }

    public function isRutin(): bool
    {
        return ($this->tipe_jadwal ?? 'rutin') === 'rutin';
    }

    public function isInsidental(): bool
    {
        return ($this->tipe_jadwal ?? 'rutin') === 'insidental';
    }

    public function getLabelFrekuensiAttribute(): string
    {
        if ($this->isInsidental()) {
            return 'Praktik Tambahan (' . ($this->tanggal_khusus ? \Carbon\Carbon::parse($this->tanggal_khusus)->translatedFormat('d M Y') : 'Insidental') . ')';
        }

        return match ($this->frekuensi ?? 'setiap_minggu') {
            'minggu_ganjil' => '2 Minggu Sekali (Ganjil)',
            'minggu_genap' => '2 Minggu Sekali (Genap)',
            default => 'Setiap Minggu',
        };
    }

    public function isActiveOnDate($date = null): bool
    {
        $dt = $date ? \Carbon\Carbon::parse($date) : \Carbon\Carbon::now();
        $hariMap = [
            'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu', 'Sunday' => 'Minggu',
        ];
        $namaHari = $hariMap[$dt->format('l')] ?? 'Senin';

        if ($this->isInsidental()) {
            return $this->tanggal_khusus === $dt->toDateString();
        }

        if (strcasecmp($this->hari, $namaHari) !== 0) {
            return false;
        }

        if (($this->frekuensi ?? 'setiap_minggu') === 'setiap_minggu') {
            return true;
        }

        $weekOfMonth = (int) ceil($dt->day / 7);
        $isOdd = ($weekOfMonth % 2 !== 0);

        if ($this->frekuensi === 'minggu_ganjil') {
            return $isOdd;
        }

        if ($this->frekuensi === 'minggu_genap') {
            return !$isOdd;
        }

        return true;
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
