<?php

namespace App\Exports;

use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Absensi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use Carbon\Carbon;

class LaporanKedisiplinanExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle, ShouldAutoSize, WithEvents
{
    protected $tanggalPilihan;
    protected $selectedKelasId;
    protected $selectedKategori;
    protected $daysInWeek;
    protected $startDateStr;
    protected $endDateStr;
    protected $startOfWeek;
    protected $endOfWeek;
    protected $kelasNama;

    public function __construct($tanggalPilihan, $selectedKelasId = null, $selectedKategori = null)
    {
        $this->tanggalPilihan = $tanggalPilihan;
        $this->selectedKelasId = $selectedKelasId;
        $this->selectedKategori = $selectedKategori;

        $carbonDate = Carbon::parse($tanggalPilihan);
        $this->startOfWeek = $carbonDate->copy()->startOfWeek(Carbon::MONDAY);
        $this->endOfWeek = $this->startOfWeek->copy()->addDays(5);

        $this->startDateStr = $this->startOfWeek->toDateString();
        $this->endDateStr = $this->endOfWeek->toDateString();

        $indonesianDayNames = [
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu'
        ];

        $this->daysInWeek = [];
        for ($i = 0; $i < 6; $i++) {
            $curr = $this->startOfWeek->copy()->addDays($i);
            $dayNameEng = $curr->format('l');
            $this->daysInWeek[] = [
                'tanggal' => $curr->toDateString(),
                'hari' => $indonesianDayNames[$dayNameEng] ?? $curr->locale('id')->dayName,
                'tgl_formatted' => $curr->format('d/m'),
            ];
        }

        $this->kelasNama = 'Semua Kelas';
        if (!empty($selectedKelasId)) {
            $k = Kelas::find($selectedKelasId);
            if ($k) {
                $this->kelasNama = $k->nama_kelas;
            }
        }
    }

    public function collection()
    {
        $siswaQuery = Siswa::with('kelas')->orderBy('id_kelas', 'asc')->orderBy('nama_siswa', 'asc');
        if (!empty($this->selectedKelasId)) {
            $siswaQuery->where('id_kelas', $this->selectedKelasId);
        }
        $siswaList = $siswaQuery->get();

        $absensiRecords = Absensi::whereBetween('tanggal', [$this->startDateStr, $this->endDateStr])
            ->whereIn('id_siswa', $siswaList->pluck('id_siswa'))
            ->get()
            ->groupBy('id_siswa');

        $result = collect();

        foreach ($siswaList as $siswa) {
            $siswaAbsen = $absensiRecords->get($siswa->id_siswa, collect());
            $absenByDate = $siswaAbsen->keyBy('tanggal');

            $totalHadirMesin = 0;
            $totalHadirManual = 0;
            $totalTidakHadir = 0;
            $harianText = [];

            foreach ($this->daysInWeek as $day) {
                $tgl = $day['tanggal'];
                $absen = $absenByDate->get($tgl);

                if ($absen) {
                    $kehadiran = strtolower($absen->kehadiran);
                    $tipeMasuk = $absen->tipe_masuk;
                    $ketLower = strtolower($absen->keterangan ?? '');

                    if (empty($tipeMasuk)) {
                        if (str_contains($ketLower, 'check in') || str_contains($ketLower, 'face') || str_contains($ketLower, 'presence') || $ketLower === 'masuk') {
                            $tipeMasuk = 'mesin';
                        } elseif (str_contains($ketLower, 'terlambat')) {
                            $tipeMasuk = 'piket';
                        } else {
                            $tipeMasuk = 'manual';
                        }
                    }

                    if ($kehadiran === 'hadir') {
                        if ($tipeMasuk === 'mesin') {
                            $harianText[] = 'Mesin (' . ($absen->jam_masuk ?? '-') . ')';
                            $totalHadirMesin++;
                        } elseif ($tipeMasuk === 'piket') {
                            $harianText[] = 'Terlambat Piket';
                            $totalHadirManual++;
                        } else {
                            $harianText[] = 'Manual Guru';
                            $totalHadirManual++;
                        }
                    } elseif ($kehadiran === 'sakit') {
                        $harianText[] = 'Sakit';
                        $totalTidakHadir++;
                    } elseif ($kehadiran === 'izin') {
                        $harianText[] = 'Izin';
                        $totalTidakHadir++;
                    } elseif ($kehadiran === 'alfa') {
                        $harianText[] = 'Alfa';
                        $totalTidakHadir++;
                    } else {
                        $harianText[] = 'Manual';
                        $totalHadirManual++;
                    }
                } else {
                    $harianText[] = '-';
                }
            }

            $totalHadir = $totalHadirMesin + $totalHadirManual;
            if ($totalHadir > 0) {
                $persentase = round(($totalHadirMesin / $totalHadir) * 100);
                if ($persentase >= 80) {
                    $statusDisiplin = 'sangat_tertib';
                    $statusLabel = 'Sangat Tertib Mesin';
                } elseif ($persentase >= 50) {
                    $statusDisiplin = 'cukup';
                    $statusLabel = 'Cukup Tertib';
                } else {
                    $statusDisiplin = 'perlu_pembinaan';
                    $statusLabel = 'Sering Manual (Lalai)';
                }
            } else {
                $persentase = 0;
                $statusDisiplin = 'tidak_hadir';
                $statusLabel = 'Belum Ada Hadir';
            }

            if (!empty($this->selectedKategori)) {
                if ($this->selectedKategori === 'tertib' && $statusDisiplin !== 'sangat_tertib') {
                    continue;
                }
                if ($this->selectedKategori === 'cukup' && $statusDisiplin !== 'cukup') {
                    continue;
                }
                if ($this->selectedKategori === 'perlu_pembinaan' && $statusDisiplin !== 'perlu_pembinaan') {
                    continue;
                }
            }

            $result->push([
                'siswa' => $siswa,
                'harian' => $harianText,
                'hadir_mesin' => $totalHadirMesin,
                'hadir_manual' => $totalHadirManual,
                'tidak_hadir' => $totalTidakHadir,
                'total_hadir' => $totalHadir,
                'persentase' => $persentase . '%',
                'status_label' => $statusLabel
            ]);
        }

        return $result;
    }

