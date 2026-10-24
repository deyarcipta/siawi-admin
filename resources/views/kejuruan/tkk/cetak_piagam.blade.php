<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Piagam TKK - {{ $siswaTkk->siswa->nama_siswa ?? 'Siswa' }} - {{ $siswaTkk->nomor_piagam ?? 'TKK' }}</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <style>
    @page {
      size: A4 landscape;
      margin: 8mm;
    }
    * {
      box-sizing: border-box;
      -webkit-print-color-adjust: exact !important;
      print-color-adjust: exact !important;
    }
    body {
      font-family: 'Times New Roman', Times, Georgia, serif;
      margin: 0;
      padding: 0;
      background-color: #f1f5f9;
      color: #1e293b;
    }
    .no-print-bar {
      background: #1e293b;
      color: #fff;
      padding: 10px 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      position: sticky;
      top: 0;
      z-index: 999;
      box-shadow: 0 2px 8px rgba(0,0,0,0.15);
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }
    .no-print-bar button, .no-print-bar a {
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
      font-size: 13px;
      padding: 6px 14px;
      border-radius: 6px;
      border: none;
      cursor: pointer;
      font-weight: 600;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 6px;
    }
    .btn-print {
      background: #2563eb;
      color: #fff;
    }
    .btn-print:hover {
      background: #1d4ed8;
    }
    .btn-back {
      background: #475569;
      color: #fff;
    }
    .btn-back:hover {
      background: #334155;
    }

    /* Sertifikat Container */
    .certificate-page {
      width: 297mm;
      min-height: 200mm;
      margin: 20px auto;
      background: #ffffff;
      padding: 12mm;
      box-shadow: 0 4px 16px rgba(0,0,0,0.1);
      position: relative;
    }

    .certificate-outer-border {
      border: 3px double #1e3a8a;
      padding: 4px;
      height: 100%;
    }

    .certificate-inner-border {
      border: 1px solid #94a3b8;
      padding: 16px 24px;
      position: relative;
      background: radial-gradient(circle at center, #ffffff 60%, #f8fafc 100%);
    }

    /* Watermark */
    .watermark {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      opacity: 0.04;
      max-width: 320px;
      pointer-events: none;
    }

    /* Header */
    .cert-header {
      text-align: center;
      margin-bottom: 12px;
    }
    .school-name {
      font-family: Arial, sans-serif;
      font-size: 16px;
      font-weight: 800;
      letter-spacing: 1.5px;
      text-transform: uppercase;
      color: #1e3a8a;
      margin: 0;
    }
    .school-sub {
      font-family: Arial, sans-serif;
      font-size: 10px;
      color: #64748b;
      margin: 2px 0 0 0;
    }
    .divider-line {
      width: 140px;
      height: 2px;
      background: #d97706;
      margin: 8px auto 14px auto;
    }

    /* Title */
    .cert-title {
      font-family: 'Times New Roman', Times, serif;
      font-size: 24px;
      font-weight: bold;
      letter-spacing: 2px;
      color: #0f172a;
      text-transform: uppercase;
      margin: 0 0 2px 0;
      text-shadow: 1px 1px 1px rgba(0,0,0,0.05);
    }
    .cert-number {
      font-family: Arial, sans-serif;
      font-size: 11px;
      font-weight: 600;
      color: #475569;
      letter-spacing: 1px;
      margin-bottom: 12px;
    }

    /* Body Text */
    .cert-intro {
      font-style: italic;
      font-size: 13px;
      color: #475569;
      margin-bottom: 4px;
      text-align: center;
    }
    .student-name {
      font-size: 22px;
      font-weight: bold;
      color: #1e3a8a;
      text-decoration: underline;
      text-transform: uppercase;
      letter-spacing: 1px;
      margin: 4px 0;
      text-align: center;
    }
    .student-meta {
      font-family: Arial, sans-serif;
      font-size: 11px;
      color: #334155;
      text-align: center;
      margin-bottom: 12px;
    }

    .cert-statement {
      font-size: 13px;
      color: #334155;
      text-align: center;
      max-width: 820px;
      margin: 0 auto 6px auto;
      line-height: 1.4;
    }

    .scheme-box {
      background: #eff6ff;
      border: 1px dashed #3b82f6;
      border-radius: 6px;
      padding: 6px 16px;
      display: inline-block;
      margin: 4px auto 12px auto;
      text-align: center;
    }
    .scheme-name {
      font-size: 16px;
      font-weight: bold;
      color: #1d4ed8;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }
    .scheme-level {
      font-family: Arial, sans-serif;
      font-size: 11px;
      font-weight: bold;
      color: #d97706;
      text-transform: uppercase;
      margin-top: 2px;
    }

    /* Unit Table */
    .unit-section {
      margin: 8px auto 16px auto;
      max-width: 860px;
    }
    .unit-table {
      width: 100%;
      border-collapse: collapse;
      font-family: Arial, sans-serif;
      font-size: 9.5px;
    }
    .unit-table th, .unit-table td {
      border: 1px solid #cbd5e1;
      padding: 3px 6px;
    }
    .unit-table th {
      background: #f1f5f9;
      font-weight: bold;
      text-align: center;
      color: #334155;
    }
    .unit-table .status-col {
      text-align: center;
      font-weight: bold;
      color: #15803d;
    }

    /* Signatures */
    .sign-section {
      display: flex;
      justify-content: space-between;
      margin-top: 14px;
      padding: 0 40px;
      font-family: Arial, sans-serif;
      font-size: 11px;
    }
    .sign-box {
      text-align: center;
      width: 220px;
    }
    .sign-space {
      height: 52px;
    }
    .sign-name {
      font-weight: bold;
      text-decoration: underline;
      color: #0f172a;
    }
    .sign-role {
      font-size: 10px;
      color: #475569;
    }

    @media print {
      body {
        background: #ffffff;
      }
      .no-print-bar {
        display: none !important;
      }
      .certificate-page {
        margin: 0 !important;
        box-shadow: none !important;
        width: 100% !important;
        min-height: 100% !important;
        page-break-after: avoid;
        padding: 0 !important;
      }
    }
  </style>
</head>
<body>

  <!-- Toolbar Aksi Layar -->
  <div class="no-print-bar">
    <div style="display: flex; align-items: center; gap: 10px;">
      <i class="fas fa-certificate text-warning" style="font-size: 18px;"></i>
      <span style="font-weight: 600;">Pratinjau Piagam Tanda Kecakapan Khusus (TKK)</span>
    </div>
    <div style="display: flex; gap: 8px;">
      <a href="javascript:window.close()" class="btn-back">
        <i class="fas fa-times"></i> Tutup
      </a>
      <button onclick="window.print()" class="btn-print">
        <i class="fas fa-print"></i> Cetak Dokumen (A4 Landscape)
      </button>
    </div>
  </div>

  <!-- Halaman Piagam Resmi -->
  <div class="certificate-page">
    <div class="certificate-outer-border">
      <div class="certificate-inner-border">

        <!-- Watermark / Lambang -->
        @if(!empty($setting->logo))
          <img src="{{ asset('storage/images/setting/' . $setting->logo) }}" class="watermark" alt="Logo">
        @endif

        <!-- Kop Sekolah -->
        <div class="cert-header">
          <h2 class="school-name">{{ $setting->nama_sekolah ?? 'SMK WISATA INDONESIA' }}</h2>
          <p class="school-sub">{{ $setting->alamat ?? 'Jl. Raya Lenteng Agung / Jl. Langgar RT 009/03 No. 1 Jakarta Selatan' }}</p>
          <div class="divider-line"></div>
        </div>

        <!-- Judul Piagam -->
        <div style="text-align: center;">
          <h1 class="cert-title">PIAGAM TANDA KECAKAPAN KHUSUS</h1>
          <div class="cert-number">NOMOR: {{ $siswaTkk->nomor_piagam ?: ('TKK/' . ($siswaTkk->jenisTkk->jurusan->kode_jurusan ?? 'KEJ') . '/' . date('Y') . '/' . str_pad($siswaTkk->id_siswa, 4, '0', STR_PAD_LEFT)) }}</div>
        </div>

        <!-- Keterangan Penerima -->
        <div class="cert-intro">Diberikan Kepada:</div>
        <div class="student-name">{{ $siswaTkk->siswa->nama_siswa ?? 'Nama Siswa' }}</div>
        <div class="student-meta">
          NIS: <strong>{{ $siswaTkk->siswa->nis ?: '-' }}</strong> &nbsp;|&nbsp; 
          NISN: <strong>{{ $siswaTkk->siswa->nisn ?: '-' }}</strong> &nbsp;|&nbsp; 
          Kelas: <strong>{{ $siswaTkk->siswa->kelas->nama_kelas ?? '-' }}</strong> &nbsp;|&nbsp;
          Kompetensi Keahlian: <strong>{{ $siswaTkk->jenisTkk->jurusan->nama_jurusan ?? '-' }}</strong>
        </div>

        <!-- Pernyataan Kelulusan & Skema -->
        <div class="cert-statement">
          Atas keberhasilannya menempuh dan dinyatakan <strong>KOMPETEN</strong> dalam seluruh unit kompetensi kejuruan yang dipersyaratkan pada Skema Sertifikasi Tanda Kecakapan Khusus:
        </div>

        <div style="text-align: center;">
          <div class="scheme-box">
            <div class="scheme-name">
              {{ $siswaTkk->jenisTkk->nomor_klaster ? 'Klaster #'.$siswaTkk->jenisTkk->nomor_klaster.' : ' : '' }}{{ $siswaTkk->jenisTkk->nama_tkk }}
            </div>
            <div class="scheme-level">
              Kualifikasi: Tingkat {{ strtoupper($siswaTkk->jenisTkk->tingkat) }}
            </div>
          </div>
        </div>

        <!-- Daftar Unit Kompetensi yang Telah Dikuasai -->
        <div class="unit-section">
          <table class="unit-table">
            <thead>
              <tr>
                <th style="width: 25px;">No</th>
                <th style="width: 110px;">Kode Unit</th>
                <th>Judul Unit Kompetensi yang Telah Dikuasai</th>
                <th style="width: 100px;">Status Capaian</th>
              </tr>
            </thead>
            <tbody>
              @forelse($siswaTkk->jenisTkk->syarats as $uIndex => $syarat)
                @php
                  $capaian = $capaianUnits->get($syarat->id_kompetensi);
                @endphp
                <tr>
                  <td style="text-align: center;">{{ $uIndex + 1 }}</td>
                  <td style="text-align: center; font-family: monospace; font-weight: bold;">{{ $syarat->kode_kompetensi }}</td>
                  <td>{{ $syarat->nama_kompetensi }}</td>
                  <td class="status-col">KOMPETEN</td>
                </tr>
              @empty
                <tr>
                  <td colspan="4" style="text-align: center; color: #64748b;">Seluruh unit kompetensi terverifikasi tuntas.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        <!-- Tanda Tangan Pengesahan -->
        <div class="sign-section">
          <div class="sign-box">
            <div>Mengetahui,</div>
            <div class="sign-role">Kepala Program Keahlian</div>
            <div class="sign-space"></div>
            <div class="sign-name">{{ $siswaTkk->pemberi->nama_guru ?? 'Deyar Cipta Rizky, S.Kom.' }}</div>
            <div class="sign-role">NIP / NUPTK: {{ $siswaTkk->pemberi->nip ?? '-' }}</div>
          </div>

          <div class="sign-box">
            <div>Jakarta, {{ $siswaTkk->tanggal_perolehan ? \Carbon\Carbon::parse($siswaTkk->tanggal_perolehan)->translatedFormat('d F Y') : \Carbon\Carbon::now()->translatedFormat('d F Y') }}</div>
            <div class="sign-role">Kepala Sekolah {{ $setting->nama_sekolah ?? 'SMK Wisata Indonesia' }}</div>
            <div class="sign-space"></div>
            <div class="sign-name">{{ $setting->kepala_sekolah ?? 'Drs. H. Sugeng Prayitno, M.Pd.' }}</div>
            <div class="sign-role">NIP: {{ $setting->nip_kepala_sekolah ?? '-' }}</div>
          </div>
        </div>

      </div>
    </div>
  </div>

</body>
</html>
