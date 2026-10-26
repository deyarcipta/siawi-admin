@extends($layout)

@push('css')
<style>
  .table-jadwal th {
    font-size: 0.74rem;
    text-transform: uppercase;
    letter-spacing: 0.6px;
    font-weight: 700;
    color: #475569;
    background-color: #f8fafc;
    border-top: 1px solid #e2e8f0 !important;
    border-bottom: 2px solid #cbd5e1 !important;
    padding: 12px 14px !important;
    vertical-align: middle;
  }
  .table-jadwal td {
    padding: 12px 14px !important;
    vertical-align: middle;
    border-bottom: 1px solid #f1f5f9;
  }
  .table-jadwal tr:hover td {
    background-color: #f8fafc;
  }
  .badge-soft-primary {
    background-color: #e0f2fe;
    color: #0369a1;
    border: 1px solid #bae6fd;
    font-weight: 700;
  }
  .badge-soft-secondary {
    background-color: #f1f5f9;
    color: #475569;
    border: 1px solid #e2e8f0;
    font-weight: 600;
  }
  .btn-action-icon {
    width: 32px;
    height: 32px;
    padding: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    font-size: 0.82rem;
    transition: all 0.15s ease;
  }
</style>
@endpush

@section('content')
<!-- Content Header -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row align-items-center mb-2">
      <div class="col-sm-7 d-flex align-items-center">
        <i class="fas fa-calendar-alt text-primary mr-3" style="font-size: 2rem; flex-shrink: 0;"></i>
        <div class="d-flex flex-column justify-content-center">
          <h1 class="m-0 font-weight-bold text-dark" style="line-height: 1.2;">Jadwal Praktik Kejuruan</h1>
          <p class="text-muted mt-1 mb-0" style="line-height: 1.2; font-size: 0.85rem;">Pengelolaan waktu penggunaan laboratorium, bengkel praktik, dan perlengkapan siswa</p>
        </div>
      </div>
      <div class="col-sm-5">
        <ol class="breadcrumb float-sm-right bg-transparent p-0 mb-0">
          <li class="breadcrumb-item"><a href="/admin/dashboard" class="text-primary font-weight-500">Dashboard</a></li>
          <li class="breadcrumb-item"><a href="/admin/kejuruan/jadwal-praktik" class="text-primary font-weight-500">Kejuruan</a></li>
          <li class="breadcrumb-item active">Jadwal Praktik</li>
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

    @if(session('error'))
      <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert" style="border-radius: 12px;">
        <i class="fas fa-exclamation-circle mr-2"></i> {!! session('error') !!}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    @endif

    @php
      $todayName = \Carbon\Carbon::now()->locale('id')->isoFormat('dddd');
      $countToday = $jadwals->where('hari', $todayName)->count();
      $countLabUsed = $jadwals->pluck('id_lab')->unique()->filter()->count();
      $countKelasUsed = $jadwals->pluck('id_kelas')->unique()->filter()->count();
    @endphp

    <!-- Summary Stat Cards -->
    <div class="row mb-3">
      <!-- Total Sesi Terjadwal -->
      <div class="col-lg-3 col-6 mb-3 mb-lg-0">
        <div class="card border-0 shadow-sm h-100 bg-white" style="border-radius: 12px; border-left: 4px solid #0284c7 !important;">
          <div class="card-body p-3 d-flex align-items-center justify-content-between">
            <div>
              <div class="text-muted text-uppercase font-weight-bold" style="font-size: 0.72rem; letter-spacing: 0.5px;">Total Sesi Praktik</div>
              <div class="font-weight-bold text-dark mt-1" style="font-size: 1.7rem; line-height: 1.1;">{{ $jadwals->count() }} <span style="font-size: 0.85rem; font-weight: 500; color: #64748b;">Sesi</span></div>
              <small class="text-muted mt-1 d-block"><i class="fas fa-clock text-primary mr-1"></i> Jadwal Mingguan</small>
            </div>
            <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 46px; height: 46px; font-size: 1.25rem; background: #f0f9ff; color: #0284c7; flex-shrink: 0;">
              <i class="fas fa-calendar-alt"></i>
            </div>
          </div>
        </div>
      </div>

      <!-- Sesi Hari Ini -->
      <div class="col-lg-3 col-6 mb-3 mb-lg-0">
        <div class="card border-0 shadow-sm h-100 bg-white" style="border-radius: 12px; border-left: 4px solid #16a34a !important;">
          <div class="card-body p-3 d-flex align-items-center justify-content-between">
            <div>
              <div class="text-muted text-uppercase font-weight-bold" style="font-size: 0.72rem; letter-spacing: 0.5px;">Sesi Hari Ini ({{ $todayName }})</div>
              <div class="font-weight-bold text-dark mt-1" style="font-size: 1.7rem; line-height: 1.1;">{{ $countToday }} <span style="font-size: 0.85rem; font-weight: 500; color: #64748b;">Sesi</span></div>
              <small class="text-muted mt-1 d-block"><i class="fas fa-calendar-check text-success mr-1"></i> Praktik Berjalan</small>
            </div>
            <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 46px; height: 46px; font-size: 1.25rem; background: #dcfce7; color: #16a34a; flex-shrink: 0;">
              <i class="fas fa-calendar-day"></i>
            </div>
          </div>
        </div>
      </div>

      <!-- Lab Terpakai -->
      <div class="col-lg-3 col-6 mb-3 mb-lg-0">
        <div class="card border-0 shadow-sm h-100 bg-white" style="border-radius: 12px; border-left: 4px solid #f59e0b !important;">
          <div class="card-body p-3 d-flex align-items-center justify-content-between">
            <div>
              <div class="text-muted text-uppercase font-weight-bold" style="font-size: 0.72rem; letter-spacing: 0.5px;">Lab / Ruang Digunakan</div>
              <div class="font-weight-bold text-dark mt-1" style="font-size: 1.7rem; line-height: 1.1;">{{ $countLabUsed }} <span style="font-size: 0.85rem; font-weight: 500; color: #64748b;">Ruang</span></div>
              <small class="text-muted mt-1 d-block"><i class="fas fa-flask text-warning mr-1"></i> Dari {{ $labs->count() }} Lab Terdaftar</small>
            </div>
            <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 46px; height: 46px; font-size: 1.25rem; background: #fffbeb; color: #f59e0b; flex-shrink: 0;">
              <i class="fas fa-flask"></i>
            </div>
          </div>
        </div>
      </div>

      <!-- Kelas Terjadwal -->
      <div class="col-lg-3 col-6 mb-3 mb-lg-0">
        <div class="card border-0 shadow-sm h-100 bg-white" style="border-radius: 12px; border-left: 4px solid #8b5cf6 !important;">
          <div class="card-body p-3 d-flex align-items-center justify-content-between">
            <div>
              <div class="text-muted text-uppercase font-weight-bold" style="font-size: 0.72rem; letter-spacing: 0.5px;">Kelas Terjadwal</div>
              <div class="font-weight-bold text-dark mt-1" style="font-size: 1.7rem; line-height: 1.1;">{{ $countKelasUsed }} <span style="font-size: 0.85rem; font-weight: 500; color: #64748b;">Rombel</span></div>
              <small class="text-muted mt-1 d-block"><i class="fas fa-users text-purple mr-1"></i> Aktif Praktik Kejuruan</small>
            </div>
            <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 46px; height: 46px; font-size: 1.25rem; background: #f5f3ff; color: #8b5cf6; flex-shrink: 0;">
              <i class="fas fa-users"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Filter Toolbar Card -->
    <div class="card border-0 shadow-sm mb-3" style="border-radius: 12px;">
      <div class="card-body p-3">
        <form method="GET" action="/admin/kejuruan/jadwal-praktik" class="row align-items-end">
          <input type="hidden" name="view" value="{{ $viewMode }}">

          <div class="col-md-3 col-sm-6 mb-2 mb-md-0">
            <label class="font-weight-bold text-secondary mb-1" style="font-size: 0.78rem; text-transform: uppercase;">Filter Kelas</label>
            <select name="id_kelas" class="form-control form-control-sm select2bs4">
              <option value="">-- Semua Kelas --</option>
              @foreach($kelas as $k)
                <option value="{{ $k->id_kelas }}" {{ request('id_kelas') == $k->id_kelas ? 'selected' : '' }}>
                  {{ $k->nama_kelas }}
                </option>
              @endforeach
            </select>
          </div>

          <div class="col-md-3 col-sm-6 mb-2 mb-md-0">
            <label class="font-weight-bold text-secondary mb-1" style="font-size: 0.78rem; text-transform: uppercase;">Filter Laboratorium</label>
            <select name="id_lab" class="form-control form-control-sm select2bs4">
              <option value="">-- Semua Lab --</option>
              @foreach($labs as $l)
                <option value="{{ $l->id_lab }}" {{ request('id_lab') == $l->id_lab ? 'selected' : '' }}>
                  {{ $l->nama_lab }}
                </option>
              @endforeach
            </select>
          </div>

          <div class="col-md-3 col-sm-6 mb-2 mb-md-0">
            <label class="font-weight-bold text-secondary mb-1" style="font-size: 0.78rem; text-transform: uppercase;">Filter Guru</label>
            <select name="id_guru" class="form-control form-control-sm select2bs4">
              <option value="">-- Semua Guru --</option>
              @foreach($gurus as $g)
                <option value="{{ $g->id_guru }}" {{ request('id_guru') == $g->id_guru ? 'selected' : '' }}>
                  {{ $g->nama_guru }}
                </option>
              @endforeach
            </select>
          </div>

          <div class="col-md-3 col-sm-6 mb-2 mb-md-0">
            <label class="font-weight-bold text-secondary mb-1" style="font-size: 0.78rem; text-transform: uppercase;">Hari Praktik</label>
            <div class="d-flex align-items-center" style="gap: 8px;">
              <select name="hari" class="form-control form-control-sm" style="flex: 1;">
                <option value="">-- Semua Hari --</option>
                @foreach($hariList as $h)
                  <option value="{{ $h }}" {{ request('hari') == $h ? 'selected' : '' }}>{{ $h }}</option>
                @endforeach
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
            @if($user->hasAnyRole(['admin', 'kaprog', 'guru_jurusan']))
              <a href="/admin/kejuruan/jadwal-praktik/create" class="btn btn-primary btn-sm shadow-sm font-weight-bold">
                <i class="fas fa-plus mr-1"></i> Tambah Jadwal
              </a>
              <a href="/admin/kejuruan/jadwal-praktik/create?tipe=insidental" class="btn btn-warning btn-sm shadow-sm font-weight-bold text-dark">
                <i class="fas fa-bolt mr-1"></i> Praktik Dadakan
              </a>
            @endif

            <div class="btn-group shadow-sm" role="group">
              <a href="{{ request()->fullUrlWithQuery(['view' => 'calendar']) }}" class="btn btn-sm {{ $viewMode === 'calendar' ? 'btn-outline-primary active' : 'btn-outline-secondary' }}">
                <i class="fas fa-calendar-week mr-1"></i> Kalender
              </a>
              <a href="{{ request()->fullUrlWithQuery(['view' => 'table']) }}" class="btn btn-sm {{ $viewMode === 'table' ? 'btn-outline-primary active' : 'btn-outline-secondary' }}">
                <i class="fas fa-list mr-1"></i> Tabel
              </a>
            </div>
          </div>

          <div>
            @if(request()->anyFilled(['id_kelas', 'id_lab', 'id_guru', 'hari']))
              <a href="/admin/kejuruan/jadwal-praktik?view={{ $viewMode }}" class="btn btn-link text-muted btn-sm p-0">
                <i class="fas fa-undo mr-1"></i> Reset Filter
              </a>
            @endif
          </div>
        </div>
      </div>
    </div>

    @if($viewMode === 'calendar')
      <!-- Tampilan Kalender Mingguan -->
      <div class="row">
        @foreach($hariList as $hari)
          <div class="col-lg-4 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; overflow: hidden;">
              <div class="card-header bg-white py-2 px-3 border-bottom d-flex align-items-center justify-content-between">
                <span class="font-weight-bold text-dark" style="font-size: 0.95rem;">
                  <i class="far fa-calendar text-primary mr-1"></i> {{ $hari }}
                </span>
                <span class="badge badge-primary px-2 py-1">
                  {{ $jadwalPerHari[$hari]->count() }} Sesi
                </span>
              </div>
              <div class="card-body p-2" style="background-color: #f8fafc; min-height: 220px;">
                @forelse($jadwalPerHari[$hari] as $j)
                  <div class="card border-0 shadow-sm mb-2" style="border-radius: 8px; border-left: 4px solid {{ $j->isInsidental() ? '#f59e0b' : '#007bff' }} !important;">
                    <div class="card-body p-2">
                      <div class="d-flex justify-content-between align-items-start mb-1 flex-wrap" style="gap: 4px;">
                        <span class="badge badge-light border text-primary font-weight-bold" style="font-size: 0.75rem;">
                          {{ substr($j->jam_mulai, 0, 5) }} - {{ substr($j->jam_selesai, 0, 5) }}
                        </span>
                        <div>
                          @if($j->isInsidental())
                            <span class="badge badge-warning text-dark px-2" style="font-size: 0.7rem;" title="Praktik Tambahan / Dadakan">
                              <i class="fas fa-bolt"></i> {{ $j->tanggal_khusus ? \Carbon\Carbon::parse($j->tanggal_khusus)->format('d/m') : 'Dadakan' }}
                            </span>
                          @elseif($j->frekuensi === 'minggu_ganjil')
                            <span class="badge badge-secondary px-2" style="font-size: 0.7rem;" title="2 Minggu Sekali (Ganjil)">
                              Siklus Ganjil
                            </span>
                          @elseif($j->frekuensi === 'minggu_genap')
                            <span class="badge badge-secondary px-2" style="font-size: 0.7rem;" title="2 Minggu Sekali (Genap)">
                              Siklus Genap
                            </span>
                          @endif
                          <span class="badge badge-info px-2" style="font-size: 0.72rem;">
                            {{ $j->kelas->nama_kelas ?? '-' }}
                          </span>
                        </div>
                      </div>
                      <h6 class="font-weight-bold text-dark mb-1" style="font-size: 0.88rem; line-height: 1.3;">
                        {{ $j->mapel->nama_mapel ?? '-' }}
                      </h6>
                      <div class="small text-muted mb-1">
                        <i class="fas fa-flask mr-1 text-secondary"></i> {{ $j->lab->nama_lab ?? '-' }}
                      </div>
                      <div class="small text-muted mb-2">
                        <i class="fas fa-user mr-1 text-secondary"></i> {{ $j->guru->nama_guru ?? '-' }}
                      </div>

                      @if($j->alat_bahan)
                        <div class="p-1 px-2 rounded mb-2" style="background-color: #eef2ff; border: 1px dashed #c7d2fe; font-size: 0.75rem;">
                          <strong class="text-primary"><i class="fas fa-toolbox mr-1"></i> Bawaan Siswa:</strong>
                          <span class="text-dark d-block text-truncate" title="{{ $j->alat_bahan }}">{{ $j->alat_bahan }}</span>
                        </div>
                      @endif

                      <div class="d-flex justify-content-end align-items-center pt-1 border-top">
                        @if($user->hasAnyRole(['admin', 'kaprog', 'guru_jurusan']))
                          <a href="/admin/kejuruan/jadwal-praktik/{{ $j->id_jadwal_praktik }}/edit" class="btn btn-xs btn-outline-primary mr-1" title="Ubah Jadwal">
                            <i class="fas fa-edit"></i>
                          </a>
                          <form action="/admin/kejuruan/jadwal-praktik/{{ $j->id_jadwal_praktik }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus jadwal praktik ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-xs btn-outline-danger" title="Hapus">
                              <i class="fas fa-trash-alt"></i>
                            </button>
                          </form>
                        @endif
                      </div>
                    </div>
                  </div>
                @empty
                  <div class="text-center py-4 text-muted small">
                    <i class="far fa-calendar-times d-block mb-1" style="font-size: 1.5rem; opacity: 0.4;"></i>
                    Tidak ada jadwal praktik
                  </div>
                @endforelse
              </div>
            </div>
          </div>
        @endforeach
      </div>

    @else
      <!-- Tampilan Mode Tabel -->
      <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
        <div class="card-header bg-white py-3 border-0 d-flex align-items-center justify-content-between flex-wrap">
          <div>
            <h3 class="card-title text-dark font-weight-bold mb-0" style="font-size: 1rem;">
              <i class="fas fa-list-ul text-primary mr-2"></i> Daftar Jadwal Praktik
            </h3>
            <span class="badge badge-light border text-secondary ml-2 px-2 py-1" style="font-size: 0.75rem;">
              Total: {{ $jadwals->count() }} Sesi
            </span>
          </div>
        </div>

        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 table-jadwal" style="font-size: 0.88rem;">
              <thead>
                <tr>
                  <th style="width: 48px;" class="text-center">No</th>
                  <th style="min-width: 140px;">Hari & Jam</th>
                  <th style="min-width: 95px;">Kelas</th>
                  <th style="min-width: 200px;">Mata Pelajaran</th>
                  <th style="min-width: 130px;">Laboratorium</th>
                  <th style="min-width: 180px;">Guru Pengajar</th>
                  <th style="width: 90px;" class="text-center">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @forelse($jadwals as $j)
                  <tr>
                    <td class="text-center align-middle font-weight-500 text-secondary">{{ $loop->iteration }}</td>
                    <td class="align-middle text-nowrap">
                      <div class="d-flex flex-column align-items-start" style="gap: 4px;">
                        <span class="badge badge-soft-primary px-2 py-1 font-weight-bold" style="font-size: 0.76rem; border-radius: 6px;">
                          {{ $j->hari }}
                        </span>
                        <span class="font-weight-bold text-dark" style="font-size: 0.84rem; letter-spacing: 0.2px;">
                          {{ substr($j->jam_mulai, 0, 5) }} &ndash; {{ substr($j->jam_selesai, 0, 5) }}
                        </span>
                      </div>
                      @if($j->isInsidental())
                        <div class="mt-1">
                          <span class="badge badge-warning text-dark px-2 py-0" style="font-size: 0.68rem; font-weight: 600;">
                            <i class="fas fa-bolt mr-1"></i>Dadakan: {{ $j->tanggal_khusus ? \Carbon\Carbon::parse($j->tanggal_khusus)->format('d/m/Y') : '' }}
                          </span>
                        </div>
                      @elseif($j->frekuensi === 'minggu_ganjil')
                        <div class="mt-1">
                          <span class="badge badge-soft-secondary px-2 py-0" style="font-size: 0.68rem;">
                            <i class="fas fa-sync-alt mr-1"></i>2 Mgg Sekali (Ganjil)
                          </span>
                        </div>
                      @elseif($j->frekuensi === 'minggu_genap')
                        <div class="mt-1">
                          <span class="badge badge-soft-secondary px-2 py-0" style="font-size: 0.68rem;">
                            <i class="fas fa-sync-alt mr-1"></i>2 Mgg Sekali (Genap)
                          </span>
                        </div>
                      @endif
                    </td>
                    <td class="align-middle text-nowrap">
                      <span class="badge badge-light border text-dark font-weight-bold px-2 py-1" style="font-size: 0.82rem; border-radius: 6px; letter-spacing: 0.3px;">
                        {{ $j->kelas->nama_kelas ?? '-' }}
                      </span>
                    </td>
                    <td class="align-middle">
                      <div class="font-weight-bold text-dark" style="font-size: 0.88rem; line-height: 1.35;">
                        {{ $j->mapel->nama_mapel ?? '-' }}
                      </div>
                    </td>
                    <td class="align-middle text-nowrap">
                      <div class="d-flex align-items-center text-dark font-weight-500" style="gap: 6px; font-size: 0.85rem;">
                        <i class="fas fa-flask text-primary" style="font-size: 0.8rem;"></i>
                        <span>{{ $j->lab->nama_lab ?? '-' }}</span>
                      </div>
                    </td>
                    <td class="align-middle text-nowrap">
                      <div class="d-flex align-items-center" style="gap: 7px;">
                        <div class="rounded-circle bg-light border d-inline-flex align-items-center justify-content-center text-secondary" style="width: 26px; height: 26px; font-size: 0.72rem; flex-shrink: 0;">
                          <i class="fas fa-user"></i>
                        </div>
                        <span class="font-weight-500 text-dark" style="font-size: 0.85rem;">{{ $j->guru->nama_guru ?? '-' }}</span>
                      </div>
                    </td>
                    <td class="text-center align-middle text-nowrap">
                      @if($user->hasAnyRole(['admin', 'kaprog', 'guru_jurusan']))
                        <div class="d-inline-flex align-items-center" style="gap: 6px;">
                          <a href="/admin/kejuruan/jadwal-praktik/{{ $j->id_jadwal_praktik }}/edit" class="btn btn-outline-primary btn-action-icon" title="Ubah Jadwal">
                            <i class="fas fa-edit"></i>
                          </a>
                          <form action="/admin/kejuruan/jadwal-praktik/{{ $j->id_jadwal_praktik }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus jadwal ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-action-icon" title="Hapus Jadwal">
                              <i class="fas fa-trash-alt"></i>
                            </button>
                          </form>
                        </div>
                      @else
                        <span class="text-muted small">-</span>
                      @endif
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="7" class="text-center py-4 text-muted">
                      <i class="fas fa-info-circle mr-1"></i> Tidak ada jadwal praktik yang sesuai filter.
                    </td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    @endif

  </div>
</div>
@endsection
