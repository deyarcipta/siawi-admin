<?php

namespace App\Models;

use App\Traits\FilterByJurusan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisTkk extends Model
{
    use HasFactory, FilterByJurusan;

    protected $table = 'jenis_tkk';
    protected $primaryKey = 'id_jenis_tkk';
    protected $guarded = [];

    protected $casts = [
        'nomor_klaster' => 'integer',
    ];

    public function getDisplayNamaAttribute(): string
    {
        if ($this->nomor_klaster) {
            return "Klaster #{$this->nomor_klaster}: {$this->nama_tkk}";
        }
        return $this->nama_tkk;
    }

    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class, 'id_jurusan', 'id_jurusan');
    }

    public function syarats()
    {
        return $this->belongsToMany(Kompetensi::class, 'tkk_syarat_kompetensi', 'id_jenis_tkk', 'id_kompetensi')
            ->withTimestamps();
    }

    public function siswaTkks()
    {
        return $this->hasMany(SiswaTkk::class, 'id_jenis_tkk', 'id_jenis_tkk');
    }

    /**
     * Dapatkan id_jurusan yang dapat diakses dan mengaktifkan modul TKK.
     */
    public static function getAccessibleJurusanIds($user = null): array
    {
        $user = $user ?: \Illuminate\Support\Facades\Auth::user();
        if (!$user) {
            return [];
        }

        if ($user->hasRole('admin')) {
            return Jurusan::where('aktif', true)
                ->get()
                ->filter(fn($j) => $j->isModulActive('tkk'))
                ->pluck('id_jurusan')
                ->toArray();
        }

        $baseIds = [];
        $pivotIds = \Illuminate\Support\Facades\DB::table('guru_jurusan')
            ->where('id_guru', $user->id_guru)
            ->pluck('id_jurusan')
            ->toArray();
        $baseIds = array_merge($baseIds, $pivotIds);

        return Jurusan::whereIn('id_jurusan', $baseIds)
            ->where('aktif', true)
            ->get()
            ->filter(fn($j) => $j->isModulActive('tkk'))
            ->pluck('id_jurusan')
            ->toArray();
    }
}
