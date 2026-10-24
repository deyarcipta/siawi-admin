<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Berita Acara UKK - {{ $kegiatan->nama_kegiatan }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #222;
            margin: 0;
            padding: 15px 25px;
            line-height: 1.4;
        }
        .header-kop {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 8px;
            margin-bottom: 18px;
        }
        .kop-text-sekolah {
            font-size: 15px;
            font-weight: bold;
            text-transform: uppercase;
            margin: 0 0 3px 0;
        }
        .kop-text-alamat {
            font-size: 10px;
            color: #444;
            margin: 0;
        }
        .judul-dokumen {
            text-align: center;
            margin-bottom: 20px;
        }
        .judul-dokumen h3 {
            margin: 0 0 4px 0;
            font-size: 13px;
            text-transform: uppercase;
            text-decoration: underline;
        }
        .judul-dokumen p {
            margin: 0;
            font-size: 11px;
            color: #333;
        }
        .info-table {
            width: 100%;
            margin-bottom: 15px;
        }
        .info-table td {
            padding: 3px 0;
            vertical-align: top;
        }
        table.border-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        table.border-table th, table.border-table td {
            border: 1px solid #333;
            padding: 6px 8px;
            vertical-align: middle;
        }
        table.border-table th {
            background-color: #f1f5f9;
            text-align: center;
            font-weight: bold;
        }
        .ttd-box {
            width: 100%;
            margin-top: 30px;
        }
        .ttd-box td {
            vertical-align: top;
            text-align: center;
            width: 50%;
        }
    </style>
