<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

trait FilterByJurusan
{
    /**
     * Scope query berdasarkan hak akses jurusan pengguna login.
     * Admin: melihat semua data.
     * Kaprog & Guru Jurusan: dibatasi jurusan yang ditugaskan pada pivot guru_jurusan.
     * Guru Mapel: dibatasi jurusan kelas tempat mengajar atau penugasan.
     */
    public function scopeForUserJurusan($query, $user = null, string $column = 'id_jurusan')
    {
        $user = $user ?: Auth::user();
        if (!$user) {
            return $query->whereRaw('1 = 0');
        }

        if ($user->hasRole('admin')) {
            return $query;
        }

        $accessibleIds = static::getAccessibleJurusanIds($user);

        if (empty($accessibleIds)) {
            return $query->whereRaw('1 = 0');
        }

        $targetColumn = str_contains($column, '.') ? $column : $this->getTable() . '.' . $column;
        return $query->whereIn($targetColumn, $accessibleIds);
    }

    /**
     * Dapatkan daftar id_jurusan yang dapat diakses oleh user.
     */
    public static function getAccessibleJurusanIds($user = null): array
    {
        $user = $user ?: Auth::user();
        if (!$user) {
            return [];
        }

        if ($user->hasRole('admin')) {
            return DB::table('jurusan')->pluck('id_jurusan')->toArray();
        }

        $ids = [];

        // 1. Ambil dari pivot guru_jurusan (penugasan Kaprog / Guru Jurusan)
        $pivotIds = DB::table('guru_jurusan')
            ->where('id_guru', $user->id_guru)
            ->pluck('id_jurusan')
            ->toArray();
        $ids = array_merge($ids, $pivotIds);

        // 2. Jika guru mengajar di jadwal kelas tertentu, sertakan jurusan dari kelas tersebut
        try {
            $jadwalJurusanIds = DB::table('jadwal_mapel')
                ->join('kelas', 'jadwal_mapel.id_kelas', '=', 'kelas.id_kelas')
                ->join('jurusan', 'kelas.kode_jurusan', '=', 'jurusan.kode_jurusan')
                ->where('jadwal_mapel.id_guru', $user->id_guru)
                ->pluck('jurusan.id_jurusan')
                ->toArray();
            $ids = array_merge($ids, $jadwalJurusanIds);
        } catch (\Throwable $e) {
            // Abaikan jika jadwal belum diatur
        }

        return array_values(array_unique(array_filter($ids)));
    }
}
