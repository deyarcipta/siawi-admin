<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Cek Status Pengajuan Surat PKL | {{ $setting->nama_sekolah ?? 'SMK Wisata Indonesia' }}</title>
  
  <link rel="icon" href="{{ asset('storage/gambar/' . ($setting->logo ?? '1790991925_logo-wi.png')) }}" type="image/x-icon">

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('lte/plugins/fontawesome-free/css/all.min.css') }}">
  <!-- AdminLTE Theme -->
  <link rel="stylesheet" href="{{ asset('lte/dist/css/adminlte.min.css') }}">

  <style>
    :root {
      --font-base: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
      --font-display: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
    }

    body {
      font-family: var(--font-base);
      background-color: #f1f5f9;
      color: #1e293b;
      margin: 0;
      padding: 0;
    }

    .hero-header {
      background: linear-gradient(135deg, #1e3a8a 0%, #1d4ed8 50%, #2563eb 100%);
      color: #ffffff;
      padding: 40px 20px 60px 20px;
      text-align: center;
    }

    .container-track {
      max-width: 760px;
      margin: -35px auto 40px auto;
      padding: 0 16px;
    }

    .search-card {
      background: #ffffff;
      border-radius: 16px;
      box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
      border: 1px solid #e2e8f0;
      padding: 24px;
      margin-bottom: 24px;
    }

    .status-card {
      background: #ffffff;
      border-radius: 16px;
      box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
      border: 1px solid #e2e8f0;
      padding: 28px;
    }

    .status-badge-lg {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 8px 20px;
      border-radius: 9999px;
      font-weight: 700;
      font-size: 0.95rem;
    }

    .footer-text {
      text-align: center;
      color: #64748b;
      font-size: 0.85rem;
      padding: 20px;
    }
  </style>
</head>
<body>

  <header class="hero-header">
    <div style="font-size: 2.2rem; margin-bottom: 8px;">
      <i class="fas fa-search-location"></i>
    </div>
    <h1 style="font-family: var(--font-display); font-weight: 800; font-size: 1.75rem; margin-bottom: 6px;">
      Lacak Status Surat Permohonan PKL
    </h1>
    <p style="font-size: 0.95rem; opacity: 0.9; max-width: 520px; margin: 0 auto;">
      Masukkan Kode Pengajuan, Nomor WhatsApp perwakilan, atau NIS siswa untuk memantau proses ACC dari tim BKK &amp; Hubin.
    </p>
  </header>

  <main class="container-track">

    @if(session('success'))
      <div class="alert alert-success shadow-sm mb-3" role="alert" style="border-radius: 12px;">
        <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
      </div>
    @endif

    <!-- Form Pencarian -->
    <div class="search-card">
      <form action="{{ route('pengajuan-pkl.tracking') }}" method="GET">
        <label class="font-weight-700 text-dark small mb-2">Cari Data Pengajuan Anda:</label>
        <div class="input-group">
          <input type="text" name="kode" class="form-control" style="border-radius: 8px 0 0 8px; padding: 12px 16px; height: auto; font-size: 1rem;" placeholder="Ketik Kode Pengajuan (misal: PKL-...) atau No. WhatsApp..." value="{{ request('kode') }}" required>
          <div class="input-group-append">
            <button class="btn btn-primary font-weight-bold px-4" type="submit" style="border-radius: 0 8px 8px 0;">
              <i class="fas fa-search mr-1"></i> Lacak
            </button>
          </div>
        </div>
      </form>
    </div>

    <!-- Hasil Tracking -->
    @if(request()->filled('kode'))
      @if($pengajuan)
        <div class="status-card">
          
          <div class="text-center mb-4 pb-3 border-bottom">
            @if($pengajuan->status === 'disetujui')
              <div class="status-badge-lg bg-success text-white mb-2">
                <i class="fas fa-check-circle"></i> SUDAH DISETUJUI (ACC)
              </div>
              <h4 class="font-weight-bold text-dark mb-1">Surat Permohonan PKL Telah Diterbitkan</h4>
              <p class="text-muted small mb-0">Silakan hubungi atau kunjungi ruang BKK &amp; Hubin untuk mengambil surat fisik atau meminta berkas cetak.</p>
            @elseif($pengajuan->status === 'ditolak')
              <div class="status-badge-lg bg-danger text-white mb-2">
                <i class="fas fa-times-circle"></i> PENGAJUAN DITOLAK
              </div>
              <h4 class="font-weight-bold text-danger mb-1">Pengajuan Belum Dapat Disetujui</h4>
              <p class="text-muted small mb-0">{{ $pengajuan->catatan_bkk ?: 'Silakan konsultasikan dengan pihak BKK untuk alternatif perusahaan lain.' }}</p>
            @else
              <div class="status-badge-lg bg-warning text-dark mb-2">
                <i class="fas fa-clock"></i> SEDANG DALAM PROSES (MENUNGGU ACC)
              </div>
              <h4 class="font-weight-bold text-dark mb-1">Menunggu Verifikasi Tim BKK</h4>
              <p class="text-muted small mb-0">Pengajuan Anda telah masuk ke sistem dan sedang ditinjau oleh Koordinator Training &amp; Wakahubin.</p>
            @endif
          </div>

          <table class="table table-bordered table-striped small mb-4">
            <tbody>
              <tr>
                <th style="width: 180px;" class="bg-light">Kode Pengajuan</th>
                <td><strong class="text-primary">{{ $pengajuan->kode_pengajuan }}</strong></td>
              </tr>
              <tr>
                <th class="bg-light">Perusahaan Tujuan</th>
                <td><strong class="text-dark">{{ $pengajuan->nama_perusahaan }}</strong></td>
              </tr>
              <tr>
                <th class="bg-light">Penerima Surat</th>
                <td>{{ $pengajuan->ditujukan_kepada }} ({{ $pengajuan->jabatan_tujuan ?: 'H.R & Learning Manager' }})</td>
              </tr>
              <tr>
                <th class="bg-light">Periode PKL</th>
                <td><strong class="text-dark">{{ $pengajuan->periode_teks }}</strong></td>
              </tr>
              @if($pengajuan->status === 'disetujui' && $pengajuan->nomor_surat)
              <tr>
                <th class="bg-light text-success font-weight-bold">Nomor Surat Resmi</th>
                <td><strong class="text-success font-monospace" style="font-size: 0.95rem;">{{ $pengajuan->nomor_surat }}</strong></td>
              </tr>
              @endif
              <tr>
                <th class="bg-light">Tanggal Pengajuan</th>
                <td>{{ $pengajuan->created_at->format('d/m/Y H:i') }} WIB</td>
              </tr>
              <tr>
                <th class="bg-light">Perwakilan Siswa</th>
                <td>{{ $pengajuan->nama_pemohon }} ({{ $pengajuan->kontak_pemohon }})</td>
              </tr>
            </tbody>
          </table>

          <h6 class="font-weight-bold text-dark mb-2"><i class="fas fa-users text-primary mr-1"></i> Daftar Siswa Dalam Pengajuan Ini:</h6>
          <div class="table-responsive">
            <table class="table table-sm table-bordered">
              <thead class="bg-light">
                <tr>
                  <th style="width: 40px;" class="text-center">No</th>
                  <th>Nama Lengkap</th>
                  <th>Program Keahlian</th>
                </tr>
              </thead>
              <tbody>
                @foreach($pengajuan->siswaList as $idx => $s)
                  <tr>
                    <td class="text-center font-weight-bold">{{ $idx + 1 }}</td>
                    <td class="font-weight-600">{{ $s->nama_siswa }}</td>
                    <td>{{ $s->program_keahlian }}</td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>

        </div>
      @else
        <div class="status-card text-center py-4">
          <i class="fas fa-question-circle text-muted fa-3x mb-3"></i>
          <h5 class="font-weight-bold text-dark">Data Pengajuan Tidak Ditemukan</h5>
          <p class="text-muted small mb-0">Pastikan kode pengajuan, nomor WA, atau NIS yang Anda masukkan sudah benar.</p>
        </div>
      @endif
    @endif

    <div class="text-center mt-4">
      <a href="{{ route('pengajuan-pkl.form') }}" class="btn btn-outline-primary btn-sm font-weight-bold mr-2">
        <i class="fas fa-plus mr-1"></i> Ajukan Surat Baru
      </a>
      <a href="{{ url('/') }}" class="btn btn-outline-secondary btn-sm font-weight-bold">
        <i class="fas fa-home mr-1"></i> Ke Halaman Utama
      </a>
    </div>

  </main>

  <footer class="footer-text">
    &copy; 2024 {{ $setting->nama_sekolah ?? 'SMK Wisata Indonesia' }} &bull; SIAWI Integrated System
  </footer>

</body>
</html>
