@extends($layout)
@section('content')
  <div class="content-header pb-2">
    <div class="container-fluid">
      <div class="row align-items-center mb-2">
        <div class="col-sm-7 d-flex align-items-center">
          <div class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle shadow-sm mr-3" style="width: 46px; height: 46px; font-size: 1.25rem;">
            <i class="fas fa-tools"></i>
          </div>
          <div>
            <h1 class="m-0 font-weight-bold text-dark" style="line-height: 1.2; font-size: 1.35rem;">Praktik Kejuruan</h1>
            <p class="text-muted mt-1 mb-0" style="font-size: 0.84rem;">Modul kegiatan praktik siswa, jobsheet kejuruan, dan pemakaian laboratorium</p>
          </div>
        </div>
        <div class="col-sm-5">
          <ol class="breadcrumb float-sm-right bg-transparent p-0 mb-0">
            <li class="breadcrumb-item"><a href="/admin/dashboard" class="text-primary font-weight-500">Dashboard</a></li>
            <li class="breadcrumb-item active">Praktik Kejuruan</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <div class="content">
    <div class="container-fluid">
      <div class="row">
        @forelse($jurusans as $j)
          <div class="col-md-6 col-lg-4 mb-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; overflow: hidden;">
              <div class="card-header bg-white py-3 border-bottom d-flex align-items-center">
                <span class="badge badge-primary px-2 py-1 mr-2 font-weight-bold">{{ $j->kode_jurusan }}</span>
                <h6 class="font-weight-bold text-dark mb-0 text-truncate" title="{{ $j->nama_jurusan }}">
                  {{ $j->nama_jurusan }}
                </h6>
              </div>
              <div class="card-body">
                <div class="d-flex justify-content-between mb-2 pb-2 border-bottom">
                  <span class="text-muted"><i class="fas fa-flask mr-1 text-secondary"></i> Laboratorium:</span>
                  <span class="font-weight-bold text-dark">{{ $j->labs->count() }} Ruangan</span>
                </div>
                <div class="d-flex justify-content-between mb-3 pb-2 border-bottom">
                  <span class="text-muted"><i class="fas fa-award mr-1 text-secondary"></i> Kompetensi:</span>
                  <span class="font-weight-bold text-dark">{{ $j->kompetensis->count() }} Capaian</span>
                </div>
                <div class="p-2 rounded bg-light text-muted small mb-0">
                  <i class="fas fa-info-circle mr-1 text-primary"></i> Modul ini siap digunakan untuk pencatatan jobsheet dan penilaian praktik kejuruan.
                </div>
              </div>
              <div class="card-footer bg-white border-top py-2 d-flex justify-content-between align-items-center">
                <a href="/admin/lab" class="btn btn-outline-primary btn-sm px-3" style="border-radius: 6px;">
                  <i class="fas fa-flask mr-1"></i> Data Lab
                </a>
                <a href="/admin/kompetensi" class="btn btn-primary btn-sm px-3" style="border-radius: 6px;">
                  <i class="fas fa-list-check mr-1"></i> Kompetensi
                </a>
              </div>
            </div>
          </div>
        @empty
          <div class="col-12">
            <div class="card border-0 shadow-sm text-center py-5" style="border-radius: 12px;">
              <div class="card-body">
                <i class="fas fa-tools text-muted mb-3" style="font-size: 3rem;"></i>
                <h5 class="font-weight-bold text-dark">Belum Ada Program Keahlian Terhubung</h5>
                <p class="text-muted small mb-0">Akun Anda belum terhubung dengan program keahlian manapun. Hubungi Admin untuk penugasan jurusan.</p>
              </div>
            </div>
          </div>
        @endforelse
      </div>
    </div>
  </div>
@endsection
