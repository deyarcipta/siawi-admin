<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Inventaris {{ $lab->nama_lab ?? 'Laboratorium' }}</title>
    <style>
        @page {
            margin: 20px 25px 25px 25px;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 10px;
            color: #1e293b;
            line-height: 1.3;
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
            margin-bottom: 14px;
        }
        .judul-laporan h3 {
            margin: 0;
            font-size: 13px;
            text-transform: uppercase;
            font-weight: bold;
            color: #0f172a;
        }
        .judul-laporan p {
            margin: 2px 0 0 0;
            font-size: 10px;
            color: #475569;
        }
        .meta-table {
            width: 100%;
            margin-bottom: 10px;
            border-collapse: collapse;
        }
        .meta-table td {
            font-size: 10px;
            padding: 2px 4px;
            border: none;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .data-table th, .data-table td {
            border: 1px solid #cbd5e1;
            padding: 5px 6px;
            font-size: 9px;
        }
        .data-table th {
            background-color: #f1f5f9;
            font-weight: bold;
            text-transform: uppercase;
            text-align: center;
            color: #0f172a;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .badge {
            display: inline-block;
            padding: 2px 5px;
            font-size: 8px;
            font-weight: bold;
            border-radius: 3px;
        }
        .badge-alat { background-color: #e0f2fe; color: #0369a1; }
        .badge-bahan { background-color: #fef3c7; color: #b45309; }
        .badge-baik { background-color: #dcfce7; color: #15803d; }
        .badge-rusak-ringan { background-color: #ffedd5; color: #c2410c; }
        .badge-rusak-berat { background-color: #fee2e2; color: #b91c1c; }
        .ttd-table {
            width: 100%;
            margin-top: 20px;
            border: none;
            page-break-inside: avoid;
        }
        .ttd-table td {
            border: none;
            text-align: center;
            vertical-align: top;
            width: 50%;
            font-size: 10px;
        }
        .ttd-space {
            height: 55px;
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
        <h3>Laporan Rekapitulasi Inventaris Laboratorium</h3>
        <p>Buku Induk Aset, Peralatan Praktik, dan Bahan Habis Pakai</p>
    </div>

    <!-- META INFO LAB -->
    <table class="meta-table">
        <tr>
            <td style="width: 15%; font-weight: bold;">Laboratorium / Bengkel</td>
            <td style="width: 35%;">: {{ $lab->nama_lab }} ({{ $lab->kode_lab ?: '-' }})</td>
            <td style="width: 15%; font-weight: bold;">Program Keahlian</td>
            <td style="width: 35%;">: {{ $lab->jurusan->nama_jurusan ?? 'Semua Jurusan' }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Lokasi Ruangan</td>
            <td>: {{ $lab->lokasi ?: 'Gedung Kejuruan' }}</td>
            <td style="font-weight: bold;">Tanggal Dicetak</td>
            <td>: {{ $tanggalCetak }}</td>
        </tr>
    </table>

    <!-- DATA TABEL INVENTARIS -->
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 25px;">No</th>
                <th style="width: 80px;">Kode Aset</th>
                <th>Nama Barang / Alat / Bahan</th>
                <th style="width: 55px;">Kategori</th>
                <th style="width: 45px;">Jumlah</th>
                <th style="width: 45px;">Tersedia</th>
                <th style="width: 65px;">Kondisi</th>
                <th style="width: 45px;">Tahun</th>
                <th style="width: 75px;">Harga Satuan</th>
                <th style="width: 85px;">Total Nilai</th>
            </tr>
        </thead>
        <tbody>
            @forelse($asets as $index => $a)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="font-bold">{{ $a->kode_aset }}</td>
                    <td>
                        <div class="font-bold">{{ $a->nama_aset }}</div>
                        <div style="color: #64748b; font-size: 8px;">{{ $a->spesifikasi ?: $a->sumber_dana }}</div>
                    </td>
                    <td class="text-center">
                        <span class="badge {{ $a->kategori === 'alat' ? 'badge-alat' : 'badge-bahan' }}">
                            {{ $a->kategori === 'alat' ? 'Alat' : 'Bahan' }}
                        </span>
                    </td>
                    <td class="text-center font-bold">{{ $a->jumlah }} {{ $a->satuan }}</td>
                    <td class="text-center font-bold">{{ $a->stok_tersedia }} {{ $a->satuan }}</td>
                    <td class="text-center">
                        <span class="badge {{ $a->kondisi === 'baik' ? 'badge-baik' : ($a->kondisi === 'rusak_ringan' ? 'badge-rusak-ringan' : 'badge-rusak-berat') }}">
                            {{ ucfirst(str_replace('_', ' ', $a->kondisi)) }}
                        </span>
                    </td>
                    <td class="text-center">{{ $a->tahun_perolehan }}</td>
                    <td class="text-right">Rp {{ number_format($a->harga_satuan, 0, ',', '.') }}</td>
                    <td class="text-right font-bold">Rp {{ number_format($a->jumlah * $a->harga_satuan, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center" style="padding: 15px; color: #64748b;">
                        Belum ada data aset laboratorium yang terdaftar.
                    </td>
                </tr>
            @endforelse
        </tbody>
        @if($asets->isNotEmpty())
            <tfoot>
                <tr style="background-color: #f8fafc;">
                    <td colspan="9" class="text-right font-bold" style="padding: 6px;">TOTAL KESELURUHAN ESTIMASI NILAI ASET:</td>
                    <td class="text-right font-bold" style="padding: 6px; color: #047857;">
                        Rp {{ number_format($totalNilaiAset, 0, ',', '.') }}
                    </td>
                </tr>
            </tfoot>
        @endif
    </table>

    <!-- TANDA TANGAN PENGESAHAN -->
    <table class="ttd-table">
        <tr>
            <td>
                Mengetahui,<br>
                Kepala Sekolah
                <div class="ttd-space"></div>
                <strong><u>{{ $setting->nama_kepala_sekolah ?? 'Drs. H. Administrator, M.Pd.' }}</u></strong><br>
                NIP. {{ $setting->nip_kepala_sekolah ?? '19750101 200003 1 001' }}
            </td>
            <td>
                {{ $setting->kabupaten ?? 'Cianjur' }}, {{ $tanggalCetak }}<br>
                Kepala Program Keahlian / Pengelola Lab
                <div class="ttd-space"></div>
                <strong><u>{{ $user->nama ?? $user->name ?? 'Pengelola Laboratorium' }}</u></strong><br>
                NIP / NUPTK. {{ $user->id_guru ?? '-' }}
            </td>
        </tr>
    </table>

</body>
</html>
