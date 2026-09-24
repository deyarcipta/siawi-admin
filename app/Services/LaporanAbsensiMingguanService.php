<?php

namespace App\Services;

/**
 * Backward compatibility alias for LaporanAbsensiBulananService
 */
class LaporanAbsensiMingguanService extends LaporanAbsensiBulananService
{
    /**
     * Alias method for hitungRekapBulanan
     */
    public function hitungRekapMingguan(?string $idKelas, string $tanggalMulai, string $tanggalSelesai): array
    {
        return $this->hitungRekapBulanan($idKelas, $tanggalMulai, $tanggalSelesai);
    }
}
