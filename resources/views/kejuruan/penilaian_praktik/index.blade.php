@extends($layout)

@section('content')
<!-- Content Header -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row align-items-center mb-2">
      <div class="col-sm-7 d-flex align-items-center">
        <i class="fas fa-clipboard-check text-primary mr-3" style="font-size: 2rem; flex-shrink: 0;"></i>
        <div class="d-flex flex-column justify-content-center">
          <h1 class="m-0 font-weight-bold text-dark" style="line-height: 1.2;">Penilaian Praktik Kejuruan</h1>
          <p class="text-muted mt-1 mb-0" style="line-height: 1.2; font-size: 0.85rem;">Penilaian kompetensi siswa satu kelas sekaligus, evaluasi KKM, dan remedial</p>
        </div>
      </div>
      <div class="col-sm-5">
        <ol class="breadcrumb float-sm-right bg-transparent p-0 mb-0">
          <li class="breadcrumb-item"><a href="/admin/dashboard" class="text-primary font-weight-500">Dashboard</a></li>
          <li class="breadcrumb-item"><a href="/admin/kejuruan/penilaian-praktik" class="text-primary font-weight-500">Kejuruan</a></li>
          <li class="breadcrumb-item active">Penilaian Praktik</li>
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
      $countTotalSesi = $penilaians->count();
      $countFinal = \App\Models\PenilaianPraktik::forUserJurusan($user)->where('status', 'final')->count();
      $countDraft = \App\Models\PenilaianPraktik::forUserJurusan($user)->where('status', 'draft')->count();
      $countKomp = $kompetensis->count();
    @endphp

    <!-- Summary Stat Cards -->
    <div class="row mb-3">
      <!-- Total Sesi -->
      <div class="col-lg-3 col-6 mb-3 mb-lg-0">
        <div class="card border-0 shadow-sm h-100 bg-white" style="border-radius: 12px; border-left: 4px solid #0284c7 !important;">
          <div class="card-body p-3 d-flex align-items-center justify-content-between">
            <div>
              <div class="text-muted text-uppercase font-weight-bold" style="font-size: 0.72rem; letter-spacing: 0.5px;">Total Sesi Penilaian</div>
              <div class="font-weight-bold text-dark mt-1" style="font-size: 1.7rem; line-height: 1.1;">{{ $countTotalSesi }} <span style="font-size: 0.85rem; font-weight: 500; color: #64748b;">Sesi</span></div>
              <small class="text-muted mt-1 d-block"><i class="fas fa-calendar-alt text-primary mr-1"></i> Penilaian Praktik</small>
            </div>
            <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 46px; height: 46px; font-size: 1.25rem; background: #f0f9ff; color: #0284c7; flex-shrink: 0;">
              <i class="fas fa-tasks"></i>
            </div>
          </div>
        </div>
      </div>

      <!-- Sudah Final -->
      <div class="col-lg-3 col-6 mb-3 mb-lg-0">
        <div class="card border-0 shadow-sm h-100 bg-white" style="border-radius: 12px; border-left: 4px solid #16a34a !important;">
          <div class="card-body p-3 d-flex align-items-center justify-content-between">
            <div>
              <div class="text-muted text-uppercase font-weight-bold" style="font-size: 0.72rem; letter-spacing: 0.5px;">Nilai Final (Rilis)</div>
              <div class="font-weight-bold text-dark mt-1" style="font-size: 1.7rem; line-height: 1.1;">{{ $countFinal }} <span style="font-size: 0.85rem; font-weight: 500; color: #64748b;">Sesi</span></div>
              <small class="text-muted mt-1 d-block"><i class="fas fa-check-circle text-success mr-1"></i> Tercatat ke Buku Induk</small>
            </div>
            <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 46px; height: 46px; font-size: 1.25rem; background: #dcfce7; color: #16a34a; flex-shrink: 0;">
              <i class="fas fa-check-double"></i>
            </div>
          </div>
        </div>
      </div>

      <!-- Draft Nilai -->
      <div class="col-lg-3 col-6 mb-3 mb-lg-0">
        <div class="card border-0 shadow-sm h-100 bg-white" style="border-radius: 12px; border-left: 4px solid #f59e0b !important;">
          <div class="card-body p-3 d-flex align-items-center justify-content-between">
            <div>
              <div class="text-muted text-uppercase font-weight-bold" style="font-size: 0.72rem; letter-spacing: 0.5px;">Status Draft</div>
              <div class="font-weight-bold text-dark mt-1" style="font-size: 1.7rem; line-height: 1.1;">{{ $countDraft }} <span style="font-size: 0.85rem; font-weight: 500; color: #64748b;">Sesi</span></div>
              <small class="text-muted mt-1 d-block"><i class="fas fa-pencil-alt text-warning mr-1"></i> Sedang Diinput</small>
            </div>
            <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 46px; height: 46px; font-size: 1.25rem; background: #fffbeb; color: #f59e0b; flex-shrink: 0;">
              <i class="fas fa-edit"></i>
            </div>
          </div>
        </div>
      </div>

      <!-- Kompetensi Diuji -->
      <div class="col-lg-3 col-6 mb-3 mb-lg-0">
        <div class="card border-0 shadow-sm h-100 bg-white" style="border-radius: 12px; border-left: 4px solid #8b5cf6 !important;">
          <div class="card-body p-3 d-flex align-items-center justify-content-between">
            <div>
              <div class="text-muted text-uppercase font-weight-bold" style="font-size: 0.72rem; letter-spacing: 0.5px;">Kompetensi Terdaftar</div>
              <div class="font-weight-bold text-dark mt-1" style="font-size: 1.7rem; line-height: 1.1;">{{ $countKomp }} <span style="font-size: 0.85rem; font-weight: 500; color: #64748b;">Unit</span></div>
              <small class="text-muted mt-1 d-block"><i class="fas fa-award text-purple mr-1"></i> Skema Penilaian</small>
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
        <form method="GET" action="/admin/kejuruan/penilaian-praktik" class="row align-items-end">
          <div class="col-md-4 col-sm-6 mb-2 mb-md-0">
            <label class="font-weight-bold text-secondary mb-1" style="font-size: 0.78rem; text-transform: uppercase;">Filter Kelas</label>
            <select name="kelas_id" class="form-control form-control-sm select2bs4">
              <option value="">-- Semua Kelas --</option>
              @foreach($kelas as $kls)
                <option value="{{ $kls->id_kelas }}" {{ request('kelas_id') == $kls->id_kelas ? 'selected' : '' }}>
                  {{ $kls->nama_kelas }}
                </option>
              @endforeach
            </select>
          </div>

          <div class="col-md-4 col-sm-6 mb-2 mb-md-0">
            <label class="font-weight-bold text-secondary mb-1" style="font-size: 0.78rem; text-transform: uppercase;">Filter Kompetensi</label>
            <select name="kompetensi_id" class="form-control form-control-sm select2bs4">
              <option value="">-- Semua Kompetensi --</option>
              @foreach($kompetensis as $k)
                <option value="{{ $k->id_kompetensi }}" {{ request('kompetensi_id') == $k->id_kompetensi ? 'selected' : '' }}>
                  [{{ $k->kode_kompetensi }}] {{ $k->nama_kompetensi }}
                </option>
              @endforeach
            </select>
          </div>

          <div class="col-md-4 col-sm-6 mb-2 mb-md-0">
            <label class="font-weight-bold text-secondary mb-1" style="font-size: 0.78rem; text-transform: uppercase;">Status Nilai</label>
            <div class="d-flex align-items-center" style="gap: 8px;">
              <select name="status" class="form-control form-control-sm" style="flex: 1;">
                <option value="">-- Semua Status --</option>
                <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft (Belum Rilis)</option>
                <option value="final" {{ request('status') === 'final' ? 'selected' : '' }}>Final (Sudah Rilis)</option>
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
            @if($user->hasAnyRole(['admin', 'kaprog', 'guru_jurusan', 'guru']))
              <a href="/admin/kejuruan/penilaian-praktik/create" class="btn btn-primary btn-sm shadow-sm font-weight-bold">
                <i class="fas fa-plus mr-1"></i> Mulai Penilaian Kelas
              </a>
              <a href="/admin/kejuruan/capaian-siswa" class="btn btn-outline-info btn-sm shadow-sm font-weight-bold">
                <i class="fas fa-medal mr-1"></i> Buku Induk Capaian Siswa
              </a>
            @endif
          </div>

          <div>
            @if(request()->anyFilled(['kelas_id', 'kompetensi_id', 'status']))
              <a href="/admin/kejuruan/penilaian-praktik" class="btn btn-link text-muted btn-sm p-0">
                <i class="fas fa-undo mr-1"></i> Reset Filter
              </a>
            @endif
          </div>
        </div>
      </div>
    </div>

    <!-- Tabel Daftar Sesi Penilaian -->
    <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
      <div class="card-header bg-white py-3 border-0 d-flex align-items-center justify-content-between flex-wrap">
        <div>
          <h3 class="card-title text-dark font-weight-bold mb-0" style="font-size: 1rem;">
            <i class="fas fa-list-ul text-primary mr-2"></i> Daftar Sesi Penilaian Praktik
          </h3>
          <span class="badge badge-light border text-secondary ml-2 px-2 py-1" style="font-size: 0.75rem;">
            Total: {{ $penilaians->count() }} Sesi
          </span>
        </div>
      </div>

      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0" style="font-size: 0.88rem;">
            <thead class="bg-light text-secondary text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">
              <tr>
                <th style="width: 50px; text-align: center;">No</th>
                <th style="width: 120px;">Tanggal</th>
                <th>Kelas</th>
                <th>Modul & Kompetensi</th>
                <th>Guru Penilai</th>
                <th style="width: 100px; text-align: center;">Siswa</th>
                <th style="width: 120px; text-align: center;">Status</th>
                <th style="width: 180px; text-align: center;">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($penilaians as $p)
                <tr>
                  <td class="text-center font-weight-bold text-muted">{{ $loop->iteration }}</td>
                  <td>
                    <span class="font-weight-600 text-dark">
                      {{ \Carbon\Carbon::parse($p->tanggal_penilaian)->translatedFormat('d M Y') }}
                    </span>
                  </td>
                  <td>
                    <span class="badge badge-light border text-primary font-weight-bold px-2 py-1" style="font-size: 0.85rem;">
                      {{ $p->kelas->nama_kelas ?? '-' }}
                    </span>
                  </td>
                  <td>
                    <div class="font-weight-bold text-dark" style="font-size: 0.95rem;">
                      {{ $p->modul->judul ?? '-' }}
                    </div>
                    <small class="text-muted">
                      [{{ $p->kompetensi->kode_kompetensi ?? '-' }}] {{ $p->kompetensi->nama_kompetensi ?? '-' }} (KKM: {{ floatval($p->kkm_saat_penilaian) }})
                    </small>
                  </td>
                  <td>
                    <span class="text-dark small font-weight-500">
                      {{ $p->guru->nama_guru ?? 'Guru Penilai' }}
                    </span>
                  </td>
                  <td class="text-center">
                    <span class="badge badge-info px-2 py-1 font-weight-bold">
                      {{ $p->nilai_siswas_count }} Siswa
                    </span>
                  </td>
                  <td class="text-center">
                    @if($p->status === 'final')
                      <span class="badge badge-success px-2 py-1 font-weight-bold">
                        <i class="fas fa-check-double mr-1"></i> Final (Rilis)
                      </span>
                    @else
                      <span class="badge badge-warning text-dark px-2 py-1 font-weight-bold">
                        <i class="fas fa-pencil-alt mr-1"></i> Draft
                      </span>
                    @endif
                  </td>
                  <td class="text-center">
                    <a href="/admin/kejuruan/penilaian-praktik/{{ $p->id_penilaian_praktik }}" class="btn btn-sm btn-outline-info mr-1" title="Lihat Rekap Hasil">
                      <i class="fas fa-eye"></i>
                    </a>
                    <a href="/admin/kejuruan/penilaian-praktik/{{ $p->id_penilaian_praktik }}/input" class="btn btn-sm btn-primary mr-1" title="Buka Matriks Input Nilai">
                      <i class="fas fa-edit mr-1"></i> Input
                    </a>
                    @if($user->hasRole('admin') || $user->hasRole('kaprog') || $p->id_guru == $user->id_guru)
                      <form action="/admin/kejuruan/penilaian-praktik/{{ $p->id_penilaian_praktik }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus sesi penilaian ini beserta seluruh nilai siswa?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus Sesi">
                          <i class="fas fa-trash-alt"></i>
                        </button>
                      </form>
                    @endif
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="8" class="text-center py-5">
                    <i class="fas fa-clipboard-list text-muted mb-2" style="font-size: 2.5rem; opacity: 0.5;"></i>
                    <h6 class="font-weight-bold text-dark">Belum Ada Sesi Penilaian Praktik</h6>
                    <p class="text-muted small mb-3">Mulai buat sesi penilaian untuk menilai hasil praktik siswa satu kelas sekaligus.</p>
                    @if($user->hasAnyRole(['admin', 'kaprog', 'guru_jurusan', 'guru']))
                      <a href="/admin/kejuruan/penilaian-praktik/create" class="btn btn-primary btn-sm px-3 shadow-sm" style="border-radius: 6px; font-weight: 600;">
                        <i class="fas fa-plus mr-1"></i> Mulai Penilaian Kelas
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