    public function map($row): array
    {
        static $no = 0;
        $no++;

        $siswa = $row['siswa'];
        $harian = $row['harian'];

        return [
            $no,
            $siswa->nis ?? '-',
            $siswa->nama_siswa,
            $siswa->kelas->nama_kelas ?? '-',
            $harian[0] ?? '-',
            $harian[1] ?? '-',
            $harian[2] ?? '-',
            $harian[3] ?? '-',
            $harian[4] ?? '-',
            $harian[5] ?? '-',
            $row['hadir_mesin'],
            $row['hadir_manual'],
            $row['tidak_hadir'],
            $row['total_hadir'],
            $row['persentase'],
            $row['status_label']
        ];
    }

    public function headings(): array
    {
        $h1 = $this->daysInWeek[0]['hari'] . ' (' . $this->daysInWeek[0]['tgl_formatted'] . ')';
        $h2 = $this->daysInWeek[1]['hari'] . ' (' . $this->daysInWeek[1]['tgl_formatted'] . ')';
        $h3 = $this->daysInWeek[2]['hari'] . ' (' . $this->daysInWeek[2]['tgl_formatted'] . ')';
        $h4 = $this->daysInWeek[3]['hari'] . ' (' . $this->daysInWeek[3]['tgl_formatted'] . ')';
        $h5 = $this->daysInWeek[4]['hari'] . ' (' . $this->daysInWeek[4]['tgl_formatted'] . ')';
        $h6 = $this->daysInWeek[5]['hari'] . ' (' . $this->daysInWeek[5]['tgl_formatted'] . ')';

        return [
            ['LAPORAN KEDISIPLINAN PENGGUNAAN MESIN ABSENSI SISWA'],
            ['Periode: ' . $this->startOfWeek->translatedFormat('d F Y') . ' s/d ' . $this->endOfWeek->translatedFormat('d F Y')],
            ['Kelas: ' . $this->kelasNama],
            [],
            [
                'No',
                'NIS',
                'Nama Siswa',
                'Kelas',
                $h1,
                $h2,
                $h3,
                $h4,
                $h5,
                $h6,
                'Hadir Mesin',
                'Hadir Manual',
                'Izin/Sakit/Alfa',
                'Total Hadir',
                '% Disiplin Mesin',
                'Status Kedisiplinan'
            ]
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $highestRow = $sheet->getHighestRow();
        $borderRange = "A5:P" . $highestRow;

        $sheet->mergeCells('A1:P1');
        $sheet->mergeCells('A2:P2');
        $sheet->mergeCells('A3:P3');

        $sheet->getRowDimension(1)->setRowHeight(24);
        $sheet->getRowDimension(2)->setRowHeight(18);
        $sheet->getRowDimension(3)->setRowHeight(18);
        $sheet->getRowDimension(5)->setRowHeight(26);

        return [
            1 => [
                'font' => ['bold' => true, 'size' => 14, 'color' => ['argb' => 'FF1E3A8A']],
                'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
            ],
            2 => [
                'font' => ['italic' => true, 'size' => 11, 'color' => ['argb' => 'FF475569']],
                'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
            ],
            3 => [
                'font' => ['bold' => true, 'size' => 11, 'color' => ['argb' => 'FF1E3A8A']],
                'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
            ],
            5 => [
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF'], 'size' => 10],
                'alignment' => ['horizontal' => 'center', 'vertical' => 'center', 'wrapText' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FF1E40AF'] // Deep Blue Header
                ],
                'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, 'color' => ['argb' => 'FFCBD5E1']]]
            ],
            $borderRange => [
                'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, 'color' => ['argb' => 'FFCBD5E1']]]
            ],
        ];
    }

    public function title(): string
    {
        return 'Kedisiplinan Mesin';
    }

    public function registerEvents(): array
    {
        return [
            \Maatwebsite\Excel\Events\AfterSheet::class => function (\Maatwebsite\Excel\Events\AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();

                // Setup Orientasi Landscape & A4
                $sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
                $sheet->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_A4);

                // Atur lebar kolom yang rapi dan proporsional (Kolom No compact)
                $sheet->getColumnDimension('A')->setAutoSize(false)->setWidth(6);
                $sheet->getColumnDimension('B')->setAutoSize(false)->setWidth(12);
                $sheet->getColumnDimension('C')->setAutoSize(false)->setWidth(30);
                $sheet->getColumnDimension('D')->setAutoSize(false)->setWidth(12);
                $sheet->getColumnDimension('E')->setAutoSize(false)->setWidth(16);
                $sheet->getColumnDimension('F')->setAutoSize(false)->setWidth(16);
                $sheet->getColumnDimension('G')->setAutoSize(false)->setWidth(16);
                $sheet->getColumnDimension('H')->setAutoSize(false)->setWidth(16);
                $sheet->getColumnDimension('I')->setAutoSize(false)->setWidth(16);
                $sheet->getColumnDimension('J')->setAutoSize(false)->setWidth(16);
                $sheet->getColumnDimension('K')->setAutoSize(false)->setWidth(14);
                $sheet->getColumnDimension('L')->setAutoSize(false)->setWidth(14);
                $sheet->getColumnDimension('M')->setAutoSize(false)->setWidth(14);
                $sheet->getColumnDimension('N')->setAutoSize(false)->setWidth(14);
                $sheet->getColumnDimension('O')->setAutoSize(false)->setWidth(16);
                $sheet->getColumnDimension('P')->setAutoSize(false)->setWidth(22);

                // Set default alignment
                $sheet->getStyle('A6:B' . $highestRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('C6:D' . $highestRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                $sheet->getStyle('E6:P' . $highestRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('A6:P' . $highestRow)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

                // Loop setiap baris data untuk pewarnaan status
                $dayColumns = ['E', 'F', 'G', 'H', 'I', 'J'];

                for ($row = 6; $row <= $highestRow; $row++) {
                    $sheet->getRowDimension($row)->setRowHeight(22);

                    // Zebra stripe untuk info siswa (A - D)
                    if ($row % 2 == 0) {
                        $sheet->getStyle('A' . $row . ':D' . $row)->getFill()
                            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                            ->getStartColor()->setARGB('FFF8FAFC');
                    }

                    // Warna untuk kolom kehadiran harian (E - J)
                    foreach ($dayColumns as $col) {
                        $cellVal = trim($sheet->getCell($col . $row)->getValue() ?? '');
                        $cellStyle = $sheet->getStyle($col . $row);

                        if (str_contains($cellVal, 'Mesin')) {
                            // Hijau Muda
                            $cellStyle->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFDCFCE7');
                            $cellStyle->getFont()->getColor()->setARGB('FF166534');
                            $cellStyle->getFont()->setBold(true);
                        } elseif (str_contains($cellVal, 'Manual')) {
                            // Oranye Muda
                            $cellStyle->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFFFEDD5');
                            $cellStyle->getFont()->getColor()->setARGB('FF9A3412');
                            $cellStyle->getFont()->setBold(true);
                        } elseif (str_contains($cellVal, 'Terlambat')) {
                            // Kuning / Amber
                            $cellStyle->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFFEF3C7');
                            $cellStyle->getFont()->getColor()->setARGB('FF92400E');
                            $cellStyle->getFont()->setBold(true);
                        } elseif (str_contains($cellVal, 'Sakit')) {
                            // Biru Muda
                            $cellStyle->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFE0F2FE');
                            $cellStyle->getFont()->getColor()->setARGB('FF0369A1');
                        } elseif (str_contains($cellVal, 'Izin')) {
                            // Ungu Muda
                            $cellStyle->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFEDE9FE');
                            $cellStyle->getFont()->getColor()->setARGB('FF5B21B6');
                        } elseif (str_contains($cellVal, 'Alfa')) {
                            // Merah Muda
                            $cellStyle->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFFEE2E2');
                            $cellStyle->getFont()->getColor()->setARGB('FF991B1B');
                            $cellStyle->getFont()->setBold(true);
                        } else {
                            $cellStyle->getFont()->getColor()->setARGB('FF94A3B8');
                        }
                    }

                    // Warna untuk Kolom Total (K - N)
                    $sheet->getStyle('K' . $row)->getFont()->getColor()->setARGB('FF166534'); // Total Hadir Mesin (Green)
                    $sheet->getStyle('K' . $row)->getFont()->setBold(true);

                    $sheet->getStyle('L' . $row)->getFont()->getColor()->setARGB('FFB45309'); // Total Hadir Manual (Orange)
                    $sheet->getStyle('L' . $row)->getFont()->setBold(true);

                    // Warna untuk Kolom % Disiplin (O) & Status (P)
                    $statusVal = trim($sheet->getCell('P' . $row)->getValue() ?? '');
                    $statusStyle = $sheet->getStyle('P' . $row);
                    $persenStyle = $sheet->getStyle('O' . $row);

                    if (str_contains($statusVal, 'Sangat Tertib')) {
                        $statusStyle->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFDCFCE7');
                        $statusStyle->getFont()->getColor()->setARGB('FF166534');
                        $statusStyle->getFont()->setBold(true);

                        $persenStyle->getFont()->getColor()->setARGB('FF166534');
                        $persenStyle->getFont()->setBold(true);
                    } elseif (str_contains($statusVal, 'Cukup Tertib')) {
                        $statusStyle->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFFEF9C3');
                        $statusStyle->getFont()->getColor()->setARGB('FF854D0E');
                        $statusStyle->getFont()->setBold(true);

                        $persenStyle->getFont()->getColor()->setARGB('FF854D0E');
                        $persenStyle->getFont()->setBold(true);
                    } elseif (str_contains($statusVal, 'Sering Manual') || str_contains($statusVal, 'Lalai')) {
                        $statusStyle->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFFEE2E2');
                        $statusStyle->getFont()->getColor()->setARGB('FF991B1B');
                        $statusStyle->getFont()->setBold(true);

                        $persenStyle->getFont()->getColor()->setARGB('FF991B1B');
                        $persenStyle->getFont()->setBold(true);
                    } else {
                        $statusStyle->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFF1F5F9');
                        $statusStyle->getFont()->getColor()->setARGB('FF64748B');
                    }
                }

                // Tambahkan Legend / Petunjuk Indikator di Bawah Tabel
                $legendStart = $highestRow + 2;
                $sheet->mergeCells('A' . $legendStart . ':D' . $legendStart);
                $sheet->setCellValue('A' . $legendStart, 'PETUNJUK INDIKATOR WARNA:');
                $titleFont = $sheet->getStyle('A' . $legendStart)->getFont();
                $titleFont->setBold(true);
                $titleFont->setSize(10);
                $titleFont->getColor()->setARGB('FF334155');

                $legendRow = $legendStart + 1;
                $sheet->getRowDimension($legendRow)->setRowHeight(22);

                $legendItems = [
                    ['start' => 'A', 'end' => 'B', 'text' => 'Mesin (Otomatis)', 'bg' => 'FFDCFCE7', 'fg' => 'FF166534'],
                    ['start' => 'C', 'end' => 'D', 'text' => 'Manual Guru', 'bg' => 'FFFFEDD5', 'fg' => 'FF9A3412'],
                    ['start' => 'E', 'end' => 'F', 'text' => 'Terlambat Piket', 'bg' => 'FFFEF3C7', 'fg' => 'FF92400E'],
                    ['start' => 'G', 'end' => 'H', 'text' => 'Sakit / Izin', 'bg' => 'FFE0F2FE', 'fg' => 'FF0369A1'],
                    ['start' => 'I', 'end' => 'J', 'text' => 'Alfa', 'bg' => 'FFFEE2E2', 'fg' => 'FF991B1B'],
                ];

                foreach ($legendItems as $item) {
                    $range = $item['start'] . $legendRow . ':' . $item['end'] . $legendRow;
                    $sheet->mergeCells($range);
                    $sheet->setCellValue($item['start'] . $legendRow, $item['text']);
                    
                    $style = $sheet->getStyle($range);
                    $style->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($item['bg']);
                    $style->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                    $style->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                    $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN)->getColor()->setARGB('FFCBD5E1');

                    $itemFont = $style->getFont();
                    $itemFont->setBold(true);
                    $itemFont->setSize(9);
                    $itemFont->getColor()->setARGB($item['fg']);
                }
            },
        ];
    }
}
