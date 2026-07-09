<?php

namespace App\Exports;

use App\Models\Siswa;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

class RekapBelumAbsenExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle, ShouldAutoSize, WithEvents
{
    protected $date;
    protected $guruPiketList;

    public function __construct($date, $guruPiketList)
    {
        $this->date = $date;
        $this->guruPiketList = $guruPiketList;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Siswa::whereNotIn('id_siswa', function ($query) {
            $query->select('id_siswa')->from('absensi')->where('tanggal', $this->date);
        })
        ->with('kelas')
        ->orderBy('id_kelas', 'asc')
        ->get();
    }

    /**
     * @param mixed $siswa
     * @return array
     */
    public function map($siswa): array
    {
        static $nomor = 0;
        $nomor++;
        return [
            $nomor,
            $siswa->kelas->nama_kelas ?? '-',
            $siswa->nis,
            $siswa->nama_siswa,
            'Absen Belum Diinput'
        ];
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        $formattedDate = \Carbon\Carbon::parse($this->date)->translatedFormat('d F Y');
        $piketNames = $this->guruPiketList ?: '-';

        return [
            ['LAPORAN KELALAIAN INPUT ABSENSI - SMK WISATA INDONESIA'],
            ['Tanggal: ' . $formattedDate],
            ['Guru Piket Bertugas: ' . $piketNames],
            [], // Empty row
            ['No', 'Nama Kelas', 'NIS', 'Nama Siswa', 'Keterangan'] // Table header
        ];
    }

    /**
     * Apply styles to sheet.
     */
    public function styles(Worksheet $sheet)
    {
        $totalData = Siswa::whereNotIn('id_siswa', function ($query) {
            $query->select('id_siswa')->from('absensi')->where('tanggal', $this->date);
        })->count();

        $rowCount = $totalData + 5; // header (5 rows) + data rows
        $borderRange = "A5:E" . $rowCount;

        // Merge headings across columns A to E
        $sheet->mergeCells('A1:E1');
        $sheet->mergeCells('A2:E2');
        $sheet->mergeCells('A3:E3');

        return [
            1 => [
                'font' => ['bold' => true, 'size' => 14],
                'alignment' => ['horizontal' => 'center'],
            ],
            2 => [
                'font' => ['italic' => true, 'size' => 11],
                'alignment' => ['horizontal' => 'center'],
            ],
            3 => [
                'font' => ['italic' => true, 'size' => 11],
                'alignment' => ['horizontal' => 'center'],
            ],
            5 => [
                'font' => ['bold' => true],
                'alignment' => ['horizontal' => 'center'],
                'borders' => ['allBorders' => ['borderStyle' => 'thin']]
            ],
            $borderRange => [
                'borders' => ['allBorders' => ['borderStyle' => 'thin']]
            ],
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Kelalaian Absen';
    }

    /**
     * Page setup orientations
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
