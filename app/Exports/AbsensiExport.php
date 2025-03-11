<?php

namespace App\Exports;

use App\Models\Siswa;
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
        $totalAbsen = $this->absensiSiswa[$data->id_siswa];
        $presentase = $totalAbsen > 0 ? ($this->countMasuk[$data->id_siswa] / $totalAbsen) * 100 : 0;

        return [
            $data->id_siswa,
            $data->nama_siswa,
            $totalAbsen?: '0',
            $this->countMasuk[$data->id_siswa]?: '0',
            $this->countSakit[$data->id_siswa]?: '-',
            $this->countIzin[$data->id_siswa]?: '-',
            $this->countAlfa[$data->id_siswa]?: '-',
            $this->countAlfa[$data->id_siswa] + $this->countIzin[$data->id_siswa] + $this->countSakit[$data->id_siswa]?: '-',
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
                    $presentaseValue = (float)str_replace('%', '', $sheet->getCell($presentaseCell)->getValue());

                    // Set warna berdasarkan nilai presentase
                    if ($presentaseValue < 90) {
                        $sheet->getStyle($presentaseCell)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
                        $sheet->getStyle($presentaseCell)->getFill()->getStartColor()->setARGB('FF0000'); // Merah
                    } else {
                        $sheet->getStyle($presentaseCell)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
                        $sheet->getStyle($presentaseCell)->getFill()->getStartColor()->setARGB('00FF00'); // Hijau
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
