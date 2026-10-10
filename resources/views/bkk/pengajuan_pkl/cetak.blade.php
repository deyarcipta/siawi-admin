<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>Surat Permohonan PKL - {{ $pengajuan->nama_perusahaan }}</title>
  <link rel="icon" href="{{ asset('storage/gambar/' . ($setting->logo ?? '1790991925_logo-wi.png')) }}" type="image/x-icon">
  
  <style>
    @page {
      size: A4 portrait;
      margin: 1.5cm 2cm 1.5cm 2cm;
    }

    body {
      font-family: 'Times New Roman', Times, serif;
      font-size: 11pt;
      line-height: 1.35;
      color: #000000;
      background-color: #f1f5f9;
      margin: 0;
      padding: 20px 0;
    }

    /* Print Screen Container */
    .print-container {
      max-width: 210mm;
      margin: 0 auto;
      background: #ffffff;
      padding: 20mm 20mm 25mm 20mm;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
      box-sizing: border-box;
      position: relative;
    }

    /* Print Control Bar */
    .no-print-bar {
      max-width: 210mm;
      margin: 0 auto 15px auto;
      padding: 12px 18px;
      background: #1e293b;
      color: #ffffff;
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      box-shadow: 0 2px 8px rgba(0,0,0,0.15);
      font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    }

    .no-print-bar .btn {
      display: inline-flex;
      align-items: center;
      padding: 8px 16px;
      font-size: 13px;
      font-weight: 600;
      border-radius: 6px;
      text-decoration: none;
      cursor: pointer;
      border: none;
      gap: 6px;
    }

    .btn-print {
      background-color: #2563eb;
      color: #ffffff;
    }
    .btn-print:hover {
      background-color: #1d4ed8;
    }

    .btn-pdf {
      background-color: #dc2626;
      color: #ffffff;
    }
    .btn-pdf:hover {
      background-color: #b91c1c;
    }

    .btn-back {
      background-color: #475569;
      color: #ffffff;
    }
    .btn-back:hover {
      background-color: #334155;
    }

    /* Kop Surat */
    .kop-banner-container {
      width: 100%;
      text-align: center;
      margin-bottom: 14px;
    }

    .kop-banner-container img {
      width: 100%;
      max-width: 100%;
      height: auto;
      display: block;
    }

    .kop-table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 2px;
    }

    .kop-logo {
      width: 90px;
      vertical-align: middle;
      text-align: center;
      padding-right: 12px;
    }

    .kop-logo img {
      max-width: 82px;
      max-height: 82px;
      display: block;
      margin: 0 auto;
    }

    .kop-text {
      text-align: center;
      vertical-align: middle;
    }

    .kop-yayasan {
      font-size: 14pt;
      font-weight: bold;
      color: #831843;
      letter-spacing: 0.5px;
      margin: 0;
      line-height: 1.15;
    }

    .kop-sekolah {
      font-size: 20pt;
      font-weight: 900;
      color: #831843;
      letter-spacing: 1px;
      margin: 2px 0;
      line-height: 1.15;
    }

    .kop-keahlian {
      font-size: 12pt;
      font-weight: bold;
      color: #831843;
      margin: 2px 0 3px 0;
      line-height: 1.15;
    }

    .kop-alamat {
      font-size: 8.5pt;
      color: #000000;
      margin: 0;
      line-height: 1.25;
    }

    .kop-kontak {
      font-size: 8.5pt;
      color: #1d4ed8;
      margin: 0;
      line-height: 1.25;
    }

    .kop-line {
      border: 0;
      border-top: 2.5px solid #000000;
      border-bottom: 1px solid #000000;
      height: 4px;
      margin: 6px 0 16px 0;
    }

    /* Metadata Surat */
    .meta-table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 18px;
    }

    .meta-table td {
      vertical-align: top;
      padding: 2px 0;
      font-size: 11pt;
      line-height: 1.4;
    }

    /* Tujuan Surat */
    .tujuan-section {
      margin-bottom: 18px;
      line-height: 1.45;
      font-size: 11pt;
    }

    /* Paragraf Isi */
    .isi-section {
      text-align: justify;
      text-justify: inter-word;
      line-height: 1.55;
      font-size: 11pt;
      margin-bottom: 14px;
    }

    .isi-section p {
      margin: 0 0 10px 0;
      text-indent: 1.25cm;
    }

    /* Tabel Siswa */
    .table-siswa {
      width: 100%;
      border-collapse: collapse;
      margin: 12px 0 18px 0;
      font-size: 10.5pt;
    }

    .table-siswa th, .table-siswa td {
      border: 1px solid #1a1a1a;
      padding: 6px 10px;
      line-height: 1.35;
    }

    .table-siswa tr {
      page-break-inside: avoid;
    }

    .table-siswa th {
      background-color: #f8fafc;
      text-align: center;
      font-weight: bold;
      text-transform: uppercase;
      font-size: 10pt;
      letter-spacing: 0.5px;
    }

    /* Tanda Tangan */
    .ttd-section {
      width: 100%;
      margin-top: 24px;
      page-break-inside: avoid;
    }

    .ttd-box {
      float: right;
      width: 280px;
      text-align: center;
      line-height: 1.35;
      font-size: 11pt;
    }

    .ttd-space {
      height: 75px;
      margin: 6px 0;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .ttd-space img {
      max-height: 70px;
      max-width: 200px;
    }

    .clearfix::after {
      content: "";
      clear: both;
      display: table;
    }

    /* Media Print */
    @media print {
      body {
        background-color: #ffffff;
        padding: 0;
      }
      .no-print-bar {
        display: none !important;
      }
      .print-container {
        max-width: 100%;
        margin: 0;
        padding: 0;
        box-shadow: none;
      }
      .page-break {
        page-break-before: always;
      }
    }
  </style>
</head>
<body>

  <!-- Control Bar (Layar Saja) -->
  <div class="no-print-bar">
    <div style="display: flex; align-items: center; gap: 8px;">
      <i class="fas fa-file-alt" style="color: #60a5fa;"></i>
      <span style="font-size: 14px; font-weight: 600;">Format Surat Permohonan PKL (SIAWI)</span>
    </div>
    <div style="display: flex; gap: 8px;">
      <a href="{{ route('admin.pengajuan-pkl.show', $pengajuan->id_pengajuan) }}" class="btn btn-back">
        &larr; Kembali
      </a>
      <a href="{{ route('admin.pengajuan-pkl.pdf', $pengajuan->id_pengajuan) }}" class="btn btn-pdf" target="_blank">
        Unduh PDF
      </a>
      <button onclick="window.print()" class="btn btn-print">
        &#128438; Cetak / Print Sekarang
      </button>
    </div>
  </div>

  <!-- Print Sheet Container -->
  <div class="print-container">

    <!-- Kop Surat Resmi (Menggunakan Kop yang ada di Setting) -->
    @if(!empty($setting->kop_surat) && file_exists(public_path('storage/gambar/' . $setting->kop_surat)))
      <div class="kop-banner-container">
        <img src="{{ asset('storage/gambar/' . $setting->kop_surat) }}" alt="Kop Surat Resmi">
      </div>
    @else
      <table class="kop-table">
        <tr>
          <td class="kop-logo">
            @if(!empty($setting->logo) && file_exists(public_path('storage/gambar/' . $setting->logo)))
              <img src="{{ asset('storage/gambar/' . $setting->logo) }}" alt="Logo">
            @else
              <img src="{{ asset('storage/gambar/1790991925_logo-wi.png') }}" alt="Logo">
            @endif
          </td>
          <td class="kop-text">
            <div class="kop-yayasan">YAYASAN TAQWASH SHOBIRIN</div>
            <div class="kop-sekolah">SMK WISATA INDONESIA</div>
            <div class="kop-keahlian">&#9679; Perhotelan &#9679; Jasa Boga &#9679; Teknik Komputer &amp; Jaringan</div>
            <div class="kop-alamat">Jl. Raya Lenteng Agung / Jl. Langgar No. 1 Kebagusan Pasar Minggu Jakarta Selatan 12520 Tel/Fax. (021) 78830761</div>
            <div class="kop-kontak">E-mail : wistin@smkwi.sch.id &nbsp;&nbsp;&nbsp;&nbsp; Website : www.smkwi.sch.id</div>
          </td>
        </tr>
      </table>
      <div class="kop-line"></div>
    @endif

    <!-- Metadata Surat (Nomor, Hal, Tanggal) -->
    <table class="meta-table">
      <tr>
        <td style="width: 65px;">Nomor</td>
        <td style="width: 15px; text-align: center;">:</td>
        <td style="width: 50%;">{{ $pengajuan->nomor_surat ?: '...../OJT/SMK-WI/' . date('m/Y') }}</td>
        <td style="text-align: right; width: 40%; font-weight: 500;">{{ $pengajuan->tanggal_surat_formatted }}</td>
      </tr>
      <tr>
        <td>H a l</td>
        <td style="text-align: center;">:</td>
        <td colspan="2"><strong>Permohonan PKL</strong></td>
      </tr>
    </table>

    <!-- Kepada Yth -->
    <div class="tujuan-section">
      Kepada Yth,<br>
      <strong style="font-size: 11pt;">{{ $pengajuan->ditujukan_kepada }}</strong><br>
      <span>Jabatan {{ $pengajuan->jabatan_tujuan ?: 'H.R & Learning Manager' }}</span><br>
      <strong style="font-size: 11pt;">{{ $pengajuan->nama_perusahaan }}</strong><br>
      <span>di {{ $pengajuan->kota_atau_tempat }}</span>
    </div>

    <!-- Salam & Isi Surat -->
    <div class="isi-section">
      <p style="text-indent: 0; margin-bottom: 8px;">Dengan Hormat,</p>
      <p>
        Berdasarkan Kurikulum SMK pariwisata program study keahlian pariwisata dan keahlian teknik komputer jaringan dan telekomunikasi diwajibkan melaksanakan praktek kerja lapangan ( PKL ) sesuai dengan kompetensi yang di harapkan.
      </p>
      @php
        // Deteksi sapaan Bapak/Ibu dari nama/jabatan
        $sapaan = 'Bapak / Ibu';
        if (stripos($pengajuan->ditujukan_kepada, 'Ibu') !== false) {
            $sapaan = 'Ibu';
        } elseif (stripos($pengajuan->ditujukan_kepada, 'Bapak') !== false) {
            $sapaan = 'Bapak';
        }
      @endphp
      <p>
        Untuk memenuhi program tersebut kami mohon kiranya bersedia {{ $sapaan }} memberikan kesempatan bagi para peserta didik kami untuk melaksanakan Praktek Kerja Lapangan ( PKL ) pada periode <strong>{{ $pengajuan->periode_teks }}</strong>. Ada pun siswa yang akan melaksanakan praktek kerja lapangan adalah :
      </p>
    </div>

    <!-- Tabel Daftar Siswa -->
    <table class="table-siswa">
      <thead>
        <tr>
          <th style="width: 45px;">NO</th>
          <th>NAMA</th>
          <th style="width: 220px;">PROGRAM KEAHLIAN</th>
        </tr>
      </thead>
      <tbody>
        @forelse($pengajuan->siswaList as $idx => $siswa)
          <tr>
            <td style="text-align: center; font-weight: 600;">{{ $idx + 1 }}</td>
            <td style="padding-left: 12px; font-weight: 500;">{{ $siswa->nama_siswa }}</td>
            <td style="text-align: center;">{{ $siswa->program_keahlian }}</td>
          </tr>
        @empty
          <tr>
            <td colspan="3" style="text-align: center; color: #666; font-style: italic;">(Belum ada data siswa yang dimasukkan)</td>
          </tr>
        @endforelse
      </tbody>
    </table>

    <!-- Penutup -->
    <div class="isi-section" style="margin-top: 16px;">
      <p style="text-indent: 0; margin-bottom: 8px;">
        Demikian Surat permohonan ini kami buat, selanjutnya kami akan menunggu informasi dari {{ $sapaan }}.
      </p>
      <p style="text-indent: 0; margin-bottom: 0;">
        Atas perhatian dan kerja sama {{ $sapaan }} kami ucapkan terima kasih
      </p>
    </div>

    <!-- Bagian Tanda Tangan -->
    <div class="ttd-section clearfix">
      <div class="ttd-box">
        <p style="margin: 0; line-height: 1.35;">
          Hormat Kami,<br>
          <strong>{{ $pengajuan->jabatan_penandatangan ?: 'Koordinator Traning & Wakahubin' }}</strong>
        </p>

        <div class="ttd-space">
          @if(!empty($pengajuan->file_stempel_ttd) && file_exists(public_path('storage/sp_ttd/' . $pengajuan->file_stempel_ttd)))
            <img src="{{ asset('storage/sp_ttd/' . $pengajuan->file_stempel_ttd) }}" alt="Ttd &amp; Stempel">
          @endif
        </div>

        <p style="margin: 0; line-height: 1.35;">
          <strong style="text-decoration: underline; font-size: 11.5pt;">{{ $pengajuan->nama_penandatangan ?: 'Nanan Supriatna' }}</strong><br>
          <span style="font-size: 10pt;">{{ $pengajuan->kontak_penandatangan ?: '082312261278' }}</span>
        </p>
      </div>
    </div>

  </div>

</body>
</html>
