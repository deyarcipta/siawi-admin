@extends($layout)

@section('content')
<!-- Content Header -->
<div class="content-header pb-2">
  <div class="container-fluid">
    <div class="row align-items-center mb-2">
      <div class="col-sm-7 d-flex align-items-center">
        <div class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle shadow-sm mr-3" style="width: 46px; height: 46px; font-size: 1.25rem;">
          <i class="fas fa-paper-plane"></i>
        </div>
        <div>
          <h1 class="m-0 font-weight-bold text-dark" style="line-height: 1.2; font-size: 1.35rem;">Rekap Mingguan & Notifikasi WA</h1>
          <p class="text-muted mt-1 mb-0" style="font-size: 0.84rem;">Rekapitulasi presensi mingguan siswa dan broadcast WhatsApp</p>
        </div>
      </div>
      <div class="col-sm-5">
        <ol class="breadcrumb float-sm-right bg-transparent p-0 mb-0">
          <li class="breadcrumb-item"><a href="/admin/dashboard" class="text-primary font-weight-500">Dashboard</a></li>
          <li class="breadcrumb-item"><a href="#" class="text-primary font-weight-500">Absensi Siswa</a></li>
          <li class="breadcrumb-item active">Rekap Mingguan & WA</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<!-- Main content -->
<div class="content">
  <div class="container-fluid">

    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    @endif

    @if(session('error'))
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    @endif

    <!-- Filter Card -->
    <div class="card mb-4">
      <div class="card-header bg-white d-flex align-items-center justify-content-between flex-wrap py-3">
        <h3 class="card-title text-dark font-weight-bold mb-0">
          <i class="fas fa-sliders-h text-primary mr-2"></i> Filter Data Pekanan & Kelas
        </h3>
      </div>
      <div class="card-body bg-white pt-3">
        <form action="{{ route('admin.laporanMingguanWa.index') }}" method="GET">
          <div class="row align-items-end">
            <div class="col-md-4 mb-3 mb-md-0">
              <label for="id_kelas" class="font-weight-bold text-secondary" style="font-size: 0.78rem; text-transform: uppercase;">PILIH KELAS:</label>
              <select name="id_kelas" id="id_kelas" class="form-control" onchange="this.form.submit()">
                <option value="all" {{ $idKelas == 'all' ? 'selected' : '' }}>-- Semua Kelas --</option>
                @foreach($daftarKelas as $kls)
                  <option value="{{ $kls->id_kelas }}" {{ $idKelas == $kls->id_kelas ? 'selected' : '' }}>
                    {{ $kls->nama_kelas }} (Wali: {{ $kls->waliKelas->nama_guru ?? 'Belum Diatur' }})
                  </option>
                @endforeach
              </select>
            </div>
            <div class="col-md-3 mb-3 mb-md-0">
              <label for="tanggal_mulai" class="font-weight-bold text-secondary" style="font-size: 0.78rem; text-transform: uppercase;">TANGGAL MULAI (SENIN):</label>
              <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="form-control" value="{{ $tanggalMulai }}">
            </div>
            <div class="col-md-3 mb-3 mb-md-0">
              <label for="tanggal_selesai" class="font-weight-bold text-secondary" style="font-size: 0.78rem; text-transform: uppercase;">TANGGAL SELESAI (JUMAT/SABTU):</label>
              <input type="date" name="tanggal_selesai" id="tanggal_selesai" class="form-control" value="{{ $tanggalSelesai }}">
            </div>
            <div class="col-md-2">
              <button type="submit" class="btn btn-primary btn-block">
                <i class="fas fa-search mr-1"></i> Tampilkan
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>

    <!-- Summary KPI Cards -->
    <div class="row mb-2">
      <!-- Total Siswa -->
      <div class="col-xl-2 col-md-4 col-6 mb-3">
        <div class="card p-3 mb-0 h-100 d-flex flex-row align-items-center justify-content-between">
          <div>
            <div class="text-muted small font-weight-bold mb-1" style="font-size: 0.72rem; letter-spacing: 0.03em; text-transform: uppercase;">Total Siswa</div>
            <div class="font-weight-bold text-dark" style="font-size: 1.45rem; line-height: 1.1;">{{ $rekapData['summary']['total_siswa'] }}</div>
          </div>
          <div class="d-flex align-items-center justify-content-center rounded-circle" style="width: 42px; height: 42px; background: #eff6ff; flex-shrink: 0;">
            <i class="fas fa-users text-primary" style="font-size: 1.1rem;"></i>
          </div>
        </div>
      </div>

      <!-- Rata-rata Hadir -->
      <div class="col-xl-2 col-md-4 col-6 mb-3">
        <div class="card p-3 mb-0 h-100 d-flex flex-row align-items-center justify-content-between">
          <div>
            <div class="text-muted small font-weight-bold mb-1" style="font-size: 0.72rem; letter-spacing: 0.03em; text-transform: uppercase;">Rata-rata Hadir</div>
            <div class="font-weight-bold text-dark" style="font-size: 1.45rem; line-height: 1.1;">{{ $rekapData['summary']['rata_rata_kehadiran'] }}%</div>
          </div>
          <div class="d-flex align-items-center justify-content-center rounded-circle" style="width: 42px; height: 42px; background: #ecfdf5; flex-shrink: 0;">
            <i class="fas fa-chart-line text-success" style="font-size: 1.1rem;"></i>
          </div>
        </div>
      </div>

      <!-- Hadir Tepat Waktu -->
      <div class="col-xl-2 col-md-4 col-6 mb-3">
        <div class="card p-3 mb-0 h-100 d-flex flex-row align-items-center justify-content-between">
          <div>
            <div class="text-muted small font-weight-bold mb-1" style="font-size: 0.72rem; letter-spacing: 0.03em; text-transform: uppercase;">Hadir Tepat</div>
            <div class="font-weight-bold text-dark" style="font-size: 1.45rem; line-height: 1.1;">{{ $rekapData['summary']['total_hadir_tepat'] }}</div>
          </div>
          <div class="d-flex align-items-center justify-content-center rounded-circle" style="width: 42px; height: 42px; background: #f0fdf4; flex-shrink: 0;">
            <i class="fas fa-check-circle text-success" style="font-size: 1.1rem;"></i>
          </div>
        </div>
      </div>

      <!-- Terlambat -->
      <div class="col-xl-2 col-md-4 col-6 mb-3">
        <div class="card p-3 mb-0 h-100 d-flex flex-row align-items-center justify-content-between">
          <div>
            <div class="text-muted small font-weight-bold mb-1" style="font-size: 0.72rem; letter-spacing: 0.03em; text-transform: uppercase;">Terlambat</div>
            <div class="font-weight-bold text-dark" style="font-size: 1.45rem; line-height: 1.1;">{{ $rekapData['summary']['total_terlambat'] }}</div>
          </div>
          <div class="d-flex align-items-center justify-content-center rounded-circle" style="width: 42px; height: 42px; background: #fffbeb; flex-shrink: 0;">
            <i class="fas fa-clock text-warning" style="font-size: 1.1rem;"></i>
          </div>
        </div>
      </div>

      <!-- Sakit / Izin -->
      <div class="col-xl-2 col-md-4 col-6 mb-3">
        <div class="card p-3 mb-0 h-100 d-flex flex-row align-items-center justify-content-between">
          <div>
            <div class="text-muted small font-weight-bold mb-1" style="font-size: 0.72rem; letter-spacing: 0.03em; text-transform: uppercase;">Sakit / Izin</div>
            <div class="font-weight-bold text-dark" style="font-size: 1.45rem; line-height: 1.1;">{{ $rekapData['summary']['total_sakit'] + $rekapData['summary']['total_izin'] }}</div>
          </div>
          <div class="d-flex align-items-center justify-content-center rounded-circle" style="width: 42px; height: 42px; background: #f8fafc; flex-shrink: 0;">
            <i class="fas fa-notes-medical text-secondary" style="font-size: 1.1rem;"></i>
          </div>
        </div>
      </div>

      <!-- Alfa / Bolos -->
      <div class="col-xl-2 col-md-4 col-6 mb-3">
        <div class="card p-3 mb-0 h-100 d-flex flex-row align-items-center justify-content-between">
          <div>
            <div class="text-muted small font-weight-bold mb-1" style="font-size: 0.72rem; letter-spacing: 0.03em; text-transform: uppercase;">Alfa / Bolos</div>
            <div class="font-weight-bold text-dark" style="font-size: 1.45rem; line-height: 1.1;">{{ $rekapData['summary']['total_alfa'] }}</div>
          </div>
          <div class="d-flex align-items-center justify-content-center rounded-circle" style="width: 42px; height: 42px; background: #fef2f2; flex-shrink: 0;">
            <i class="fas fa-user-times text-danger" style="font-size: 1.1rem;"></i>
          </div>
        </div>
      </div>
    </div>

    <!-- Data Table Card -->
    <div class="card">
      <div class="card-header bg-white d-flex align-items-md-center justify-content-between flex-column flex-md-row py-3">
        <div class="d-flex align-items-center mb-2 mb-md-0">
          <i class="fas fa-table text-primary mr-2" style="font-size: 1.25rem;"></i>
          <div class="d-flex flex-column justify-content-center">
            <h3 class="card-title font-weight-bold text-dark mb-0" style="font-size: 1.05rem; float: none !important; margin: 0; line-height: 1.2;">
              Rekapitulasi Presensi Mingguan Siswa
            </h3>
            <div class="text-muted" style="font-size: 0.78rem; line-height: 1.2; font-weight: 500; margin-top: 3px;">
              Periode: <span class="font-weight-bold text-dark">{{ $rekapData['periode']['label'] }}</span>
            </div>
          </div>
        </div>
        <div class="card-tools d-flex align-items-center flex-wrap w-100-mobile ml-md-auto mt-2 mt-md-0" style="gap: 8px;">
          <!-- Button Preview WA -->
          <button type="button" class="btn btn-secondary btn-sm" data-toggle="modal" data-target="#modalPreviewWa" onclick="loadPreviewWa()">
            <i class="fas fa-eye mr-1 text-primary"></i> Preview Format WA
          </button>

          @if($idKelas && $idKelas !== 'all')
          <!-- Button Kirim ke Wali Kelas -->
          <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalKirimWali">
            <i class="fas fa-chalkboard-teacher mr-1"></i> Kirim ke Wali Kelas
          </button>

          <!-- Button Kirim ke Orang Tua -->
          <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#modalKirimOrtu">
            <i class="fab fa-whatsapp mr-1"></i> Broadcast WA Mingguan
          </button>
          @endif
        </div>
      </div>

      <div class="card-body">
        <div class="p-3 mb-3 rounded-lg d-flex align-items-center" style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 12px;">
          <div class="d-flex align-items-center justify-content-center rounded-circle mr-3" style="width: 38px; height: 38px; background: #dcfce7; flex-shrink: 0;">
            <i class="fas fa-shield-alt text-success"></i>
          </div>
          <div style="font-size: 0.83rem; line-height: 1.45;">
            <strong class="text-success">Protokol Anti-Blokir WhatsApp Aktif:</strong>
            <span class="text-muted ml-1">
              Pengiriman pesan otomatis diproses di latar belakang secara antrean (*queue*) dengan jeda acak 12–18 detik, rotasi sesi load balancing, dan istirahat berkala untuk keamanan nomor WA.
            </span>
          </div>
        </div>

        <div class="table-responsive">
          <table id="example2" class="table table-bordered table-hover table-striped">
            <thead>
              <tr class="text-center">
                <th style="width: 10px;">No</th>
                <th class="text-left">Nama Siswa</th>
                <th>Hadir</th>
                <th>Terlambat</th>
                <th>Sakit</th>
                <th>Izin</th>
                <th>Alfa</th>
                <th>% Kehadiran</th>
                <th>Evaluasi Kedisiplinan</th>
              </tr>
            </thead>
            <tbody>
              @foreach($rekapData['rekap_siswa'] as $item)
              <tr>
                <td class="text-center font-weight-bold">{{ $loop->iteration }}</td>
                <td>
                  <div class="font-weight-bold text-dark" style="font-size: 0.88rem;">{{ $item['siswa']->nama_siswa }}</div>
                  <div class="text-muted small" style="font-size: 0.76rem;">
                    NIS: {{ $item['siswa']->nis }} &bull; {{ $item['siswa']->kelas->nama_kelas ?? '-' }}
                  </div>
                </td>
                <td class="text-center font-weight-bold text-success">{{ $item['hadir_tepat'] }}</td>
                <td class="text-center font-weight-bold text-warning">{{ $item['terlambat'] }}</td>
                <td class="text-center font-weight-bold text-info">{{ $item['sakit'] }}</td>
                <td class="text-center font-weight-bold text-secondary">{{ $item['izin'] }}</td>
                <td class="text-center font-weight-bold text-danger">{{ $item['alfa'] }}</td>
                <td class="text-center">
                  <div class="d-flex align-items-center justify-content-center">
                    <span class="font-weight-bold">{{ $item['persentase'] }}%</span>
                  </div>
                </td>
                <td class="text-center">
                  <span class="badge {{ $item['badge_class'] }} p-2" style="font-size: 11px;">
                    {{ $item['status_kedisiplinan'] }}
                  </span>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>

  </div>
