@extends($layout)
@section('content')
<style>
  /* Laporan Kedisiplinan Custom Modern Styling */
  .kedisiplinan-container {
    padding: 24px 30px;
    background-color: #f8fafc;
    min-height: calc(100vh - 70px);
    font-family: var(--font-family-base, 'Inter', sans-serif);
  }

  .page-header-title {
    font-size: 1.45rem;
    font-weight: 800;
    color: #0f172a;
    letter-spacing: -0.02em;
    margin-bottom: 2px;
  }

  .page-header-subtitle {
    font-size: 0.85rem;
    color: #64748b;
    margin-bottom: 0;
  }

  .breadcrumb-custom {
    background: transparent;
    padding: 0;
    margin: 0;
    font-size: 0.80rem;
    font-weight: 500;
  }

  .breadcrumb-custom .breadcrumb-item a {
    color: #64748b;
    text-decoration: none;
  }

  .breadcrumb-custom .breadcrumb-item.active {
    color: #94a3b8;
  }

  /* Modern Card Container */
  .modern-card {
    background: #ffffff;
    border-radius: 16px;
    border: 1px solid #f1f5f9;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
    margin-bottom: 16px;
    overflow: hidden;
  }

  .modern-card-header {
    padding: 18px 22px 14px;
    border-bottom: 1px solid #f1f5f9;
    display: flex;
    align-items: center;
    justify-content: space-between;
  }

  .modern-card-title {
    font-size: 1.02rem;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 0;
  }

  /* Filter Form Controls */
  .filter-label {
    font-size: 0.76rem;
    font-weight: 600;
    color: #64748b;
    margin-bottom: 6px;
    display: block;
  }

  .filter-input {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    font-size: 0.84rem;
    color: #1e293b;
    font-weight: 500;
    padding: 8px 14px;
    height: 40px;
    width: 100%;
    transition: all 0.2s ease;
  }

  .filter-input:focus {
    background: #ffffff;
    border-color: #1d72fe;
    outline: none;
    box-shadow: 0 0 0 3px rgba(29, 114, 254, 0.12);
  }

  .btn-filter-primary {
    background: #1d72fe;
    color: #ffffff;
    border: none;
    border-radius: 10px;
    font-weight: 600;
    font-size: 0.84rem;
    padding: 8px 24px;
    height: 40px;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    justify-content: center;
  }

  .btn-filter-primary:hover {
    background: #155ecc;
    color: #ffffff;
    box-shadow: 0 4px 12px rgba(29, 114, 254, 0.3);
  }

  .btn-filter-excel {
    background: #ffffff;
    color: #0f172a;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    font-weight: 600;
    font-size: 0.84rem;
    padding: 8px 22px;
    height: 40px;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    text-decoration: none !important;
  }

  .btn-filter-excel:hover {
    background: #f8fafc;
    border-color: #cbd5e1;
    color: #0f172a;
  }

  /* Metric Stat Cards */
  .stat-card-custom {
    background: #ffffff;
    border-radius: 14px;
    border: 1px solid #f1f5f9;
    padding: 18px 20px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 24px;
  }

  .stat-icon-circle {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
  }

  .stat-icon-circle-blue {
    background: #eff6ff;
  }
  .stat-icon-circle-blue .dot {
    width: 14px;
    height: 14px;
    border-radius: 50%;
    background: #1d72fe;
  }

  .stat-icon-circle-amber {
    background: #fffbeb;
  }
  .stat-icon-circle-amber .dot {
    width: 14px;
    height: 14px;
    border-radius: 50%;
    background: #d97706;
  }

  .stat-icon-circle-green {
    background: #ecfdf5;
  }
  .stat-icon-circle-green .dot {
    width: 14px;
    height: 14px;
    border-radius: 50%;
    background: #10b981;
  }

  .stat-icon-circle-red {
    background: #fef2f2;
  }
  .stat-icon-circle-red .dot {
    width: 14px;
    height: 14px;
    border-radius: 50%;
    background: #ef4444;
  }

  .stat-label-custom {
    font-size: 0.78rem;
    font-weight: 500;
    color: #64748b;
    margin-bottom: 2px;
  }

  .stat-value-custom {
    font-size: 1.45rem;
    font-weight: 800;
    color: #0f172a;
    line-height: 1.1;
  }

  /* Table Custom */
  .table-kedisiplinan {
    width: 100%;
    margin-bottom: 0;
    border-collapse: collapse;
  }

  .table-kedisiplinan th {
    font-size: 0.72rem;
    text-transform: uppercase;
    color: #94a3b8;
    font-weight: 700;
    letter-spacing: 0.05em;
    padding: 14px 18px;
    border-bottom: 1px solid #f1f5f9;
    background: #ffffff;
  }

  .table-kedisiplinan td {
    padding: 14px 18px;
    vertical-align: middle;
    border-bottom: 1px solid #f8fafc;
    font-size: 0.84rem;
    color: #334155;
  }

  .table-kedisiplinan tr:last-child td {
    border-bottom: none;
  }

  .table-kedisiplinan tr:hover td {
    background-color: #fafbfd;
  }

  /* Status Dots (Senin - Sabtu) */
  .dot-status {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    display: inline-block;
    transition: transform 0.15s ease;
  }

  .dot-status:hover {
    transform: scale(1.3);
  }

  .dot-green {
    background-color: #10b981;
    box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.2);
  }

  .dot-amber {
    background-color: #f59e0b;
    box-shadow: 0 0 0 2px rgba(245, 158, 11, 0.2);
  }

  .dot-gray {
    background-color: #cbd5e1;
  }

  /* Progress Bar Mini */
  .disiplin-progress-wrap {
    display: flex;
    align-items: center;
    gap: 10px;
  }

  .disiplin-progress-bar {
    width: 65px;
    height: 6px;
    background-color: #f1f5f9;
    border-radius: 4px;
    overflow: hidden;
  }

  .disiplin-progress-fill {
    height: 100%;
    border-radius: 4px;
  }

  .progress-bar-green {
    background-color: #10b981;
  }

  .progress-bar-blue {
    background-color: #2563eb;
  }

  .progress-bar-amber {
    background-color: #f59e0b;
  }

  .progress-bar-red {
    background-color: #ef4444;
  }

  .progress-bar-gray {
    background-color: #cbd5e1;
  }

  /* Status Pills */
  .status-pill {
    padding: 4px 14px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    display: inline-block;
    text-align: center;
    white-space: nowrap;
  }

  .status-pill-green {
    background: #ecfdf5;
    color: #059669;
  }

  .status-pill-blue {
    background: #eff6ff;
    color: #2563eb;
  }

  .status-pill-amber {
    background: #fffbeb;
    color: #d97706;
  }

  .status-pill-red {
    background: #fef2f2;
    color: #dc2626;
  }

  .status-pill-gray {
    background: #f8fafc;
    color: #94a3b8;
  }

  /* Legend Footer */
  .legend-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.80rem;
    color: #64748b;
    font-weight: 500;
  }

  /* Modern Card Header */
  .modern-card-header {
    padding: 16px 20px;
    border-bottom: 1px solid #f1f5f9;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 10px;
  }

  .modern-card-title {
    font-size: 1.02rem;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 0;
  }

  /* Custom Sleek Pagination */
  .pagination-container {
    padding: 16px 22px;
    border-top: 1px solid #f1f5f9;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
  }

  .pagination-info {
    font-size: 0.84rem;
    color: #64748b;
  }

  @media (max-width: 767.98px) {
    .modern-card-header {
      padding: 14px 16px;
      flex-direction: column;
      align-items: flex-start;
      gap: 8px;
    }
    .modern-card-title {
      font-size: 0.94rem;
      line-height: 1.35;
      width: 100%;
    }
    .pagination-container {
      padding: 14px 12px;
      flex-direction: column;
      align-items: center;
      gap: 12px;
    }
    .pagination-container > div {
      width: 100%;
      justify-content: center !important;
      text-align: center;
    }
    .pagination-container .pagination {
      justify-content: center !important;
      flex-wrap: wrap !important;
      gap: 4px;
    }
    .pagination-container .page-item .page-link {
      min-width: 32px !important;
      height: 32px !important;
      padding: 0 6px !important;
      font-size: 0.82rem !important;
    }
  }
