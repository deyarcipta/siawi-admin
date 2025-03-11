<?php

namespace App\Exports;

use App\Models\AbsensiGuru;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AbsensiGuruExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle, ShouldAutoSize
{
    public function __construct($tanggal)
    {
        $this->tanggal = $tanggal;
        $this->data = AbsensiGuru::with('guru')
            ->whereDate('tanggal', $tanggal) // Filter hanya untuk tanggal yang dipilih
            ->get();
    }

    public function collection()
    {
        return $this->data;
    }

    public function map($absensi): array
    {
        return [
            $absensi->id_absenguru,  // No
            $absensi->guru->nama_guru,
            $absensi->hari,
            $absensi->tanggal,
            $absensi->jam_masuk ?? '-',
            $absensi->jam_pulang ?? '-',
            $absensi->kehadiran,
            $absensi->keterangan,
        ];
    }

    public function headings(): array
    {
        return [
            ['Rekap Harian Guru SMK WISATA INDONESIA'],
            [], // Judul
            ['No', 'Nama Guru',  'Hari', 'Tanggal', 'Jam Masuk', 'Jam Pulang', 'Kehadiran', 'Keterangan'] // Header tabel
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $rowCount = count($this->data) + 3; // Jumlah data + 3 (judul + header)
        $borderRange = "A3:H" . $rowCount; // Dinamis berdasarkan jumlah data
        // Merge judul dari A1 sampai H1
        $sheet->mergeCells('A1:H1');
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 14],
                'alignment' => ['horizontal' => 'center'],
            ], // Judul
            3 => [
                'font' => ['bold' => true],
                'alignment' => ['horizontal' => 'center'],
                'borders' => ['allBorders' => ['borderStyle' => 'thin']]
            ], // Header
            $borderRange => [
                'borders' => ['allBorders' => ['borderStyle' => 'thin']]
            ], 
        ];
    }

    public function title(): string
    {
        return 'Absensi Guru';
    }
}
