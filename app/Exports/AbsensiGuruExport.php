<?php

namespace App\Exports;

use App\Models\AbsensiGuru;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

class AbsensiGuruExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle, ShouldAutoSize, WithEvents
{
    protected $tanggal;
    protected $data;

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
        static $nomor = 0;
        $nomor++;
        return [
            $nomor,  // No
            $absensi->guru->nama_guru,
            $absensi->hari,
            $absensi->tanggal,
            $absensi->jam_masuk ?? '-',
            $absensi->jam_pulang ?? '-',
            $absensi->kehadiran,
        ];
    }

    public function headings(): array
    {
        return [
            ['Rekap Harian Guru SMK WISATA INDONESIA'],
            [], // Judul
            ['No', 'Nama Guru', 'Hari', 'Tanggal', 'Jam Masuk', 'Jam Pulang', 'Kehadiran'] // Header tabel tanpa Keterangan
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $rowCount = count($this->data) + 3; // Jumlah data + 3 (judul + header)
        $borderRange = "A3:G" . $rowCount; // Dinamis berdasarkan jumlah data (Tanpa kolom Keterangan)

        // Merge judul dari A1 sampai G1
        $sheet->mergeCells('A1:G1');

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

    /**
     * Mengatur ukuran kertas menjadi A4 dan orientasi Portrait
     */
    public function registerEvents(): array
    {
        return [
            \Maatwebsite\Excel\Events\AfterSheet::class => function (\Maatwebsite\Excel\Events\AfterSheet $event) {
                $event->sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_PORTRAIT);
                $event->sheet->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_A4);
            },
        ];
    }
}
