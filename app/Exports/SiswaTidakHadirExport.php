<?php

namespace App\Exports;

use App\Models\Absensi;
use App\Models\Kelas;
use App\Models\Setting;
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
use Carbon\Carbon;

class SiswaTidakHadirExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle, ShouldAutoSize, WithEvents
{
    protected $tanggalMulai;
    protected $tanggalAkhir;
    protected $idKelas;
    protected $status;
    protected $user;
    protected $data;
    protected $namaKelasText;

    public function __construct($tanggalMulai, $tanggalAkhir, $idKelas = null, $status = null, $user = null)
    {
        $this->tanggalMulai = $tanggalMulai;
        $this->tanggalAkhir = $tanggalAkhir;
        $this->idKelas = $idKelas;
        $this->status = $status;
        $this->user = $user;

        $query = Absensi::with(['siswa.kelas', 'kelas'])
            ->whereBetween('tanggal', [$this->tanggalMulai, $this->tanggalAkhir])
            ->whereIn('kehadiran', ['sakit', 'izin', 'alfa']);

        if ($this->user && $this->user->role == 'wali_kelas') {
            $kelasWali = Kelas::where('id_guru', $this->user->id_guru)->first();
            if ($kelasWali) {
                $query->where('id_kelas', $kelasWali->id_kelas);
                $this->namaKelasText = 'Kelas ' . $kelasWali->nama_kelas;
            }
        } elseif (!empty($this->idKelas)) {
            $query->where('id_kelas', $this->idKelas);
            $kelas = Kelas::find($this->idKelas);
            $this->namaKelasText = $kelas ? 'Kelas ' . $kelas->nama_kelas : 'Semua Kelas';
        } else {
            $this->namaKelasText = 'Semua Kelas';
        }

        if (!empty($this->status) && in_array(strtolower($this->status), ['sakit', 'izin', 'alfa'])) {
            $query->where('kehadiran', strtolower($this->status));
        }

        $this->data = $query->orderBy('tanggal', 'asc')->orderBy('created_at', 'asc')->get();
    }

    public function collection()
    {
        return $this->data;
    }

    public function map($item): array
    {
        static $no = 0;
        $no++;

        $nisnNis = $item->siswa->nisn ?? ($item->siswa->nis ?? '-');
        $namaKelas = $item->kelas->nama_kelas ?? ($item->siswa->kelas->nama_kelas ?? '-');
        
        $hariTanggal = Carbon::parse($item->tanggal)->locale('id')->isoFormat('dddd, DD/MM/YYYY');
        
        $statusText = strtoupper($item->kehadiran);
        if ($statusText === 'SAKIT') {
            $statusFormatted = 'Sakit (S)';
        } elseif ($statusText === 'IZIN') {
            $statusFormatted = 'Izin (I)';
        } elseif ($statusText === 'ALFA') {
            $statusFormatted = 'Alfa / Tanpa Keterangan (A)';
        } else {
            $statusFormatted = ucfirst($item->kehadiran);
        }

        $keterangan = ($item->keterangan && $item->keterangan !== '-') ? $item->keterangan : '-';

        return [
            $no,
            $item->siswa->nama_siswa ?? 'Siswa Tidak Ditemukan',
            $nisnNis,
            $namaKelas,
            $hariTanggal,
            $statusFormatted,
            $keterangan
        ];
    }

    public function headings(): array
    {
        $setting = Setting::find(1);
        $namaSekolah = $setting->nama_sekolah ?? 'SMK WISATA INDONESIA';
        
        $periodeStr = Carbon::parse($this->tanggalMulai)->locale('id')->isoFormat('D MMMM Y');
        if ($this->tanggalMulai !== $this->tanggalAkhir) {
            $periodeStr .= ' s/d ' . Carbon::parse($this->tanggalAkhir)->locale('id')->isoFormat('D MMMM Y');
        }

        return [
            ['REKAPITULASI DATA SISWA TIDAK HADIR'],
            [$namaSekolah . ' | ' . $this->namaKelasText . ' | Periode: ' . $periodeStr],
            [],
            ['No', 'Nama Siswa', 'NISN / NIS', 'Kelas', 'Hari & Tanggal', 'Status Kehadiran', 'Keterangan / Alasan']
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $rowCount = count($this->data) + 4;
        $borderRange = "A4:G" . max(4, $rowCount);

        $sheet->mergeCells('A1:G1');
        $sheet->mergeCells('A2:G2');

        $styles = [
            1 => [
                'font' => ['bold' => true, 'size' => 14, 'color' => ['argb' => 'FF0F172A']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            ],
            2 => [
                'font' => ['bold' => true, 'size' => 10, 'color' => ['argb' => 'FF475569']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            ],
            4 => [
                'font' => ['bold' => true, 'size' => 10, 'color' => ['argb' => 'FFFFFFFF']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FF1E293B']
                ]
            ],
            $borderRange => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['argb' => 'FFCBD5E1']
                    ]
                ]
            ],
        ];

        // Format kolom center
        $sheet->getStyle('A5:A' . $rowCount)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('C5:E' . $rowCount)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('F5:F' . $rowCount)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        return $styles;
    }

    public function title(): string
    {
        return 'Siswa Tidak Hadir';
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
