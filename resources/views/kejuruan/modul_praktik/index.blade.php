@extends($layout)

@section('content')
<!-- Content Header -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row align-items-center mb-2">
      <div class="col-sm-7 d-flex align-items-center">
        <i class="fas fa-book-reader text-primary mr-3" style="font-size: 2rem; flex-shrink: 0;"></i>
        <div class="d-flex flex-column justify-content-center">
          <h1 class="m-0 font-weight-bold text-dark" style="line-height: 1.2;">Bank Modul Praktik</h1>
          <p class="text-muted mt-1 mb-0" style="line-height: 1.2; font-size: 0.85rem;">Repositori jobsheet, lembar kerja siswa, dan rubrik penilaian kejuruan</p>
        </div>
      </div>
      <div class="col-sm-5">
        <ol class="breadcrumb float-sm-right bg-transparent p-0 mb-0">
          <li class="breadcrumb-item"><a href="/admin/dashboard" class="text-primary font-weight-500">Dashboard</a></li>
          <li class="breadcrumb-item"><a href="/admin/kejuruan/modul-praktik" class="text-primary font-weight-500">Kejuruan</a></li>
          <li class="breadcrumb-item active">Bank Modul Praktik</li>
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
        <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    @endif

    @if(session('error'))
      <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert" style="border-radius: 12px;">
        <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    @endif

    @php
      $countTotal = $moduls->total();
      $countTerbit = \App\Models\ModulPraktik::forUserJurusan($user)->where('status', 'terbit')->count();
      $countDraft = \App\Models\ModulPraktik::forUserJurusan($user)->where('status', 'draft')->count();
      $countKompetensi = $kompetensis->count();
    @endphp

    <!-- Summary Stat Cards -->
    <div class="row mb-3">
      <!-- Total Modul -->
      <div class="col-lg-3 col-6 mb-3 mb-lg-0">
        <div class="card border-0 shadow-sm h-100 bg-white" style="border-radius: 12px; border-left: 4px solid #0284c7 !important;">
          <div class="card-body p-3 d-flex align-items-center justify-content-between">
            <div>
              <div class="text-muted text-uppercase font-weight-bold" style="font-size: 0.72rem; letter-spacing: 0.5px;">Total Modul Praktik</div>
              <div class="font-weight-bold text-dark mt-1" style="font-size: 1.7rem; line-height: 1.1;">{{ $countTotal }} <span style="font-size: 0.85rem; font-weight: 500; color: #64748b;">Modul</span></div>
              <small class="text-muted mt-1 d-block"><i class="fas fa-folder text-primary mr-1"></i> Bank Praktikum</small>
            </div>
            <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 46px; height: 46px; font-size: 1.25rem; background: #f0f9ff; color: #0284c7; flex-shrink: 0;">
              <i class="fas fa-book"></i>
            </div>
          </div>
        </div>
      </div>

      <!-- Terbit / Siap Pakai -->
      <div class="col-lg-3 col-6 mb-3 mb-lg-0">
        <div class="card border-0 shadow-sm h-100 bg-white" style="border-radius: 12px; border-left: 4px solid #16a34a !important;">
          <div class="card-body p-3 d-flex align-items-center justify-content-between">
            <div>
              <div class="text-muted text-uppercase font-weight-bold" style="font-size: 0.72rem; letter-spacing: 0.5px;">Modul Terbit</div>
              <div class="font-weight-bold text-dark mt-1" style="font-size: 1.7rem; line-height: 1.1;">{{ $countTerbit }} <span style="font-size: 0.85rem; font-weight: 500; color: #64748b;">Modul</span></div>
              <small class="text-muted mt-1 d-block"><i class="fas fa-check-circle text-success mr-1"></i> Siap Digunakan</small>
            </div>
            <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 46px; height: 46px; font-size: 1.25rem; background: #dcfce7; color: #16a34a; flex-shrink: 0;">
              <i class="fas fa-check-double"></i>
            </div>
          </div>
        </div>
      </div>

      <!-- Draft / Penyusunan -->
      <div class="col-lg-3 col-6 mb-3 mb-lg-0">
        <div class="card border-0 shadow-sm h-100 bg-white" style="border-radius: 12px; border-left: 4px solid #f59e0b !important;">
          <div class="card-body p-3 d-flex align-items-center justify-content-between">
            <div>
              <div class="text-muted text-uppercase font-weight-bold" style="font-size: 0.72rem; letter-spacing: 0.5px;">Status Draft</div>
              <div class="font-weight-bold text-dark mt-1" style="font-size: 1.7rem; line-height: 1.1;">{{ $countDraft }} <span style="font-size: 0.85rem; font-weight: 500; color: #64748b;">Modul</span></div>
              <small class="text-muted mt-1 d-block"><i class="fas fa-pencil-alt text-warning mr-1"></i> Sedang Disusun</small>
            </div>
            <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 46px; height: 46px; font-size: 1.25rem; background: #fffbeb; color: #f59e0b; flex-shrink: 0;">
              <i class="fas fa-edit"></i>
            </div>
          </div>
        </div>
      </div>

      <!-- Unit Kompetensi Terkait -->
      <div class="col-lg-3 col-6 mb-3 mb-lg-0">
        <div class="card border-0 shadow-sm h-100 bg-white" style="border-radius: 12px; border-left: 4px solid #8b5cf6 !important;">
          <div class="card-body p-3 d-flex align-items-center justify-content-between">
            <div>
              <div class="text-muted text-uppercase font-weight-bold" style="font-size: 0.72rem; letter-spacing: 0.5px;">Kompetensi Terdaftar</div>
              <div class="font-weight-bold text-dark mt-1" style="font-size: 1.7rem; line-height: 1.1;">{{ $countKompetensi }} <span style="font-size: 0.85rem; font-weight: 500; color: #64748b;">Unit</span></div>
              <small class="text-muted mt-1 d-block"><i class="fas fa-award text-purple mr-1"></i> Skema Kejuruan</small>
            </div>
            <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 46px; height: 46px; font-size: 1.25rem; background: #f5f3ff; color: #8b5cf6; flex-shrink: 0;">
              <i class="fas fa-award"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Filter Toolbar Card -->
    <div class="card border-0 shadow-sm mb-3" style="border-radius: 12px;">
      <div class="card-body p-3">
        <form method="GET" action="/admin/kejuruan/modul-praktik" class="row align-items-end">
          <div class="col-md-4 col-sm-6 mb-2 mb-md-0">
            <label class="font-weight-bold text-secondary mb-1" style="font-size: 0.78rem; text-transform: uppercase;">Cari Judul / Kode Modul</label>
            <input type="text" name="q" value="{{ request('q') }}" class="form-control form-control-sm" placeholder="Contoh: Routing OSPF...">
          </div>

          <div class="col-md-4 col-sm-6 mb-2 mb-md-0">
            <label class="font-weight-bold text-secondary mb-1" style="font-size: 0.78rem; text-transform: uppercase;">Filter Target CP / TP</label>
            <select name="kompetensi_id" class="form-control form-control-sm select2bs4">
              <option value="">-- Semua Target CP / TP --</option>
              @foreach($kompetensis as $k)
                <option value="{{ $k->id_kompetensi }}" {{ request('kompetensi_id') == $k->id_kompetensi ? 'selected' : '' }}>
                  [{{ $k->kode_kompetensi }}] {{ $k->nama_kompetensi }}
                </option>
              @endforeach
            </select>
          </div>

          <div class="col-md-4 col-sm-6 mb-2 mb-md-0">
            <label class="font-weight-bold text-secondary mb-1" style="font-size: 0.78rem; text-transform: uppercase;">Status Modul</label>
            <div class="d-flex align-items-center" style="gap: 8px;">
              <select name="status" class="form-control form-control-sm" style="flex: 1;">
                <option value="">-- Semua Status --</option>
                <option value="terbit" {{ request('status') === 'terbit' ? 'selected' : '' }}>Terbit</option>
                <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="arsip" {{ request('status') === 'arsip' ? 'selected' : '' }}>Arsip</option>
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
              <a href="/admin/kejuruan/modul-praktik/create" class="btn btn-primary btn-sm shadow-sm font-weight-bold">
                <i class="fas fa-plus mr-1"></i> Buat Modul Praktik Baru
              </a>
            @endif
          </div>

          <div>
            @if(request()->anyFilled(['q', 'kompetensi_id', 'status']))
              <a href="/admin/kejuruan/modul-praktik" class="btn btn-link text-muted btn-sm p-0">
                <i class="fas fa-undo mr-1"></i> Reset Filter
              </a>
            @endif
          </div>
        </div>
      </div>
    </div>

    <!-- Modul Grid Cards Header -->
    <div class="d-flex align-items-center justify-content-between mb-2">
      <h6 class="font-weight-bold text-dark mb-0">
        <i class="fas fa-list-ul text-primary mr-1"></i> Daftar Modul Praktik
      </h6>
      <span class="badge badge-light border text-muted px-2 py-1 font-weight-normal">
        Menampilkan {{ $moduls->count() }} dari {{ $moduls->total() }} Modul
      </span>
    </div>

    <!-- Modul Grid Cards -->
    <div class="row">
      @forelse($moduls as $m)
        <div class="col-md-6 col-lg-4 mb-3">
          <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; overflow: hidden; transition: transform 0.15s ease-in-out;">
            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
              <div>
                <span class="badge badge-light border text-primary font-weight-bold px-2 py-1" style="font-size: 0.75rem;">
                  {{ $m->kode_modul }}
                </span>
                <span class="badge badge-secondary ml-1 px-2 py-1 font-weight-bold" style="font-size: 0.72rem;">
                  v{{ $m->versi }}
                </span>
              </div>
              <div>
                @if($m->status === 'terbit')
                  <span class="badge badge-success px-2 py-1 font-weight-bold">
                    <i class="fas fa-check-circle mr-1"></i> Terbit
                  </span>
                @elseif($m->status === 'draft')
                  <span class="badge badge-warning text-dark px-2 py-1 font-weight-bold">
                    <i class="fas fa-pencil-alt mr-1"></i> Draft
                  </span>
                @else
                  <span class="badge badge-secondary px-2 py-1 font-weight-bold">
                    Arsip
                  </span>
                @endif
              </div>
            </div>

            <div class="card-body">
              <h5 class="font-weight-bold text-dark mb-2" style="font-size: 1.05rem; line-height: 1.3;">
                <a href="/admin/kejuruan/modul-praktik/{{ $m->id_modul_praktik }}" class="text-dark text-decoration-none hover-primary">
                  {{ $m->judul }}
                </a>
              </h5>

              <p class="text-muted small mb-3" style="line-height: 1.5; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                {{ $m->tujuan }}
              </p>

              <div class="p-2 rounded bg-light text-muted small mb-2">
                <i class="fas fa-award mr-1 text-primary"></i> 
                <strong>Target CP/TP:</strong>
                @php
                  $modulKompetensis = $m->kompetensis && $m->kompetensis->count() > 0 
                    ? $m->kompetensis 
                    : ($m->kompetensi ? collect([$m->kompetensi]) : collect());
                  $firstK = $modulKompetensis->first();
                  $moreCount = $modulKompetensis->count() - 1;
                @endphp
                @if($firstK)
                  <span>{{ $firstK->nama_kompetensi }}</span>
                  @if($firstK->jurusan)
                    <span class="badge badge-info ml-1">{{ $firstK->jurusan->kode_jurusan ?? $firstK->jurusan->nama_jurusan }}</span>
                  @endif
                  @if($moreCount > 0)
                    <span class="badge badge-primary ml-1" title="+{{ $moreCount }} Target CP/TP lainnya">+{{ $moreCount }} CP/TP</span>
                  @endif
                @else
                  <span>-</span>
                @endif
              </div>

              <div class="d-flex justify-content-between align-items-center text-muted small mt-2 pt-2 border-top">
                <span>
                  <i class="fas fa-user-edit mr-1"></i> {{ $m->guru->nama_guru ?? 'Guru Pengampu' }}
                </span>
                <span>
                  <i class="fas fa-list-check mr-1 text-secondary"></i> {{ $m->rubriks->count() }} Indikator
                </span>
              </div>
            </div>

            <div class="card-footer bg-white border-top py-2 d-flex justify-content-between align-items-center">
              <a href="/admin/kejuruan/modul-praktik/{{ $m->id_modul_praktik }}" class="btn btn-outline-info btn-sm px-3" style="border-radius: 6px;">
                <i class="fas fa-eye mr-1"></i> Detail
              </a>
              <div>
                @if($user->hasRole('admin') || $user->hasRole('kaprog') || $m->id_guru == $user->id_guru)
                  <a href="/admin/kejuruan/modul-praktik/{{ $m->id_modul_praktik }}/edit" class="btn btn-outline-primary btn-sm px-2 mr-1" style="border-radius: 6px;" title="Ubah Modul">
                    <i class="fas fa-edit"></i>
                  </a>
                  <form action="/admin/kejuruan/modul-praktik/{{ $m->id_modul_praktik }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus modul praktik ini beserta seluruh riwayatnya?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger btn-sm px-2" style="border-radius: 6px;" title="Hapus">
                      <i class="fas fa-trash-alt"></i>
                    </button>
                  </form>
                @endif
              </div>
            </div>
          </div>
        </div>
      @empty
        <div class="col-12">
          <div class="card border-0 shadow-sm text-center py-5" style="border-radius: 12px;">
            <div class="card-body">
              <i class="fas fa-book-open text-muted mb-3" style="font-size: 3rem; opacity: 0.5;"></i>
              <h5 class="font-weight-bold text-dark">Belum Ada Modul Praktik</h5>
              <p class="text-muted small mb-3">Mulai buat modul praktikum kejuruan pertama untuk mempermudah guru dan siswa.</p>
              @if($user->hasAnyRole(['admin', 'kaprog', 'guru_jurusan']))
                <a href="/admin/kejuruan/modul-praktik/create" class="btn btn-primary btn-sm px-4 shadow-sm" style="border-radius: 8px; font-weight: 600;">
                  <i class="fas fa-plus mr-1"></i> Buat Modul Baru
                </a>
              @endif
            </div>
          </div>
        </div>
      @endforelse
    </div>

    <!-- Pagination -->
    @if($moduls->hasPages())
      <div class="d-flex justify-content-center mt-3">
        <div class="pagination-sm">
          {{ $moduls->links() }}
        </div>
      </div>
    @endif

  </div>
</div>
@endsection