</div>

<!-- Modal Preview Format Pesan WA -->
<div class="modal fade" id="modalPreviewWa" tabindex="-1" role="dialog" aria-labelledby="modalPreviewWaTitle" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header bg-info text-white">
        <h5 class="modal-title font-weight-bold" id="modalPreviewWaTitle">
          <i class="fab fa-whatsapp mr-1"></i> Preview Template Notifikasi WhatsApp
        </h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
          <li class="nav-item">
            <a class="nav-link active" id="pills-ortu-tab" data-toggle="pill" href="#pills-ortu" role="tab" aria-controls="pills-ortu" aria-selected="true">
              <i class="fas fa-user-friends mr-1"></i> Format Orang Tua Siswa
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="pills-wali-tab" data-toggle="pill" href="#pills-wali" role="tab" aria-controls="pills-wali" aria-selected="false">
              <i class="fas fa-chalkboard-teacher mr-1"></i> Format Wali Kelas
            </a>
          </li>
        </ul>
        <div class="tab-content" id="pills-tabContent">
          <div class="tab-pane fade show active" id="pills-ortu" role="tabpanel" aria-labelledby="pills-ortu-tab">
            <div class="card bg-light">
              <div class="card-body">
                <pre id="previewOrtuText" class="p-3 bg-white border rounded text-dark" style="white-space: pre-wrap; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size: 13px;">Memuat preview template...</pre>
              </div>
            </div>
          </div>
          <div class="tab-pane fade" id="pills-wali" role="tabpanel" aria-labelledby="pills-wali-tab">
            <div class="card bg-light">
              <div class="card-body">
                <pre id="previewWaliText" class="p-3 bg-white border rounded text-dark" style="white-space: pre-wrap; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size: 13px;">Memuat preview template...</pre>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Konfirmasi Kirim ke Orang Tua -->
