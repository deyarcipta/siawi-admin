<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Pengajuan Surat Permohonan PKL | {{ $setting->nama_sekolah ?? 'SMK Wisata Indonesia' }}</title>
  
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
      padding: 40px 20px 70px 20px;
      text-align: center;
      position: relative;
    }

    .school-logo-badge {
      width: 75px;
      height: 75px;
      background: #ffffff;
      border-radius: 16px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      padding: 6px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.15);
      margin-bottom: 14px;
    }

    .school-logo-badge img {
      max-width: 100%;
      max-height: 100%;
      object-fit: contain;
    }

    .form-wrapper {
      max-width: 860px;
      margin: -40px auto 40px auto;
      padding: 0 16px;
      position: relative;
    }

    .form-card {
      background: #ffffff;
      border-radius: 18px;
      box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
      border: 1px solid #e2e8f0;
      padding: 32px 28px;
    }

    .section-title {
      font-family: var(--font-display);
      font-weight: 700;
      font-size: 1.1rem;
      color: #0f172a;
      display: flex;
      align-items: center;
      gap: 10px;
      margin-bottom: 18px;
      padding-bottom: 8px;
      border-bottom: 2px solid #f1f5f9;
    }

    .form-control, .custom-select {
      border-radius: 8px;
      border-color: #cbd5e1;
      padding: 10px 14px;
      height: auto;
      font-size: 0.95rem;
    }

    .form-control:focus {
      border-color: #2563eb;
      box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
    }

    .table-siswa-input th {
      background-color: #f8fafc;
      font-size: 0.85rem;
      font-weight: 700;
      color: #334155;
    }

    .btn-submit {
      background: linear-gradient(135deg, #2563eb, #1d4ed8);
      color: #ffffff;
      font-weight: 700;
      font-size: 1.05rem;
      padding: 14px 28px;
      border-radius: 10px;
      border: none;
      width: 100%;
      box-shadow: 0 4px 15px rgba(37, 99, 235, 0.3);
      transition: all 0.2s ease;
    }

    .btn-submit:hover {
      background: linear-gradient(135deg, #1d4ed8, #1e40af);
      transform: translateY(-1px);
      box-shadow: 0 6px 20px rgba(37, 99, 235, 0.4);
      color: #ffffff;
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

  <!-- Header Banner -->
  <header class="hero-header">
    <div class="school-logo-badge">
      <img src="{{ asset('storage/gambar/' . ($setting->logo ?? '1790991925_logo-wi.png')) }}" alt="Logo">
    </div>
    <h1 style="font-family: var(--font-display); font-weight: 800; font-size: 1.85rem; margin-bottom: 6px;">
      Pengajuan Surat Permohonan PKL
    </h1>
    <p style="font-size: 1rem; opacity: 0.9; max-width: 600px; margin: 0 auto;">
      {{ $setting->nama_sekolah ?? 'SMK Wisata Indonesia' }} &bull; Hubungan Industri &amp; BKK
    </p>
    <div class="mt-3">
      <a href="{{ route('pengajuan-pkl.tracking') }}" class="btn btn-sm btn-outline-light font-weight-bold" style="border-radius: 20px; padding: 6px 18px;">
        <i class="fas fa-search mr-1"></i> Cek Status Pengajuan Saya
      </a>
    </div>
  </header>

  <!-- Container Form -->
  <main class="form-wrapper">
    <div class="form-card">

      @if($errors->any())
        <div class="alert alert-danger shadow-sm mb-4" role="alert">
          <h6 class="font-weight-bold mb-1"><i class="fas fa-exclamation-triangle mr-1"></i> Mohon periksa kembali formulir Anda:</h6>
          <ul class="mb-0 pl-3 small">
            @foreach($errors->all() as $err)
              <li>{{ $err }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form action="{{ route('pengajuan-pkl.submit') }}" method="POST">
        @csrf

        <!-- 1. Perusahaan Tujuan -->
        <div class="section-title">
          <i class="fas fa-hotel text-primary"></i> 1. Tempat / Perusahaan Tujuan PKL
        </div>

        <div class="form-group mb-3">
          <label class="font-weight-600 text-dark small">Pilih dari Daftar Mitra DU/DI Sekolah (Jika Tersedia)</label>
          <select class="form-control" id="selectMitra">
            <option value="">-- Pilih Mitra atau Ketik Manual di Bawah --</option>
            @foreach($perusahaan as $p)
              <option value="{{ $p->id_perusahaan }}" 
                      data-nama="{{ $p->nama_perusahaan }}" 
                      data-alamat="{{ $p->alamat }}" 
                      data-pic="{{ $p->pic ?: $p->penanggung_jawab }}">
                {{ $p->nama_perusahaan }} ({{ $p->kota ?: 'Mitra' }})
              </option>
            @endforeach
          </select>
          <input type="hidden" name="id_perusahaan" id="id_perusahaan" value="{{ old('id_perusahaan') }}">
        </div>

        <div class="form-group mb-3">
          <label class="font-weight-600 text-dark small">Nama Perusahaan / Hotel / Industri <span class="text-danger">*</span></label>
          <input type="text" name="nama_perusahaan" id="nama_perusahaan" class="form-control" value="{{ old('nama_perusahaan') }}" required placeholder="Contoh: Hotel Le Meridian Jakarta">
        </div>

        <div class="row">
          <div class="col-md-6 form-group mb-3">
            <label class="font-weight-600 text-dark small">Ditujukan Kepada (Nama Penerima / HRD) <span class="text-danger">*</span></label>
            <input type="text" name="ditujukan_kepada" id="ditujukan_kepada" class="form-control" value="{{ old('ditujukan_kepada', 'Ibu. Cathleen Abigail') }}" required placeholder="Contoh: Ibu. Cathleen Abigail / Pimpinan HRD">
          </div>
          <div class="col-md-6 form-group mb-3">
            <label class="font-weight-600 text-dark small">Jabatan Penerima</label>
            <input type="text" name="jabatan_tujuan" id="jabatan_tujuan" class="form-control" value="{{ old('jabatan_tujuan', 'H.R & Learning Manager') }}" placeholder="Contoh: H.R & Learning Manager / HRD Manager">
          </div>
        </div>

        <div class="form-group mb-4">
          <label class="font-weight-600 text-dark small">Alamat Perusahaan (Opsional)</label>
          <input type="text" name="alamat_perusahaan" id="alamat_perusahaan" class="form-control" value="{{ old('alamat_perusahaan') }}" placeholder="Contoh: Jl. Jend. Sudirman Kav. 18-20, Jakarta">
        </div>

        <!-- 2. Periode PKL -->
        <div class="section-title">
          <i class="fas fa-calendar-alt text-primary"></i> 2. Rencana Periode Pelaksanaan PKL
        </div>

        <div class="form-group mb-3">
          <label class="font-weight-600 text-dark small">Teks Periode PKL <span class="text-danger">*</span></label>
          <input type="text" name="periode_teks" class="form-control" value="{{ old('periode_teks', 'Januari 2027 – Juni 2027 ( 6 bulan )') }}" required placeholder="Contoh: Januari 2027 – Juni 2027 ( 6 bulan )">
          <small class="text-muted">Teks ini yang akan langsung tercetak di surat resmi permohonan PKL.</small>
        </div>

        <div class="row mb-4">
          <div class="col-md-6 form-group">
            <label class="font-weight-600 text-dark small">Estimasi Tanggal Mulai (Opsional)</label>
            <input type="date" name="tanggal_mulai" class="form-control" value="{{ old('tanggal_mulai') }}">
          </div>
          <div class="col-md-6 form-group">
            <label class="font-weight-600 text-dark small">Estimasi Tanggal Selesai (Opsional)</label>
            <input type="date" name="tanggal_selesai" class="form-control" value="{{ old('tanggal_selesai') }}">
          </div>
        </div>

        <!-- 3. Siswa Pemohon & Anggota Kelompok -->
        <div class="section-title justify-content-between">
          <div><i class="fas fa-users text-primary"></i> 3. Data Siswa yang Diajukan (Kelompok)</div>
          <button type="button" class="btn btn-outline-primary btn-sm font-weight-bold" id="btnAddSiswa" style="border-radius: 6px;">
            <i class="fas fa-plus mr-1"></i> Tambah Anggota
          </button>
        </div>
        <p class="text-muted small mb-3">
          Bisa terdiri dari 1 orang siswa atau sekelompok siswa yang bersama-sama melamar ke perusahaan tersebut.
        </p>

        <div class="table-responsive mb-4">
          <table class="table table-bordered table-siswa-input">
            <thead>
              <tr>
                <th style="width: 45px;" class="text-center">No</th>
                <th>Nama Lengkap Siswa <span class="text-danger">*</span></th>
                <th style="width: 220px;">Program Keahlian <span class="text-danger">*</span></th>
                <th style="width: 140px;">NIS</th>
                <th style="width: 50px;" class="text-center"></th>
              </tr>
            </thead>
            <tbody id="siswaListBody">
              <tr class="row-siswa">
                <td class="text-center font-weight-bold row-no">1</td>
                <td>
                  <input type="text" name="siswa[0][nama]" class="form-control form-control-sm" required placeholder="Nama lengkap siswa...">
                </td>
                <td>
                  <select name="siswa[0][program_keahlian]" class="form-control form-control-sm" required>
                    <option value="Perhotelan">Perhotelan</option>
                    <option value="Kuliner">Kuliner (Jasa Boga)</option>
                    <option value="Teknik Komputer & Jaringan">Teknik Komputer & Jaringan</option>
                  </select>
                </td>
                <td>
                  <input type="text" name="siswa[0][nis]" class="form-control form-control-sm" placeholder="NIS">
                </td>
                <td class="text-center">
                  <button type="button" class="btn btn-sm btn-outline-danger btn-del-row" disabled>
                    <i class="fas fa-times"></i>
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- 4. Kontak Perwakilan Siswa -->
        <div class="section-title">
          <i class="fas fa-address-book text-primary"></i> 4. Kontak Perwakilan Siswa (Untuk Verifikasi BKK)
        </div>

        <div class="row">
          <div class="col-md-6 form-group mb-3">
            <label class="font-weight-600 text-dark small">Nama Siswa Perwakilan / Ketua Kelompok <span class="text-danger">*</span></label>
            <input type="text" name="nama_pemohon" class="form-control" value="{{ old('nama_pemohon') }}" required placeholder="Contoh: Raicha Anggie Dwi Anjani">
          </div>
          <div class="col-md-6 form-group mb-3">
            <label class="font-weight-600 text-dark small">No. WhatsApp Aktif Perwakilan <span class="text-danger">*</span></label>
            <input type="text" name="kontak_pemohon" class="form-control" value="{{ old('kontak_pemohon') }}" required placeholder="Contoh: 081234567890">
            <small class="text-muted">Untuk konfirmasi surat selesai atau informasi dari pihak BKK.</small>
          </div>
        </div>

        <div class="form-group mb-4">
          <label class="font-weight-600 text-dark small">Catatan Tambahan untuk BKK (Opsional)</label>
          <textarea name="catatan_siswa" class="form-control" rows="2" placeholder="Tuliskan keterangan jika ada permintaan khusus...">{{ old('catatan_siswa') }}</textarea>
        </div>

        <!-- Tombol Submit -->
        <button type="submit" class="btn-submit">
          <i class="fas fa-paper-plane mr-2"></i> Kirim Pengajuan Surat Permohonan PKL
        </button>

      </form>
    </div>
  </main>

  <footer class="footer-text">
    &copy; 2024 {{ $setting->nama_sekolah ?? 'SMK Wisata Indonesia' }} &bull; SIAWI Integrated System
  </footer>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Auto-fill dari dropdown mitra
      const selectMitra = document.getElementById('selectMitra');
      if (selectMitra) {
        selectMitra.addEventListener('change', function() {
          const opt = this.options[this.selectedIndex];
          if (this.value) {
            document.getElementById('id_perusahaan').value = this.value;
            document.getElementById('nama_perusahaan').value = opt.getAttribute('data-nama') || '';
            document.getElementById('alamat_perusahaan').value = opt.getAttribute('data-alamat') || '';
            if (opt.getAttribute('data-pic')) {
              document.getElementById('ditujukan_kepada').value = opt.getAttribute('data-pic');
            }
          } else {
            document.getElementById('id_perusahaan').value = '';
          }
        });
      }

      // Dynamic Row Siswa
      let idx = 1;
      const tbody = document.getElementById('siswaListBody');
      const btnAdd = document.getElementById('btnAddSiswa');

      btnAdd.addEventListener('click', function() {
        const tr = document.createElement('tr');
        tr.className = 'row-siswa';
        tr.innerHTML = `
          <td class="text-center font-weight-bold row-no">${tbody.children.length + 1}</td>
          <td>
            <input type="text" name="siswa[${idx}][nama]" class="form-control form-control-sm" required placeholder="Nama lengkap siswa...">
          </td>
          <td>
            <select name="siswa[${idx}][program_keahlian]" class="form-control form-control-sm" required>
              <option value="Perhotelan">Perhotelan</option>
              <option value="Kuliner">Kuliner (Jasa Boga)</option>
              <option value="Teknik Komputer & Jaringan">Teknik Komputer & Jaringan</option>
            </select>
          </td>
          <td>
            <input type="text" name="siswa[${idx}][nis]" class="form-control form-control-sm" placeholder="NIS">
          </td>
          <td class="text-center">
            <button type="button" class="btn btn-sm btn-outline-danger btn-del-row">
              <i class="fas fa-times"></i>
            </button>
          </td>
        `;
        tbody.appendChild(tr);
        idx++;
        updateNumbers();
      });

      tbody.addEventListener('click', function(e) {
        if (e.target.closest('.btn-del-row')) {
          const row = e.target.closest('tr');
          if (tbody.children.length > 1) {
            row.remove();
            updateNumbers();
          }
        }
      });

      function updateNumbers() {
        Array.from(tbody.children).forEach((row, i) => {
          row.querySelector('.row-no').textContent = i + 1;
          const del = row.querySelector('.btn-del-row');
          del.disabled = (tbody.children.length === 1);
        });
      }
    });
  </script>
</body>
</html>
