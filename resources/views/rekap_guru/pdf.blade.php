<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rekap Kehadiran Mengajar Guru</title>
    <style>
        @page {
            margin: 20px 25px 25px 25px;
        }
        body { font-family: sans-serif; font-size: 11px; margin: 0; padding: 0; }
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
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            table-layout: fixed;
        }
        table.data-table th, table.data-table td {
            border: 1px solid #000;
            padding: 4px 2px;
            text-align: center;
            font-size: 9px;
            word-wrap: break-word;
            overflow: hidden;
        }
        table.data-table th {
            background: #ddd;
            font-weight: bold;
        }
        thead {
            display: table-header-group;
        }
        tr {
            page-break-inside: avoid;
        }
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
        <h3>Rekap Kehadiran Mengajar Guru</h3>
        <p>Periode: {{ $tanggalAwal }} s/d {{ $tanggalAkhir }}</p>
    </div>

    <table class="data-table">
        <colgroup>
            <col style="width: 3%;">
            <col style="width: 15%;">
            <col style="width: 14%;">
            <col style="width: 7%;">
            <col style="width: 7%;">
            <col style="width: 8%;">
            <col style="width: 6%;">
            <col style="width: 11%;">
            <col style="width: 7%;">
            <col style="width: 9%;">
            <col style="width: 6%;">
            <col style="width: 7%;">
        </colgroup>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Guru</th>
                <th>Mata Pelajaran</th>
                <th>Kelas</th>
                <th>Tanggal</th>
                <th>Tanggal Isi Jurnal</th>
                <th>Hari</th>
                <th>Jam Absen</th>
                <th>Jam Jurnal</th>
                <th>Materi Jurnal</th>
                <th>Status Absensi</th>
                <th>Status Jurnal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $row)
            <tr>
                <td>{{ $row['no'] }}</td>
                <td style="text-align: left;">{{ $row['nama_guru'] }}</td>
                <td style="text-align: left;">{{ $row['mapel'] }}</td>
                <td>{{ $row['kelas'] }}</td>
                <td>{{ $row['tanggal'] }}</td>
                <td>
                    {{ $row['created_at'] ? \Carbon\Carbon::parse($row['created_at'])->format('d-m-Y H:i') : '-' }}
                </td>
                <td>{{ $row['hari'] }}</td>
                <td>{{ $row['jam_absen'] }}</td>
                <td>{{ $row['jam_jurnal'] }}</td>
                <td style="text-align: left;">{{ $row['jurnal'] }}</td>
                <td>{{ $row['status_absensi'] }}</td>
                <td>{{ $row['status_jurnal'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
