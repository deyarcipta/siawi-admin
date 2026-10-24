<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekapitulasi Nilai UKK - {{ $kegiatan->nama_kegiatan }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            color: #222;
            margin: 0;
            padding: 10px;
        }
        .header-kop {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 6px;
            margin-bottom: 12px;
        }
        .kop-text-sekolah {
            font-size: 15px;
            font-weight: bold;
            text-transform: uppercase;
            margin: 0 0 2px 0;
        }
        .kop-text-alamat {
            font-size: 9px;
            color: #444;
            margin: 0;
        }
        .judul-rekap {
            text-align: center;
            margin-bottom: 15px;
        }
        .judul-rekap h3 {
            margin: 0 0 2px 0;
            font-size: 13px;
            text-transform: uppercase;
        }
        .judul-rekap p {
            margin: 0;
            font-size: 10px;
            color: #444;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        table.data-table th, table.data-table td {
            border: 1px solid #333;
            padding: 4px 6px;
            vertical-align: middle;
        }
        table.data-table th {
            background-color: #f1f5f9;
            text-align: center;
            font-weight: bold;
            font-size: 9px;
        }
        .badge-kompeten {
            color: green;
            font-weight: bold;
        }
        .badge-belum {
            color: red;
            font-weight: bold;
        }
        .ttd-box {
            width: 100%;
            margin-top: 20px;
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
            <img src="{{ public_path('storage/' . $setting->kop_surat) }}" style="max-width: 100%; height: auto; max-height: 70px;" alt="Kop Surat">
        @else
            <div class="kop-text-sekolah">{{ $setting->nama_sekolah ?? 'SMK PUSAT KEUNGGULAN' }}</div>
            <div class="kop-text-alamat">{{ $setting->alamat ?? 'Jl. Pendidikan No. 1' }} | Telp: {{ $setting->no_telp ?? '-' }} | Website: {{ $setting->website ?? '-' }}</div>
        @endif
    </div>

    <!-- JUDUL LAPORAN -->
    <div class="judul-rekap">
        <h3>REKAPITULASI PENILAIAN UJI KOMPETENSI KEAHLIAN (UKK)</h3>
        <p>Kegiatan: {{ $kegiatan->nama_kegiatan }} | Jurusan: {{ $kegiatan->jurusan->nama_jurusan ?? '-' }} | TA: {{ $kegiatan->tahun_ajaran }}</p>
    </div>

    <!-- TABEL REKAP NILAI -->
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 25px;" rowspan="2">No</th>
                <th style="width: 70px;" rowspan="2">NIS</th>
                <th rowspan="2">Nama Peserta Siswa</th>
                <th style="width: 70px;" rowspan="2">Kelas</th>
                <th colspan="{{ $kegiatan->units->count() }}">Nilai per Unit Kompetensi</th>
                <th style="width: 60px;" rowspan="2">Nilai Akhir</th>
                <th style="width: 85px;" rowspan="2">Status Kelulusan</th>
            </tr>
            <tr>
                @foreach($kegiatan->units as $u)
                    <th style="font-size: 8px;" title="{{ $u->kompetensi->nama_kompetensi ?? '' }}">
                        {{ $u->kompetensi->kode_kompetensi ?? ('Unit ' . $loop->iteration) }}<br>
                        (KKM: {{ floatval($u->kkm) }})
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @forelse($kegiatan->pesertas as $p)
                @php
                    $nilaiMap = $p->nilais->keyBy('id_ukk_unit');
                @endphp
                <tr>
                    <td style="text-align: center;">{{ $loop->iteration }}</td>
                    <td style="text-align: center;">{{ $p->siswa->nis ?? '-' }}</td>
                    <td><strong>{{ $p->siswa->nama_siswa ?? 'Siswa' }}</strong></td>
                    <td style="text-align: center;">{{ $p->siswa->kelas->nama_kelas ?? '-' }}</td>

                    @foreach($kegiatan->units as $u)
                        @php
                            $nl = $nilaiMap[$u->id_ukk_unit] ?? null;
                            $score = $nl ? floatval($nl->nilai) : null;
                        @endphp
                        <td style="text-align: center; {{ $score !== null && $score < $u->kkm ? 'color: red; font-weight: bold;' : '' }}">
                            {{ $score !== null ? $score : '-' }}
                        </td>
                    @endforeach

                    <td style="text-align: center; font-weight: bold; font-size: 11px;">
                        {{ $p->status_kelulusan !== 'belum_dinilai' ? floatval($p->nilai_akhir) : '-' }}
                    </td>
                    <td style="text-align: center;">
                        @if($p->status_kelulusan === 'kompeten')
                            <span class="badge-kompeten">KOMPETEN</span>
                        @elseif($p->status_kelulusan === 'belum_kompeten')
                            <span class="badge-belum">BELUM KOMPETEN</span>
                        @else
                            <span style="color: #666;">BELUM DINILAI</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="{{ 6 + $kegiatan->units->count() }}" style="text-align: center; padding: 15px;">
                        Belum ada peserta terdaftar dalam kegiatan UKK ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- TANDA TANGAN -->
    <table class="ttd-box">
        <tr>
            <td>
                Mengetahui,<br>
                Kepala Sekolah<br><br><br><br>
                <strong><u>{{ $setting->nama_kepala_sekolah ?? '............................................' }}</u></strong><br>
                NIP. {{ $setting->nip_kepala_sekolah ?? '-' }}
            </td>
            <td>
                {{ $setting->kabupaten ?? 'Cianjur' }}, {{ \Carbon\Carbon::parse($kegiatan->tanggal_selesai)->translatedFormat('d F Y') }}<br>
                Kepala Program Keahlian {{ $kegiatan->jurusan->nama_jurusan ?? '' }}<br><br><br><br>
                <strong><u>{{ $kegiatan->pembuat->nama_guru ?? '............................................' }}</u></strong><br>
                NIP. {{ $kegiatan->pembuat->nip ?? '-' }}
            </td>
        </tr>
    </table>

</body>
</html>
