<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AsetLab extends Model
{
    use HasFactory;

    protected $table = 'aset_lab';

    protected $primaryKey = 'id_aset';

    protected $guarded = [];

    protected $casts = [
        'jumlah' => 'integer',
        'stok_tersedia' => 'integer',
        'stok_minimum' => 'integer',
        'harga_satuan' => 'float',
    ];

    public function lab()
    {
        return $this->belongsTo(Lab::class, 'id_lab', 'id_lab');
    }

    public function peminjamans()
    {
        return $this->hasMany(PeminjamanAset::class, 'id_aset', 'id_aset');
    }

    public function pemakaians()
    {
        return $this->hasMany(PemakaianBahanLab::class, 'id_aset', 'id_aset');
    }

    public function perawatans()
    {
        return $this->hasMany(PerawatanAsetLab::class, 'id_aset', 'id_aset');
    }

    /**
     * Scope akses per lab jurusan: Kaprog hanya melihat lab jurusannya, Admin melihat semua.
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

        return $query->whereHas('lab', function ($q) use ($accessibleJurusanIds) {
            $q->whereIn('id_jurusan', $accessibleJurusanIds);
        });
    }

    public function scopeAlat($query)
    {
        return $query->where('kategori', 'alat');
    }

    public function scopeBahan($query)
    {
        return $query->where('kategori', 'bahan_habis_pakai');
    }

    public function scopeStokMenipis($query)
    {
        return $query->where('kategori', 'bahan_habis_pakai')
            ->whereColumn('stok_tersedia', '<=', 'stok_minimum');
    }
}
