<?php

namespace App\Exports;

use App\Models\Absensi;
use App\Models\Siswa;
use App\Models\Kelas;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
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
        // Ambil semua siswa dalam kelas yang dipilih beserta data absensinya sesuai rentang tanggal
        $siswa = Siswa::where('id_kelas', $this->idKelas)
            ->with(['absensi' => function ($query) {
                $query->whereBetween('tanggal', [$this->tanggalAwal, $this->tanggalAkhir]);
            }])
            ->get();

        $data = [];
        $no = 1;

        foreach ($siswa as $item) {
            $row = [$no++, $item->nama_siswa];

            // Untuk setiap tanggal dalam rentang, ambil data absensi siswa
            foreach ($this->dates as $date) {
                // Cari absensi untuk siswa dan tanggal tersebut
                $absen = Absensi::where('id_siswa', $item->id_siswa)
                    ->where('tanggal', $date)
                    ->first();
                
                // Tambahkan kolom Jam Masuk dan Jam Pulang ke dalam baris
                // $row[] = ($absen && !empty($absen->jam_masuk)) ? $absen->jam_masuk : '-';
                // $row[] = ($absen && !empty($absen->jam_pulang)) ? $absen->jam_pulang : '-';
                if ($absen) {
                    // Pastikan tidak ada spasi tambahan dan case insensitive
                    $status = strtolower(trim($absen->kehadiran));
                    // Jika status termasuk salah satu kategori (sakit, izin, alfa)
                    if (in_array($status, ['sakit', 'izin', 'alfa'])) {
                        // Menggabungkan dua kolom: cell pertama diisi dengan 'sakit',
                        // dan cell kedua dikosongkan
                        $row[] = $absen->kehadiran;
                        $row[] = '';
                    } else {
                        $row[] = $absen->jam_masuk;
                        $row[] = $absen->jam_pulang;
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
        // Ambil data kelas untuk menampilkan nama kelas di judul
        $kelas = Kelas::find($this->idKelas);
        $header1 = ['Rekap Kehadiran Siswa - ' . ($kelas ? $kelas->nama_kelas : 'Kelas')];
        $header2 = ['']; // Baris kosong sebagai pemisah

        // Baris header utama dengan No, Nama Siswa dan tanggal
        $header3 = ['No', 'Nama Siswa'];
        // Baris header label untuk masing-masing tanggal (Jam Masuk dan Jam Pulang)
        $header4 = ['', ''];

        foreach ($this->dates as $date) {
            $formattedDate = Carbon::parse($date)->format('d M Y');
            // Baris 3: tanggal akan dimerge untuk dua kolom
            $header3[] = $formattedDate;
            $header3[] = '';
            // Baris 4: label untuk masing-masing kolom tanggal
            $header4[] = 'Masuk';
            $header4[] = 'Pulang';
        }

        return [$header1, $header2, $header3, $header4];
    }

    public function styles(Worksheet $sheet)
    {
        // Hitung total kolom: 2 kolom awal ditambah 2 kolom untuk setiap tanggal
        $columnCount = count($this->dates) * 2 + 2;
        // Baris data = jumlah siswa pada kelas ditambah 4 header baris
        $rowCount = Siswa::where('id_kelas', $this->idKelas)->count() + 4;

        // Merge header judul (baris 1) ke seluruh kolom
        $sheet->mergeCells("A1:" . $sheet->getHighestColumn() . "1");
        // Merge kolom "No" dan "Nama Siswa" agar header di baris 3-4 terlihat rapi
        $sheet->mergeCells("A3:A4");
        $sheet->mergeCells("B3:B4");

        // Merge sel untuk setiap tanggal di header baris 3 (dari kolom ke-3)
        $startCol = 3; // kolom C
        for ($i = 0; $i < count($this->dates); $i++) {
            $col1 = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($startCol);
            $col2 = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($startCol + 1);
            $sheet->mergeCells("$col1" . "3:" . "$col2" . "3");
            $startCol += 2;
        }

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
                                ->getStartColor()->setARGB('FFFF0000'); // Merah
                        }

                        if (empty($nextCell) || $nextCell === '-') {
                            $colName = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex + 1);
                            $sheet->getStyle("{$colName}{$rowIndex}")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                ->getStartColor()->setARGB('FFFF0000'); // Merah
                        }
                    }
                    $colIndex += 2;
                }
            }
        }

        // Terapkan border untuk seluruh tabel mulai dari header baris 3 hingga data terakhir
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
                $event->sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
                $event->sheet->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_A4);
            },
        ];
    }
}
