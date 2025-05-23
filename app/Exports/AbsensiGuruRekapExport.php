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

        // Siapkan array tanggal dalam format Y-m-d
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
        // Ambil semua guru beserta data absensinya di rentang tanggal
        $teachers = Guru::with(['absensi_guru' => function ($query) {
            $query->whereBetween('tanggal', [$this->tanggalAwal, $this->tanggalAkhir]);
        }])->get();

        $data = [];
        $no = 1;

        foreach ($teachers as $teacher) {
            $row = [$no++, $teacher->nama_guru];

            foreach ($this->dates as $date) {
                // Ambil record absensi untuk guru dan tanggal tersebut
                $attendance = AbsensiGuru::where('id_guru', $teacher->id_guru)
                    ->where('tanggal', $date)
                    ->first();

                if ($attendance) {
                    // Pastikan tidak ada spasi tambahan dan case insensitive
                    $status = strtolower(trim($attendance->kehadiran));
                    // Jika status termasuk salah satu kategori (sakit, izin, alfa)
                    if (in_array($status, ['sakit', 'izin', 'alfa'])) {
                        // Menggabungkan dua kolom: cell pertama diisi dengan 'sakit',
                        // dan cell kedua dikosongkan
                        $row[] = $attendance->kehadiran;
                        $row[] = '';
                    } else {
                        $row[] = $attendance->jam_masuk;
                        $row[] = $attendance->jam_pulang;
                    }
                } else {
                    // Jika tidak ada record, tampilkan tanda '-' pada kedua kolom
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
        // Header 1: Judul rekap
        $header1 = ['Rekap Kehadiran Guru SMK WISATA INDONESIA'];
        $header2 = ['']; // Baris kosong

        // Header 3: Kolom utama (No, Nama Guru) dan header tanggal (dengan 2 kolom per tanggal)
        $header3 = ['No', 'Nama Guru'];
        // Header 4: Label masing-masing subkolom absensi
        $header4 = ['', ''];

        foreach ($this->dates as $date) {
            $formattedDate = Carbon::parse($date)->format('d M Y');
            // Di baris 3, tanggal ditampilkan sebagai header yang akan dimerge secara horizontal (In dan Out)
            $header3[] = $formattedDate;
            $header3[] = '';
            // Di baris 4, label masing-masing kolom absensi
            $header4[] = 'Masuk';
            $header4[] = 'Pulang';
        }

        return [$header1, $header2, $header3, $header4];
    }

    public function styles(Worksheet $sheet)
    {
        // Jumlah kolom: 2 kolom awal + 2 kolom per tanggal
        $columnCount = count($this->dates) * 2 + 2;
        // Jumlah baris: total guru + 4 baris header
        $rowCount = Guru::count() + 4;

        // Merge header judul (baris 1) ke seluruh kolom yang digunakan
        $sheet->mergeCells("A1:" . $sheet->getHighestColumn() . "1");
        // Merge header "No" dan "Nama Guru"
        $sheet->mergeCells("A3:A4");
        $sheet->mergeCells("B3:B4");

        // Merge sel untuk tanggal di header baris 3 (setiap 2 kolom digabung)
        $startCol = 3;
        for ($i = 0; $i < count($this->dates); $i++) {
            $col1 = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($startCol);
            $col2 = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($startCol + 1);
            $sheet->mergeCells("$col1"."3:"."$col2"."3");
            $startCol += 2;
        }

        // Tambahkan border untuk seluruh tabel (header dan data)
        $sheet->getStyle("A3:" . $sheet->getHighestColumn() . $rowCount)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ]);

        foreach ($sheet->toArray(null, true, true, true) as $rowIndex => $row) {
            if ($rowIndex >= 5) { // Baris data mulai dari baris ke-5
                $colIndex = 3; // Mulai dari kolom C (In/Out pertama)
                foreach ($this->dates as $date) {
                    $cellValue = $sheet->getCellByColumnAndRow($colIndex, $rowIndex)->getValue();
                    $nextCell = $sheet->getCellByColumnAndRow($colIndex + 1, $rowIndex)->getValue();

                    // Jika cell kedua kosong dan yang pertama adalah SAKIT/IZIN/ALFA
                    $cellValueLower = strtolower(trim($cellValue));
                    $nextCellLower = strtolower(trim($nextCell));

                    // Merge untuk sakit/izin/alfa
                    if (in_array($cellValueLower, ['sakit', 'izin', 'alfa']) && $nextCell == '') {
                        $col1 = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex);
                        $col2 = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex + 1);
                        $sheet->mergeCells("{$col1}{$rowIndex}:{$col2}{$rowIndex}");
                        $sheet->getStyle("{$col1}{$rowIndex}")->applyFromArray([
                            'alignment' => ['horizontal' => 'center'],
                            'font' => ['bold' => true],
                        ]);
                    } else {
                        // Cek cell kosong dan warnai merah
                        if (empty($cellValue) || $cellValue === '-') {
                            $colName = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex);
                            $sheet->getStyle("{$colName}{$rowIndex}")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                ->getStartColor()->setARGB('FFED2939');
                                // ->getStartColor()->setARGB('FFFF0000'); // Merah
                        }

                        if (empty($nextCell) || $nextCell === '-') {
                            $colName = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex + 1);
                            $sheet->getStyle("{$colName}{$rowIndex}")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                ->getStartColor()->setARGB('FFFD7OO');
                                // ->getStartColor()->setARGB('FFFF0000'); // Merah
                        }
                    }
                    $colIndex += 2;
                }
            }
        }

        return [
            1 => [
                'font'      => ['bold' => true, 'size' => 14],
                'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
            ],
            3 => [
                'font'      => ['bold' => true],
                'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
            ],
            4 => [
                'font'      => ['bold' => true],
                'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
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
            \Maatwebsite\Excel\Events\AfterSheet::class => function (\Maatwebsite\Excel\Events\AfterSheet $event) {
                $event->sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
                $event->sheet->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_A4);
            },
        ];
    }
}
