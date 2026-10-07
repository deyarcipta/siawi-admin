<?php

namespace App\Exports;

use App\Models\Siswa;
use App\Models\Absensi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents; // Pastikan ini ditambahkan
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Events\AfterSheet;

class AbsensiExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle, ShouldAutoSize, WithEvents
{
    protected $siswa;
    protected $absensiSiswa;
    protected $countMasuk;
    protected $countSakit;
    protected $countIzin;
    protected $countAlfa;
    protected $kelasNama;

    public function __construct($siswa, $absensiSiswa, $countMasuk, $countSakit, $countIzin, $countAlfa, $kelasNama)
    {
        $this->siswa = $siswa;
        $this->absensiSiswa = $absensiSiswa;
        $this->countMasuk = $countMasuk;
        $this->countSakit = $countSakit;
        $this->countIzin = $countIzin;
        $this->countAlfa = $countAlfa;
        $this->kelasNama = $kelasNama;
    }

    public function collection()
    {
        return $this->siswa;
    }

    public function headings(): array
    {
        return [
            ['REKAP KEHADIRAN SISWA'],
            ['NAMA KELAS: ' . $this->kelasNama],
            [],
            [
                'No',
                'Nama Siswa',
                'Total Absen',
                'Masuk',
                'S',
                'I',
                'A',
                'Total S,I,A',
                'Presentase'
            ],
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:I1')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $sheet->getStyle('A2:I2')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $sheet->getStyle('A4:I4')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A2')->getAlignment()->setHorizontal('center');

        $sheet->mergeCells('A1:I1');
        $sheet->mergeCells('A2:I2');

        $rowCount = count($this->siswa) + 4;
        for ($row = 4; $row <= $rowCount; $row++) {
            $sheet->getStyle("A{$row}:I{$row}")->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        }
    }

    public function map($data): array
    {
        $id = $data->id_siswa;
        $totalAbsen = $this->absensiSiswa[$id] ?? 0;
        $masuk = $this->countMasuk[$id] ?? 0;
        $sakit = $this->countSakit[$id] ?? 0;
        $izin = $this->countIzin[$id] ?? 0;
        $alfa = $this->countAlfa[$id] ?? 0;
        $totalTidakHadir = $sakit + $izin + $alfa;
        $presentase = $totalAbsen > 0 ? ($masuk / $totalAbsen) * 100 : 0;

        return [
            $id,
            $data->nama_siswa,
            (string) $totalAbsen,
            (string) $masuk,
            $sakit > 0 ? (string) $sakit : '-',
            $izin > 0 ? (string) $izin : '-',
            $alfa > 0 ? (string) $alfa : '-',
            $totalTidakHadir > 0 ? (string) $totalTidakHadir : '-',
            round($presentase, 2) . '%',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;
                $rowCount = count($this->siswa) + 4;

                for ($row = 5; $row <= $rowCount; $row++) { // 5 karena data dimulai dari baris 5
                    $presentaseCell = "I{$row}"; // Sel presentase
                    $cell = $sheet->getCell($presentaseCell);
                    $val = $cell ? $cell->getValue() : '0';
                    $presentaseValue = (float) str_replace('%', '', (string) $val);

                    // Set warna berdasarkan nilai presentase
                    if ($presentaseValue < 90) {
                        $sheet->getStyle($presentaseCell)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
                        $sheet->getStyle($presentaseCell)->getFill()->getStartColor()->setARGB('FFFFCCCC');
                    } else {
                        $sheet->getStyle($presentaseCell)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
                        $sheet->getStyle($presentaseCell)->getFill()->getStartColor()->setARGB('FFD4EDDA');
                    }
                }
            },
        ];
    }

    public function title(): string
    {
        return 'Absensi'; // Nama worksheet
    }
}
