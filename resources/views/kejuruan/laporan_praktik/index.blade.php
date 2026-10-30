@extends($layout)

@section('content')
<!-- Content Header -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row align-items-center mb-2">
      <div class="col-sm-7 d-flex align-items-center">
        <i class="fas fa-file-invoice text-primary mr-3" style="font-size: 2rem; flex-shrink: 0;"></i>
        <div class="d-flex flex-column justify-content-center">
          <h1 class="m-0 font-weight-bold text-dark" style="line-height: 1.2;">Rekap & Laporan Praktik Kejuruan</h1>
          <p class="text-muted mt-1 mb-0" style="line-height: 1.2; font-size: 0.85rem;">Rekapitulasi periodik mingguan, bulanan, tahunan dan monitoring pelaksanaan praktik kejuruan</p>
        </div>
      </div>
      <div class="col-sm-5">
        <ol class="breadcrumb float-sm-right bg-transparent p-0 mb-0">
          <li class="breadcrumb-item"><a href="/admin/dashboard" class="text-primary font-weight-500">Dashboard</a></li>
          <li class="breadcrumb-item"><a href="/admin/kejuruan/laporan-praktik" class="text-primary font-weight-500">Kejuruan</a></li>
          <li class="breadcrumb-item active">Laporan Praktik</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<!-- Main content -->
