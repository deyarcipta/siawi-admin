<?php

namespace App\Exports;

use App\Models\Absensi;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\SiswaPkl;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Carbon\Carbon;

class AbsensiSiswaRekapExport implements FromCollection, WithHeadings, WithStyles, WithTitle, ShouldAutoSize, WithEvents
{
    protected $idKelas;
    protected $tanggalAwal;
    protected $tanggalAkhir;
    protected $dates;

    public function __construct($idKelas, $tanggalAwal, $tanggalAkhir)
    {
        $this->idKelas = $idKelas;
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
        $siswa = Siswa::where('id_kelas', $this->idKelas)
            ->with(['absensi' => function ($query) {
                $query->whereBetween('tanggal', [$this->tanggalAwal, $this->tanggalAkhir]);
            }])
            ->get();

        $data = [];
        $no = 1;

        foreach ($siswa as $item) {
            $row = [$no++, $item->nama_siswa];

            foreach ($this->dates as $date) {
                $isPkl = SiswaPkl::where('id_siswa', $item->id_siswa)
                    ->where('status', 'PKL')
                    ->where('tanggal_mulai', '<=', $date)
                    ->where('tanggal_selesai', '>=', $date)
                    ->exists();

                if ($isPkl) {
                    $row[] = 'PKL';
                    $row[] = '';
                } else {
                    $absen = Absensi::where('id_siswa', $item->id_siswa)
                        ->where('tanggal', $date)
                        ->first();

                    if ($absen) {
                        $status = strtolower(trim($absen->kehadiran));
                        if (in_array($status, ['sakit', 'izin', 'alfa'])) {
                            $row[] = $absen->kehadiran;
                            $row[] = '';
                        } else {
                            $row[] = $absen->jam_masuk ?: '-';
                            $row[] = $absen->jam_pulang ?: '-';
                        }
                    } else {
                        $row[] = '-';
                        $row[] = '-';
                    }
                }
            }

            $data[] = $row;
        }

        return collect($data);
    }

    public function headings(): array
    {
        $kelas = Kelas::find($this->idKelas);
        $header1 = ['Rekap Kehadiran Siswa - ' . ($kelas ? $kelas->nama_kelas : 'Kelas')];
        $header2 = [''];
        $header3 = ['No', 'Nama Siswa'];
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
        $rowCount = Siswa::where('id_kelas', $this->idKelas)->count() + 4;

        // Merge judul dan header
        $sheet->mergeCells("A1:" . $sheet->getHighestColumn() . "1");
        $sheet->mergeCells("A3:A4");
        $sheet->mergeCells("B3:B4");

        // Merge tanggal
        $startCol = 3;
        foreach ($this->dates as $date) {
            $col1 = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($startCol);
            $col2 = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($startCol + 1);
            $sheet->mergeCells("{$col1}3:{$col2}3");
            $startCol += 2;
        }

        // Style isi tabel
        foreach ($sheet->toArray(null, true, true, true) as $rowIndex => $row) {
            if ($rowIndex >= 5) {
                $colIndex = 3;
                foreach ($this->dates as $date) {
                    $cell1 = $sheet->getCellByColumnAndRow($colIndex, $rowIndex);
                    $cell2 = $sheet->getCellByColumnAndRow($colIndex + 1, $rowIndex);
                    $val1 = strtolower(trim($cell1->getValue()));
                    $val2 = strtolower(trim($cell2->getValue()));

                    // Merge untuk sakit/izin/alfa/pkl
                    if (in_array($val1, ['sakit', 'izin', 'alfa', 'pkl']) && $val2 == '') {
                        $col1 = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex);
                        $col2 = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex + 1);
                        $sheet->mergeCells("{$col1}{$rowIndex}:{$col2}{$rowIndex}");

                        $sheet->getStyle("{$col1}{$rowIndex}")->applyFromArray([
                            'alignment' => ['horizontal' => 'center'],
                            'font' => ['bold' => true],
                        ]);

                        if ($val1 === 'pkl') {
                            $sheet->getStyle("{$col1}{$rowIndex}")->getFill()
                                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                ->getStartColor()->setARGB('FFADD8E6'); // biru muda
                        }
                    } else {
                        // Kosong = merah
                        if (empty($val1) || $val1 === '-') {
                            $colName = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex);
                            $sheet->getStyle("{$colName}{$rowIndex}")->getFill()
                                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                ->getStartColor()->setARGB('FFFF0000');
                        }
                        if (empty($val2) || $val2 === '-') {
                            $colName = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex + 1);
                            $sheet->getStyle("{$colName}{$rowIndex}")->getFill()
                                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                ->getStartColor()->setARGB('FFFF0000');
                        }
                    }
                    $colIndex += 2;
                }
            }
        }

        // Border tabel
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
        return 'Rekap Absensi Siswa';
    }

    public function registerEvents(): array
    {
        return [
            \Maatwebsite\Excel\Events\AfterSheet::class => function (\Maatwebsite\Excel\Events\AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Page setup
                $sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
                $sheet->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_A4);

                // Nama siswa isi rata kiri
                $rowCount = Siswa::where('id_kelas', $this->idKelas)->count() + 4;
                $sheet->getStyle("B5:B{$rowCount}")
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_LEFT)
                    ->setVertical(Alignment::VERTICAL_CENTER);

                // Kolom No + semua data Masuk/Pulang rata tengah
                $lastCol = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($this->dates) * 2 + 2);
                $sheet->getStyle("A5:A{$rowCount}")
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                    ->setVertical(Alignment::VERTICAL_CENTER);
                $sheet->getStyle("C5:{$lastCol}{$rowCount}")
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                    ->setVertical(Alignment::VERTICAL_CENTER);
            },
        ];
    }
}
