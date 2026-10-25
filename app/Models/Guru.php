<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticableTrait;

class Guru extends Model implements Authenticatable
{
    use AuthenticableTrait;
    
    use HasFactory;

    protected $table = 'guru';

    protected $guarded = [];

    protected $primaryKey = 'id_guru';

    protected $casts = [
        'roles' => 'array',
    ];

    protected $_cachedRolesList = null;

    public function absensi_guru()
    {
        return $this->hasMany(AbsensiGuru::class, 'id_guru', 'id_guru'); 
    }

    public function jurnalMengajar()
    {
        return $this->hasMany(JurnalMengajar::class, 'id_guru', 'id_guru');
    }

    public function kelasWali()
    {
        return $this->hasOne(Kelas::class, 'id_guru', 'id_guru');
    }

    public function jadwalMapel()
    {
        return $this->hasMany(JadwalMapel::class, 'id_guru', 'id_guru');
    }

    public function guruPiket()
    {
        return $this->hasMany(GuruPiket::class, 'id_guru', 'id_guru');
    }

    public function piketPembiasaanPagi()
    {
        return $this->hasMany(PiketPembiasaanPagi::class, 'id_guru', 'id_guru');
    }

    /**
     * Get list of all roles held by the teacher (combining primary role, roles array, and wali kelas)
     */
    public function getRolesListAttribute(): array
    {
        if ($this->_cachedRolesList !== null) {
            return $this->_cachedRolesList;
        }

        $roleList = [];

        if (!empty($this->roles)) {
            $parsed = is_array($this->roles) ? $this->roles : json_decode($this->roles, true);
            if (is_array($parsed)) {
                $roleList = array_merge($roleList, $parsed);
            }
        }

        if (!empty($this->role)) {
            $roleList[] = $this->role;
        }

        try {
            if ($this->kelasWali()->exists()) {
                $roleList[] = 'wali_kelas';
            }
        } catch (\Throwable $e) {
            // Ignore during migrations or unmigrated tests
        }

        $this->_cachedRolesList = array_values(array_unique(array_filter($roleList)));
        return $this->_cachedRolesList;
    }

    /**
     * Check if teacher has a specific role.
     */
    public function hasRole(string $role): bool
    {
        if ($this->role === 'admin' || in_array('admin', $this->roles_list)) {
            return true;
        }

        return in_array($role, $this->roles_list);
    }

    /**
     * Check if teacher has at least one of the specified roles.
     */
    public function hasAnyRole($roles): bool
    {
        if ($this->role === 'admin' || in_array('admin', $this->roles_list)) {
            return true;
        }

        if (is_string($roles)) {
            $roles = [$roles];
        }

        return count(array_intersect($roles, $this->roles_list)) > 0;
    }

    /**
     * Get the highest / primary displayed role based on school structural hierarchy.
     */
    public function getHighestRoleAttribute(): string
    {
        $rolePriority = [
            'admin' => 1,
            'kurikulum' => 2,
            'kesiswaan' => 3,
            'kaprog' => 4,
            'hubin' => 5,
            'keuangan' => 6,
            'tata_usaha' => 7,
            'wali_kelas' => 8,
            'guru_jurusan' => 9,
            'guru' => 10,
            'staff' => 11,
        ];

        $roles = $this->roles_list;
        if (empty($roles)) {
            return $this->role ?? 'guru';
        }

        usort($roles, function ($a, $b) use ($rolePriority) {
            $pA = $rolePriority[$a] ?? 99;
            $pB = $rolePriority[$b] ?? 99;
            return $pA <=> $pB;
        });

        return $roles[0];
    }

    /**
     * Get human-friendly label for the highest role.
     */
    public function getHighestRoleLabelAttribute(): string
    {
        $labels = [
            'admin' => 'Admin',
            'kurikulum' => 'Kurikulum',
            'kesiswaan' => 'Kesiswaan',
            'kaprog' => 'Kaprog',
            'hubin' => 'Hubin',
            'keuangan' => 'Keuangan',
            'tata_usaha' => 'Tata Usaha',
            'wali_kelas' => 'Wali Kelas',
            'guru_jurusan' => 'Guru Jurusan',
            'guru' => 'Guru',
            'staff' => 'Staff',
        ];

        $highest = $this->highest_role;
        return $labels[$highest] ?? ucfirst(str_replace('_', ' ', $highest));
    }

    /**
     * Check if teacher acts strictly as a Wali Kelas for student/attendance/academic modules
     * (has role wali_kelas or assigned as kelasWali, and NOT having any of the bypass roles).
     */
    public function isWaliKelasStrict(array $bypassRoles = ['admin', 'kesiswaan', 'kurikulum']): bool
    {
        if ($this->hasAnyRole($bypassRoles)) {
            return false;
        }

        return $this->hasRole('wali_kelas') || $this->kelasWali()->exists();
    }

    /**
     * Check if teacher acts strictly as a Wali Kelas for Keuangan / Tagihan
     * (has role wali_kelas or assigned as kelasWali, but NOT admin or keuangan).
     */
    public function isWaliKelasKeuanganStrict(): bool
    {
        return $this->isWaliKelasStrict(['admin', 'keuangan']);
    }

    /**
     * Get list of kelas IDs that this teacher manages as Wali Kelas.
     */
    public function getKelasWaliIds(): array
    {
        return Kelas::where('id_guru', $this->id_guru)->pluck('id_kelas')->toArray();
    }

    /**
     * Relasi ke jurusan melalui tabel pivot guru_jurusan.
     */
    public function jurusans()
    {
        return $this->belongsToMany(Jurusan::class, 'guru_jurusan', 'id_guru', 'id_jurusan')
            ->withPivot('role')
            ->withTimestamps();
    }

    /**
     * Relasi ke jurusan khusus untuk tugas Kaprog.
     */
    public function kaprogJurusan()
    {
        return $this->belongsToMany(Jurusan::class, 'guru_jurusan', 'id_guru', 'id_jurusan')
            ->wherePivot('role', 'kaprog')
            ->withTimestamps();
    }

    /**
     * Cek apakah guru memegang amanah Kaprog.
     */
    public function isKaprog(): bool
    {
        return $this->hasRole('kaprog');
    }

    /**
     * Cek apakah guru mengajar sebagai Guru Jurusan.
     */
    public function isGuruJurusan(): bool
    {
        return $this->hasRole('guru_jurusan');
    }

    /**
     * Dapatkan objek Jurusan utama yang dipegang sebagai Kaprog.
     */
    public function getJurusanKaprogAttribute()
    {
        return $this->kaprogJurusan()->first();
    }

    /**
     * Scope query untuk mencari guru yang memiliki role tertentu
     * (baik di kolom 'role' utama maupun di array/json kolom 'roles').
     */
    public function scopeHavingRole($query, string $role)
    {
        return $query->where(function ($q) use ($role) {
            $q->where('role', $role)
              ->orWhereJsonContains('roles', $role)
              ->orWhere('roles', 'like', '%"' . $role . '"%');
        });
    }
}

