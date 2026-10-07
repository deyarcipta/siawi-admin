<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekapitulasi Presensi Kehadiran Siswa - {{ $dataKelas->nama_kelas }}</title>
    <style>
        @page {
            margin: 20px 25px 25px 25px;
            size: A4 portrait;
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10px;
            color: #1f2937;
            line-height: 1.3;
        }
        .kop-table {
            width: 100%;
            border-collapse: collapse;
            border-bottom: 2px solid #1e3a8a;
            padding-bottom: 8px;
            margin-bottom: 12px;
        }
        .kop-table td {
            vertical-align: middle;
        }
        .kop-logo {
            width: 65px;
            text-align: center;
        }
        .kop-logo img {
            width: 55px;
            height: auto;
        }
        .kop-text {
            text-align: center;
        }
        .kop-text h2 {
            margin: 0;
            font-size: 15px;
            font-weight: bold;
            color: #1e3a8a;
            letter-spacing: 0.5px;
        }
        .kop-text h1 {
            margin: 2px 0;
            font-size: 17px;
            font-weight: 800;
            color: #0f172a;
        }
        .kop-text p {
            margin: 0;
            font-size: 8.5px;
            color: #475569;
        }
        .report-title {
            text-align: center;
            margin-bottom: 12px;
        }
        .report-title h3 {
            margin: 0;
            font-size: 13px;
            font-weight: bold;
            text-transform: uppercase;
            color: #1e3a8a;
            letter-spacing: 0.5px;
        }
        .report-title p {
            margin: 3px 0 0 0;
            font-size: 10px;
            color: #64748b;
            font-weight: bold;
        }
        .info-table {
            width: 100%;
            margin-bottom: 10px;
            border-collapse: collapse;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 6px 10px;
            border-radius: 4px;
        }
        .info-table td {
            padding: 3px 6px;
            font-size: 9.5px;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }
        .data-table th {
            background-color: #1e3a8a;
            color: #ffffff;
            font-size: 9px;
            font-weight: bold;
            padding: 6px 4px;
            border: 1px solid #cbd5e1;
            text-align: center;
            text-transform: uppercase;
        }
        .data-table td {
            padding: 4.5px 4px;
            border: 1px solid #cbd5e1;
            font-size: 9px;
            vertical-align: middle;
        }
        .data-table tr:nth-child(even) {
            background-color: #f8fafc;
        }
        .text-center { text-align: center; }
        .text-left { text-align: left; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        
        .badge-green {
            background-color: #d4edda;
            color: #155724;
            font-weight: bold;
            padding: 2px 4px;
            border-radius: 3px;
        }
        .badge-red {
            background-color: #f8d7da;
            color: #721c24;
            font-weight: bold;
            padding: 2px 4px;
            border-radius: 3px;
        }
        .signature-section {
            width: 100%;
            margin-top: 25px;
            page-break-inside: avoid;
        }
        .signature-table {
            width: 100%;
            border-collapse: collapse;
        }
        .signature-table td {
            width: 50%;
            text-align: center;
            vertical-align: top;
            font-size: 9.5px;
        }
    </style>
</head>
<body>

    <!-- KOP SURAT RESMI -->
    <table class="kop-table">
        <tr>
            <td class="kop-logo">
                @if(!empty($setting->logo) && file_exists(public_path('storage/logo/' . $setting->logo)))
                    <img src="{{ public_path('storage/logo/' . $setting->logo) }}" alt="Logo">
                @elseif(file_exists(public_path('images/logo.png')))
                    <img src="{{ public_path('images/logo.png') }}" alt="Logo">
                @endif
            </td>
            <td class="kop-text">
                <h2>YAYASAN PENDIDIKAN WISATA INDONESIA</h2>
                <h1>{{ strtoupper($setting->nama_sekolah ?? 'SMK WISATA INDONESIA') }}</h1>
                <p>{{ $setting->alamat ?? 'Jl. Raya Wisata No. 12, Jakarta Selatan' }} | Telp: {{ $setting->no_telp ?? '-' }}</p>
                <p>Website: https://siawi.smkwisataindonesia.sch.id | Email: {{ $setting->email ?? 'info@smkwisataindonesia.sch.id' }}</p>
            </td>
        </tr>
    </table>

    <!-- JUDUL LAPORAN -->
    <div class="report-title">
        <h3>REKAPITULASI PRESENSI KEHADIRAN SISWA</h3>
        <p>Periode: {{ \Carbon\Carbon::parse($tanggal_awal)->translatedFormat('d F Y') }} s/d {{ \Carbon\Carbon::parse($tanggal_akhir)->translatedFormat('d F Y') }}</p>
    </div>

    <!-- INFORMASI KELAS & STATISTIK -->
    <table class="info-table">
        <tr>
            <td width="15%" class="font-bold">Kelas</td>
            <td width="35%">: <strong>{{ $dataKelas->nama_kelas }}</strong></td>
            <td width="20%" class="font-bold">Total Siswa</td>
            <td width="30%">: {{ count($siswa) }} Siswa</td>
        </tr>
        <tr>
            <td class="font-bold">Wali Kelas</td>
            <td>: {{ $dataKelas->guru->nama_guru ?? '-' }}</td>
            <td class="font-bold">Rata-rata Kehadiran</td>
            <td>: 
                @php
                    $sumPct = 0;
                    $validCount = 0;
                    foreach ($siswa as $s) {
                        $tot = $absensiSiswa[$s->id_siswa] ?? 0;
                        $msk = $countMasuk[$s->id_siswa] ?? 0;
                        if ($tot > 0) {
                            $sumPct += ($msk / $tot) * 100;
                            $validCount++;
                        }
                    }
                    $avgPct = $validCount > 0 ? round($sumPct / $validCount, 1) : 100;
                @endphp
                <span class="{{ $avgPct >= 90 ? 'badge-green' : 'badge-red' }}">{{ $avgPct }}%</span>
            </td>
        </tr>
    </table>

    <!-- TABEL DATA REKAP -->
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 25px;">No</th>
                <th style="text-align: left; padding-left: 6px;">Nama Siswa</th>
                <th style="width: 50px;">Total Hari</th>
                <th style="width: 45px;">Hadir</th>
                <th style="width: 35px;">S</th>
                <th style="width: 35px;">I</th>
                <th style="width: 35px;">A</th>
                <th style="width: 55px;">Total S/I/A</th>
                <th style="width: 65px;">% Kehadiran</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totHadirAll = 0;
                $totSakitAll = 0;
                $totIzinAll = 0;
                $totAlfaAll = 0;
            @endphp
            @forelse($siswa as $index => $item)
                @php
                    $id = $item->id_siswa;
                    $totalAbsen = $absensiSiswa[$id] ?? 0;
                    $masuk = $countMasuk[$id] ?? 0;
                    $sakit = $countSakit[$id] ?? 0;
                    $izin = $countIzin[$id] ?? 0;
                    $alfa = $countAlfa[$id] ?? 0;
                    $totalTidakHadir = $sakit + $izin + $alfa;
                    $presentase = $totalAbsen > 0 ? round(($masuk / $totalAbsen) * 100, 1) : 0;

                    $totHadirAll += $masuk;
                    $totSakitAll += $sakit;
                    $totIzinAll += $izin;
                    $totAlfaAll += $alfa;
                @endphp
                <tr>
                    <td class="text-center font-bold">{{ $index + 1 }}</td>
                    <td class="text-left font-bold" style="padding-left: 6px;">{{ strtoupper($item->nama_siswa) }}</td>
                    <td class="text-center">{{ $totalAbsen }}</td>
                    <td class="text-center font-bold" style="color: #1e3a8a;">{{ $masuk }}</td>
                    <td class="text-center">{{ $sakit > 0 ? $sakit : '-' }}</td>
                    <td class="text-center">{{ $izin > 0 ? $izin : '-' }}</td>
                    <td class="text-center" style="{{ $alfa > 0 ? 'color: #b91c1c; font-weight: bold;' : '' }}">{{ $alfa > 0 ? $alfa : '-' }}</td>
                    <td class="text-center">{{ $totalTidakHadir > 0 ? $totalTidakHadir : '-' }}</td>
                    <td class="text-center">
                        <span class="{{ $presentase >= 90 ? 'badge-green' : 'badge-red' }}">
                            {{ $presentase }}%
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center" style="padding: 15px; color: #94a3b8;">
                        Tidak ada data presensi siswa pada periode ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
        @if(count($siswa) > 0)
        <tfoot>
            <tr style="background-color: #f1f5f9; font-weight: bold;">
                <td colspan="3" class="text-center" style="padding: 6px;">TOTAL KESELURUHAN</td>
                <td class="text-center" style="color: #1e3a8a;">{{ $totHadirAll }}</td>
                <td class="text-center">{{ $totSakitAll }}</td>
                <td class="text-center">{{ $totIzinAll }}</td>
                <td class="text-center" style="color: #b91c1c;">{{ $totAlfaAll }}</td>
                <td class="text-center">{{ $totSakitAll + $totIzinAll + $totAlfaAll }}</td>
                <td class="text-center">{{ $avgPct }}%</td>
            </tr>
        </tfoot>
        @endif
    </table>

    <!-- TANDA TANGAN -->
    <div class="signature-section">
        <table class="signature-table">
            <tr>
                <td>
                    Mengetahui,<br>
                    Kepala Sekolah
                    <br><br><br><br><br>
                    <strong><u>M. Taufiqurrahman, S.Hum.</u></strong><br>
                    <span>Kepala SMK Wisata Indonesia</span>
                </td>
                <td>
                    Jakarta, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}<br>
                    Wali Kelas {{ $dataKelas->nama_kelas }}
                    <br><br><br><br><br>
                    <strong><u>{{ $dataKelas->guru->nama_guru ?? '( ............................................... )' }}</u></strong><br>
                    <span>NIP/NUPTK: {{ $dataKelas->guru->nip ?? '-' }}</span>
                </td>
            </tr>
        </table>
    </div>

</body>
</html>
