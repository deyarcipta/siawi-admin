<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class AbsensiExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle, WithColumnWidths, WithEvents
{
    protected $siswa;
    protected $absensiSiswa;
    protected $countMasuk;
    protected $countSakit;
    protected $countIzin;
    protected $countAlfa;
    protected $kelasNama;
    protected $tanggalAwal;
    protected $tanggalAkhir;
    protected $waliKelas;
    protected $rowNumber = 0;

    public function __construct($siswa, $absensiSiswa, $countMasuk, $countSakit, $countIzin, $countAlfa, $kelasNama, $tanggalAwal = null, $tanggalAkhir = null, $waliKelas = null)
    {
        $this->siswa = $siswa;
        $this->absensiSiswa = $absensiSiswa;
        $this->countMasuk = $countMasuk;
        $this->countSakit = $countSakit;
        $this->countIzin = $countIzin;
        $this->countAlfa = $countAlfa;
        $this->kelasNama = $kelasNama;
        $this->tanggalAwal = $tanggalAwal;
        $this->tanggalAkhir = $tanggalAkhir;
        $this->waliKelas = $waliKelas;
    }

    public function collection()
    {
        return $this->siswa;
    }

    public function headings(): array
    {
        $periodeText = ($this->tanggalAwal && $this->tanggalAkhir)
            ? 'PERIODE: ' . \Carbon\Carbon::parse($this->tanggalAwal)->translatedFormat('d F Y') . ' s/d ' . \Carbon\Carbon::parse($this->tanggalAkhir)->translatedFormat('d F Y')
            : 'REKAPITULASI PRESENSI';

        return [
            ['REKAPITULASI PRESENSI KEHADIRAN SISWA'],
            ['KELAS: ' . strtoupper($this->kelasNama) . ($this->waliKelas ? '  |  WALI KELAS: ' . strtoupper($this->waliKelas) : '')],
            [$periodeText],
            [],
            [
                'No',
                'Nama Siswa',
                'Total Hari Absen',
                'Hadir',
                'Sakit (S)',
                'Izin (I)',
                'Alfa (A)',
                'Total S / I / A',
                'Persentase Kehadiran'
            ],
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Styling Judul Header (Baris 1 - 3)
        $sheet->mergeCells('A1:I1');
        $sheet->mergeCells('A2:I2');
        $sheet->mergeCells('A3:I3');

        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FF1E3A8A'));
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(11)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FF334155'));
        $sheet->getStyle('A3')->getFont()->setBold(true)->setSize(10)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FF64748B'));

        $sheet->getStyle('A1:A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Header Tabel di Baris 5
        $headerRange = 'A5:I5';
        $sheet->getStyle($headerRange)->getFont()->setBold(true)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FFFFFFFF'));
        $sheet->getStyle($headerRange)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF1E3A8A');
        $sheet->getStyle($headerRange)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER)->setWrapText(true);
        $sheet->getRowDimension(5)->setRowHeight(30);

        // Border Tabel
        $rowCount = count($this->siswa) + 5;
        $tableRange = "A5:I{$rowCount}";
        $sheet->getStyle($tableRange)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FFCBD5E1'));

        // Alignments Kolom Data
        $sheet->getStyle("A6:A{$rowCount}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("B6:B{$rowCount}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle("C6:I{$rowCount}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    }

    /**
     * Menentukan lebar kolom presisi (kolom D, E, F, G ~70px).
     */
    public function columnWidths(): array
    {
        return [
            'A' => 6,   // No
            'B' => 34,  // Nama Siswa
            'C' => 12,  // Total Hari
            'D' => 10,  // Hadir (~70px)
            'E' => 10,  // Sakit (S) (~70px)
            'F' => 10,  // Izin (I) (~70px)
            'G' => 10,  // Alfa (A) (~70px)
            'H' => 14,  // Total S / I / A
            'I' => 18,  // Persentase Kehadiran
        ];
    }

    public function map($data): array
    {
        $this->rowNumber++;
        $id = $data->id_siswa;
        $totalAbsen = $this->absensiSiswa[$id] ?? 0;
        $masuk = $this->countMasuk[$id] ?? 0;
        $sakit = $this->countSakit[$id] ?? 0;
        $izin = $this->countIzin[$id] ?? 0;
        $alfa = $this->countAlfa[$id] ?? 0;
        $totalTidakHadir = $sakit + $izin + $alfa;
        $presentase = $totalAbsen > 0 ? ($masuk / $totalAbsen) * 100 : 0;

        return [
            $this->rowNumber,
            strtoupper($data->nama_siswa),
            $totalAbsen > 0 ? $totalAbsen : 0,
            $masuk > 0 ? $masuk : 0,
            $sakit > 0 ? $sakit : '-',
            $izin > 0 ? $izin : '-',
            $alfa > 0 ? $alfa : '-',
            $totalTidakHadir > 0 ? $totalTidakHadir : '-',
            round($presentase, 1) . '%',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;
                $rowCount = count($this->siswa) + 5;

                // Pewarnaan Badge Persentase
                for ($row = 6; $row <= $rowCount; $row++) {
                    $sheet->getRowDimension($row)->setRowHeight(20);
                    $presentaseCell = "I{$row}";
                    $cell = $sheet->getCell($presentaseCell);
                    $val = $cell ? $cell->getValue() : '0';
                    $presentaseValue = (float) str_replace('%', '', (string) $val);

                    if ($presentaseValue < 90) {
                        // Merah lembut jika < 90%
                        $sheet->getStyle($presentaseCell)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFF8D7DA');
                        $sheet->getStyle($presentaseCell)->getFont()->setBold(true)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FF721C24'));
                    } else {
                        // Hijau lembut jika >= 90%
                        $sheet->getStyle($presentaseCell)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFD4EDDA');
                        $sheet->getStyle($presentaseCell)->getFont()->setBold(true)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FF155724'));
                    }
                }

                // Tanda Tangan Wali Kelas di Bawah Tabel
                $signRowStart = $rowCount + 2;
                $sheet->setCellValue("G{$signRowStart}", 'Jakarta, ' . \Carbon\Carbon::now()->translatedFormat('d F Y'));
                $sheet->setCellValue("G" . ($signRowStart + 1), 'Wali Kelas ' . $this->kelasNama);
                
                $signRowEnd = $signRowStart + 4;
                $sheet->setCellValue("G{$signRowEnd}", $this->waliKelas ? strtoupper($this->waliKelas) : '(...............................................)');
                $sheet->getStyle("G{$signRowEnd}")->getFont()->setBold(true)->setUnderline(true);
            },
        ];
    }

    public function title(): string
    {
        return 'Rekap Presensi';
    }
}
