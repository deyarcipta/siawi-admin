<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Praktik Kejuruan - {{ $periodeLabel }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #333;
            margin: 0;
            padding: 10px;
        }
        .header-kop {
            text-align: center;
            border-bottom: 2px solid #222;
            padding-bottom: 8px;
            margin-bottom: 15px;
        }
        .header-kop img.kop-banner {
            max-width: 100%;
            height: auto;
            max-height: 80px;
        }
        .kop-text-sekolah {
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            margin: 0 0 4px 0;
        }
        .kop-text-alamat {
            font-size: 10px;
            color: #555;
            margin: 0;
        }
        .judul-laporan {
            text-align: center;
            margin-bottom: 15px;
        }
        .judul-laporan h3 {
            margin: 0 0 4px 0;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .judul-laporan p {
            margin: 0;
            font-size: 11px;
            color: #555;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table.data-table th, table.data-table td {
            border: 1px solid #444;
            padding: 6px 8px;
            vertical-align: middle;
        }
        table.data-table th {
            background-color: #f2f2f2;
            text-align: center;
            font-weight: bold;
        }
        .text-center {
            text-align: center;
        }
        .badge-status {
            font-size: 9px;
            padding: 2px 5px;
            border-radius: 3px;
            font-weight: bold;
            display: inline-block;
        }
        .status-disetujui {
            background-color: #d4edda;
            color: #155724;
        }
        .status-menunggu {
            background-color: #fff3cd;
            color: #856404;
        }
        .status-ditolak {
            background-color: #f8d7da;
            color: #721c24;
        }
        .ttd-table {
            width: 100%;
            margin-top: 25px;
            border: none;
        }
        .ttd-table td {
            border: none;
            text-align: center;
            vertical-align: top;
            width: 50%;
        }
        .ttd-space {
            height: 60px;
        }
    </style>
</head>
<body>

    <!-- KOP SURAT SEKOLAH -->
    <div class="header-kop">
        @if($setting && $setting->kop_surat && file_exists(public_path('storage/gambar/' . $setting->kop_surat)))
            <img src="{{ public_path('storage/gambar/' . $setting->kop_surat) }}" class="kop-banner" alt="Kop Surat">
        @else
            <div class="kop-text-sekolah">{{ $setting->nama_sekolah ?? 'SMK PUSAT KEUNGGULAN' }}</div>
            <div class="kop-text-alamat">
                {{ $setting->alamat ?? 'Jalan Raya Pendidikan No. 1' }} | Telp: {{ $setting->no_telp ?? '-' }} | Website: {{ $setting->website ?? '-' }}
            </div>
        @endif
    </div>

    <!-- JUDUL LAPORAN -->
    <div class="judul-laporan">
        <h3>Laporan Realisasi Praktik Kejuruan & Penggunaan Lab</h3>
        <p>Periode: <strong>{{ $periodeLabel }}</strong></p>
    </div>

    <!-- DATA TABEL -->
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 25px;">No</th>
                <th style="width: 75px;">Tanggal</th>
                <th style="width: 80px;">Kelas</th>
                <th>Mata Pelajaran</th>
                <th>Laboratorium</th>
                <th>Guru Instruktur</th>
                <th>Materi Praktik</th>
                <th style="width: 50px;">Hadir</th>
                <th style="width: 80px;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($jurnals as $j)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td class="text-center">{{ $j->tanggal->format('d/m/Y') }}</td>
                    <td class="text-center">{{ $j->jadwalPraktik->kelas->nama_kelas ?? '-' }}</td>
                    <td>{{ $j->jadwalPraktik->mapel->nama_mapel ?? '-' }}</td>
                    <td>{{ $j->jadwalPraktik->lab->nama_lab ?? '-' }}</td>
                    <td>{{ $j->jadwalPraktik->guru->nama_guru ?? '-' }}</td>
                    <td>{{ $j->materi }}</td>
                    <td class="text-center">{{ $j->jumlah_hadir }}</td>
                    <td class="text-center">
                        @if($j->status_approval === 'disetujui')
                            <span class="badge-status status-disetujui">Disetujui</span>
                        @elseif($j->status_approval === 'ditolak')
                            <span class="badge-status status-ditolak">Ditolak</span>
                        @else
                            <span class="badge-status status-menunggu">Menunggu</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center">Tidak ada catatan jurnal praktik pada periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- TANDA TANGAN -->
    <table class="ttd-table">
        <tr>
            <td>
                Mengetahui,<br>
                <strong>Kepala Program Keahlian</strong>
                <div class="ttd-space"></div>
                <u><strong>{{ $kaprog->nama_guru ?? '...................................................' }}</strong></u><br>
                NIP/NUPTK: {{ $kaprog->username ?? '........................' }}
            </td>
            <td>
                Dicetak pada: {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}<br>
                <strong>Guru Instruktur Pengajar</strong>
                <div class="ttd-space"></div>
                <u><strong>{{ $user->nama_guru ?? '...................................................' }}</strong></u><br>
                NIP/NUPTK: {{ $user->username ?? '........................' }}
            </td>
        </tr>
    </table>

</body>
</html>
