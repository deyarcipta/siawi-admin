<?php

namespace App\Exports;

use App\Models\Siswa;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Collection;

class SiswaExport implements FromCollection
{
    public function collection()
    {
        // Ambil data dengan relasi
        $siswa = Siswa::with(['jurusan', 'level', 'kelas'])->get();

        // Map ke bentuk yang ingin ditampilkan
        return $siswa->map(function ($item) {
            return [
                'Id' => $item->id_siswa,
                'Nama' => $item->nama_siswa,
                'NIS' => $item->nis,
                'Jurusan' => $item->jurusan->nama_jurusan ?? '-',
                'Level' => $item->level->nama_level ?? '-',
                'Kelas' => $item->kelas->nama_kelas ?? '-',
                // Tambahkan kolom lain sesuai kebutuhan
            ];
        });
    }
    public function headings(): array
    {
        return [
            'ID',
            'Nama',
            'NIS',
            'Jurusan',
            'Level',
            'Kelas',
        ];
    }
}
