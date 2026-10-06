@extends($layout)

@section('content')
<!-- Content Header -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row align-items-center mb-2">
      <div class="col-sm-7 d-flex align-items-center">
        <i class="fas fa-user-times text-primary mr-3" style="font-size: 2rem; flex-shrink: 0;"></i>
        <div class="d-flex flex-column justify-content-center">
          <h1 class="m-0 font-weight-bold text-dark" style="line-height: 1.2;">Data Siswa Tidak Hadir</h1>
          <p class="text-muted mt-1 mb-0" style="line-height: 1.2; font-size: 0.85rem;">Pemantauan dan rekapitulasi siswa yang tidak hadir (Sakit, Izin, Alfa)</p>
        </div>
      </div>
      <div class="col-sm-5">
        <ol class="breadcrumb float-sm-right bg-transparent p-0 mb-0">
          <li class="breadcrumb-item"><a href="/admin/dashboard" class="text-primary font-weight-500">Dashboard</a></li>
          <li class="breadcrumb-item"><a href="/admin/absensi" class="text-primary font-weight-500">Presensi</a></li>
          <li class="breadcrumb-item active">Siswa Tidak Hadir</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<!-- Main content -->
<div class="content">
  <div class="container-fluid">

    <!-- Summary Stat Cards -->
    <div class="row mb-3">
      <!-- Total Tidak Hadir -->
      <div class="col-lg-3 col-6 mb-3 mb-lg-0">
        <div class="card border-0 shadow-sm h-100 bg-white" style="border-radius: 12px; border-left: 4px solid #dc2626 !important;">
          <div class="card-body p-3 d-flex align-items-center justify-content-between">
            <div>
              <div class="text-muted text-uppercase font-weight-bold" style="font-size: 0.72rem; letter-spacing: 0.5px;">Total Tidak Hadir</div>
              <div class="font-weight-bold text-dark mt-1" style="font-size: 1.7rem; line-height: 1.1;">{{ $countTotal }} <span style="font-size: 0.85rem; font-weight: 500; color: #64748b;">Siswa</span></div>
              <small class="text-muted mt-1 d-block"><i class="fas fa-calendar-alt text-danger mr-1"></i> Periode Terpilih</small>
            </div>
            <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 46px; height: 46px; font-size: 1.25rem; background: #fef2f2; color: #dc2626; flex-shrink: 0;">
              <i class="fas fa-user-times"></i>
            </div>
          </div>
        </div>
      </div>

      <!-- Sakit -->
      <div class="col-lg-3 col-6 mb-3 mb-lg-0">
        <div class="card border-0 shadow-sm h-100 bg-white" style="border-radius: 12px; border-left: 4px solid #f59e0b !important;">
          <div class="card-body p-3 d-flex align-items-center justify-content-between">
            <div>
              <div class="text-muted text-uppercase font-weight-bold" style="font-size: 0.72rem; letter-spacing: 0.5px;">Sakit (S)</div>
              <div class="font-weight-bold text-dark mt-1" style="font-size: 1.7rem; line-height: 1.1;">{{ $countSakit }} <span style="font-size: 0.85rem; font-weight: 500; color: #64748b;">Siswa</span></div>
              <small class="text-muted mt-1 d-block"><i class="fas fa-clinic-medical text-warning mr-1"></i> Surat / Info Dokter</small>
            </div>
            <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 46px; height: 46px; font-size: 1.25rem; background: #fffbeb; color: #f59e0b; flex-shrink: 0;">
              <i class="fas fa-procedures"></i>
            </div>
          </div>
        </div>
      </div>

      <!-- Izin -->
      <div class="col-lg-3 col-6 mb-3 mb-lg-0">
        <div class="card border-0 shadow-sm h-100 bg-white" style="border-radius: 12px; border-left: 4px solid #0284c7 !important;">
          <div class="card-body p-3 d-flex align-items-center justify-content-between">
            <div>
              <div class="text-muted text-uppercase font-weight-bold" style="font-size: 0.72rem; letter-spacing: 0.5px;">Izin (I)</div>
              <div class="font-weight-bold text-dark mt-1" style="font-size: 1.7rem; line-height: 1.1;">{{ $countIzin }} <span style="font-size: 0.85rem; font-weight: 500; color: #64748b;">Siswa</span></div>
              <small class="text-muted mt-1 d-block"><i class="fas fa-envelope-open-text text-info mr-1"></i> Izin Orang Tua</small>
            </div>
            <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 46px; height: 46px; font-size: 1.25rem; background: #f0f9ff; color: #0284c7; flex-shrink: 0;">
              <i class="fas fa-file-signature"></i>
            </div>
          </div>
        </div>
      </div>

      <!-- Alfa -->
      <div class="col-lg-3 col-6 mb-3 mb-lg-0">
        <div class="card border-0 shadow-sm h-100 bg-white" style="border-radius: 12px; border-left: 4px solid #b91c1c !important;">
          <div class="card-body p-3 d-flex align-items-center justify-content-between">
            <div>
              <div class="text-muted text-uppercase font-weight-bold" style="font-size: 0.72rem; letter-spacing: 0.5px;">Tanpa Keterangan (A)</div>
              <div class="font-weight-bold text-dark mt-1" style="font-size: 1.7rem; line-height: 1.1;">{{ $countAlfa }} <span style="font-size: 0.85rem; font-weight: 500; color: #64748b;">Siswa</span></div>
              <small class="text-muted mt-1 d-block"><i class="fas fa-exclamation-circle text-danger mr-1"></i> Perlu Tindak Lanjut</small>
            </div>
            <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 46px; height: 46px; font-size: 1.25rem; background: #fee2e2; color: #b91c1c; flex-shrink: 0;">
              <i class="fas fa-user-slash"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Filter Toolbar -->
    <div class="card border-0 shadow-sm mb-3" style="border-radius: 12px;">
      <div class="card-body p-3">
        <form method="GET" action="{{ url('/admin/siswa-tidak-hadir') }}" class="row align-items-end">
          <div class="{{ $user->role == 'wali_kelas' ? 'col-md-4' : 'col-md-3' }} col-sm-6 mb-2 mb-md-0">
            <label class="font-weight-bold text-secondary mb-1" style="font-size: 0.78rem; text-transform: uppercase;">Tanggal Mulai</label>
            <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="form-control form-control-sm" value="{{ $tanggalMulai }}">
          </div>

          <div class="{{ $user->role == 'wali_kelas' ? 'col-md-4' : 'col-md-3' }} col-sm-6 mb-2 mb-md-0">
            <label class="font-weight-bold text-secondary mb-1" style="font-size: 0.78rem; text-transform: uppercase;">Tanggal Akhir</label>
            <input type="date" name="tanggal_akhir" id="tanggal_akhir" class="form-control form-control-sm" value="{{ $tanggalAkhir }}">
          </div>

          @if($user->role != 'wali_kelas')
            <div class="col-md-3 col-sm-6 mb-2 mb-md-0">
              <label class="font-weight-bold text-secondary mb-1" style="font-size: 0.78rem; text-transform: uppercase;">Filter Kelas</label>
              <select name="id_kelas" class="form-control form-control-sm">
                <option value="">-- Semua Kelas --</option>
                @foreach($kelasList as $k)
                  <option value="{{ $k->id_kelas }}" {{ $selectedKelas == $k->id_kelas ? 'selected' : '' }}>
                    {{ $k->nama_kelas }}
                  </option>
                @endforeach
              </select>
            </div>
          @endif

          <div class="{{ $user->role == 'wali_kelas' ? 'col-md-4' : 'col-md-3' }} col-sm-6 mb-2 mb-md-0">
            <label class="font-weight-bold text-secondary mb-1" style="font-size: 0.78rem; text-transform: uppercase;">Status</label>
            <div class="input-group input-group-sm">
              <select name="status" class="form-control form-control-sm">
                <option value="">-- Semua Status --</option>
                <option value="sakit" {{ $selectedStatus == 'sakit' ? 'selected' : '' }}>Sakit</option>
                <option value="izin" {{ $selectedStatus == 'izin' ? 'selected' : '' }}>Izin</option>
                <option value="alfa" {{ $selectedStatus == 'alfa' ? 'selected' : '' }}>Alfa</option>
              </select>
              <div class="input-group-append">
                <button type="submit" class="btn btn-primary btn-sm px-3 shadow-none" title="Terapkan Filter">
                  <i class="fas fa-search"></i>
                </button>
              </div>
            </div>
          </div>
        </form>

        <div class="d-flex align-items-center justify-content-between mt-3 pt-2 border-top">
          <div class="d-flex align-items-center" style="gap: 8px;">
            <a href="{{ url('/admin/siswa-tidak-hadir?today=1') }}" class="btn btn-sm btn-outline-danger {{ $tanggalMulai == Carbon\Carbon::today()->toDateString() && $tanggalAkhir == Carbon\Carbon::today()->toDateString() ? 'active' : '' }}">
              <i class="fas fa-calendar-day mr-1"></i> Hari Ini ({{ Carbon\Carbon::today()->locale('id')->isoFormat('D MMM Y') }})
            </a>
            <a href="{{ url('/admin/siswa-tidak-hadir/export-excel') }}?tanggal_mulai={{ $tanggalMulai }}&tanggal_akhir={{ $tanggalAkhir }}&id_kelas={{ $selectedKelas }}&status={{ $selectedStatus }}" class="btn btn-sm btn-success shadow-sm">
              <i class="fas fa-file-excel mr-1"></i> Download Excel
            </a>
          </div>
          <div>
            @if($tanggalMulai != Carbon\Carbon::today()->toDateString() || $tanggalAkhir != Carbon\Carbon::today()->toDateString() || !empty($selectedKelas) || !empty($selectedStatus))
              <a href="{{ url('/admin/siswa-tidak-hadir?today=1') }}" class="btn btn-link text-muted btn-sm p-0">
                <i class="fas fa-undo mr-1"></i> Reset Filter
              </a>
            @endif
          </div>
        </div>
      </div>
    </div>

    <!-- Data Table Card -->
    <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
      <div class="card-header bg-white py-3 border-0 d-flex align-items-center justify-content-between flex-wrap">
        <div>
          <h3 class="card-title text-dark font-weight-bold mb-0" style="font-size: 1rem;">
            <i class="fas fa-list-ul text-danger mr-2"></i> Daftar Siswa Tidak Hadir
          </h3>
          <span class="badge badge-light border text-secondary ml-2 px-2 py-1" style="font-size: 0.75rem;">
            Periode: {{ Carbon\Carbon::parse($tanggalMulai)->format('d/m/Y') }} - {{ Carbon\Carbon::parse($tanggalAkhir)->format('d/m/Y') }}
          </span>
        </div>
      </div>

      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0" style="font-size: 0.88rem;">
            <thead class="bg-light text-secondary text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">
              <tr>
                <th style="width: 50px; text-align: center;" class="py-3">No</th>
                <th class="py-3">Nama Siswa</th>
                <th style="width: 130px;" class="py-3">Kelas</th>
                <th style="width: 140px;" class="py-3">Tanggal</th>
                <th style="width: 130px; text-align: center;" class="py-3">Status</th>
                <th class="py-3">Keterangan / Alasan</th>
              </tr>
            </thead>
            <tbody>
              @forelse($dataTidakHadir as $item)
                @php
                  $status = strtolower($item->kehadiran);
                  $badgeClass = 'badge-danger';
                  $iconClass = 'fa-times-circle';
                  $label = 'Alfa';

                  if ($status === 'sakit') {
                    $badgeClass = 'badge-warning text-dark';
                    $iconClass = 'fa-procedures';
                    $label = 'Sakit';
                  } elseif ($status === 'izin') {
                    $badgeClass = 'badge-info text-white';
                    $iconClass = 'fa-file-signature';
                    $label = 'Izin';
                  }
                @endphp
                <tr>
                  <td class="text-center font-weight-bold text-muted">{{ $loop->iteration }}</td>
                  <td>
                    <span class="font-weight-bold text-dark">{{ $item->siswa->nama_siswa ?? 'Siswa Tidak Ditemukan' }}</span>
                    @if($item->siswa && ($item->siswa->nisn || $item->siswa->nis))
                      <br><small class="text-muted">NISN/NIS: {{ $item->siswa->nisn ?? $item->siswa->nis }}</small>
                    @endif
                  </td>
                  <td>
                    <span class="badge badge-light border text-dark px-2 py-1" style="font-size: 0.78rem;">
                      {{ $item->kelas->nama_kelas ?? ($item->siswa->kelas->nama_kelas ?? '-') }}
                    </span>
                  </td>
                  <td>
                    <span class="font-weight-500 text-dark">{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</span>
                    <small class="text-muted d-block">{{ \Carbon\Carbon::parse($item->tanggal)->locale('id')->isoFormat('dddd') }}</small>
                  </td>
                  <td class="text-center">
                    <span class="badge {{ $badgeClass }} px-3 py-1 font-weight-bold" style="font-size: 0.78rem; border-radius: 20px;">
                      <i class="fas {{ $iconClass }} mr-1"></i> {{ $label }}
                    </span>
                  </td>
                  <td>
                    @if($item->keterangan && $item->keterangan !== '-')
                      <span class="text-dark">{{ $item->keterangan }}</span>
                    @else
                      <span class="text-muted font-italic small">- Tidak ada catatan tambahan -</span>
                    @endif
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="6" class="text-center py-5 text-muted">
                    <div class="d-flex flex-column align-items-center justify-content-center">
                      <div class="rounded-circle d-flex align-items-center justify-content-center mb-2" style="width: 54px; height: 54px; background: #ecfdf5; color: #059669;">
                        <i class="fas fa-user-check" style="font-size: 1.6rem;"></i>
                      </div>
                      <h6 class="font-weight-bold text-dark mb-1">Semua Siswa Hadir</h6>
                      <p class="small text-muted mb-0">Tidak ditemukan catatan ketidakhadiran (Sakit/Izin/Alfa) pada periode atau filter yang dipilih.</p>
                    </div>
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>

  </div>
</div>
@endsection