</head>
<body>

    <!-- KOP SURAT SEKOLAH -->
    <div class="header-kop">
        @if(!empty($setting->kop_surat) && file_exists(public_path('storage/' . $setting->kop_surat)))
            <img src="{{ public_path('storage/' . $setting->kop_surat) }}" style="max-width: 100%; height: auto; max-height: 80px;" alt="Kop Surat">
        @else
            <div class="kop-text-sekolah">{{ $setting->nama_sekolah ?? 'SMK PUSAT KEUNGGULAN' }}</div>
            <div class="kop-text-alamat">{{ $setting->alamat ?? 'Jl. Pendidikan No. 1' }} | Telp: {{ $setting->no_telp ?? '-' }} | Website: {{ $setting->website ?? '-' }}</div>
        @endif
    </div>

    <!-- JUDUL BERITA ACARA -->
    <div class="judul-dokumen">
        <h3>BERITA ACARA PELAKSANAAN UJI KOMPETENSI KEAHLIAN (UKK)</h3>
        <p>Nomor: {{ $kegiatan->nomor_sk ?: '... / UKK / SMK / ' . date('Y') }}</p>
    </div>

    <p style="text-align: justify; margin-bottom: 12px;">
        Pada hari ini, tanggal <strong>{{ \Carbon\Carbon::parse($kegiatan->tanggal_selesai)->translatedFormat('d F Y') }}</strong>, bertempat di <strong>{{ $kegiatan->lab->nama_lab ?? 'Laboratorium Praktik' }}</strong> {{ $setting->nama_sekolah ?? 'SMK' }}, telah diselenggarakan kegiatan asesmen Uji Kompetensi Keahlian (UKK) dengan rincian sebagai berikut:
    </p>

    <!-- TABEL INFORMASI KEGIATAN -->
    <table class="info-table">
        <tr>
            <td style="width: 25%;">Nama Kegiatan</td>
            <td style="width: 3%;">:</td>
            <td style="width: 72%;"><strong>{{ $kegiatan->nama_kegiatan }}</strong></td>
        </tr>
        <tr>
            <td>Program Keahlian</td>
            <td>:</td>
            <td>{{ $kegiatan->jurusan->nama_jurusan ?? '-' }}</td>
        </tr>
        <tr>
            <td>Waktu Pelaksanaan</td>
            <td>:</td>
            <td>{{ $kegiatan->tanggal_mulai->translatedFormat('d F Y') }} s.d. {{ $kegiatan->tanggal_selesai->translatedFormat('d F Y') }}</td>
        </tr>
        <tr>
            <td>Tahun Ajaran</td>
            <td>:</td>
            <td>{{ $kegiatan->tahun_ajaran }}</td>
        </tr>
        <tr>
            <td>Tempat Uji Kompetensi (TUK)</td>
            <td>:</td>
            <td>{{ $kegiatan->lab->nama_lab ?? 'Laboratorium Kejuruan' }}</td>
        </tr>
    </table>

    <!-- REKAPITULASI HASIL KELULUSAN -->
    <h4 style="margin: 15px 0 6px 0; font-size: 11px; text-transform: uppercase;">A. Rekapitulasi Hasil Peserta Asesmen</h4>
    <table class="border-table">
        <thead>
            <tr>
                <th>Total Peserta</th>
                <th>Hadir</th>
                <th>Kompeten (Lulus)</th>
                <th>Belum Kompeten</th>
                <th>Tingkat Kelulusan</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="text-align: center; font-weight: bold;">{{ $totalPeserta }} Siswa</td>
                <td style="text-align: center;">{{ $kegiatan->pesertas->where('status_kehadiran', 'hadir')->count() }} Siswa</td>
                <td style="text-align: center; font-weight: bold; color: green;">{{ $totalKompeten }} Siswa</td>
                <td style="text-align: center; font-weight: bold; color: red;">{{ $totalBelum }} Siswa</td>
                <td style="text-align: center; font-weight: bold;">
                    {{ $totalPeserta > 0 ? round(($totalKompeten / $totalPeserta) * 100) : 0 }}%
                </td>
            </tr>
        </tbody>
    </table>

    <!-- DAFTAR TIM PENGUJI / ASESOR -->
    <h4 style="margin: 15px 0 6px 0; font-size: 11px; text-transform: uppercase;">B. Tim Asesor / Penguji UKK</h4>
    <table class="border-table">
        <thead>
            <tr>
                <th style="width: 35px;">No</th>
                <th>Nama Asesor / Penguji</th>
                <th style="width: 100px;">Jenis</th>
                <th>Instansi / DU-DI</th>
                <th style="width: 140px;">Tanda Tangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($kegiatan->pengujis as $png)
                <tr>
                    <td style="text-align: center;">{{ $loop->iteration }}</td>
                    <td><strong>{{ $png->nama_tampil }}</strong></td>
                    <td style="text-align: center;">{{ ucfirst($png->jenis) }}</td>
                    <td>{{ $png->instansi ?: 'Internal Sekolah' }}</td>
                    <td style="height: 38px; vertical-align: bottom;">
                        <span style="font-size: 9px; color: #888;">{{ $loop->iteration }}. ......................................</span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center;">Belum ada tim penguji yang dicatat.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <p style="text-align: justify; margin-top: 15px;">
        Demikian Berita Acara Pelaksanaan Uji Kompetensi Keahlian ini dibuat dengan sesungguhnya untuk dapat dipergunakan sebagaimana mestinya.
    </p>

    <!-- TANDA TANGAN PENGESAHAN -->
    <table class="ttd-box">
        <tr>
            <td>
                Mengetahui,<br>
                Kepala Program Keahlian {{ $kegiatan->jurusan->nama_jurusan ?? '' }}<br><br><br><br>
                <strong><u>{{ $kegiatan->pembuat->nama_guru ?? '............................................' }}</u></strong><br>
                NIP. {{ $kegiatan->pembuat->nip ?? '-' }}
            </td>
            <td>
                Ditetapkan di: {{ $setting->kabupaten ?? 'Cianjur' }}<br>
                Pada tanggal: {{ \Carbon\Carbon::parse($kegiatan->tanggal_selesai)->translatedFormat('d F Y') }}<br>
                Kepala Sekolah,<br><br><br><br>
                <strong><u>{{ $setting->nama_kepala_sekolah ?? '............................................' }}</u></strong><br>
                NIP. {{ $setting->nip_kepala_sekolah ?? '-' }}
            </td>
        </tr>
    </table>

</body>
</html>
