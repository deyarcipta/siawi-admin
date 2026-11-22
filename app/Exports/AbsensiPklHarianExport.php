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

class AbsensiPklHarianExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle, ShouldAutoSize, WithEvents
{
    protected $tanggal;
    protected $idPerusahaan;
    protected $idKelas;
    protected $filterStatus;
    protected $dataList;
    protected $namaSekolah;
    protected $namaPerusahaan;
    protected $namaKelas;

    public function __construct($tanggal, $idPerusahaan = null, $idKelas = null, $filterStatus = null)
    {
        $this->tanggal = $tanggal ?: now()->toDateString();
        $this->idPerusahaan = $idPerusahaan;
        $this->idKelas = $idKelas;
        $this->filterStatus = $filterStatus;

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

        // Ambil data siswa yang aktif masa PKL pada tanggal tersebut
        $query = SiswaPkl::with(['siswa.kelas', 'kelas', 'perusahaan'])
            ->where('status', '!=', 'selesai')
            ->where('tanggal_mulai', '<=', $this->tanggal)
            ->where('tanggal_selesai', '>=', $this->tanggal);

        if ($this->idPerusahaan) {
            $query->where('id_perusahaan', $this->idPerusahaan);
        }

        if ($this->idKelas) {
            $query->where('id_kelas', $this->idKelas);
        }

        $siswaPklList = $query->get();
        $siswaIds = $siswaPklList->pluck('id_siswa')->toArray();

        $absensiMap = AbsensiPkl::whereIn('id_siswa', $siswaIds)
            ->where('tanggal', $this->tanggal)
            ->get()
            ->keyBy('id_siswa');

        $mapped = $siswaPklList->map(function ($item) use ($absensiMap) {
            $absen = $absensiMap->get($item->id_siswa);
            $item->absensi = $absen;
            $item->status_kehadiran = $absen ? $absen->status : 'belum_absen';
            return $item;
        });

        if ($this->filterStatus && $this->filterStatus !== 'semua') {
            $mapped = $mapped->where('status_kehadiran', $this->filterStatus);
        }

        $this->dataList = $mapped->values();
    }

    public function collection()
    {
        return $this->dataList;
    }

    public function map($row): array
    {
        static $no = 0;
        $no++;

        $absen = $row->absensi;
        $statusText = 'Belum Absen';
        if ($absen) {
            $statusRaw = strtolower(trim((string) $absen->status));
            if ($statusRaw === 'hadir' || $statusRaw === 'terlambat') {
                $statusText = 'Hadir';
            } elseif ($statusRaw === 'izin') {
                $statusText = 'Izin';
            } elseif ($statusRaw === 'sakit') {
                $statusText = 'Sakit';
            } else {
                $statusText = ucfirst($statusRaw);
            }
        }

        $jarakText = '-';
        if ($absen && $absen->jarak_masuk_meter !== null) {
            $jarakText = number_format($absen->jarak_masuk_meter) . ' m';
        }

        $tglFormatted = Carbon::parse($this->tanggal)->translatedFormat('d/m/Y');

        return [
            $no,
            $row->siswa->nis ?? '-',
            $row->siswa->nama_siswa ?? '-',
            $row->kelas->nama_kelas ?? ($row->siswa->kelas->nama_kelas ?? '-'),
            $row->perusahaan->nama_perusahaan ?? '-',
            $tglFormatted,
            $absen->jam_masuk ? substr($absen->jam_masuk, 0, 5) : '-',
            $absen->jam_pulang ? substr($absen->jam_pulang, 0, 5) : '-',
            $statusText,
            $jarakText,
            $absen->keterangan ?? '-',
        ];
    }

    public function headings(): array
    {
        $subInfo = 'Tanggal: ' . Carbon::parse($this->tanggal)->translatedFormat('l, d F Y');
        if ($this->namaPerusahaan) {
            $subInfo .= ' | Mitra: ' . $this->namaPerusahaan;
        }
        if ($this->namaKelas) {
            $subInfo .= ' | Kelas: ' . $this->namaKelas;
        }
        if ($this->filterStatus && $this->filterStatus !== 'semua') {
            $subInfo .= ' | Status: ' . ucfirst($this->filterStatus);
        }

        return [
            [strtoupper($this->namaSekolah)],
            ['LAPORAN HARIAN PRESENSI SISWA PKL'],
            [$subInfo],
            [], // Baris kosong
            [
                'No',
                'NIS',
                'Nama Siswa',
                'Kelas',
                'Mitra DU/DI',
                'Tanggal',
                'Jam Masuk',
                'Jam Pulang',
                'Status Kehadiran',
                'Radius / Jarak',
                'Keterangan',
            ]
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $totalRows = count($this->dataList);
        $lastRow = 5 + $totalRows;

        // Merge Judul
        $sheet->mergeCells('A1:K1');
        $sheet->mergeCells('A2:K2');
        $sheet->mergeCells('A3:K3');

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
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 10],
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
            $sheet->getStyle("A5:K{$lastRow}")->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => 'CBD5E1'],
                    ],
                ],
            ]);

            // Alignment kolom
            $sheet->getStyle("A6:A{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("B6:B{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("D6:D{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("F6:F{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("G6:H{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("I6:I{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("J6:J{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }

        $sheet->getRowDimension(5)->setRowHeight(26);

        return $styles;
    }

    public function title(): string
    {
        return 'Presensi PKL Harian';
    }

    public function registerEvents(): array
    {
        return [
            \Maatwebsite\Excel\Events\AfterSheet::class => function (\Maatwebsite\Excel\Events\AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
                $sheet->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_A4);

                $sheet->getColumnDimension('A')->setWidth(6);
            },
        ];
    }
}

