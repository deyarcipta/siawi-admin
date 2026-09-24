<?php

namespace App\Exports;

use App\Models\Setting;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class LaporanPelanggaranExport implements FromCollection, WithHeadings, WithStyles, WithTitle, ShouldAutoSize, WithEvents
{
    protected $dataRekap;
    protected $startDate;
    protected $endDate;
    protected $namaKelas;
    protected $setting;

    public function __construct(array $dataRekap, string $startDate, string $endDate, string $namaKelas = 'Semua Kelas')
    {
        $this->dataRekap = $dataRekap;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->namaKelas = $namaKelas;
        $this->setting = Setting::first();
    }

    public function collection()
    {
        $rows = [];
        $no = 1;

        foreach ($this->dataRekap as $item) {
            $siswa = $item['siswa'];
            $namaKelas = $siswa->kelas->nama_kelas ?? '-';
            $waliKelas = $siswa->kelas->waliKelas->nama_guru ?? '-';

            // Ringkasan pelanggaran
            $detailList = [];
            foreach ($item['pelanggaran_list'] as $p) {
                $tglFormatted = $p->created_at ? \Carbon\Carbon::parse($p->created_at)->format('d/m/Y') : ($p->tanggal ?? '-');
                $detailList[] = "• " . ($p->point->nama_point ?? 'Pelanggaran') . " (" . $p->skor_point . " poin - " . $tglFormatted . ")";
            }
            $ringkasanPelanggaran = implode("\n", $detailList);

            $rows[] = [
                'no' => $no++,
                'nis' => "'" . ($siswa->nis ?? '-'),
                'nama' => $siswa->nama_siswa,
                'kelas' => $namaKelas,
                'wali_kelas' => $waliKelas,
                'total_kasus' => $item['total_kasus'],
                'total_poin' => $item['total_poin'],
                'status_sp' => $item['status_sp'],
                'rincian' => $ringkasanPelanggaran ?: '-'
            ];
        }

        return collect($rows);
    }

    public function headings(): array
    {
        return [
            'NO',
            'NIS',
            'NAMA SISWA',
            'KELAS',
            'WALI KELAS',
            'JUMLAH KASUS',
            'TOTAL POIN',
            'STATUS SP',
            'RINCIAN PELANGGARAN'
        ];
    }

    public function title(): string
    {
        return 'Rekap Pelanggaran';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            5 => [
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FF1E3A8A'] // Navy Dark
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ]
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $namaSekolah = $this->setting->nama_sekolah ?? 'SMK WISATA INDONESIA';

                // Insert header rows
                $sheet->insertNewRowBefore(1, 4);

                // Judul Laporan
                $sheet->mergeCells('A1:I1');
                $sheet->setCellValue('A1', strtoupper($namaSekolah));
                $sheet->getStyle('A1')->getFont()->setSize(14)->setBold(true);
                $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $sheet->mergeCells('A2:I2');
                $sheet->setCellValue('A2', 'LAPORAN REKAPITULASI POIN PELANGGARAN SISWA');
                $sheet->getStyle('A2')->getFont()->setSize(12)->setBold(true);
                $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $sheet->mergeCells('A3:I3');
                $sheet->setCellValue('A3', "Periode: {$this->startDate} s/d {$this->endDate} | Filter Kelas: {$this->namaKelas}");
                $sheet->getStyle('A3')->getFont()->setSize(10)->setItalic(true);
                $sheet->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Total rows
                $lastRow = count($this->dataRekap) + 5;

                // Border tabel
                $styleArray = [
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => 'FFD1D5DB'],
                        ],
                    ],
                ];
                $sheet->getStyle("A5:I{$lastRow}")->applyFromArray($styleArray);

                // Enable wrap text for column I (Rincian Pelanggaran)
                $sheet->getStyle("I5:I{$lastRow}")->getAlignment()->setWrapText(true);

                // Alignment tengah untuk kolom NO, NIS, KELAS, KASUS, POIN, STATUS
                $sheet->getStyle("A6:B{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("D6:D{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("F6:H{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Row Total di paling bawah
                $summaryRow = $lastRow + 1;
                $sheet->mergeCells("A{$summaryRow}:E{$summaryRow}");
                $sheet->setCellValue("A{$summaryRow}", "TOTAL KESELURUHAN");
                $sheet->setCellValue("F{$summaryRow}", "=SUM(F6:F{$lastRow})");
                $sheet->setCellValue("G{$summaryRow}", "=SUM(G6:G{$lastRow})");
                $sheet->setCellValue("H{$summaryRow}", "");
                $sheet->setCellValue("I{$summaryRow}", "");

                $sheet->getStyle("A{$summaryRow}:I{$summaryRow}")->getFont()->setBold(true);
                $sheet->getStyle("A{$summaryRow}:I{$summaryRow}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFF3F4F6');
                $sheet->getStyle("A{$summaryRow}:I{$summaryRow}")->applyFromArray($styleArray);
                $sheet->getStyle("A{$summaryRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle("F{$summaryRow}:G{$summaryRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            }
        ];
    }
}
