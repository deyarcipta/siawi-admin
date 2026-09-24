<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Rekapitulasi Pelanggaran Siswa</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            color: #1f2937;
            margin: 15px;
        }
        .kop-table {
            width: 100%;
            border-collapse: collapse;
            border-bottom: 2px solid #000;
            padding-bottom: 8px;
            margin-bottom: 15px;
        }
        .kop-table td {
            vertical-align: middle;
        }
        .title-section {
            text-align: center;
            margin-bottom: 15px;
        }
        .title-section h3 {
            margin: 0;
            font-size: 14px;
            text-transform: uppercase;
        }
        .title-section p {
            margin: 3px 0 0 0;
            font-size: 10px;
            color: #4b5563;
        }
        .summary-box {
            width: 100%;
            margin-bottom: 12px;
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
            padding: 8px;
        }
        .summary-box table {
            width: 100%;
            font-size: 10px;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .data-table th {
            background-color: #1e3a8a;
            color: #ffffff;
            font-size: 10px;
            font-weight: bold;
            padding: 6px 4px;
            border: 1px solid #000;
            text-align: center;
        }
        .data-table td {
            border: 1px solid #4b5563;
            padding: 5px 4px;
            font-size: 9.5px;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .font-bold { font-weight: bold; }
        .badge {
            display: inline-block;
            padding: 2px 5px;
            font-size: 8.5px;
            font-weight: bold;
            border-radius: 3px;
        }
        .badge-danger { background-color: #ef4444; color: #fff; }
        .badge-warning { background-color: #f59e0b; color: #fff; }
        .badge-success { background-color: #10b981; color: #fff; }
        .signature-table {
            width: 100%;
            margin-top: 30px;
            border-collapse: collapse;
        }
        .signature-table td {
            text-align: center;
            vertical-align: top;
            font-size: 10px;
        }
    </style>
</head>
<body>

    <!-- KOP SURAT -->
    <table class="kop-table">
        <tr>
            <td style="width: 70px; text-align: center;">
                @if($setting->logo)
                    <img src="{{ public_path('storage/gambar/' . $setting->logo) }}" alt="Logo" style="max-height: 55px;">
                @endif
            </td>
            <td style="text-align: center;">
                <h2 style="margin: 0; font-size: 16px; font-weight: bold;">{{ strtoupper($setting->nama_sekolah ?? 'SMK WISATA INDONESIA') }}</h2>
                <p style="margin: 2px 0; font-size: 10px;">{{ $setting->alamat ?? '-' }} {{ $setting->kel ? ', ' . $setting->kel : '' }} {{ $setting->kec ? ', ' . $setting->kec : '' }}</p>
                <p style="margin: 0; font-size: 10px;">{{ $setting->kota ?? '-' }} - {{ $setting->prov ?? '-' }}</p>
            </td>
        </tr>
    </table>

    <!-- JUDUL -->
    <div class="title-section">
        <h3>Laporan Rekapitulasi Pelanggaran & Poin Siswa</h3>
        <p>Periode: {{ \Carbon\Carbon::parse($tanggalMulai)->locale('id')->translatedFormat('d F Y') }} s/d {{ \Carbon\Carbon::parse($tanggalSelesai)->locale('id')->translatedFormat('d F Y') }} | Kelas: {{ $namaKelas }}</p>
    </div>

    <!-- RINGKASAN DATA -->
    <div class="summary-box">
        <table>
            <tr>
                <td><strong>Total Kasus Pelanggaran:</strong> {{ $summary['total_kasus'] }} kejadian</td>
                <td><strong>Total Akumulasi Poin:</strong> {{ $summary['total_poin'] }} poin</td>
                <td><strong>Siswa Terlibat:</strong> {{ $summary['total_siswa_pelanggar'] }} siswa</td>
                <td><strong>Siswa Terkena SP:</strong> {{ $summary['total_siswa_sp'] }} siswa</td>
            </tr>
        </table>
    </div>

    <!-- TABEL REKAPITULASI -->
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 25px;">NO</th>
                <th style="width: 75px;">NIS</th>
                <th style="width: 140px;" class="text-left">NAMA SISWA</th>
                <th style="width: 80px;">KELAS</th>
                <th style="width: 110px;">WALI KELAS</th>
                <th style="width: 60px;">JML KASUS</th>
                <th style="width: 65px;">TOTAL POIN</th>
                <th style="width: 75px;">STATUS SP</th>
                <th class="text-left">RINGKASAN PELANGGARAN</th>
            </tr>
        </thead>
        <tbody>
            @forelse($dataRekap as $idx => $item)
                @php
                    $s = $item['siswa'];
                    $detailList = [];
                    foreach ($item['pelanggaran_list'] as $p) {
                        $detailList[] = ($p->point->nama_point ?? 'Pelanggaran') . " (" . $p->skor_point . "p)";
                    }
                    $rincianText = implode(', ', $detailList);
                @endphp
                <tr>
                    <td class="text-center font-bold">{{ $idx + 1 }}</td>
                    <td class="text-center">{{ $s->nis ?? '-' }}</td>
                    <td class="font-bold">{{ $s->nama_siswa }}</td>
                    <td class="text-center">{{ $s->kelas->nama_kelas ?? '-' }}</td>
                    <td class="text-center">{{ $s->kelas->waliKelas->nama_guru ?? '-' }}</td>
                    <td class="text-center">{{ $item['total_kasus'] }}</td>
                    <td class="text-center font-bold" style="color: #b91c1c;">{{ $item['total_poin'] }}</td>
                    <td class="text-center font-bold">
                        {{ $item['status_sp'] }}
                    </td>
                    <td style="font-size: 8.5px; color: #374151;">{{ $rincianText ?: '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center" style="padding: 20px;">Tidak ada catatan pelanggaran pada periode ini.</td>
                </tr>
            @endforelse
        </tbody>
        @if(count($dataRekap) > 0)
            <tfoot>
                <tr style="background-color: #f3f4f6; font-weight: bold;">
                    <td colspan="5" class="text-right font-bold">TOTAL KESELURUHAN:</td>
                    <td class="text-center font-bold">{{ $summary['total_kasus'] }}</td>
                    <td class="text-center font-bold" style="color: #b91c1c;">{{ $summary['total_poin'] }}</td>
                    <td colspan="2"></td>
                </tr>
            </tfoot>
        @endif
    </table>

    <!-- TANDA TANGAN -->
    <table class="signature-table">
        <tr>
            <td style="width: 50%;">
                <p>Mengetahui,</p>
                <p><strong>Kepala Sekolah</strong></p>
                <div style="height: 50px;"></div>
                <p style="text-decoration: underline; font-weight: bold;">{{ $setting->nama_kepsek ?? '..................................' }}</p>
                <p style="margin-top: -8px;">NIP: {{ $setting->nip_kepsek ?? '-' }}</p>
            </td>
            <td style="width: 50%;">
                <p>{{ $setting->kota ?? 'Jakarta' }}, {{ \Carbon\Carbon::now('Asia/Jakarta')->locale('id')->translatedFormat('d F Y') }}</p>
                <p><strong>Koordinator Kesiswaan / Guru BK</strong></p>
                <div style="height: 50px;"></div>
                <p style="text-decoration: underline; font-weight: bold;">( .................................................. )</p>
                <p style="margin-top: -8px;">NIP: ............................................</p>
            </td>
        </tr>
    </table>

</body>
</html>
