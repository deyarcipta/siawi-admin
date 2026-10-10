<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>Surat Permohonan PKL - {{ $pengajuan->nama_perusahaan }}</title>
  <style>
    @page {
      margin: 1.5cm 2cm 1.5cm 2cm;
    }

    body {
      font-family: 'Times New Roman', Times, serif;
      font-size: 11pt;
      line-height: 1.35;
      color: #000000;
      margin: 0;
      padding: 0;
    }

    /* Kop Surat */
    .kop-banner-container {
      width: 100%;
      text-align: center;
      margin-bottom: 12px;
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
      width: 85px;
      vertical-align: middle;
      text-align: center;
    }

    .kop-logo img {
      width: 78px;
      height: 78px;
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
      font-size: 19pt;
      font-weight: bold;
      color: #831843;
      letter-spacing: 1px;
      margin: 2px 0;
      line-height: 1.15;
    }

    .kop-keahlian {
      font-size: 11.5pt;
      font-weight: bold;
      color: #831843;
      margin: 2px 0;
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
      border-top: 2.5px solid #000000;
      border-bottom: 1px solid #000000;
      height: 2px;
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
      line-height: 1.5;
      font-size: 11pt;
      margin-bottom: 12px;
    }

    .isi-section p {
      margin: 0 0 8px 0;
      text-indent: 1.2cm;
    }

    /* Tabel Siswa */
    .table-siswa {
      width: 100%;
      border-collapse: collapse;
      margin: 12px 0 16px 0;
      font-size: 10pt;
    }

    .table-siswa th, .table-siswa td {
      border: 1px solid #1a1a1a;
      padding: 5px 8px;
      line-height: 1.35;
    }

    .table-siswa tr {
      page-break-inside: avoid;
    }

    .table-siswa th {
      background-color: #f8fafc;
      text-align: center;
      font-weight: bold;
      font-size: 9.5pt;
      letter-spacing: 0.5px;
    }

    /* Tanda Tangan */
    .ttd-table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
      page-break-inside: avoid;
    }

    .ttd-box {
      width: 280px;
      text-align: center;
      line-height: 1.35;
      font-size: 11pt;
    }

    .ttd-space {
      height: 70px;
    }
  </style>
</head>
<body>

  <!-- Kop Surat Resmi (Menggunakan Kop yang ada di Setting) -->
  @if(!empty($setting->kop_surat) && file_exists(public_path('storage/gambar/' . $setting->kop_surat)))
    <div class="kop-banner-container">
      <img src="{{ public_path('storage/gambar/' . $setting->kop_surat) }}" alt="Kop Surat Resmi">
    </div>
  @else
    <table class="kop-table">
      <tr>
        <td class="kop-logo">
          @php
            $logoPath = null;
            if (!empty($setting->logo) && file_exists(public_path('storage/gambar/' . $setting->logo))) {
                $logoPath = public_path('storage/gambar/' . $setting->logo);
            } elseif (file_exists(public_path('storage/gambar/1790991925_logo-wi.png'))) {
                $logoPath = public_path('storage/gambar/1790991925_logo-wi.png');
            }
          @endphp
          @if($logoPath)
            <img src="{{ $logoPath }}" alt="Logo">
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
      <td style="text-align: right; width: 40%; font-weight: bold;">{{ $pengajuan->tanggal_surat_formatted }}</td>
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
    <strong>{{ $pengajuan->ditujukan_kepada }}</strong><br>
    <span>Jabatan {{ $pengajuan->jabatan_tujuan ?: 'H.R & Learning Manager' }}</span><br>
    <strong>{{ $pengajuan->nama_perusahaan }}</strong><br>
    <span>di {{ $pengajuan->kota_atau_tempat }}</span>
  </div>

  <!-- Salam & Isi Surat -->
  <div class="isi-section">
    <p style="text-indent: 0; margin-bottom: 6px;">Dengan Hormat,</p>
    <p>
      Berdasarkan Kurikulum SMK pariwisata program study keahlian pariwisata dan keahlian teknik komputer jaringan dan telekomunikasi diwajibkan melaksanakan praktek kerja lapangan ( PKL ) sesuai dengan kompetensi yang di harapkan.
    </p>
    @php
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
        <th style="width: 40px;">NO</th>
        <th>NAMA</th>
        <th style="width: 190px;">PROGRAM KEAHLIAN</th>
      </tr>
    </thead>
    <tbody>
      @forelse($pengajuan->siswaList as $idx => $siswa)
        <tr>
          <td style="text-align: center; font-weight: bold;">{{ $idx + 1 }}</td>
          <td style="padding-left: 10px;">{{ $siswa->nama_siswa }}</td>
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
  <div class="isi-section" style="margin-top: 14px;">
    <p style="text-indent: 0; margin-bottom: 6px;">
      Demikian Surat permohonan ini kami buat, selanjutnya kami akan menunggu informasi dari {{ $sapaan }}.
    </p>
    <p style="text-indent: 0; margin-bottom: 0;">
      Atas perhatian dan kerja sama {{ $sapaan }} kami ucapkan terima kasih
    </p>
  </div>

  <!-- Bagian Tanda Tangan -->
  <table class="ttd-table">
    <tr>
      <td style="width: 50%;"></td>
      <td class="ttd-box">
        Hormat Kami,<br>
        <strong>{{ $pengajuan->jabatan_penandatangan ?: 'Koordinator Traning & Wakahubin' }}</strong>
        <div class="ttd-space"></div>
        <strong style="text-decoration: underline; font-size: 11pt;">{{ $pengajuan->nama_penandatangan ?: 'Nanan Supriatna' }}</strong><br>
        <span>{{ $pengajuan->kontak_penandatangan ?: '082312261278' }}</span>
      </td>
    </tr>
  </table>

</body>
</html>
