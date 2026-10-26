<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Jurnal Mengajar</title>
    <style>
        @page {
            margin: 20px 25px 25px 25px;
        }
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; margin: 0; padding: 0; }
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
        .title-section { text-align: center; margin-bottom: 12px; }
        .title-section h3 { margin: 0; font-size: 14px; text-transform: uppercase; }
        .title-section p { margin: 3px 0 0 0; font-size: 11px; color: #555; }
        table.data-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table.data-table th, table.data-table td { border: 1px solid #333; padding: 6px; text-align: left; }
        table.data-table th { background: #f2f2f2; font-weight: bold; }
    </style>
</head>
<body>
    <!-- KOP SURAT (LANDSCAPE RESMI) -->
    @php
        $appSetting = \App\Models\Setting::find(1);
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

    <div class="title-section">
        <h3>Laporan Jurnal Mengajar Guru</h3>
        <p>Periode: {{ $tanggalAwal }} s/d {{ $tanggalAkhir }}</p>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Guru</th>
                <th>Mata Pelajaran</th>
                <th>Jam</th>
                <th>Kelas</th>
                <th>Materi</th>
                <th>Foto</th>
            </tr>
        </thead>
        <tbody>
            @foreach($jurnal as $i => $j)
            <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $j->tanggal }}</td>
                <td>{{ $j->guru->nama_guru ?? '-' }}</td>
                <td>{{ $j->jadwal->mapel->nama_mapel ?? '-' }}</td>
                <td>{{ $j->jam_awal ?? '-' }} s/d {{ $j->jam_akhir ?? '-'}}</td>
                <td>{{ $j->kelas->nama_kelas ?? '-' }}</td>
                <td>{{ $j->materi }}</td>
                <td>
                  @if($j->foto_kelas && file_exists(public_path('storage/' . str_replace('storage/', '', $j->foto_kelas))))
                      <img src="{{ public_path('storage/' . $j->foto_kelas) }}"
                        alt="Foto {{ $j->materi }}"
                        class="img-thumbnail"
                        style="max-height:80px;">
                  @else
                      <span class="text-muted">Tidak ada foto</span>
                  @endif
              </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
