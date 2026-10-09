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
            'hubin' => 4,
            'keuangan' => 5,
            'tata_usaha' => 6,
            'wali_kelas' => 7,
            'guru' => 8,
            'staff' => 9,
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
            'hubin' => 'Hubin',
            'keuangan' => 'Keuangan',
            'tata_usaha' => 'Tata Usaha',
            'wali_kelas' => 'Wali Kelas',
            'guru' => 'Guru',
            'staff' => 'Staff',
        ];

        $highest = $this->highest_role;
        return $labels[$highest] ?? ucfirst(str_replace('_', ' ', $highest));
    }
}
