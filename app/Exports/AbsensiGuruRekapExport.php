<?php

namespace App\Exports;

use App\Models\AbsensiGuru;
use App\Models\Guru;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use Carbon\Carbon;

class AbsensiGuruRekapExport implements FromCollection, WithHeadings, WithStyles, WithTitle, ShouldAutoSize, WithEvents
{
    protected $tanggalAwal;
    protected $tanggalAkhir;
    protected $dates;

    public function __construct($tanggalAwal, $tanggalAkhir)
    {
        $this->tanggalAwal  = $tanggalAwal;
        $this->tanggalAkhir = $tanggalAkhir;

        $this->dates = [];
        $start = Carbon::parse($tanggalAwal);
        $end   = Carbon::parse($tanggalAkhir);
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

                if ($attendance) {
                    $status = strtolower(trim($attendance->kehadiran));
                    if (in_array($status, ['sakit', 'izin', 'alfa'])) {
                        $row[] = $attendance->kehadiran;
                        $row[] = '';
                    } else {
                        $row[] = $attendance->jam_masuk ?: '-';
                        $row[] = $attendance->jam_pulang ?: '-';
                    }
                } else {
                    $row[] = '-';
                    $row[] = '-';
                }
            }

            $data[] = $row;
        }

        return collect($data);
    }

    public function headings(): array
    {
        $header1 = ['Rekap Kehadiran Guru SMK WISATA INDONESIA'];
        $header2 = [''];
        $header3 = ['No', 'Nama Guru'];
        $header4 = ['', ''];

        foreach ($this->dates as $date) {
            $formattedDate = Carbon::parse($date)->format('d M Y');
            $header3[] = $formattedDate;
            $header3[] = '';
            $header4[] = 'Masuk';
            $header4[] = 'Pulang';
        }

        return [$header1, $header2, $header3, $header4];
    }

    public function styles(Worksheet $sheet)
    {
        $columnCount = count($this->dates) * 2 + 2;
        $rowCount = Guru::count() + 4;

        // Merge header
        $sheet->mergeCells("A1:" . $sheet->getHighestColumn() . "1");
        $sheet->mergeCells("A3:A4");
        $sheet->mergeCells("B3:B4");

        $startCol = 3;
        foreach ($this->dates as $date) {
            $col1 = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($startCol);
            $col2 = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($startCol + 1);
            $sheet->mergeCells("{$col1}3:{$col2}3");
            $startCol += 2;
        }

        // Border
        $sheet->getStyle("A3:" . $sheet->getHighestColumn() . $rowCount)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ]);

        // Header semua center kecuali Nama Guru
        $sheet->getStyle("A1:A1")->getAlignment()->setHorizontal('center')->setVertical('center');
        $sheet->getStyle("A3:A4")->getAlignment()->setHorizontal('center')->setVertical('center'); // No
        $sheet->getStyle("B3:B4")->getAlignment()->setHorizontal('center')->setVertical('center');   // Nama Guru
        $startCol = 3;
        foreach ($this->dates as $date) {
            $col1 = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($startCol);
            $col2 = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($startCol + 1);
            $sheet->getStyle("{$col1}3:{$col2}4")->getAlignment()->setHorizontal('center')->setVertical('center');
            $startCol += 2;
        }

        // Style baris data
        foreach ($sheet->toArray(null, true, true, true) as $rowIndex => $row) {
            if ($rowIndex >= 5) {
                $colIndex = 1;
                $highestCol = $sheet->getHighestColumn();

                while ($colIndex <= \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestCol)) {
                    $cellValue = $sheet->getCellByColumnAndRow($colIndex, $rowIndex)->getValue();
                    $cellLower = strtolower(trim($cellValue));

                    // Merge Sakit/Izin/Alfa
                    if (in_array($cellLower, ['sakit', 'izin', 'alfa'])) {
                        $colName = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex);
                        $nextColName = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex + 1);
                        $sheet->mergeCells("{$colName}{$rowIndex}:{$nextColName}{$rowIndex}");
                        $sheet->getStyle("{$colName}{$rowIndex}")->applyFromArray([
                            'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
                            'font' => ['bold' => true],
                        ]);
                        $colIndex += 2;
                        continue;
                    }

                    // Masuk/Pulang kosong
                    if (empty(trim($cellValue)) || $cellValue === '-') {
                        $sheet->getStyleByColumnAndRow($colIndex, $rowIndex)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                            ->getStartColor()->setARGB('FFED2939');
                        $sheet->getCellByColumnAndRow($colIndex, $rowIndex)->setValue('-');
                    }

                    // Center kecuali Nama Guru (kolom 2)
                    if ($colIndex !== 2) {
                        $sheet->getStyleByColumnAndRow($colIndex, $rowIndex)->getAlignment()->setHorizontal('center')->setVertical('center');
                    } else {
                        $sheet->getStyleByColumnAndRow($colIndex, $rowIndex)->getAlignment()->setHorizontal('left')->setVertical('center');
                    }

                    $colIndex++;
                }
            }
        }

        return [
            1 => [
                'font' => ['bold' => true, 'size' => 14],
            ],
            3 => [
                'font' => ['bold' => true],
            ],
            4 => [
                'font' => ['bold' => true],
            ],
        ];
    }

    public function title(): string
    {
        return 'Rekap Absensi Guru';
    }

    public function registerEvents(): array
    {
        return [
            \Maatwebsite\Excel\Events\AfterSheet::class => function ($event) {
                $event->sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
                $event->sheet->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_A4);
            },
        ];
    }
}
