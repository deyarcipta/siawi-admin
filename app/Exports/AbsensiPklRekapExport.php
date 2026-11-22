<?php

namespace App\Exports;

use App\Models\Setting;
use App\Models\SiswaPkl;
use App\Models\AbsensiPkl;
use App\Models\Perusahaan;
use App\Models\Kelas;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class AbsensiPklRekapExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle, WithEvents
{
    protected $tanggalAwal;
    protected $tanggalAkhir;
    protected $idPerusahaan;
    protected $idKelas;
    protected $dates = [];
    protected $dataList;
    protected $namaSekolah;
    protected $namaPerusahaan;
    protected $namaKelas;
    protected $totalColumns;

    public function __construct($tanggalAwal, $tanggalAkhir, $idPerusahaan = null, $idKelas = null)
    {
        $this->tanggalAwal = $tanggalAwal ?: now()->startOfMonth()->toDateString();
        $this->tanggalAkhir = $tanggalAkhir ?: now()->toDateString();
        $this->idPerusahaan = $idPerusahaan;
        $this->idKelas = $idKelas;

        $setting = Setting::first();
        $this->namaSekolah = $setting->nama_sekolah ?? 'SMK WISATA INDONESIA';

        if ($this->idPerusahaan) {
            $p = Perusahaan::find($this->idPerusahaan);
            $this->namaPerusahaan = $p ? $p->nama_perusahaan : null;
        }

        if ($this->idKelas) {
            $k = Kelas::find($this->idKelas);
            $this->namaKelas = $k ? $k->nama_kelas : null;
        }

        // Generate list of dates
        $start = Carbon::parse($this->tanggalAwal);
        $end = Carbon::parse($this->tanggalAkhir);
        if ($start > $end) {
            $temp = $start;
            $start = $end;
            $end = $temp;
            $this->tanggalAwal = $start->toDateString();
            $this->tanggalAkhir = $end->toDateString();
        }

        while ($start <= $end) {
            $this->dates[] = $start->toDateString();
            $start->addDay();
        }

        // Ambil siswa PKL yang periodenya beririsan dengan rentang tanggal
        $query = SiswaPkl::with(['siswa.kelas', 'kelas', 'perusahaan'])
            ->where('tanggal_mulai', '<=', $this->tanggalAkhir)
            ->where('tanggal_selesai', '>=', $this->tanggalAwal);

        if ($this->idPerusahaan) {
            $query->where('id_perusahaan', $this->idPerusahaan);
        }

        if ($this->idKelas) {
            $query->where('id_kelas', $this->idKelas);
        }

        $siswaPklList = $query->get();
        $siswaIds = $siswaPklList->pluck('id_siswa')->toArray();

        // Ambil semua absensi siswa pada rentang tanggal
        $absensiRows = AbsensiPkl::whereIn('id_siswa', $siswaIds)
            ->whereBetween('tanggal', [$this->tanggalAwal, $this->tanggalAkhir])
            ->get();

        // Grouping: [id_siswa][tanggal] => AbsensiPkl
        $absensiMap = [];
        foreach ($absensiRows as $absen) {
            $absensiMap[$absen->id_siswa][$absen->tanggal] = $absen;
        }

        // Bentuk data list terstruktur
        $this->dataList = $siswaPklList->map(function ($item) use ($absensiMap) {
            $item->attendanceMap = $absensiMap[$item->id_siswa] ?? [];
            return $item;
        })->values();

        // 5 fixed info + date count + 4 summary columns (Hadir, Izin, Sakit, Total Hadir)
        $this->totalColumns = 5 + count($this->dates) + 4;
    }

    public function collection()
    {
        return $this->dataList;
    }

    public function map($row): array
    {
        static $no = 0;
        $no++;

        $tglMulai = $row->tanggal_mulai ? date('Y-m-d', strtotime($row->tanggal_mulai)) : null;
        $tglSelesai = $row->tanggal_selesai ? date('Y-m-d', strtotime($row->tanggal_selesai)) : null;

        $res = [
            $no,
            $row->siswa->nis ?? '-',
            $row->siswa->nama_siswa ?? '-',
            $row->kelas->nama_kelas ?? ($row->siswa->kelas->nama_kelas ?? '-'),
            $row->perusahaan->nama_perusahaan ?? '-',
        ];

        $hadirCount = 0;
        $izinCount = 0;
        $sakitCount = 0;

        foreach ($this->dates as $date) {
            // Apakah siswa aktif PKL di tanggal ini?
            $isActiveOnDate = (!$tglMulai || $date >= $tglMulai) && (!$tglSelesai || $date <= $tglSelesai);

            if (!$isActiveOnDate) {
                $res[] = '-';
                continue;
            }

            $absen = $row->attendanceMap[$date] ?? null;

            if ($absen) {
                $status = strtolower(trim((string) $absen->status));
                if ($status === 'hadir' || $status === 'terlambat') {
                    $res[] = 'H';
                    $hadirCount++;
                } elseif ($status === 'izin') {
                    $res[] = 'I';
                    $izinCount++;
                } elseif ($status === 'sakit') {
                    $res[] = 'S';
                    $sakitCount++;
                } else {
                    $res[] = 'H';
                    $hadirCount++;
                }
            } else {
                // Tanggal tanpa presensi (off shift / libur)
                $res[] = '-';
            }
        }

        // Summary calculations
        $res[] = $hadirCount;
        $res[] = $izinCount;
        $res[] = $sakitCount;
        $res[] = $hadirCount; // Total Hadir

        return $res;
    }

    public function headings(): array
    {
        $lastColLetter = Coordinate::stringFromColumnIndex($this->totalColumns);
        $tglAwalFmt = Carbon::parse($this->tanggalAwal)->translatedFormat('d M Y');
        $tglAkhirFmt = Carbon::parse($this->tanggalAkhir)->translatedFormat('d M Y');

        $subInfo = "Periode: {$tglAwalFmt} s/d {$tglAkhirFmt}";
        if ($this->namaPerusahaan) {
            $subInfo .= ' | Mitra: ' . $this->namaPerusahaan;
        }
        if ($this->namaKelas) {
            $subInfo .= ' | Kelas: ' . $this->namaKelas;
        }

        // Header Baris 5
        $headerRow = [
            'No',
            'NIS',
            'Nama Siswa',
            'Kelas',
            'Mitra DU/DI',
        ];

        foreach ($this->dates as $d) {
            $headerRow[] = Carbon::parse($d)->format('d/m');
        }

        $headerRow[] = 'Hadir (H)';
        $headerRow[] = 'Izin (I)';
        $headerRow[] = 'Sakit (S)';
        $headerRow[] = 'Total Hadir';

        return [
            [strtoupper($this->namaSekolah)],
            ['REKAPITULASI PRESENSI SISWA PKL'],
            [$subInfo],
            [], // Baris kosong
            $headerRow,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $lastColLetter = Coordinate::stringFromColumnIndex($this->totalColumns);
        $totalRows = count($this->dataList);
        $lastRow = 5 + $totalRows;

        // Merge Judul
        $sheet->mergeCells("A1:{$lastColLetter}1");
        $sheet->mergeCells("A2:{$lastColLetter}2");
        $sheet->mergeCells("A3:{$lastColLetter}3");

        $styles = [
            1 => [
                'font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => '0F172A']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
            2 => [
                'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => '0284C7']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
            3 => [
                'font' => ['size' => 10, 'italic' => true, 'color' => ['rgb' => '475569']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
            5 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 9],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '0284C7'],
                ],
            ],
        ];

        if ($totalRows > 0) {
            $sheet->getStyle("A5:{$lastColLetter}{$lastRow}")->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => 'CBD5E1'],
                    ],
                ],
            ]);

            // Alignment kolom
            $sheet->getStyle("A6:B{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("D6:D{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            // Date & summary columns centered
            $startDateColLetter = Coordinate::stringFromColumnIndex(6);
            $sheet->getStyle("{$startDateColLetter}6:{$lastColLetter}{$lastRow}")
                ->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }

        $sheet->getRowDimension(5)->setRowHeight(26);

        return $styles;
    }

    public function title(): string
    {
        return 'Rekap Presensi PKL';
    }

    public function registerEvents(): array
    {
        return [
            \Maatwebsite\Excel\Events\AfterSheet::class => function (\Maatwebsite\Excel\Events\AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
                $sheet->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_A4);

                $lastColLetter = Coordinate::stringFromColumnIndex($this->totalColumns);

                // Set lebar kolom pasti agar No dan kolom lainnya presisi
                $sheet->getColumnDimension('A')->setWidth(6);
                $sheet->getColumnDimension('B')->setWidth(13);
                $sheet->getColumnDimension('C')->setWidth(28);
                $sheet->getColumnDimension('D')->setWidth(12);
                $sheet->getColumnDimension('E')->setWidth(26);

                $numDates = count($this->dates);
                for ($c = 6; $c <= 5 + $numDates; $c++) {
                    $colLetter = Coordinate::stringFromColumnIndex($c);
                    $sheet->getColumnDimension($colLetter)->setWidth(5.5);
                }

                for ($c = 6 + $numDates; $c <= $this->totalColumns; $c++) {
                    $colLetter = Coordinate::stringFromColumnIndex($c);
                    $sheet->getColumnDimension($colLetter)->setWidth(11);
                }

                $totalRows = count($this->dataList);
                $noteRow1 = 5 + $totalRows + 2;
                $noteRow2 = $noteRow1 + 1;

                // Merge catatan footnote agar tidak memperlebar kolom A
                $sheet->mergeCells("A{$noteRow1}:{$lastColLetter}{$noteRow1}");
                $sheet->mergeCells("A{$noteRow2}:{$lastColLetter}{$noteRow2}");

                $sheet->setCellValue("A{$noteRow1}", "* Keterangan Kode: H = Hadir | I = Izin | S = Sakit | (-) = Tidak Ada Presensi / Libur Shift");
                $sheet->setCellValue("A{$noteRow2}", "* Catatan: Hari kerja dan libur siswa PKL mengikuti jadwal shift operasional masing-masing Mitra DU/DI.");

                $sheet->getStyle("A{$noteRow1}:{$lastColLetter}{$noteRow2}")->applyFromArray([
                    'font' => ['size' => 9, 'italic' => true, 'color' => ['rgb' => '64748B']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
                ]);
            },
        ];
    }
}