</style>

<div class="kedisiplinan-container">
  
  <!-- Page Header & Breadcrumb -->
  <div class="row align-items-center mb-4">
    <div class="col-md-7 col-12 mb-2 mb-md-0 d-flex align-items-center">
      <i class="fas fa-fingerprint text-primary mr-3" style="font-size: 2rem; flex-shrink: 0;"></i>
      <div class="d-flex flex-column justify-content-center">
        <h1 class="page-header-title mb-0" style="line-height: 1.2;">Laporan Kedisiplinan Absensi Siswa</h1>
        <p class="page-header-subtitle mt-1" style="line-height: 1.2;">Rekap absensi mingguan: mesin otomatis dan manual guru</p>
      </div>
    </div>
    <div class="col-md-5 col-12 text-md-right">
      <ol class="breadcrumb breadcrumb-custom justify-content-md-end">
        <li class="breadcrumb-item"><a href="/admin/dashboard">Home</a></li>
        <li class="breadcrumb-item"><a href="/admin/absensi">Absensi</a></li>
        <li class="breadcrumb-item active">Kedisiplinan</li>
      </ol>
    </div>
  </div>

  <!-- Filter Card -->
  <div class="modern-card p-3 mb-4">
    <form action="{{ route('admin.laporanKedisiplinan.index') }}" method="GET">
      <div class="row align-items-end">
        
        <!-- Periode Filter -->
        <div class="col-lg-3 col-md-6 col-12 mb-3 mb-lg-0">
          <label class="filter-label">Periode</label>
          <input type="date" name="tanggal" class="filter-input" value="{{ $tanggalPilihan }}">
        </div>

        <!-- Kelas Filter -->
        <div class="col-lg-3 col-md-6 col-12 mb-3 mb-lg-0">
          <label class="filter-label">Kelas</label>
          <select name="id_kelas" class="filter-input">
            <option value="">Semua kelas</option>
            @foreach($kelasList as $k)
              <option value="{{ $k->id_kelas }}" {{ $selectedKelasId == $k->id_kelas ? 'selected' : '' }}>
                {{ $k->nama_kelas }}
              </option>
            @endforeach
          </select>
        </div>

        <!-- Kategori Filter -->
        <div class="col-lg-3 col-md-6 col-12 mb-3 mb-lg-0">
          <label class="filter-label">Kategori</label>
          <select name="kategori" class="filter-input">
            <option value="">Semua kategori</option>
            <option value="tertib" {{ $selectedKategori == 'tertib' ? 'selected' : '' }}>Tertib (≥80%)</option>
            <option value="cukup" {{ $selectedKategori == 'cukup' ? 'selected' : '' }}>Perlu pantau (50% - 79%)</option>
            <option value="perlu_pembinaan" {{ $selectedKategori == 'perlu_pembinaan' ? 'selected' : '' }}>Pembinaan (<50%)</option>
          </select>
        </div>

        <!-- Action Buttons -->
        <div class="col-lg-3 col-md-6 col-12 d-flex align-items-center" style="gap: 8px;">
          <button type="submit" class="btn-filter-primary flex-grow-1">
            Tampilkan
          </button>
          <a href="{{ route('admin.laporanKedisiplinan.export', ['tanggal' => $tanggalPilihan, 'id_kelas' => $selectedKelasId, 'kategori' => $selectedKategori]) }}" class="btn-filter-excel">
            Excel
          </a>
        </div>

      </div>
    </form>
  </div>

  <!-- Row: 4 Metric Stat Cards -->
  <div class="row mb-2">
    <!-- Card 1: Total Siswa -->
    <div class="col-xl-3 col-md-6 col-12">
      <div class="stat-card-custom">
        <div class="stat-icon-circle stat-icon-circle-blue">
          <div class="dot"></div>
        </div>
        <div>
          <div class="stat-label-custom">Total siswa</div>
          <div class="stat-value-custom">{{ $totalSiswa }}</div>
        </div>
      </div>
    </div>

    <!-- Card 2: Rata-rata Kepatuhan -->
    <div class="col-xl-3 col-md-6 col-12">
      <div class="stat-card-custom">
        <div class="stat-icon-circle stat-icon-circle-amber">
          <div class="dot"></div>
        </div>
        <div>
          <div class="stat-label-custom">Rata-rata kepatuhan</div>
          <div class="stat-value-custom">{{ $rataRataKepatuhan }}%</div>
        </div>
      </div>
    </div>

    <!-- Card 3: Tertib Mesin -->
    <div class="col-xl-3 col-md-6 col-12">
      <div class="stat-card-custom">
        <div class="stat-icon-circle stat-icon-circle-green">
          <div class="dot"></div>
        </div>
        <div>
          <div class="stat-label-custom">Tertib mesin (80%+)</div>
          <div class="stat-value-custom">{{ $countSangatTertib }}</div>
        </div>
      </div>
    </div>

    <!-- Card 4: Sering Manual -->
    <div class="col-xl-3 col-md-6 col-12">
      <div class="stat-card-custom">
        <div class="stat-icon-circle stat-icon-circle-red">
          <div class="dot"></div>
        </div>
        <div>
          <div class="stat-label-custom">Sering manual (&lt;50%)</div>
          <div class="stat-value-custom">{{ $countPerluPembinaan }}</div>
        </div>
      </div>
    </div>
  </div>

  <!-- Table Card: Rekapitulasi Mingguan -->
  <div class="modern-card">
    <div class="modern-card-header d-flex align-items-center justify-content-between flex-wrap">
      <h3 class="modern-card-title mb-0">
        <i class="fas fa-table text-primary mr-2"></i> Rekap Pekan: {{ \Carbon\Carbon::parse($startDateStr)->translatedFormat('d F') }} &ndash; {{ \Carbon\Carbon::parse($endDateStr)->translatedFormat('d F Y') }}
      </h3>
      <span class="badge badge-light border text-muted px-3 py-1 font-weight-600 ml-auto mt-2 mt-md-0" style="border-radius: 20px; font-size: 0.78rem;">
        {{ $totalHasilFilter }} siswa
      </span>
    </div>

    <div class="table-responsive">
      <table class="table-kedisiplinan">
        <thead>
          <tr>
            <th>SISWA</th>
            @foreach($daysInWeek as $day)
              <th class="text-center">{{ $day['hari_singkat'] }}</th>
            @endforeach
            <th>MESIN</th>
            <th>MANUAL</th>
            <th>DISIPLIN</th>
            <th class="text-center">STATUS</th>
          </tr>
        </thead>
        <tbody>
          @forelse($paginatedRekap as $item)
            @php
              $siswa = $item['siswa'];
              $harian = $item['harian'];
            @endphp
            <tr>
              <!-- Siswa Info -->
              <td>
                <div class="font-weight-bold text-dark" style="font-size: 0.88rem;">{{ $siswa->nama_siswa }}</div>
                <div class="text-muted" style="font-size: 0.74rem;">{{ $siswa->nis ?? '-' }} | {{ $siswa->kelas->nama_kelas ?? '-' }}</div>
              </td>

              <!-- Daily Dots -->
              @foreach($daysInWeek as $day)
                @php
                  $dayData = $harian[$day['tanggal']] ?? null;
                  $status = $dayData['status'] ?? 'belum_absen';
                  $dotClass = 'dot-gray';
                  $tooltipText = 'Belum ada data';

                  if ($status === 'mesin') {
                    $dotClass = 'dot-green';
                    $tooltipText = 'Tap Mesin: ' . ($dayData['jam_masuk'] !== '-' ? substr($dayData['jam_masuk'], 0, 5) : '-');
                  } elseif ($status === 'manual' || $status === 'piket') {
                    $dotClass = 'dot-amber';
                    $tooltipText = 'Manual Guru / Piket';
                  } elseif ($status === 'sakit' || $status === 'izin' || $status === 'alfa') {
                    $dotClass = 'dot-gray';
                    $tooltipText = ucfirst($status);
                  }
                @endphp
                <td class="text-center">
                  <span class="dot-status {{ $dotClass }}" data-toggle="tooltip" data-placement="top" title="{{ $day['hari'] }} ({{ $day['tgl_formatted'] }}): {{ $tooltipText }}"></span>
                </td>
              @endforeach

              <!-- Mesin -->
              <td>
                <span class="font-weight-500 text-dark">{{ $item['total_hadir_mesin'] }} hari</span>
              </td>

              <!-- Manual -->
              <td>
                <span class="font-weight-500 text-dark">{{ $item['total_hadir_manual'] }} hari</span>
              </td>

              <!-- Disiplin -->
              <td>
                <div class="disiplin-progress-wrap">
                  <span class="font-weight-bold text-dark" style="min-width: 38px;">{{ $item['persentase_mesin'] }}%</span>
                  <div class="disiplin-progress-bar">
                    <div class="disiplin-progress-fill {{ $item['progress_class'] }}" style="width: {{ $item['persentase_mesin'] }}%;"></div>
                  </div>
                </div>
              </td>

              <!-- Status -->
              <td class="text-center">
                <span class="status-pill {{ $item['status_badge'] }}">
                  {{ $item['status_label'] }}
                </span>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="{{ 5 + count($daysInWeek) }}" class="text-center py-5 text-muted">
                <i class="fas fa-info-circle mr-1"></i> Tidak ada data kedisiplinan siswa pada periode atau filter ini.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <!-- Table Footer: Legend & Pagination -->
    <!-- Table Footer: Legend & Pagination -->
    <div class="pagination-container">
      <!-- Legend -->
      <div class="d-flex align-items-center justify-content-center justify-content-md-start flex-wrap w-100-mobile" style="gap: 16px;">
        <div class="legend-item">
          <span class="dot-status dot-green"></span>
          <span>Tap mesin</span>
        </div>
        <div class="legend-item">
          <span class="dot-status dot-amber"></span>
          <span>Manual guru</span>
        </div>
        <div class="legend-item">
          <span class="dot-status dot-gray"></span>
          <span>Belum ada</span>
        </div>
      </div>

      <!-- Pagination Info & Soft-Pill Navigation -->
      <div class="d-flex flex-column flex-md-row align-items-center justify-content-center justify-content-md-end w-100-mobile" style="gap: 12px;">
        <div class="pagination-info text-muted text-center" style="font-size: 0.84rem; font-weight: 500;">
          Menampilkan <strong class="text-dark">{{ $paginatedRekap->firstItem() ?? 0 }} - {{ $paginatedRekap->lastItem() ?? 0 }}</strong> dari <strong class="text-dark">{{ $paginatedRekap->total() }}</strong> siswa
        </div>

        <div class="d-flex justify-content-center">
          {{ $paginatedRekap->onEachSide(1)->links('pagination::bootstrap-4') }}
        </div>
      </div>
    </div>

  </div>

</div>

@push('scripts')
<script>
  $(function () {
    $('[data-toggle="tooltip"]').tooltip();
  });
</script>
@endpush
@endsection
