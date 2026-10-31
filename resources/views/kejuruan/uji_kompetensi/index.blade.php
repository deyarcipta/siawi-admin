@extends($layout)

@section('content')
<!-- Content Header -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row align-items-center mb-2">
      <div class="col-sm-7 d-flex align-items-center">
        <i class="fas fa-award text-primary mr-3" style="font-size: 2rem; flex-shrink: 0;"></i>
        <div class="d-flex flex-column justify-content-center">
          <h1 class="m-0 font-weight-bold text-dark" style="line-height: 1.2;">Uji Kompetensi Keahlian (UKK)</h1>
          <p class="text-muted mt-1 mb-0" style="line-height: 1.2; font-size: 0.85rem;">Pengelolaan kegiatan asesmen sertifikasi kejuruan, penguji internal/eksternal, dan berita acara</p>
        </div>
      </div>
      <div class="col-sm-5">
        <ol class="breadcrumb float-sm-right bg-transparent p-0 mb-0">
          <li class="breadcrumb-item"><a href="/admin/dashboard" class="text-primary font-weight-500">Dashboard</a></li>
          <li class="breadcrumb-item"><a href="/admin/kejuruan/uji-kompetensi" class="text-primary font-weight-500">Kejuruan</a></li>
          <li class="breadcrumb-item active">Uji Kompetensi</li>
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

    <!-- Summary Stat Cards -->
    <div class="row mb-3">
      <!-- Total Kegiatan UKK -->
      <div class="col-lg-3 col-6 mb-3 mb-lg-0">
        <div class="card border-0 shadow-sm h-100 bg-white" style="border-radius: 12px; border-left: 4px solid #0284c7 !important;">
          <div class="card-body p-3 d-flex align-items-center justify-content-between">
            <div>
              <div class="text-muted text-uppercase font-weight-bold" style="font-size: 0.72rem; letter-spacing: 0.5px;">Kegiatan UKK</div>
              <div class="font-weight-bold text-dark mt-1" style="font-size: 1.7rem; line-height: 1.1;">{{ $totalKegiatan }} <span style="font-size: 0.85rem; font-weight: 500; color: #64748b;">Sesi</span></div>
              <small class="text-muted mt-1 d-block"><i class="fas fa-calendar-alt text-primary mr-1"></i> Agenda Ujian</small>
            </div>
            <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 46px; height: 46px; font-size: 1.25rem; background: #f0f9ff; color: #0284c7; flex-shrink: 0;">
              <i class="fas fa-clipboard-check"></i>
            </div>
          </div>
        </div>
      </div>

      <!-- Total Peserta Terdaftar -->
      <div class="col-lg-3 col-6 mb-3 mb-lg-0">
        <div class="card border-0 shadow-sm h-100 bg-white" style="border-radius: 12px; border-left: 4px solid #16a34a !important;">
          <div class="card-body p-3 d-flex align-items-center justify-content-between">
            <div>
              <div class="text-muted text-uppercase font-weight-bold" style="font-size: 0.72rem; letter-spacing: 0.5px;">Peserta Terdaftar</div>
              <div class="font-weight-bold text-dark mt-1" style="font-size: 1.7rem; line-height: 1.1;">{{ $totalPeserta }} <span style="font-size: 0.85rem; font-weight: 500; color: #64748b;">Siswa</span></div>
              <small class="text-muted mt-1 d-block"><i class="fas fa-users text-success mr-1"></i> Seluruh Jurusan</small>
            </div>
            <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 46px; height: 46px; font-size: 1.25rem; background: #dcfce7; color: #16a34a; flex-shrink: 0;">
              <i class="fas fa-user-graduate"></i>
            </div>
          </div>
        </div>
      </div>

      <!-- Tingkat Kelulusan -->
      <div class="col-lg-3 col-6 mb-3 mb-lg-0">
        <div class="card border-0 shadow-sm h-100 bg-white" style="border-radius: 12px; border-left: 4px solid #8b5cf6 !important;">
          <div class="card-body p-3 d-flex align-items-center justify-content-between">
            <div>
              <div class="text-muted text-uppercase font-weight-bold" style="font-size: 0.72rem; letter-spacing: 0.5px;">Kompeten (Lulus)</div>
              <div class="font-weight-bold text-dark mt-1" style="font-size: 1.7rem; line-height: 1.1;">{{ $persenLulus }}% <span style="font-size: 0.85rem; font-weight: 500; color: #64748b;">({{ $totalKompeten }} Siswa)</span></div>
              <small class="text-muted mt-1 d-block"><i class="fas fa-check-double text-purple mr-1"></i> Capaian Asesmen</small>
            </div>
            <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 46px; height: 46px; font-size: 1.25rem; background: #f5f3ff; color: #8b5cf6; flex-shrink: 0;">
              <i class="fas fa-medal"></i>
            </div>
          </div>
        </div>
      </div>

      <!-- Tim Penguji -->
      <div class="col-lg-3 col-6 mb-3 mb-lg-0">
        <div class="card border-0 shadow-sm h-100 bg-white" style="border-radius: 12px; border-left: 4px solid #f59e0b !important;">
          <div class="card-body p-3 d-flex align-items-center justify-content-between">
            <div>
              <div class="text-muted text-uppercase font-weight-bold" style="font-size: 0.72rem; letter-spacing: 0.5px;">Tim Asesor / Penguji</div>
              <div class="font-weight-bold text-dark mt-1" style="font-size: 1.7rem; line-height: 1.1;">{{ $totalPenguji }} <span style="font-size: 0.85rem; font-weight: 500; color: #64748b;">Orang</span></div>
              <small class="text-muted mt-1 d-block"><i class="fas fa-briefcase text-warning mr-1"></i> Guru & DU-DI Mitra</small>
            </div>
            <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 46px; height: 46px; font-size: 1.25rem; background: #fffbeb; color: #f59e0b; flex-shrink: 0;">
              <i class="fas fa-user-check"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Filter Toolbar Card -->
    <div class="card border-0 shadow-sm mb-3" style="border-radius: 12px;">
      <div class="card-body p-3">
        <form method="GET" action="/admin/kejuruan/uji-kompetensi" class="row align-items-end">
          <div class="col-md-4 col-sm-6 mb-2 mb-md-0">
            <label class="font-weight-bold text-secondary mb-1" style="font-size: 0.78rem; text-transform: uppercase;">Filter Jurusan</label>
            <select name="id_jurusan" class="form-control form-control-sm select2bs4">
              <option value="">-- Semua Jurusan --</option>
              @foreach($jurusans as $j)
                <option value="{{ $j->id_jurusan }}" {{ request('id_jurusan') == $j->id_jurusan ? 'selected' : '' }}>
                  {{ $j->nama_jurusan }}
                </option>
              @endforeach
            </select>
          </div>

          <div class="col-md-4 col-sm-6 mb-2 mb-md-0">
            <label class="font-weight-bold text-secondary mb-1" style="font-size: 0.78rem; text-transform: uppercase;">Status Pelaksanaan</label>
            <div class="d-flex align-items-center" style="gap: 8px;">
              <select name="status" class="form-control form-control-sm" style="flex: 1;">
                <option value="">-- Semua Status --</option>
                <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft (Persiapan)</option>
                <option value="berlangsung" {{ request('status') === 'berlangsung' ? 'selected' : '' }}>Sedang Berlangsung</option>
                <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
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
            @if($user->hasAnyRole(['admin', 'kaprog']))
              <a href="/admin/kejuruan/uji-kompetensi/create" class="btn btn-primary btn-sm shadow-sm font-weight-bold">
                <i class="fas fa-plus mr-1"></i> Buat Kegiatan UKK Baru
              </a>
            @endif
            <a href="/admin/kejuruan/capaian-siswa" class="btn btn-outline-secondary btn-sm shadow-sm">
              <i class="fas fa-medal mr-1"></i> Buku Induk Capaian Siswa
            </a>
          </div>

          <div>
            @if(request()->anyFilled(['id_jurusan', 'status', 'tahun_ajaran']))
              <a href="/admin/kejuruan/uji-kompetensi" class="btn btn-link text-muted btn-sm p-0">
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
            <i class="fas fa-list-ul text-primary mr-2"></i> Daftar Kegiatan Uji Kompetensi Keahlian
          </h3>
          <span class="badge badge-light border text-secondary ml-2 px-2 py-1" style="font-size: 0.75rem;">
            Total: {{ $kegiatans->count() }} Kegiatan
          </span>
        </div>
      </div>

      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0" style="font-size: 0.88rem;">
            <thead class="bg-light text-secondary text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">
              <tr>
                <th style="width: 50px" class="text-center">No</th>
                <th>Nama Kegiatan UKK</th>
                <th>Jurusan & Lab</th>
                <th>Waktu Pelaksanaan</th>
                <th style="width: 100px" class="text-center">Peserta</th>
                <th style="width: 100px" class="text-center">Penguji</th>
                <th style="width: 120px" class="text-center">Status</th>
                <th style="width: 150px" class="text-center">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($kegiatans as $k)
                <tr>
                  <td class="text-center align-middle font-weight-bold text-muted">{{ $loop->iteration }}</td>
                  <td class="align-middle">
                    <a href="/admin/kejuruan/uji-kompetensi/{{ $k->id_ukk_kegiatan }}" class="font-weight-bold text-dark text-decoration-none hover-primary">
                      {{ $k->nama_kegiatan }}
                    </a>
                    <small class="text-muted d-block mt-1">
                      SK: {{ $k->nomor_sk ?: 'Belum diatur' }} (TA: {{ $k->tahun_ajaran }})
                    </small>
                  </td>
                  <td class="align-middle">
                    <span class="badge badge-info px-2 py-1 mb-1 d-inline-block font-weight-bold" title="{{ $k->jurusan->nama_jurusan ?? '-' }}" style="cursor: help;">{{ $k->jurusan->nama_singkat ?? '-' }}</span>
                    <small class="text-muted d-block"><i class="fas fa-flask mr-1"></i> {{ $k->lab->nama_lab ?? 'Lab Umum' }}</small>
                  </td>
                  <td class="align-middle">
                    <span class="font-weight-500 text-dark">{{ $k->tanggal_mulai->format('d/m/Y') }}</span>
                    <small class="text-muted d-block">s.d. {{ $k->tanggal_selesai->format('d/m/Y') }}</small>
                  </td>
                  <td class="text-center align-middle">
                    <span class="badge badge-primary px-2 py-1 font-weight-bold">
                      {{ $k->pesertas->count() }} Siswa
                    </span>
                  </td>
                  <td class="text-center align-middle">
                    <span class="badge badge-secondary px-2 py-1 font-weight-bold">
                      {{ $k->pengujis->count() }} Penguji
                    </span>
                  </td>
                  <td class="text-center align-middle">
                    @if($k->status === 'selesai')
                      <span class="badge badge-success px-2 py-1 font-weight-bold">Selesai</span>
                    @elseif($k->status === 'berlangsung')
                      <span class="badge badge-primary px-2 py-1 font-weight-bold">Berlangsung</span>
                    @else
                      <span class="badge badge-warning text-dark px-2 py-1 font-weight-bold">Draft</span>
                    @endif
                  </td>
                  <td class="text-center align-middle">
                    <a href="/admin/kejuruan/uji-kompetensi/{{ $k->id_ukk_kegiatan }}" class="btn btn-sm btn-outline-info mr-1" title="Buka Kegiatan & Nilai">
                      <i class="fas fa-eye mr-1"></i> Detail
                    </a>
                    @if($user->hasAnyRole(['admin', 'kaprog']))
                      <form action="/admin/kejuruan/uji-kompetensi/{{ $k->id_ukk_kegiatan }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus kegiatan UKK ini beserta seluruh rekor nilai pesertanya?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                          <i class="fas fa-trash-alt"></i>
                        </button>
                      </form>
                    @endif
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="8" class="text-center py-5 text-muted">
                    <i class="fas fa-award mb-2" style="font-size: 2.5rem; opacity: 0.4;"></i>
                    <h6 class="font-weight-bold text-dark">Belum Ada Kegiatan Uji Kompetensi</h6>
                    <p class="small mb-3">Mulai buat kegiatan UKK baru untuk mengelola penguji, unit kompetensi uji, dan asesmen siswa.</p>
                    @if($user->hasAnyRole(['admin', 'kaprog']))
                      <a href="/admin/kejuruan/uji-kompetensi/create" class="btn btn-primary btn-sm px-3 shadow-sm font-weight-bold">
                        <i class="fas fa-plus mr-1"></i> Buat Kegiatan UKK Sekarang
                      </a>
                    @endif
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
