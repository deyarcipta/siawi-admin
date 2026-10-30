@extends($layout)

@section('content')
<!-- Content Header -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row align-items-center mb-2">
      <div class="col-sm-7 d-flex align-items-center">
        <i class="fas fa-medal text-primary mr-3" style="font-size: 2rem; flex-shrink: 0;"></i>
        <div class="d-flex flex-column justify-content-center">
          <h1 class="m-0 font-weight-bold text-dark" style="line-height: 1.2;">Tanda Kecakapan Khusus (TKK)</h1>
          <p class="text-muted mt-1 mb-0" style="line-height: 1.2; font-size: 0.85rem;">Standarisasi lencana keahlian siswa berbasis pencapaian unit kompetensi kejuruan</p>
        </div>
      </div>
      <div class="col-sm-5">
        <ol class="breadcrumb float-sm-right bg-transparent p-0 mb-0">
          <li class="breadcrumb-item"><a href="/admin/dashboard" class="text-primary font-weight-500">Dashboard</a></li>
          <li class="breadcrumb-item"><a href="/admin/kejuruan/tkk" class="text-primary font-weight-500">Kejuruan</a></li>
          <li class="breadcrumb-item active">Master TKK</li>
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
      <!-- Total Jenis TKK -->
      <div class="col-lg-3 col-6 mb-3 mb-lg-0">
        <div class="card border-0 shadow-sm h-100 bg-white" style="border-radius: 12px; border-left: 4px solid #0284c7 !important;">
          <div class="card-body p-3 d-flex align-items-center justify-content-between">
            <div>
              <div class="text-muted text-uppercase font-weight-bold" style="font-size: 0.72rem; letter-spacing: 0.5px;">Master Jenis TKK</div>
              <div class="font-weight-bold text-dark mt-1" style="font-size: 1.7rem; line-height: 1.1;">{{ $totalJenisTkk }} <span style="font-size: 0.85rem; font-weight: 500; color: #64748b;">Lencana</span></div>
              <small class="text-muted mt-1 d-block"><i class="fas fa-award text-primary mr-1"></i> Skema Keahlian</small>
            </div>
            <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 46px; height: 46px; font-size: 1.25rem; background: #f0f9ff; color: #0284c7; flex-shrink: 0;">
              <i class="fas fa-certificate"></i>
            </div>
          </div>
        </div>
      </div>

      <!-- TKK Diberikan (Resmi) -->
      <div class="col-lg-3 col-6 mb-3 mb-lg-0">
        <div class="card border-0 shadow-sm h-100 bg-white" style="border-radius: 12px; border-left: 4px solid #16a34a !important;">
          <div class="card-body p-3 d-flex align-items-center justify-content-between">
            <div>
              <div class="text-muted text-uppercase font-weight-bold" style="font-size: 0.72rem; letter-spacing: 0.5px;">TKK Diberikan</div>
              <div class="font-weight-bold text-dark mt-1" style="font-size: 1.7rem; line-height: 1.1;">{{ $totalPenerima }} <span style="font-size: 0.85rem; font-weight: 500; color: #64748b;">Piagam</span></div>
              <small class="text-muted mt-1 d-block"><i class="fas fa-check-circle text-success mr-1"></i> Telah Disahkan</small>
            </div>
            <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 46px; height: 46px; font-size: 1.25rem; background: #dcfce7; color: #16a34a; flex-shrink: 0;">
              <i class="fas fa-medal"></i>
            </div>
          </div>
        </div>
      </div>

      <!-- Dalam Proses Syarat -->
      <div class="col-lg-3 col-6 mb-3 mb-lg-0">
        <div class="card border-0 shadow-sm h-100 bg-white" style="border-radius: 12px; border-left: 4px solid #f59e0b !important;">
          <div class="card-body p-3 d-flex align-items-center justify-content-between">
            <div>
              <div class="text-muted text-uppercase font-weight-bold" style="font-size: 0.72rem; letter-spacing: 0.5px;">Dalam Proses</div>
              <div class="font-weight-bold text-dark mt-1" style="font-size: 1.7rem; line-height: 1.1;">{{ $totalProses }} <span style="font-size: 0.85rem; font-weight: 500; color: #64748b;">Siswa</span></div>
              <small class="text-muted mt-1 d-block"><i class="fas fa-hourglass-half text-warning mr-1"></i> Memenuhi Sebagian Syarat</small>
            </div>
            <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 46px; height: 46px; font-size: 1.25rem; background: #fffbeb; color: #f59e0b; flex-shrink: 0;">
              <i class="fas fa-spinner"></i>
            </div>
          </div>
        </div>
      </div>

      <!-- Total Syarat Kompetensi -->
      <div class="col-lg-3 col-6 mb-3 mb-lg-0">
        <div class="card border-0 shadow-sm h-100 bg-white" style="border-radius: 12px; border-left: 4px solid #8b5cf6 !important;">
          <div class="card-body p-3 d-flex align-items-center justify-content-between">
            <div>
              <div class="text-muted text-uppercase font-weight-bold" style="font-size: 0.72rem; letter-spacing: 0.5px;">Syarat Kompetensi</div>
              <div class="font-weight-bold text-dark mt-1" style="font-size: 1.7rem; line-height: 1.1;">{{ $totalSyaratKomp }} <span style="font-size: 0.85rem; font-weight: 500; color: #64748b;">Tautan</span></div>
              <small class="text-muted mt-1 d-block"><i class="fas fa-link text-purple mr-1"></i> Standar Terhubung</small>
            </div>
            <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 46px; height: 46px; font-size: 1.25rem; background: #f5f3ff; color: #8b5cf6; flex-shrink: 0;">
              <i class="fas fa-layer-group"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Filter Toolbar Card -->
    <div class="card border-0 shadow-sm mb-3" style="border-radius: 12px;">
      <div class="card-body p-3">
        <form method="GET" action="/admin/kejuruan/tkk" class="row align-items-end">
          <div class="col-md-5 col-sm-6 mb-2 mb-md-0">
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
            <label class="font-weight-bold text-secondary mb-1" style="font-size: 0.78rem; text-transform: uppercase;">Tingkat Kemahiran</label>
            <div class="d-flex align-items-center" style="gap: 8px;">
              <select name="tingkat" class="form-control form-control-sm" style="flex: 1;">
                <option value="">-- Semua Tingkat --</option>
                <option value="dasar" {{ request('tingkat') === 'dasar' ? 'selected' : '' }}>Tingkat Dasar (Purwa)</option>
                <option value="menengah" {{ request('tingkat') === 'menengah' ? 'selected' : '' }}>Tingkat Menengah (Madya)</option>
                <option value="utama" {{ request('tingkat') === 'utama' ? 'selected' : '' }}>Tingkat Utama</option>
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
              <a href="/admin/kejuruan/tkk/create" class="btn btn-primary btn-sm shadow-sm font-weight-bold">
                <i class="fas fa-plus mr-1"></i> Tambah Jenis TKK Baru
              </a>
            @endif
            <a href="/admin/kejuruan/tkk/pemberian" class="btn btn-outline-primary btn-sm shadow-sm font-weight-bold">
              <i class="fas fa-user-check mr-1"></i> Matriks Kelayakan Siswa
            </a>
            <a href="/admin/kejuruan/tkk/rekap" class="btn btn-outline-info btn-sm shadow-sm font-weight-bold">
              <i class="fas fa-file-invoice mr-1"></i> Rekapitulasi TKK (Hubin)
            </a>
            @if($user->hasAnyRole(['admin', 'kaprog']))
              <a href="/admin/kejuruan/pengaturan-nomor" class="btn btn-outline-secondary btn-sm shadow-sm font-weight-bold">
                <i class="fas fa-barcode mr-1"></i> Format Nomor Piagam
              </a>
            @endif
          </div>

          <div>
            @if(request()->anyFilled(['id_jurusan', 'tingkat']))
              <a href="/admin/kejuruan/tkk" class="btn btn-link text-muted btn-sm p-0">
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
            <i class="fas fa-list-ul text-primary mr-2"></i> Daftar Tanda Kecakapan Khusus (TKK)
          </h3>
          <span class="badge badge-light border text-secondary ml-2 px-2 py-1" style="font-size: 0.75rem;">
            Total: {{ $jenisTkks->count() }} Jenis
          </span>
        </div>
      </div>

      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0" style="font-size: 0.88rem;">
            <thead class="bg-light text-secondary text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">
              <tr>
                <th style="width: 50px" class="text-center">No</th>
                <th style="min-width: 220px;">Nama TKK & Keahlian</th>
                <th style="width: 100px;" class="text-center">Jurusan</th>
                <th style="width: 120px;" class="text-center">Tingkat</th>
                <th style="min-width: 220px;">Syarat Unit Kompetensi</th>
                <th style="width: 100px;" class="text-center">Penerima</th>
                <th style="width: 130px;" class="text-center">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($jenisTkks as $t)
                <tr>
                  <td class="text-center align-middle font-weight-bold text-muted">{{ $loop->iteration }}</td>
                  <td class="align-middle">
                    @if($t->nomor_klaster || $t->kode_tkk)
                      <div class="d-flex align-items-center mb-1 flex-wrap" style="gap: 5px;">
                        @if($t->nomor_klaster)
                          <span class="badge badge-primary font-weight-bold" style="font-size: 0.72rem;">Klaster #{{ $t->nomor_klaster }}</span>
                        @endif
                        @if($t->kode_tkk)
                          <span class="badge badge-light border text-monospace text-muted" style="font-size: 0.72rem;">{{ $t->kode_tkk }}</span>
                        @endif
                      </div>
                    @endif
                    <div class="font-weight-bold text-dark d-flex align-items-center" style="font-size: 0.95rem;">
                      <i class="fas fa-medal text-warning mr-2" style="font-size: 0.95rem; flex-shrink: 0;"></i>
                      <span>{{ $t->nama_tkk }}</span>
                    </div>
                    <small class="text-muted d-block mt-1">{{ $t->deskripsi ?: 'Standar sertifikasi kompetensi keahlian kejuruan' }}</small>
                  </td>
                  <td class="text-center align-middle">
                    <span class="badge badge-info px-2 py-1 font-weight-bold" title="{{ $t->jurusan->nama_jurusan ?? '' }}" style="font-size: 0.82rem; cursor: help;">
                      {{ $t->jurusan->kode_jurusan ?? $t->jurusan->nama_jurusan ?? '-' }}
                    </span>
                  </td>
                  <td class="text-center align-middle">
                    @if($t->tingkat === 'utama')
                      <span class="badge badge-danger px-2 py-1 font-weight-bold">Tingkat Utama</span>
                    @elseif($t->tingkat === 'menengah')
                      <span class="badge badge-primary px-2 py-1 font-weight-bold">Tingkat Madya</span>
                    @else
                      <span class="badge badge-secondary px-2 py-1 font-weight-bold">Tingkat Dasar</span>
                    @endif
                  </td>
                  <td class="align-middle">
                    @if($t->syarats->isEmpty())
                      <span class="badge badge-light border text-info font-weight-bold px-2 py-1">
                        <i class="fas fa-book-reader mr-1"></i> Verifikasi Buku Saku
                      </span>
                      <small class="text-muted d-block mt-1">Buku saku fisik (langsung disahkan)</small>
                    @else
                      <span class="badge badge-light border text-primary font-weight-bold px-2 py-1 mb-1">
                        {{ $t->syarats->count() }} Unit Kompetensi
                      </span>
                      <div class="small text-muted" style="max-width: 250px;">
                        @foreach($t->syarats->take(2) as $s)
                          <div class="text-truncate" title="{{ $s->nama_kompetensi }}">• {{ $s->nama_kompetensi }}</div>
                        @endforeach
                        @if($t->syarats->count() > 2)
                          <span class="text-muted font-italic">+ {{ $t->syarats->count() - 2 }} unit lainnya</span>
                        @endif
                      </div>
                    @endif
                  </td>
                  <td class="text-center align-middle">
                    @php
                      $diberikanCount = $t->siswaTkks->where('status', 'diberikan')->count();
                      $prosesCount = $t->siswaTkks->where('status', 'proses')->count();
                    @endphp
                    <div class="d-flex flex-column align-items-center" style="gap: 4px;">
                      <span class="badge badge-success px-2 py-1 font-weight-bold" title="{{ $diberikanCount }} siswa resmi menerima piagam TKK">
                        <i class="fas fa-award mr-1"></i> {{ $diberikanCount }} Penerima
                      </span>
                      @if($prosesCount > 0)
                        <span class="badge badge-warning text-dark px-2 py-1 font-weight-bold" title="{{ $prosesCount }} siswa dalam proses pemenuhan unit kompetensi" style="font-size: 0.74rem;">
                          <i class="fas fa-clock mr-1"></i> {{ $prosesCount }} Proses
                        </span>
                      @endif
                    </div>
                  </td>
                  <td class="text-center align-middle">
                    <div class="btn-group" role="group">
                      <a href="/admin/kejuruan/tkk/pemberian?id_jenis_tkk={{ $t->id_jenis_tkk }}" class="btn btn-sm btn-outline-primary" title="Kelayakan & Sahkan Siswa">
                        <i class="fas fa-users-cog"></i>
                      </a>
                      @if($user->hasAnyRole(['admin', 'kaprog']))
                        <a href="/admin/kejuruan/tkk/{{ $t->id_jenis_tkk }}/edit" class="btn btn-sm btn-outline-warning" title="Ubah TKK">
                          <i class="fas fa-edit"></i>
                        </a>
                        <form action="/admin/kejuruan/tkk/{{ $t->id_jenis_tkk }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus Jenis TKK \'{{ $t->nama_tkk }}\'? Semua syarat unit kompetensi dan riwayat terkait akan dihapus.')">
                          @csrf
                          @method('DELETE')
                          <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus TKK">
                            <i class="fas fa-trash-alt"></i>
                          </button>
                        </form>
                      @endif
                    </div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="7" class="text-center py-5 text-muted">
                    <i class="fas fa-medal mb-2" style="font-size: 2.5rem; opacity: 0.4;"></i>
                    <h6 class="font-weight-bold text-dark">Belum Ada Master Jenis TKK</h6>
                    <p class="small mb-3">Mulai definisikan jenis Tanda Kecakapan Khusus (TKK) dan syarat unit kompetensi kelulusannya.</p>
                    @if($user->hasAnyRole(['admin', 'kaprog']))
                      <a href="/admin/kejuruan/tkk/create" class="btn btn-primary btn-sm px-3 shadow-sm font-weight-bold">
                        <i class="fas fa-plus mr-1"></i> Tambah Jenis TKK
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
