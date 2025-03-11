<?php

namespace App\Exports;

use App\Models\AbsensiGuru;
use App\Models\Guru;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class AbsensiGuruRekapExport implements FromCollection, WithHeadings, WithStyles, WithTitle, ShouldAutoSize
{
    protected $tanggalAwal;
    protected $tanggalAkhir;
    protected $dates;

    public function __construct($tanggalAwal, $tanggalAkhir)
    {
        $this->tanggalAwal = $tanggalAwal;
        $this->tanggalAkhir = $tanggalAkhir;

        $this->dates = [];
        $start = Carbon::parse($tanggalAwal);
        $end = Carbon::parse($tanggalAkhir);
        while ($start <= $end) {
            $this->dates[] = $start->format('Y-m-d');
            $start->addDay();
        }
    }

    public function collection()
    {
        $teachers = Guru::with(['absensi_guru' => function ($query) {
            $query->whereBetween('tanggal', [$this->tanggalAwal, $this->tanggalAkhir]);
        }])->get();

        $data = [];
        $no = 1;

        foreach ($teachers as $teacher) {
            $row = [$no++, $teacher->nama_guru];

            foreach ($this->dates as $date) {
                $attendance = AbsensiGuru::where('id_guru', $teacher->id_guru)
                ->where('tanggal', $date)
                ->first();
                $row[] = $attendance ? $attendance->jam_masuk : '-';
                $row[] = $attendance ? $attendance->jam_pulang : '-';
            }

            $data[] = $row;
        }

        return collect($data);
    }

    public function headings(): array
    {
        $header1 = ['Rekap Kehadiran Guru SMK WISATA INDONESIA'];
        $header2 = ['']; // Baris kosong
        $header3 = ['No', 'Nama Guru'];
        $header4 = ['', ''];

        foreach ($this->dates as $date) {
            $formattedDate = Carbon::parse($date)->format('d M');
            $header3[] = $formattedDate;
            $header3[] = '';
            $header4[] = 'In';
            $header4[] = 'Out';
        }

        return [$header1, $header2, $header3, $header4];
    }

    public function styles(Worksheet $sheet)
    {
        $columnCount = count($this->dates) * 2 + 2; // Kolom No & Nama Guru + tanggal
        $rowCount = Guru::count() + 4; // 4 = Header rows

        // Merge judul ke seluruh kolom yang digunakan
        $sheet->mergeCells("A1:" . $sheet->getHighestColumn() . "1");
        $sheet->mergeCells("A3:A4");
        $sheet->mergeCells("B3:B4");

        for ($i = 3; $i <= $columnCount; $i += 2) {
            $sheet->mergeCellsByColumnAndRow($i, 3, $i + 1, 3);
        }

        // Menambahkan border untuk seluruh tabel (termasuk data)
        $sheet->getStyle("A3:" . $sheet->getHighestColumn() . $rowCount)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ]);

        return [
            1 => [
                'font' => ['bold' => true, 'size' => 14],
                'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
            ],
            3 => [
                'font' => ['bold' => true],
                'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
            ],
            4 => [
                'font' => ['bold' => true],
                'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
            ],
        ];
    }

    public function title(): string
    {
        return 'Rekap Absensi Guru';
    }
}
