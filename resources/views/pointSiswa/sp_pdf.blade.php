<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Surat Peringatan {{ $spType }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.4;
        }
        .header-table {
            width: 100%;
            border-bottom: 3px double #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header-logo {
            width: 80px;
            text-align: center;
        }
        .header-logo img {
            max-width: 75px;
            max-height: 75px;
        }
        .header-text {
            text-align: center;
        }
        .header-text h2 {
            margin: 0;
            font-size: 16px;
            text-transform: uppercase;
        }
        .header-text h3 {
            margin: 2px 0;
            font-size: 14px;
            text-transform: uppercase;
        }
        .header-text p {
            margin: 0;
            font-size: 10px;
            color: #555;
        }
        .title {
            text-align: center;
            margin-bottom: 20px;
        }
        .title h4 {
            margin: 0;
            font-size: 14px;
            text-decoration: underline;
            text-transform: uppercase;
        }
        .title p {
            margin: 2px 0 0 0;
            font-size: 11px;
        }
        .student-info {
            width: 100%;
            margin-bottom: 20px;
        }
        .student-info td {
            padding: 3px 0;
        }
        .student-info td.label {
            width: 150px;
        }
        .student-info td.colon {
            width: 15px;
            text-align: center;
        }
        .content {
            margin-bottom: 20px;
            text-align: justify;
        }
        .table-violations {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            margin-bottom: 20px;
        }
        .table-violations th, .table-violations td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }
        .table-violations th {
            background-color: #f2f2f2;
        }
        .signature-table {
            width: 100%;
            margin-top: 40px;
        }
        .signature-table td {
            width: 33%;
            text-align: center;
            vertical-align: top;
        }
        .signature-space {
            height: 70px;
        }
        .footer-date {
            text-align: right;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

    <!-- Kop Surat -->
    <table class="header-table">
        <tr>
            <td class="header-logo">
                @if($setting->logo)
                    <img src="{{ public_path('storage/gambar/' . $setting->logo) }}" alt="Logo">
                @endif
            </td>
            <td class="header-text">
                <h2>YAYASAN SMK WISATA INDONESIA</h2>
                <h3>{{ $setting->nama_sekolah }}</h3>
                <p>Alamat: {{ $setting->alamat }}, Kel. {{ $setting->kel }}, Kec. {{ $setting->kec }}, {{ $setting->kota }}, Prov. {{ $setting->prov }}</p>
            </td>
        </tr>
    </table>

    <!-- Judul Surat -->
    <div class="title">
        <h4>SURAT PERINGATAN {{ $spType }} (SP - {{ $spType }})</h4>
        <p>Nomor: {{ rand(100, 999) }}/SP-{{ $spType }}/SMK-WI/{{ date('Y') }}</p>
    </div>

    <!-- Informasi Siswa -->
    <p>Surat peringatan ini diberikan kepada siswa yang bersangkutan di bawah ini:</p>
    <table class="student-info">
        <tr>
            <td class="label">Nama Lengkap</td>
            <td class="colon">:</td>
            <td><strong>{{ $siswa->nama_siswa }}</strong></td>
        </tr>
        <tr>
            <td class="label">NIS / NISN</td>
            <td class="colon">:</td>
            <td>{{ $siswa->nis }} / {{ $siswa->nisn }}</td>
        </tr>
        <tr>
            <td class="label">Kelas / Jurusan</td>
            <td class="colon">:</td>
            <td>{{ $siswa->kelas->nama_kelas }} / {{ $siswa->jurusan->nama_jurusan }}</td>
        </tr>
        <tr>
            <td class="label">Total Point Pelanggaran</td>
            <td class="colon">:</td>
            <td><strong>{{ $total_point }} Poin</strong></td>
        </tr>
    </table>

    <!-- Isi Surat -->
    <div class="content">
        <p>Surat Peringatan {{ $spType }} (SP-{{ $spType }}) ini diterbitkan sehubungan dengan akumulasi pelanggaran tata tertib sekolah yang dilakukan oleh siswa di atas. Siswa telah melebihi batas toleransi akumulasi poin pelanggaran kedisiplinan tingkat {{ $spType }} (minimal {{ $threshold }} poin).</p>
        
        <p>Berikut adalah rincian pelanggaran kedisiplinan yang telah tercatat dalam sistem:</p>
    </div>

    <!-- Tabel Pelanggaran -->
    <table class="table-violations">
        <thead>
            <tr>
                <th style="width: 5%">No</th>
                <th>Bentuk Pelanggaran</th>
                <th>Tanggal Kejadian</th>
                <th>Skor</th>
                <th>Pelapor</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pointSiswa as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->point->nama_point }}</td>
                <td>{{ $item->tanggal }}</td>
                <td>{{ $item->skor_point }} Poin</td>
                <td>{{ $item->guru->nama_guru }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="content">
        <p>Pihak sekolah mengimbau kepada siswa yang bersangkutan untuk segera memperbaiki sikap dan perilaku serta tidak mengulangi tindakan pelanggaran tata tertib sekolah di kemudian hari. Kepada Orang Tua / Wali murid diharapkan memberikan bimbingan dan pengawasan yang lebih intensif di rumah demi kebaikan siswa.</p>
        @if($spType == $maxSp)
            <p><strong>Peringatan Keras:</strong> Dikarenakan siswa telah mencapai Surat Peringatan {{ $spType }} (SP-{{ $spType }}), maka Orang Tua / Wali murid diwajibkan untuk hadir di sekolah guna melakukan pertemuan khusus dengan pihak Kesiswaan dan Kepala Sekolah.</p>
        @endif
    </div>

    <!-- Tanggal & Tanda Tangan -->
    <div class="footer-date">
        {{ $setting->kota }}, {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y') }}
    </div>

    <table class="signature-table">
        <tr>
            <td>
                Orang Tua / Wali Siswa
                <div class="signature-space"></div>
                ( ......................................... )
            </td>
            <td>
                Wali Kelas / Kesiswaan
                <div class="signature-space"></div>
                ( ......................................... )
            </td>
            <td>
                Kepala Sekolah
                <div class="signature-space"></div>
                <strong>{{ $setting->nama_kepsek }}</strong><br>
                NIP: {{ $setting->nip_kepsek }}
            </td>
        </tr>
    </table>

</body>
</html>
