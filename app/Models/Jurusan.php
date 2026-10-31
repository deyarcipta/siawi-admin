<?php

namespace App\Models;

use App\Traits\FilterByJurusan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jurusan extends Model
{
    use HasFactory, FilterByJurusan;

    protected $table = 'jurusan';

    protected $guarded = [];
    
    protected $primaryKey = 'id_jurusan';

    protected $casts = [
        'aktif' => 'boolean',
    ];

    public function labs()
    {
        return $this->hasMany(Lab::class, 'id_jurusan', 'id_jurusan');
    }

    public function kompetensis()
    {
        return $this->hasMany(Kompetensi::class, 'id_jurusan', 'id_jurusan');
    }

    public function kelas()
    {
        return $this->hasMany(Kelas::class, 'kode_jurusan', 'kode_jurusan');
    }

    public function gurus()
    {
        return $this->belongsToMany(Guru::class, 'guru_jurusan', 'id_jurusan', 'id_guru')
            ->withPivot('role')
            ->withTimestamps();
    }

    public function kaprog()
    {
        return $this->belongsToMany(Guru::class, 'guru_jurusan', 'id_jurusan', 'id_guru')
            ->wherePivot('role', 'kaprog')
            ->withTimestamps();
    }

    /**
     * Nama jurusan ringkas (menggunakan kode_jurusan jika nama terlalu panjang agar tabel rapi)
     */
    public function getNamaSingkatAttribute(): string
    {
        if (!empty($this->kode_jurusan) && mb_strlen($this->nama_jurusan ?? '') > 18) {
            return $this->kode_jurusan;
        }
        return $this->nama_jurusan ?? '-';
    }

    public function getModulKejuruanAttribute($value): array
    {
        $default = [
            'tkk' => true,
            'ukk' => true,
            'praktik' => true,
            'portofolio' => true,
            'lab' => true,
        ];

        if (!$value) {
            return $default;
        }

        $decoded = json_decode($value, true);
        if (!is_array($decoded)) {
            return $default;
        }

        return array_merge($default, $decoded);
    }

    public function isModulActive(string $module): bool
    {
        $modules = $this->modul_kejuruan;
        return !empty($modules[$module]);
    }

    public static function userCanAccessModul($user, string $module): bool
    {
        if (!$user) {
            return false;
        }

        if ($user->hasRole('admin')) {
            $activeJurusans = static::where('aktif', true)->get();
            if ($activeJurusans->isEmpty()) {
                return true;
            }
            return $activeJurusans->contains(fn($j) => $j->isModulActive($module));
        }

        $accessibleIds = static::getAccessibleJurusanIds($user);
        if (empty($accessibleIds)) {
            return false;
        }

        return static::whereIn('id_jurusan', $accessibleIds)
            ->where('aktif', true)
            ->get()
            ->contains(fn($j) => $j->isModulActive($module));
    }
}