<div class="content">
  <div class="container-fluid">
    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert" style="border-radius: 12px;">
        <i class="fas fa-check-circle mr-2"></i> {!! session('success') !!}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    @endif

    <!-- STATISTIK RINGKASAN -->
    <div class="row mb-3">
      <!-- Total Pertemuan -->
      <div class="col-lg-3 col-6 mb-3 mb-lg-0">
        <div class="card border-0 shadow-sm h-100 bg-white" style="border-radius: 12px; border-left: 4px solid #0284c7 !important;">
          <div class="card-body p-3 d-flex align-items-center justify-content-between">
            <div>
              <div class="text-muted text-uppercase font-weight-bold" style="font-size: 0.72rem; letter-spacing: 0.5px;">Total Pertemuan</div>
              <div class="font-weight-bold text-dark mt-1" style="font-size: 1.7rem; line-height: 1.1;">{{ $stats['total_pertemuan'] }} <span style="font-size: 0.85rem; font-weight: 500; color: #64748b;">Sesi</span></div>
              <small class="text-muted mt-1 d-block"><i class="fas fa-calendar-alt text-primary mr-1"></i> Periode {{ $periodeLabel }}</small>
            </div>
            <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 46px; height: 46px; font-size: 1.25rem; background: #f0f9ff; color: #0284c7; flex-shrink: 0;">
              <i class="fas fa-clipboard-list"></i>
            </div>
          </div>
        </div>
      </div>

      <!-- Total Siswa Hadir -->
      <div class="col-lg-3 col-6 mb-3 mb-lg-0">
        <div class="card border-0 shadow-sm h-100 bg-white" style="border-radius: 12px; border-left: 4px solid #16a34a !important;">
          <div class="card-body p-3 d-flex align-items-center justify-content-between">
            <div>
              <div class="text-muted text-uppercase font-weight-bold" style="font-size: 0.72rem; letter-spacing: 0.5px;">Akumulasi Hadir</div>
              <div class="font-weight-bold text-dark mt-1" style="font-size: 1.7rem; line-height: 1.1;">{{ $stats['total_siswa_hadir'] }} <span style="font-size: 0.85rem; font-weight: 500; color: #64748b;">Siswa</span></div>
              <small class="text-muted mt-1 d-block"><i class="fas fa-user-check text-success mr-1"></i> Presensi Praktik</small>
            </div>
            <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 46px; height: 46px; font-size: 1.25rem; background: #dcfce7; color: #16a34a; flex-shrink: 0;">
              <i class="fas fa-users"></i>
            </div>
          </div>
        </div>
      </div>

      <!-- Disetujui Kaprog -->
      <div class="col-lg-3 col-6 mb-3 mb-lg-0">
        <div class="card border-0 shadow-sm h-100 bg-white" style="border-radius: 12px; border-left: 4px solid #10b981 !important;">
          <div class="card-body p-3 d-flex align-items-center justify-content-between">
            <div>
              <div class="text-muted text-uppercase font-weight-bold" style="font-size: 0.72rem; letter-spacing: 0.5px;">Disetujui Kaprog</div>
              <div class="font-weight-bold text-dark mt-1" style="font-size: 1.7rem; line-height: 1.1;">{{ $stats['disetujui'] }} <span style="font-size: 0.85rem; font-weight: 500; color: #64748b;">Laporan</span></div>
              <small class="text-muted mt-1 d-block"><i class="fas fa-check-double text-success mr-1"></i> Terverifikasi</small>
            </div>
            <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 46px; height: 46px; font-size: 1.25rem; background: #ecfdf5; color: #10b981; flex-shrink: 0;">
              <i class="fas fa-check-circle"></i>
            </div>
          </div>
        </div>
      </div>

      <!-- Menunggu Review -->
      <div class="col-lg-3 col-6 mb-3 mb-lg-0">
        <div class="card border-0 shadow-sm h-100 bg-white" style="border-radius: 12px; border-left: 4px solid #f59e0b !important;">
          <div class="card-body p-3 d-flex align-items-center justify-content-between">
            <div>
              <div class="text-muted text-uppercase font-weight-bold" style="font-size: 0.72rem; letter-spacing: 0.5px;">Menunggu Review</div>
              <div class="font-weight-bold text-dark mt-1" style="font-size: 1.7rem; line-height: 1.1;">{{ $stats['menunggu'] }} <span style="font-size: 0.85rem; font-weight: 500; color: #64748b;">Laporan</span></div>
              <small class="text-muted mt-1 d-block"><i class="fas fa-hourglass-half text-warning mr-1"></i> Perlu Persetujuan</small>
            </div>
            <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 46px; height: 46px; font-size: 1.25rem; background: #fffbeb; color: #f59e0b; flex-shrink: 0;">
              <i class="fas fa-user-clock"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- FILTER & TAB PERIODE -->
    <div class="card border-0 shadow-sm mb-3" style="border-radius: 12px;">
      <div class="card-header bg-white py-2 px-3 border-bottom d-flex align-items-center justify-content-between flex-wrap" style="gap: 8px;">
        <ul class="nav nav-pills" id="pills-tab" role="tablist">
          <li class="nav-item">
            <a class="nav-link py-1 px-3 {{ $periode === 'mingguan' ? 'active' : '' }}" href="{{ request()->fullUrlWithQuery(['periode' => 'mingguan']) }}" style="border-radius: 6px; font-weight: 500;">
              <i class="fas fa-calendar-week mr-1"></i> Mingguan
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link py-1 px-3 {{ $periode === 'bulanan' ? 'active' : '' }}" href="{{ request()->fullUrlWithQuery(['periode' => 'bulanan']) }}" style="border-radius: 6px; font-weight: 500;">
              <i class="fas fa-calendar-alt mr-1"></i> Bulanan
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link py-1 px-3 {{ $periode === 'tahunan' ? 'active' : '' }}" href="{{ request()->fullUrlWithQuery(['periode' => 'tahunan']) }}" style="border-radius: 6px; font-weight: 500;">
              <i class="fas fa-calendar mr-1"></i> Tahunan
            </a>
          </li>
        </ul>
        <span class="badge badge-light border text-primary font-weight-bold px-2 py-1" style="font-size: 0.82rem;">
          Rentang: {{ $periodeLabel }}
        </span>
      </div>

      <div class="card-body p-3">
        <form method="GET" action="/admin/kejuruan/laporan-praktik" class="row">
          <input type="hidden" name="periode" value="{{ $periode }}">

          @if($periode === 'mingguan')
            <div class="col-md-3 col-sm-6 mb-2">
              <label class="font-weight-bold text-secondary mb-1" style="font-size: 0.78rem; text-transform: uppercase;">Tanggal Awal</label>
              <input type="date" name="tanggal_awal" class="form-control form-control-sm" value="{{ request('tanggal_awal', \Carbon\Carbon::now()->startOfWeek()->toDateString()) }}">
            </div>
            <div class="col-md-3 col-sm-6 mb-2">
              <label class="font-weight-bold text-secondary mb-1" style="font-size: 0.78rem; text-transform: uppercase;">Tanggal Akhir</label>
              <input type="date" name="tanggal_akhir" class="form-control form-control-sm" value="{{ request('tanggal_akhir', \Carbon\Carbon::now()->endOfWeek()->toDateString()) }}">
            </div>
          @elseif($periode === 'bulanan')
            <div class="col-md-3 col-sm-6 mb-2">
              <label class="font-weight-bold text-secondary mb-1" style="font-size: 0.78rem; text-transform: uppercase;">Bulan</label>
              <select name="bulan" class="form-control form-control-sm">
                @for($m = 1; $m <= 12; $m++)
                  <option value="{{ $m }}" {{ request('bulan', \Carbon\Carbon::now()->month) == $m ? 'selected' : '' }}>
                    {{ \Carbon\Carbon::createFromDate(null, $m, 1)->translatedFormat('F') }}
                  </option>
                @endfor
              </select>
            </div>
            <div class="col-md-3 col-sm-6 mb-2">
              <label class="font-weight-bold text-secondary mb-1" style="font-size: 0.78rem; text-transform: uppercase;">Tahun</label>
              <select name="tahun" class="form-control form-control-sm">
                @for($y = \Carbon\Carbon::now()->year; $y >= \Carbon\Carbon::now()->year - 3; $y--)
                  <option value="{{ $y }}" {{ request('tahun', \Carbon\Carbon::now()->year) == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
              </select>
            </div>
          @elseif($periode === 'tahunan')
            <div class="col-md-6 col-sm-6 mb-2">
              <label class="font-weight-bold text-secondary mb-1" style="font-size: 0.78rem; text-transform: uppercase;">Pilih Tahun</label>
              <select name="tahun" class="form-control form-control-sm">
                @for($y = \Carbon\Carbon::now()->year; $y >= \Carbon\Carbon::now()->year - 3; $y--)
                  <option value="{{ $y }}" {{ request('tahun', \Carbon\Carbon::now()->year) == $y ? 'selected' : '' }}>Tahun {{ $y }}</option>
                @endfor
              </select>
            </div>
          @endif

          <div class="col-md-3 col-sm-6 mb-2">
            <label class="font-weight-bold text-secondary mb-1" style="font-size: 0.78rem; text-transform: uppercase;">Guru Pengajar</label>
            <select name="id_guru" class="form-control form-control-sm select2bs4">
              <option value="">-- Semua Guru --</option>
              @foreach($gurus as $g)
                <option value="{{ $g->id_guru }}" {{ request('id_guru') == $g->id_guru ? 'selected' : '' }}>
                  {{ $g->nama_guru }}
                </option>
              @endforeach
            </select>
          </div>

          <div class="col-md-3 col-sm-6 mb-2">
            <label class="font-weight-bold text-secondary mb-1" style="font-size: 0.78rem; text-transform: uppercase;">Laboratorium</label>
            <select name="id_lab" class="form-control form-control-sm select2bs4">
              <option value="">-- Semua Lab --</option>
              @foreach($labs as $l)
                <option value="{{ $l->id_lab }}" {{ request('id_lab') == $l->id_lab ? 'selected' : '' }}>
                  {{ $l->nama_lab }}
                </option>
              @endforeach
            </select>
          </div>

          <div class="col-md-3 col-sm-6 mb-2">
            <label class="font-weight-bold text-secondary mb-1" style="font-size: 0.78rem; text-transform: uppercase;">Status Approval</label>
            <div class="d-flex align-items-center" style="gap: 8px;">
              <select name="status_approval" class="form-control form-control-sm" style="flex: 1;">
                <option value="">-- Semua Status --</option>
                <option value="disetujui" {{ request('status_approval') === 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                <option value="menunggu" {{ request('status_approval') === 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                <option value="ditolak" {{ request('status_approval') === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
              </select>
              <button type="submit" class="btn btn-primary btn-sm px-3 d-flex align-items-center justify-content-center shadow-sm" style="height: 31px; flex-shrink: 0;" title="Terapkan Filter">
                <i class="fas fa-search"></i>
              </button>
            </div>
          </div>
        </form>

        <!-- Lower Action Toolbar -->
        <div class="d-flex align-items-center justify-content-between mt-3 pt-2 border-top flex-wrap" style="gap: 8px;">
          <div class="d-flex align-items-center flex-wrap" style="gap: 8px;">
            <a href="{{ url('/admin/kejuruan/laporan-praktik/pdf?' . http_build_query(request()->all())) }}" target="_blank" class="btn btn-danger btn-sm shadow-sm font-weight-bold">
              <i class="fas fa-file-pdf mr-1"></i> Cetak PDF
            </a>
            <a href="{{ url('/admin/kejuruan/laporan-praktik/excel?' . http_build_query(request()->all())) }}" class="btn btn-success btn-sm shadow-sm font-weight-bold">
              <i class="fas fa-file-excel mr-1"></i> Ekspor Excel
            </a>
            <a href="/admin/kejuruan/jurnal-praktik" class="btn btn-outline-secondary btn-sm shadow-sm">
              <i class="fas fa-clipboard-check mr-1"></i> Jurnal Harian
            </a>
          </div>

          <div>
            @if(request()->anyFilled(['tanggal_awal', 'tanggal_akhir', 'bulan', 'tahun', 'status_approval', 'id_guru', 'id_lab']))
              <a href="/admin/kejuruan/laporan-praktik?periode={{ $periode }}" class="btn btn-link text-muted btn-sm p-0">
                <i class="fas fa-undo mr-1"></i> Reset Filter
              </a>
            @endif
          </div>
        </div>
      </div>
    </div>

    <!-- TABEL REKAP JURNAL -->
    <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
      <div class="card-header bg-white py-3 border-0 d-flex align-items-center justify-content-between flex-wrap">
        <div>
          <h3 class="card-title text-dark font-weight-bold mb-0" style="font-size: 1rem;">
            <i class="fas fa-list-ul text-primary mr-2"></i> Rekap Jurnal Praktik
          </h3>
          <span class="badge badge-light border text-secondary ml-2 px-2 py-1" style="font-size: 0.75rem;">
            Total: {{ count($jurnals) }} Data
          </span>
        </div>
      </div>

      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0" style="font-size: 0.88rem;">
            <thead class="bg-light text-secondary text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">
              <tr>
                <th style="width: 45px" class="text-center">No</th>
                <th style="width: 100px">Tanggal</th>
                <th>Kelas & Mapel</th>
                <th>Laboratorium</th>
                <th>Guru Instruktur</th>
                <th>Materi Praktik</th>
                <th style="width: 90px" class="text-center">Hadir</th>
                <th style="width: 120px" class="text-center">Status</th>
                <th style="width: 120px" class="text-center">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($jurnals as $j)
                <tr>
                  <td class="text-center align-middle">{{ $loop->iteration }}</td>
                  <td class="align-middle font-weight-500">{{ $j->tanggal->format('d/m/Y') }}</td>
                  <td class="align-middle">
                    <div class="font-weight-bold text-dark">{{ $j->jadwalPraktik->mapel->nama_mapel ?? '-' }}</div>
                    <small class="text-muted">{{ $j->jadwalPraktik->kelas->nama_kelas ?? '-' }}</small>
                  </td>
                  <td class="align-middle">{{ $j->jadwalPraktik->lab->nama_lab ?? '-' }}</td>
                  <td class="align-middle text-muted">{{ $j->jadwalPraktik->guru->nama_guru ?? '-' }}</td>
                  <td class="align-middle" style="max-width: 220px;">
                    <span class="d-inline-block text-truncate" style="max-width: 210px;" title="{{ strip_tags($j->materi) }}">
                      {{ strip_tags($j->materi) }}
                    </span>
                  </td>
                  <td class="text-center align-middle">
                    <span class="badge badge-info px-2 py-1 font-weight-bold">{{ $j->jumlah_hadir }}</span>
                  </td>
                  <td class="text-center align-middle">
                    @if($j->status_approval === 'disetujui')
                      <span class="badge badge-success px-2 py-1">Disetujui</span>
                    @elseif($j->status_approval === 'ditolak')
                      <span class="badge badge-danger px-2 py-1" title="{{ $j->catatan_kaprog }}">Ditolak</span>
                    @else
                      <span class="badge badge-warning text-dark px-2 py-1">Menunggu</span>
                    @endif
                  </td>
                  <td class="text-center align-middle">
                    <a href="/admin/kejuruan/jurnal-praktik/{{ $j->id_jurnal_praktik }}?from=laporan-praktik" class="btn btn-sm btn-outline-info" title="Lihat Detail Jurnal & Bukti Praktik">
                      <i class="fas fa-eye"></i>
                    </a>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="9" class="text-center py-4 text-muted">
                    <i class="fas fa-info-circle mr-1"></i> Tidak ada jurnal praktik pada periode {{ $periodeLabel }}.
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