@if($idKelas && $idKelas !== 'all')
<div class="modal fade" id="modalKirimOrtu" tabindex="-1" role="dialog" aria-labelledby="modalKirimOrtuTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title font-weight-bold" id="modalKirimOrtuTitle">
          <i class="fab fa-whatsapp mr-1"></i> Konfirmasi Broadcast WhatsApp Orang Tua
        </h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{ route('admin.laporanMingguanWa.kirimOrangTua') }}" method="POST">
        @csrf
        <input type="hidden" name="id_kelas" value="{{ $idKelas }}">
        <input type="hidden" name="tanggal_mulai" value="{{ $tanggalMulai }}">
        <input type="hidden" name="tanggal_selesai" value="{{ $tanggalSelesai }}">
        
        <div class="modal-body text-center p-4">
          <i class="fab fa-whatsapp text-success fa-4x mb-3"></i>
          <h5>Kirim Laporan Rekap Mingguan ke Orang Tua?</h5>
          <p class="text-muted">
            Sistem akan mengirimkan laporan rekap absensi pekanan personal ke seluruh nomor WhatsApp Orang Tua siswa kelas <strong>{{ $selectedKelas->nama_kelas ?? '' }}</strong> (Total: <strong>{{ count($rekapData['rekap_siswa']) }} siswa</strong>).
          </p>
          <div class="alert alert-info text-left text-sm mb-0">
            <i class="fas fa-info-circle mr-1"></i> Pesan akan dimasukkan ke antrean background (*queue*) dan dikirim bertahap dengan jeda aman 12–18 detik per pesan.
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-success font-weight-bold">
            <i class="fas fa-paper-plane mr-1"></i> Ya, Masukkan ke Antrean
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Konfirmasi Kirim ke Wali Kelas -->
<div class="modal fade" id="modalKirimWali" tabindex="-1" role="dialog" aria-labelledby="modalKirimWaliTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title font-weight-bold" id="modalKirimWaliTitle">
          <i class="fas fa-chalkboard-teacher mr-1"></i> Kirim Rekap Mingguan ke Wali Kelas
        </h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{ route('admin.laporanMingguanWa.kirimWaliKelas') }}" method="POST">
        @csrf
        <input type="hidden" name="id_kelas" value="{{ $idKelas }}">
        <input type="hidden" name="tanggal_mulai" value="{{ $tanggalMulai }}">
        <input type="hidden" name="tanggal_selesai" value="{{ $tanggalSelesai }}">
        
        <div class="modal-body text-center p-4">
          <i class="fas fa-chalkboard-teacher text-primary fa-4x mb-3"></i>
          <h5>Kirim Ringkasan Kelas ke Wali Kelas?</h5>
          <p class="text-muted">
            Ringkasan absensi kelas <strong>{{ $selectedKelas->nama_kelas ?? '' }}</strong> akan dikirimkan ke nomor WhatsApp Wali Kelas: <strong>{{ $selectedKelas->waliKelas->nama_guru ?? 'Belum Diatur' }}</strong> ({{ $selectedKelas->waliKelas->no_hp ?? 'No HP belum ada' }}).
          </p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary font-weight-bold">
            <i class="fas fa-paper-plane mr-1"></i> Ya, Kirim Ringkasan
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endif

<script>
function loadPreviewWa() {
    const idKelas = "{{ $idKelas }}";
    const tglMulai = "{{ $tanggalMulai }}";
    const tglSelesai = "{{ $tanggalSelesai }}";

    fetch(`/admin/laporan-mingguan-wa/preview?id_kelas=${idKelas}&tanggal_mulai=${tglMulai}&tanggal_selesai=${tglSelesai}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('previewOrtuText').textContent = data.preview_orang_tua;
            document.getElementById('previewWaliText').textContent = data.preview_wali_kelas;
        })
        .catch(err => {
            console.error('Error loading preview:', err);
            document.getElementById('previewOrtuText').textContent = 'Gagal memuat preview template.';
            document.getElementById('previewWaliText').textContent = 'Gagal memuat preview template.';
        });
}
</script>
@endsection
