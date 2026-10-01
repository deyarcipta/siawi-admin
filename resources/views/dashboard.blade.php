@extends($layout)
@section('content')
<style>
  /* === MODERN DASHBOARD CUSTOM STYLING === */
  .dashboard-container {
    padding: 24px 28px;
  }

  /* Header Greeting */
  .dashboard-header-title {
    font-size: 1.6rem;
    font-weight: 800;
    color: #0f172a;
    letter-spacing: -0.02em;
    margin-bottom: 2px;
  }

  .dashboard-header-subtitle {
    font-size: 0.88rem;
    color: #64748b;
    margin-bottom: 0;
  }

  .btn-outline-custom {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    color: #334155;
    font-weight: 600;
    font-size: 0.84rem;
    padding: 8px 18px;
    border-radius: 10px;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
  }

  .btn-outline-custom:hover {
    background: #f8fafc;
    border-color: #cbd5e1;
    color: #0f172a;
  }

  .btn-primary-custom {
    background: #1d72fe;
    border: none;
    color: #ffffff !important;
    font-weight: 600;
    font-size: 0.84rem;
    padding: 8px 20px;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(29, 114, 254, 0.28);
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    text-decoration: none !important;
  }

  .btn-primary-custom:hover {
    background: #155ecc;
    transform: translateY(-1px);
    box-shadow: 0 6px 16px rgba(29, 114, 254, 0.35);
  }

  /* Metric Stat Cards */
  .stat-card {
    background: #ffffff;
    border-radius: 16px;
    border: 1px solid #edf2f7;
    padding: 18px 20px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
  }

  .stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
  }

  .stat-card-header {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 12px;
  }

  .stat-icon-wrapper {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    flex-shrink: 0;
  }

  .stat-icon-blue { background: #eff6ff; color: #2563eb; }
  .stat-icon-green { background: #f0fdf4; color: #16a34a; }
  .stat-icon-amber { background: #fffbeb; color: #d97706; }
  .stat-icon-red { background: #fef2f2; color: #dc2626; }
  .stat-icon-purple { background: #faf5ff; color: #9333ea; }

  .stat-title {
    font-size: 0.84rem;
    font-weight: 600;
    color: #64748b;
    white-space: nowrap;
  }

  .stat-value {
    font-size: 1.85rem;
    font-weight: 800;
    color: #0f172a;
    line-height: 1;
    letter-spacing: -0.02em;
    margin-bottom: 6px;
  }

  .stat-subtext {
    font-size: 0.74rem;
    color: #94a3b8;
    font-weight: 500;
    margin-bottom: 0;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }

  /* Content Cards */
  .modern-card {
    background: #ffffff;
    border-radius: 16px;
    border: 1px solid #edf2f7;
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
    font-size: 1.05rem;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 2px;
  }

  .modern-card-subtitle {
    font-size: 0.78rem;
    color: #94a3b8;
    margin-bottom: 0;
  }

  .btn-soft-primary {
    background: #eff6ff;
    color: #1d72fe;
    border: none;
    font-weight: 600;
    font-size: 0.78rem;
    padding: 6px 14px;
    border-radius: 20px;
    transition: all 0.2s ease;
  }

  .btn-soft-primary:hover {
    background: #dbeafe;
    color: #155ecc;
  }

  /* Table styling */
  .table-dashboard {
    width: 100%;
    margin-bottom: 0;
  }

  .table-dashboard th {
    font-size: 0.72rem;
    text-transform: uppercase;
    color: #94a3b8;
    font-weight: 700;
    letter-spacing: 0.05em;
    padding: 12px 22px;
    border-bottom: 1px solid #f1f5f9;
    background: #ffffff;
  }

  .table-dashboard td {
    padding: 13px 22px;
    vertical-align: middle;
    border-bottom: 1px solid #f8fafc;
    font-size: 0.85rem;
    color: #334155;
  }

  .table-dashboard tr:last-child td {
    border-bottom: none;
  }

  .table-dashboard tr:hover td {
    background-color: #fafbfd;
  }

  .badge-status-pending {
    background: #fffbeb;
    color: #b45309;
    font-weight: 600;
    font-size: 0.74rem;
    padding: 5px 12px;
    border-radius: 20px;
    display: inline-block;
  }

  /* Badge Status SP Radar Siswa */
  .sp-danger {
    background: #fef2f2;
    color: #dc2626;
    border: 1px solid #fee2e2;
    font-weight: 600;
    font-size: 0.74rem;
    padding: 4px 11px;
    border-radius: 20px;
    display: inline-block;
    white-space: nowrap;
  }

  .sp-warning {
    background: #fff7ed;
    color: #ea580c;
    border: 1px solid #ffedd5;
    font-weight: 600;
    font-size: 0.74rem;
    padding: 4px 11px;
    border-radius: 20px;
    display: inline-block;
    white-space: nowrap;
  }

  .sp-amber {
    background: #fefce8;
    color: #ca8a04;
    border: 1px solid #fef08a;
    font-weight: 600;
    font-size: 0.74rem;
    padding: 4px 11px;
    border-radius: 20px;
    display: inline-block;
    white-space: nowrap;
  }

  .sp-info {
    background: #f0f9ff;
    color: #0284c7;
    border: 1px solid #e0f2fe;
    font-weight: 600;
    font-size: 0.74rem;
    padding: 4px 11px;
    border-radius: 20px;
    display: inline-block;
    white-space: nowrap;
  }

  .btn-link-action {
    color: #1d72fe;
    font-weight: 600;
    font-size: 0.82rem;
    text-decoration: none !important;
    cursor: pointer;
  }

  .btn-link-action:hover {
    color: #155ecc;
    text-decoration: underline !important;
  }

  .avatar-initial {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 0.88rem;
    flex-shrink: 0;
  }

  .avatar-red { background: #fee2e2; color: #dc2626; }
  .avatar-amber { background: #fef3c7; color: #d97706; }
  .avatar-yellow { background: #fef9c3; color: #ca8a04; }
  .avatar-blue { background: #e0f2fe; color: #0284c7; }
  .avatar-purple { background: #f3e8ff; color: #9333ea; }
  .avatar-green { background: #dcfce7; color: #15803d; }

  /* Guru Piket List */
  .piket-item {
    padding: 13px 22px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    border-bottom: 1px solid #f8fafc;
  }

  .piket-item:last-child {
    border-bottom: none;
  }

  .piket-guru-info {
    min-width: 0;
    flex: 1;
    overflow: hidden;
  }

  .piket-guru-name {
    font-weight: 700;
    color: #0f172a;
    font-size: 0.84rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }

  .piket-guru-role {
    color: #94a3b8;
    font-size: 0.72rem;
    margin-top: 1px;
  }

  .time-badge {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    color: #475569;
    font-size: 0.75rem;
    font-weight: 600;
    padding: 4px 10px;
    border-radius: 20px;
    white-space: nowrap !important;
    flex-shrink: 0 !important;
    display: inline-block;
  }

  /* Early Birds Tab */
  .early-birds-tab-btn {
    background: transparent;
    border: none;
    font-size: 0.78rem;
    font-weight: 600;
    color: #64748b;
    padding: 4px 12px;
    border-radius: 20px;
    cursor: pointer;
    transition: all 0.2s ease;
  }

  .early-birds-tab-btn.active {
    background: #1d72fe;
    color: #ffffff;
  }

  .rank-circle {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    background: #dcfce7;
    color: #15803d;
    font-size: 0.75rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
  }

  .time-green-pill {
    background: #dcfce7;
    color: #15803d;
    font-size: 0.75rem;
    font-weight: 700;
    padding: 3px 10px;
    border-radius: 20px;
    white-space: nowrap !important;
    flex-shrink: 0 !important;
  }

  /* Donut Legend */
  .donut-legend-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 10px;
    font-size: 0.82rem;
  }

  .donut-legend-color {
    width: 10px;
    height: 10px;
    border-radius: 3px;
    display: inline-block;
    margin-right: 8px;
  }

  /* Responsive Adjustments for Dashboard */
  @media (max-width: 575.98px) {
    .dashboard-header-title {
      font-size: 1.25rem !important;
    }
    .dashboard-header-subtitle {
      font-size: 0.76rem !important;
    }
    .btn-outline-custom,
    .btn-primary-custom {
      width: 100% !important;
      justify-content: center !important;
      margin-bottom: 8px !important;
      margin-right: 0 !important;
    }
    .early-birds-tab {
      width: 100% !important;
      justify-content: space-between !important;
      margin-top: 8px !important;
    }
    .modern-card-header {
      flex-direction: column !important;
      align-items: flex-start !important;
      gap: 10px !important;
    }
    .modern-card-header > div:last-child {
      width: 100% !important;
      display: flex !important;
      justify-content: flex-end !important;
    }
  }
</style>

@php
  $timeIcon = $greetingIcon ?? 'fas fa-sun';
  $timeColor = $greetingIconColor ?? '#f59e0b';
  $timeBg = $greetingIconBg ?? '#fffbeb';
  $timeBorder = $greetingIconBorder ?? '#fef3c7';
@endphp

<div class="dashboard-container">
  <!-- Top Greeting & Action Header -->
  <div class="row align-items-center mb-4">
    <div class="col-md-7 col-12 mb-3 mb-md-0 d-flex align-items-center">
      <div class="greeting-time-icon mr-3" style="width: 50px; height: 50px; border-radius: 14px; background: {{ $timeBg }}; border: 1px solid {{ $timeBorder }}; display: flex; align-items: center; justify-content: center; font-size: 1.6rem; color: {{ $timeColor }}; box-shadow: 0 4px 12px rgba(0,0,0,0.03); flex-shrink: 0;">
        <i class="{{ $timeIcon }}"></i>
      </div>
      <div class="d-flex flex-column justify-content-center">
        <h1 class="dashboard-header-title mb-0" style="line-height: 1.2;">{{ $greetingText }}, {{ explode(' ', $user->nama_guru)[0] }}</h1>
        <p class="dashboard-header-subtitle mt-1 mb-0" style="line-height: 1.2;">Ringkasan kehadiran dan kegiatan sekolah hari ini.</p>
      </div>
    </div>
    <div class="col-md-5 col-12 text-md-right">
      <button type="button" class="btn-outline-custom mr-2" data-toggle="modal" data-target="#modalExportReport">
        <i class="fas fa-file-export"></i> Ekspor laporan
      </button>
      <a href="/admin/absensi" class="btn-primary-custom">
        <i class="fas fa-clipboard-check"></i> Input absensi
      </a>
    </div>
  </div>

  <!-- Row 1: 5 Metric Stat Cards -->
  <div class="row mb-4">
    <!-- 1. Total Siswa -->
    <div class="col-xl col-lg-4 col-md-6 col-12 mb-3 mb-xl-0">
      <div class="stat-card">
        <div class="stat-card-header">
          <div class="stat-icon-wrapper stat-icon-blue">
            <i class="fas fa-user-friends"></i>
          </div>
          <span class="stat-title">Total siswa</span>
        </div>
        <div>
          <div class="stat-value">{{ $totalSiswa }}</div>
          <p class="stat-subtext" title="Aktif T.A. 2026/27">Aktif T.A. 2026/27</p>
        </div>
      </div>
    </div>

    <!-- 2. Siswa Hadir -->
    <div class="col-xl col-lg-4 col-md-6 col-12 mb-3 mb-xl-0">
      <div class="stat-card">
        <div class="stat-card-header">
          <div class="stat-icon-wrapper stat-icon-green">
            <i class="fas fa-check-circle"></i>
          </div>
          <span class="stat-title">Siswa hadir</span>
        </div>
        <div>
          <div class="stat-value">{{ $totalHadir }}</div>
          <p class="stat-subtext" title="{{ $persenHadirFormatted }}% dari {{ $totalSiswa }} siswa">{{ $persenHadirFormatted }}% dari {{ $totalSiswa }} siswa</p>
        </div>
      </div>
    </div>

    <!-- 3. Terlambat -->
    <div class="col-xl col-lg-4 col-md-6 col-12 mb-3 mb-xl-0">
      <div class="stat-card">
        <div class="stat-card-header">
          <div class="stat-icon-wrapper stat-icon-amber">
            <i class="fas fa-clock"></i>
          </div>
          <span class="stat-title">Terlambat</span>
        </div>
        <div>
          <div class="stat-value">{{ $totalTerlambat }}</div>
          <p class="stat-subtext" title="{{ $totalTerlambat > 0 ? $totalTerlambat . ' siswa terlambat' : 'Belum ada hari ini' }}">
            {{ $totalTerlambat > 0 ? $totalTerlambat . ' siswa terlambat' : 'Belum ada hari ini' }}
          </p>
        </div>
      </div>
    </div>

    <!-- 4. Tidak Hadir -->
    <div class="col-xl col-lg-4 col-md-6 col-12 mb-3 mb-xl-0">
      <div class="stat-card">
        <div class="stat-card-header">
          <div class="stat-icon-wrapper stat-icon-red">
            <i class="fas fa-times-circle"></i>
          </div>
          <span class="stat-title">Tidak hadir</span>
        </div>
        <div>
          <div class="stat-value">{{ $jumlahTidakHadirAll }}</div>
          <p class="stat-subtext" title="Sakit, izin, atau alpa">Sakit, izin, atau alpa</p>
        </div>
      </div>
    </div>

    <!-- 5. Guru Hadir -->
    <div class="col-xl col-lg-4 col-md-6 col-12 mb-3 mb-xl-0">
      <div class="stat-card">
        <div class="stat-card-header">
          <div class="stat-icon-wrapper stat-icon-purple">
            <i class="fas fa-chalkboard-teacher"></i>
          </div>
          <span class="stat-title">Guru hadir</span>
        </div>
        <div>
          <div class="stat-value">{{ $totalGuruHadir }}/{{ $totalGuru }}</div>
          <p class="stat-subtext" title="{{ $guruBelumHadirCount }} guru belum hadir">{{ $guruBelumHadirCount }} guru belum hadir</p>
        </div>
      </div>
    </div>
  </div>

  <!-- Row 2: Main Grid Layout (Left 65% / Right 35%) -->
  <div class="row">
    <!-- LEFT COLUMN (~65%) -->
    <div class="col-lg-8 col-12">
      
      <!-- Card 1: Kelas yang belum absen -->
      <div class="modern-card">
        <div class="modern-card-header">
          <div>
            <div class="modern-card-title">Kelas yang belum absen</div>
            <div class="modern-card-subtitle">{{ $kelasBelumAbsenCount }} kelas perlu dicek hari ini</div>
          </div>
          <button type="button" class="btn-soft-primary" onclick="ingatkanSemuaKelas()">
            <i class="fas fa-bell mr-1"></i> Ingatkan semua
          </button>
        </div>
        <div class="table-responsive">
          <table class="table-dashboard">
            <thead>
              <tr>
                <th>Kelas</th>
                <th>Jumlah siswa</th>
                <th>Status</th>
                <th class="text-right">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($kelasData as $item)
                @php
                  $namaKls = $item['kelas']->nama_kelas;
                @endphp
                <tr>
                  <td>
                    <span class="font-weight-bold text-dark" style="font-size: 0.88rem;">{{ $namaKls }}</span>
                  </td>
                  <td>
                    <span class="text-secondary font-weight-500">{{ $item['totalSiswaKelas'] }} siswa</span>
                  </td>
                  <td>
                    <span class="badge-status-pending">Belum diabsen</span>
                  </td>
                  <td class="text-right">
                    <a href="javascript:void(0)" class="btn-link-action" data-toggle="modal" data-target="#modalSiswa{{ $item['kelas']->id_kelas }}">
                      Lihat detail
                    </a>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="4" class="text-center py-4 text-muted">
                    <i class="fas fa-check-circle text-success mr-2"></i> Semua kelas telah menyelesaikan absensi hari ini!
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

      <!-- Card 2: Radar Siswa Kritis -->
      <div class="modern-card">
        <div class="modern-card-header">
          <div>
            <div class="modern-card-title">Radar siswa kritis</div>
            <div class="modern-card-subtitle">Pelanggaran tertinggi</div>
          </div>
          <a href="/admin/pointSiswa" class="btn-link-action" style="font-size: 0.78rem;">
            Lihat semua <i class="fas fa-chevron-right ml-1" style="font-size: 0.7rem;"></i>
          </a>
        </div>
        <div class="table-responsive">
          <table class="table-dashboard">
            <thead>
              <tr>
                <th>Siswa</th>
                <th>Poin Pelanggaran</th>
                <th>Status SP</th>
                <th class="text-right">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @php
                $avatarColors = ['avatar-red', 'avatar-amber', 'avatar-yellow', 'avatar-blue', 'avatar-purple'];
              @endphp
              @forelse($siswaKritisFormatted as $idx => $kritis)
                @php
                  $initial = strtoupper(substr($kritis->siswa->nama_siswa ?? 'S', 0, 1));
                  $colorClass = $avatarColors[$idx % count($avatarColors)];
                  $namaSiswa = $kritis->siswa->nama_siswa ?? 'Siswa';
                  $namaKelas = $kritis->siswa->kelas->nama_kelas ?? '-';
                @endphp
                <tr>
                  <td>
                    <div class="d-flex align-items-center" style="gap: 12px;">
                      <div class="avatar-initial {{ $colorClass }}">
                        {{ $initial }}
                      </div>
                      <div style="min-width: 0;">
                        <a href="{{ url('/admin/pointSiswa/reviewPointSiswa/' . $kritis->id_siswa) }}" class="font-weight-bold text-dark text-truncate d-block text-decoration-none" style="font-size: 0.85rem; max-width: 200px;" title="{{ $namaSiswa }}">{{ $namaSiswa }}</a>
                        <div class="text-muted font-weight-500" style="font-size: 0.74rem;">{{ $namaKelas }}</div>
                      </div>
                    </div>
                  </td>
                  <td>
                    <span class="font-weight-bold text-dark" style="font-size: 0.88rem;">{{ $kritis->total_skor }}</span>
                    <span class="text-secondary font-weight-500" style="font-size: 0.78rem;">poin</span>
                  </td>
                  <td>
                    <span class="{{ $kritis->badge_class }}">
                      {{ $kritis->status_sp }}
                    </span>
                  </td>
                  <td class="text-right">
                    <a href="{{ url('/admin/pointSiswa/reviewPointSiswa/' . $kritis->id_siswa) }}" class="btn-link-action">
                      Lihat detail
                    </a>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="4" class="text-center py-4 text-muted">
                    <i class="fas fa-shield-alt text-success mr-2"></i> Tidak ada siswa dengan poin pelanggaran kritis.
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

    </div>

    <!-- RIGHT COLUMN (~35%) -->
    <div class="col-lg-4 col-12">
      
      <!-- Card 1: Guru piket hari ini -->
      <div class="modern-card">
        <div class="modern-card-header">
          <div>
            <div class="modern-card-title">Guru piket hari ini</div>
          </div>
          <a href="/admin/guruPiket" class="btn-link-action" style="font-size: 0.78rem;">
            Jadwal <i class="fas fa-chevron-right ml-1" style="font-size: 0.7rem;"></i>
          </a>
        </div>
        <div class="p-0">
          @forelse($guruPiketHariIni as $piket)
            @php
              $guruName = $piket->guru->nama_guru ?? 'Guru Piket';
              $initialPiket = strtoupper(substr($guruName, 0, 1));
            @endphp
            <div class="piket-item">
              <div class="d-flex align-items-center" style="gap: 12px; min-width: 0; flex: 1;">
                <div class="avatar-initial avatar-blue" style="width: 34px; height: 34px; font-size: 0.82rem;">
                  {{ $initialPiket }}
                </div>
                <div class="piket-guru-info">
                  <div class="piket-guru-name" title="{{ $guruName }}">{{ $guruName }}</div>
                  <div class="piket-guru-role">Guru piket</div>
                </div>
              </div>
              <div>
                <span class="time-badge">{{ $piket->jam_tugas ?? '06.30 - 10.00' }}</span>
              </div>
            </div>
          @empty
            <div class="text-center py-4 text-muted" style="font-size: 0.82rem;">
              <i class="far fa-calendar-times mr-1"></i> Tidak ada jadwal guru piket hari ini
            </div>
          @endforelse
        </div>
      </div>

      <!-- Card 2: Kehadiran siswa (Donut Chart) -->
      <div class="modern-card">
        <div class="modern-card-header">
          <div class="modern-card-title">Kehadiran siswa</div>
        </div>
        <div class="p-4">
          <div class="row align-items-center">
            <div class="col-6 position-relative text-center">
              <div style="width: 120px; height: 120px; margin: 0 auto; position: relative;">
                <canvas id="kehadiranDonutChart" width="120" height="120"></canvas>
                <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center; pointer-events: none;">
                  <div class="font-weight-bold text-dark" style="font-size: 1.05rem; line-height: 1;">{{ $persenHadirFormatted }}%</div>
                </div>
              </div>
            </div>
            <div class="col-6 pl-0">
              <div class="donut-legend-item">
                <span class="text-muted"><span class="donut-legend-color" style="background: #10b981;"></span> Hadir</span>
                <span class="font-weight-bold text-dark">{{ $totalHadir }}</span>
              </div>
              <div class="donut-legend-item">
                <span class="text-muted"><span class="donut-legend-color" style="background: #ef4444;"></span> Tidak hadir</span>
                <span class="font-weight-bold text-dark">{{ $jumlahTidakHadirAll }}</span>
              </div>
              <div class="donut-legend-item">
                <span class="text-muted"><span class="donut-legend-color" style="background: #f59e0b;"></span> Terlambat</span>
                <span class="font-weight-bold text-dark">{{ $totalTerlambat }}</span>
              </div>
              <div class="donut-legend-item mb-0">
                <span class="text-muted"><span class="donut-legend-color" style="background: #94a3b8;"></span> Belum absen</span>
                <span class="font-weight-bold text-dark">{{ $totalBelumAbsen }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Card 3: Datang paling awal -->
      <div class="modern-card">
        <div class="modern-card-header">
          <div class="modern-card-title">Datang paling awal</div>
          <div class="d-flex" style="background: #f1f5f9; border-radius: 20px; padding: 2px;">
            <button type="button" class="early-birds-tab-btn active" id="tabBtnSiswa" onclick="switchEarlyTab('siswa')">Siswa</button>
            <button type="button" class="early-birds-tab-btn" id="tabBtnGuru" onclick="switchEarlyTab('guru')">Guru</button>
          </div>
        </div>
        <div class="p-0">
          <!-- Early Siswa List -->
          <div id="earlyListSiswa">
            @forelse($siswaTerajin as $idx => $early)
              <div class="piket-item">
                <div class="d-flex align-items-center" style="gap: 12px; min-width: 0; flex: 1;">
                  <div class="rank-circle">{{ $idx + 1 }}</div>
                  <div class="piket-guru-info">
                    <div class="piket-guru-name" title="{{ $early->siswa->nama_siswa ?? 'Siswa' }}">{{ $early->siswa->nama_siswa ?? 'Siswa' }}</div>
                    <div class="piket-guru-role">{{ $early->kelas->nama_kelas ?? '-' }}</div>
                  </div>
                </div>
                <div>
                  <span class="time-green-pill">{{ substr($early->jam_masuk, 0, 5) }}</span>
                </div>
              </div>
            @empty
              <div class="text-center py-4 text-muted" style="font-size: 0.82rem;">
                Belum ada data kehadiran siswa hari ini
              </div>
            @endforelse
          </div>

          <!-- Early Guru List (Hidden by default) -->
          <div id="earlyListGuru" style="display: none;">
            @forelse($guruTerajin as $idx => $earlyG)
              <div class="piket-item">
                <div class="d-flex align-items-center" style="gap: 12px; min-width: 0; flex: 1;">
                  <div class="rank-circle">{{ $idx + 1 }}</div>
                  <div class="piket-guru-info">
                    <div class="piket-guru-name" title="{{ $earlyG->guru->nama_guru ?? 'Guru' }}">{{ $earlyG->guru->nama_guru ?? 'Guru' }}</div>
                    <div class="piket-guru-role">Guru Pengajar</div>
                  </div>
                </div>
                <div>
                  <span class="time-green-pill">{{ substr($earlyG->jam_masuk, 0, 5) }}</span>
                </div>
              </div>
            @empty
              <div class="text-center py-4 text-muted" style="font-size: 0.82rem;">
                Belum ada data kehadiran guru hari ini
              </div>
            @endforelse
          </div>
        </div>
      </div>

    </div>
  </div>
</div>

<!-- MODALS: INPUT PRESENSI SISWA PER KELAS (UNTUK WALI KELAS & GURU PIKET) -->
@foreach ($kelasData as $data)
<div class="modal fade" id="modalSiswa{{ $data['kelas']->id_kelas }}" tabindex="-1" role="dialog" aria-labelledby="modalLabel{{ $data['kelas']->id_kelas }}" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content" style="border-radius: 16px; border: none; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.15);">
      <div class="modal-header border-0 p-4 pb-2" style="background: #ffffff;">
        <div>
          <h5 class="modal-title font-weight-bold text-dark" id="modalLabel{{ $data['kelas']->id_kelas }}">
            Input Presensi Siswa — {{ $data['kelas']->nama_kelas }}
          </h5>
          <p class="text-muted mb-0" style="font-size: 0.82rem;">
            Terdapat <strong class="text-danger">{{ $data['jumlahBelumAbsen'] }} siswa</strong> dari total {{ $data['totalSiswaKelas'] }} siswa yang belum absen.
          </p>
        </div>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <form action="{{ route('admin.absensi.simpan') }}" method="POST">
        @csrf
        <div class="modal-body p-4 pt-2">
          <!-- Quick Set Presence Buttons -->
          <div class="d-flex justify-content-between align-items-center mb-3 p-2" style="background: #f8fafc; border-radius: 10px;">
            <span class="text-muted font-weight-600" style="font-size: 0.78rem;">Pilih status cepat untuk semua:</span>
            <div style="gap: 6px;" class="d-flex">
              <button type="button" class="btn btn-xs btn-outline-success" style="border-radius: 6px; font-size: 0.75rem; font-weight: 600;" onclick="setAllKehadiran('{{ $data['kelas']->id_kelas }}', 'hadir')">
                <i class="fas fa-check-circle mr-1"></i> Semua Hadir
              </button>
              <button type="button" class="btn btn-xs btn-outline-danger" style="border-radius: 6px; font-size: 0.75rem; font-weight: 600;" onclick="setAllKehadiran('{{ $data['kelas']->id_kelas }}', 'alfa')">
                <i class="fas fa-times-circle mr-1"></i> Semua Alfa
              </button>
            </div>
          </div>

          <!-- Student List with Dropdown -->
          <div style="max-height: 380px; overflow-y: auto; padding-right: 4px;">
            <ul class="list-group list-group-flush">
              @foreach ($data['siswaBelumAbsen'] as $idx => $siswa)
                @php
                  $initialS = strtoupper(substr($siswa->nama_siswa ?? 'S', 0, 1));
                  $avatarColorList = ['avatar-blue', 'avatar-purple', 'avatar-green', 'avatar-amber'];
                  $colorS = $avatarColorList[$idx % count($avatarColorList)];
                @endphp
                <li class="list-group-item d-flex justify-content-between align-items-center py-2 px-2" style="border-bottom: 1px solid #f1f5f9;">
                  <div class="d-flex align-items-center" style="gap: 12px; min-width: 0; flex: 1;">
                    <div class="avatar-initial {{ $colorS }}" style="width: 32px; height: 32px; font-size: 0.8rem;">
                      {{ $initialS }}
                    </div>
                    <div style="min-width: 0; flex: 1;">
                      <div class="font-weight-bold text-dark text-truncate" style="font-size: 0.86rem;" title="{{ $siswa->nama_siswa }}">
                        {{ $siswa->nama_siswa }}
                      </div>
                      <div class="text-muted" style="font-size: 0.72rem;">NISN: {{ $siswa->nisn ?? '-' }}</div>
                    </div>
                  </div>

                  <input type="hidden" name="id_siswa[]" value="{{ $siswa->id_siswa }}">
                  <input type="hidden" name="id_kelas[]" value="{{ $data['kelas']->id_kelas }}">
                  <input type="hidden" name="id_jurusan[]" value="{{ $data['id_jurusan'] }}">

                  <div style="width: 120px; flex-shrink: 0;" class="ml-2">
                    <select name="kehadiran[]" class="form-control form-control-sm select-kehadiran-{{ $data['kelas']->id_kelas }}" style="border-radius: 8px; font-weight: 600; font-size: 0.82rem;">
                      <option value="hadir">Hadir</option>
                      <option value="sakit">Sakit</option>
                      <option value="izin">Izin</option>
                      <option value="alfa" selected>Alfa</option>
                    </select>
                  </div>
                </li>
              @endforeach
            </ul>
          </div>
        </div>

        <div class="modal-footer border-0 p-4 pt-2 d-flex justify-content-between" style="background: #ffffff;">
          <button type="button" class="btn btn-outline-success" style="border-radius: 10px; font-weight: 600; font-size: 0.84rem;" onclick="kirimWaWaliKelas('{{ $data['kelas']->nama_kelas }}', '{{ $data['jumlahBelumAbsen'] }}')">
            <i class="fab fa-whatsapp mr-1"></i> Ingatkan Wali Kelas
          </button>
          <div style="gap: 8px;" class="d-flex">
            <button type="button" class="btn btn-light" data-dismiss="modal" style="border-radius: 10px; font-weight: 600; font-size: 0.84rem;">Tutup</button>
            <button type="submit" class="btn-primary-custom" style="font-size: 0.84rem;">
              <i class="fas fa-save mr-1"></i> Simpan Kehadiran
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
@endforeach

<!-- MODAL: EKSPOR LAPORAN -->
<div class="modal fade" id="modalExportReport" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content" style="border-radius: 16px; border: none; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.15);">
      <div class="modal-header border-0 p-4 pb-2">
        <div>
          <h5 class="modal-title font-weight-bold text-dark">Ekspor Laporan SIAWI</h5>
          <p class="text-muted mb-0" style="font-size: 0.82rem;">Pilih jenis laporan presensi yang ingin diunduh</p>
        </div>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body p-4 pt-2">
        <div class="list-group list-group-flush">
          <a href="/admin/rekapAbsen" class="list-group-item list-group-item-action d-flex align-items-center justify-content-between p-3" style="border-radius: 10px; margin-bottom: 8px; border: 1px solid #f1f5f9;">
            <div class="d-flex align-items-center" style="gap: 12px;">
              <div class="stat-icon-wrapper stat-icon-blue" style="width: 34px; height: 34px;">
                <i class="fas fa-file-excel"></i>
              </div>
              <div>
                <div class="font-weight-bold text-dark" style="font-size: 0.85rem;">Rekap Absensi Siswa Harian</div>
                <div class="text-muted" style="font-size: 0.74rem;">Format Excel / Tabel Kelas</div>
              </div>
            </div>
            <i class="fas fa-download text-muted"></i>
          </a>

          <a href="/admin/laporan-bulanan-wa" class="list-group-item list-group-item-action d-flex align-items-center justify-content-between p-3" style="border-radius: 10px; margin-bottom: 8px; border: 1px solid #f1f5f9;">
            <div class="d-flex align-items-center" style="gap: 12px;">
              <div class="stat-icon-wrapper stat-icon-green" style="width: 34px; height: 34px;">
                <i class="fas fa-calendar-alt"></i>
              </div>
              <div>
                <div class="font-weight-bold text-dark" style="font-size: 0.85rem;">Laporan Absensi Bulanan & WA</div>
                <div class="text-muted" style="font-size: 0.74rem;">Rekapitulasi periodik lengkap</div>
              </div>
            </div>
            <i class="fas fa-download text-muted"></i>
          </a>

          <a href="/admin/rekapAbsenGuru" class="list-group-item list-group-item-action d-flex align-items-center justify-content-between p-3" style="border-radius: 10px; border: 1px solid #f1f5f9;">
            <div class="d-flex align-items-center" style="gap: 12px;">
              <div class="stat-icon-wrapper stat-icon-purple" style="width: 34px; height: 34px;">
                <i class="fas fa-user-check"></i>
              </div>
              <div>
                <div class="font-weight-bold text-dark" style="font-size: 0.85rem;">Rekap Kehadiran Guru</div>
                <div class="text-muted" style="font-size: 0.74rem;">Data presensi dan ketepatan waktu</div>
              </div>
            </div>
            <i class="fas fa-download text-muted"></i>
          </a>
        </div>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
  // Tab Switcher Datang Paling Awal (Siswa vs Guru)
  function switchEarlyTab(type) {
    if (type === 'siswa') {
      $('#tabBtnSiswa').addClass('active');
      $('#tabBtnGuru').removeClass('active');
      $('#earlyListSiswa').show();
      $('#earlyListGuru').hide();
    } else {
      $('#tabBtnGuru').addClass('active');
      $('#tabBtnSiswa').removeClass('active');
      $('#earlyListGuru').show();
      $('#earlyListSiswa').hide();
    }
  }

  // Set Semua Kehadiran Cepat (Hadir/Alfa)
  function setAllKehadiran(idKelas, status) {
    $('.select-kehadiran-' + idKelas).val(status);
  }

  // Kirim WhatsApp Pengingat Wali Kelas
  function kirimWaWaliKelas(namaKelas, jumlah) {
    Swal.fire({
      icon: 'success',
      title: 'Pengingat Diproses!',
      text: 'Pemberitahuan presensi untuk Wali Kelas ' + namaKelas + ' (' + jumlah + ' siswa) telah disiapkan.',
      confirmButtonColor: '#1d72fe'
    });
  }

  // Ingatkan Semua Kelas Action
  function ingatkanSemuaKelas() {
    Swal.fire({
      title: 'Ingatkan Semua Kelas?',
      text: 'Pemberitahuan pengingat absensi akan dikirimkan ke seluruh wali kelas yang belum menyelesaikan absensi.',
      icon: 'question',
      showCancelButton: true,
      confirmButtonColor: '#1d72fe',
      cancelButtonColor: '#94a3b8',
      confirmButtonText: 'Ya, Kirim Pengingat',
      cancelButtonText: 'Batal'
    }).then((result) => {
      if (result.isConfirmed) {
        Swal.fire({
          icon: 'success',
          title: 'Pengingat Terkirim!',
          text: 'Pesan pengingat presensi berhasil dikirimkan ke wali kelas terkait.',
          confirmButtonColor: '#1d72fe'
        });
      }
    });
  }

  // Render Donut Chart Kehadiran Siswa
  $(document).ready(function() {
    var ctx = document.getElementById('kehadiranDonutChart');
    if (ctx) {
      var totalHadir = {{ $totalHadir ?? 0 }};
      var tidakHadir = {{ $jumlahTidakHadirAll ?? 0 }};
      var terlambat = {{ $totalTerlambat ?? 0 }};
      var belumAbsen = {{ $totalBelumAbsen ?? 0 }};

      if (totalHadir === 0 && tidakHadir === 0 && terlambat === 0 && belumAbsen === 0) {
        belumAbsen = 1;
      }

      new Chart(ctx.getContext('2d'), {
        type: 'doughnut',
        data: {
          labels: ['Hadir', 'Tidak Hadir', 'Terlambat', 'Belum Absen'],
          datasets: [{
            data: [totalHadir, tidakHadir, terlambat, belumAbsen],
            backgroundColor: [
              '#10b981', // Hadir (Green)
              '#ef4444', // Tidak Hadir (Red)
              '#f59e0b', // Terlambat (Amber)
              '#e2e8f0'  // Belum Absen (Gray)
            ],
            borderWidth: 0,
            hoverOffset: 4
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          cutoutPercentage: 75,
          legend: {
            display: false
          },
          tooltips: {
            callbacks: {
              label: function(tooltipItem, data) {
                var label = data.labels[tooltipItem.index] || '';
                var value = data.datasets[0].data[tooltipItem.index] || 0;
                return label + ': ' + value;
              }
            }
          }
        }
      });
    }

    // Global dashboard search filter helper
    $('#global-dashboard-search').on('keyup', function() {
      var value = $(this).val().toLowerCase();
      $('.table-dashboard tbody tr').filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
      });
      $('.student-radar-item').filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
      });
    });
  });
</script>
@endpush
@endsection