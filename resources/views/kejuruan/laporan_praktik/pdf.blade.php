<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Praktik Kejuruan - {{ $periodeLabel }}</title>
    <style>
        @page {
            margin: 20px 25px 25px 25px;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .header-kop-table {
            width: 100%;
            border-collapse: collapse;
            border: none;
            margin-bottom: 4px;
        }
        .header-kop-table td {
            border: none !important;
            padding: 0;
            vertical-align: middle;
        }
        .header-kop-table .kop-logo-td {
            width: 70px;
            text-align: center;
        }
        .header-kop-table .kop-logo-td img {
            max-width: 60px;
            max-height: 60px;
        }
        .header-kop-table .kop-text-td {
            text-align: center;
            padding-right: 70px;
        }
        .kop-yayasan {
            font-size: 11px;
            font-weight: bold;
            color: #111;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        .kop-sekolah {
            font-size: 17px;
            font-weight: bold;
            color: #000;
            margin: 2px 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .kop-kejuruan {
            font-size: 10px;
            font-weight: bold;
            color: #333;
            margin-bottom: 2px;
        }
        .kop-alamat {
            font-size: 9px;
            color: #444;
        }
        .kop-divider-line {
            border-bottom: 2.5px solid #000;
            margin-bottom: 12px;
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
        .materi-box {
            text-align: left;
            font-size: 10px;
            line-height: 1.4;
        }
        .materi-box p {
            margin: 0 0 3px 0;
        }
        .materi-box ul, .materi-box ol {
            margin: 0;
            padding-left: 14px;
        }
        .materi-box li {
            margin-bottom: 2px;
        }
        .filter-info-table {
            width: 100%;
            border: none;
            margin-bottom: 8px;
        }
        .filter-info-table td {
            border: none;
            padding: 2px 4px;
            font-size: 11px;
        }
    </style>
</head>
<body>

    <!-- KOP SURAT SEKOLAH (LANDSCAPE RESMI) -->
    @php
        $appSetting = $setting ?? \App\Models\Setting::find(1);
    @endphp
    <table class="header-kop-table">
        <tr>
            <td class="kop-logo-td">
                @if($appSetting && $appSetting->logo && file_exists(public_path('storage/gambar/' . $appSetting->logo)))
                    <img src="{{ public_path('storage/gambar/' . $appSetting->logo) }}" alt="Logo">
                @endif
            </td>
            <td class="kop-text-td">
                <div class="kop-yayasan">YAYASAN TAQWASH SHOBIRIN</div>
                <div class="kop-sekolah">{{ strtoupper($appSetting->nama_sekolah ?? 'SMK WISATA INDONESIA') }}</div>
                <div class="kop-kejuruan">Perhotelan &bull; Kuliner &bull; Teknik Komputer dan Jaringan</div>
                <div class="kop-alamat">{{ $appSetting->alamat ?? 'Jl. Raya Lenteng Agung Gg. Langgar Kebagusan Pasar Minggu Jakarta Selatan' }} | Tlp/Fax: {{ $appSetting->no_telp ?? '78830781' }}</div>
            </td>
        </tr>
    </table>
    <div class="kop-divider-line"></div>

    <!-- JUDUL LAPORAN -->
    <div class="judul-laporan">
        <h3>Laporan Realisasi Praktik Kejuruan & Penggunaan Lab</h3>
        <p>Periode: <strong>{{ $periodeLabel }}</strong></p>
    </div>

    @if(!empty($selectedGuru) || !empty($selectedLab))
    <table class="filter-info-table">
        <tr>
            @if(!empty($selectedGuru))
            <td style="width: 50%;">
                <strong>Guru Pengampu / Instruktur:</strong> {{ $selectedGuru->nama_guru }} 
                @if($selectedGuru->username) <span style="color: #666;">(NIP/NUPTK: {{ $selectedGuru->username }})</span> @endif
            </td>
            @endif
            @if(!empty($selectedLab))
            <td style="{{ !empty($selectedGuru) ? 'width: 50%; text-align: right;' : 'width: 100%;' }}">
                <strong>Laboratorium:</strong> {{ $selectedLab->nama_lab }}
            </td>
            @endif
        </tr>
    </table>
    @endif

    <!-- DATA TABEL -->
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 25px;">No</th>
                <th style="width: 70px;">Tanggal</th>
                <th style="width: 75px;">Kelas</th>
                <th>Mata Pelajaran</th>
                <th>Laboratorium</th>
                @if(empty($selectedGuru))
                    <th style="width: 125px;">Guru Instruktur</th>
                @endif
                <th style="{{ !empty($selectedGuru) ? 'width: 45%;' : 'width: 32%;' }}">Materi Praktik</th>
                <th style="width: 45px;">Hadir</th>
                <th style="width: 75px;">Status</th>
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
                    @if(empty($selectedGuru))
                        <td>{{ $j->jadwalPraktik->guru->nama_guru ?? '-' }}</td>
                    @endif
                    <td class="materi-box">
                        {!! strip_tags($j->materi, '<p><br><b><strong><i><em><u><ul><ol><li><code>') !!}
                    </td>
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
                    <td colspan="{{ !empty($selectedGuru) ? 8 : 9 }}" class="text-center">Tidak ada catatan jurnal praktik pada periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- TANDA TANGAN -->
    <table class="ttd-table">
        <tr>
            @if(!empty($selectedGuru))
                {{-- Skenario A: Rekap Khusus Per Guru --}}
                <td>
                    Mengetahui,<br>
                    <strong>Kepala Program Keahlian</strong>
                    <div class="ttd-space"></div>
                    <u><strong>{{ $kaprog->nama_guru ?? ($user->hasRole('kaprog') ? $user->nama_guru : '...................................................') }}</strong></u><br>
                    NIP/NUPTK: {{ $kaprog->username ?? ($user->hasRole('kaprog') ? $user->username : '........................') }}
                </td>
                <td>
                    Dicetak pada: {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}<br>
                    <strong>Guru Instruktur Pengajar</strong>
                    <div class="ttd-space"></div>
                    <u><strong>{{ $selectedGuru->nama_guru }}</strong></u><br>
                    NIP/NUPTK: {{ $selectedGuru->username ?? '........................' }}
                </td>
            @else
                {{-- Skenario B: Rekap Umum Jurusan (Semua Guru) --}}
                <td>
                    Mengetahui,<br>
                    <strong>Kepala Sekolah</strong>
                    <div class="ttd-space"></div>
                    <u><strong>{{ $setting->nama_kepsek ?? '...................................................' }}</strong></u><br>
                    NIP/NUPTK: {{ $setting->nip_kepsek ?? '........................' }}
                </td>
                <td>
                    Dicetak pada: {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}<br>
                    <strong>Kepala Program Keahlian</strong>
                    <div class="ttd-space"></div>
                    <u><strong>{{ $kaprog->nama_guru ?? ($user->hasRole('kaprog') ? $user->nama_guru : '...................................................') }}</strong></u><br>
                    NIP/NUPTK: {{ $kaprog->username ?? ($user->hasRole('kaprog') ? $user->username : '........................') }}
                </td>
            @endif
        </tr>
    </table>

</body>
</html>
