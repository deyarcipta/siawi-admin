@extends($layout)

@section('content')
<!-- Content Header -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row align-items-center mb-2">
      <div class="col-sm-7 d-flex align-items-center">
        <i class="fas fa-flask text-primary mr-3" style="font-size: 2rem; flex-shrink: 0;"></i>
        <div class="d-flex flex-column justify-content-center">
          <h1 class="m-0 font-weight-bold text-dark" style="line-height: 1.2;">Data Ruang Laboratorium</h1>
          <p class="text-muted mt-1 mb-0" style="line-height: 1.2; font-size: 0.85rem;">Daftar ruang praktik kejuruan, bengkel kerja, dan kapasitas laboratorium</p>
        </div>
      </div>
      <div class="col-sm-5">
        <ol class="breadcrumb float-sm-right bg-transparent p-0 mb-0">
          <li class="breadcrumb-item"><a href="/admin/dashboard" class="text-primary font-weight-500">Dashboard</a></li>
          <li class="breadcrumb-item"><a href="/admin/lab" class="text-primary font-weight-500">Kejuruan</a></li>
          <li class="breadcrumb-item active">Data Ruang Lab</li>
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
      $countTotal = $labs->count();
      $totalKapasitas = $labs->sum('kapasitas');
      $countKhusus = $labs->whereNotNull('id_jurusan')->count();
      $countUmum = $labs->whereNull('id_jurusan')->count();
    @endphp

    <!-- Summary Stat Cards -->
    <div class="row mb-3">
      <!-- Total Lab -->
      <div class="col-lg-3 col-6 mb-3 mb-lg-0">
        <div class="card border-0 shadow-sm h-100 bg-white" style="border-radius: 12px; border-left: 4px solid #0284c7 !important;">
          <div class="card-body p-3 d-flex align-items-center justify-content-between">
            <div>
              <div class="text-muted text-uppercase font-weight-bold" style="font-size: 0.72rem; letter-spacing: 0.5px;">Total Ruang Lab</div>
              <div class="font-weight-bold text-dark mt-1" style="font-size: 1.7rem; line-height: 1.1;">{{ $countTotal }} <span style="font-size: 0.85rem; font-weight: 500; color: #64748b;">Ruang</span></div>
              <small class="text-muted mt-1 d-block"><i class="fas fa-flask text-primary mr-1"></i> Laboratorium & Bengkel</small>
            </div>
            <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 46px; height: 46px; font-size: 1.25rem; background: #f0f9ff; color: #0284c7; flex-shrink: 0;">
              <i class="fas fa-flask"></i>
            </div>
          </div>
        </div>
      </div>

      <!-- Daya Tampung Siswa -->
      <div class="col-lg-3 col-6 mb-3 mb-lg-0">
        <div class="card border-0 shadow-sm h-100 bg-white" style="border-radius: 12px; border-left: 4px solid #16a34a !important;">
          <div class="card-body p-3 d-flex align-items-center justify-content-between">
            <div>
              <div class="text-muted text-uppercase font-weight-bold" style="font-size: 0.72rem; letter-spacing: 0.5px;">Total Kapasitas</div>
              <div class="font-weight-bold text-dark mt-1" style="font-size: 1.7rem; line-height: 1.1;">{{ $totalKapasitas }} <span style="font-size: 0.85rem; font-weight: 500; color: #64748b;">Siswa</span></div>
              <small class="text-muted mt-1 d-block"><i class="fas fa-users text-success mr-1"></i> Daya Tampung Serentak</small>
            </div>
            <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 46px; height: 46px; font-size: 1.25rem; background: #dcfce7; color: #16a34a; flex-shrink: 0;">
              <i class="fas fa-users"></i>
            </div>
          </div>
        </div>
      </div>

      <!-- Lab Khusus Kejuruan -->
      <div class="col-lg-3 col-6 mb-3 mb-lg-0">
        <div class="card border-0 shadow-sm h-100 bg-white" style="border-radius: 12px; border-left: 4px solid #f59e0b !important;">
          <div class="card-body p-3 d-flex align-items-center justify-content-between">
            <div>
              <div class="text-muted text-uppercase font-weight-bold" style="font-size: 0.72rem; letter-spacing: 0.5px;">Lab Khusus Jurusan</div>
              <div class="font-weight-bold text-dark mt-1" style="font-size: 1.7rem; line-height: 1.1;">{{ $countKhusus }} <span style="font-size: 0.85rem; font-weight: 500; color: #64748b;">Ruang</span></div>
              <small class="text-muted mt-1 d-block"><i class="fas fa-tag text-warning mr-1"></i> Terikat Program Keahlian</small>
            </div>
            <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 46px; height: 46px; font-size: 1.25rem; background: #fffbeb; color: #f59e0b; flex-shrink: 0;">
              <i class="fas fa-tag"></i>
            </div>
          </div>
        </div>
      </div>

      <!-- Lab Umum / Bersama -->
      <div class="col-lg-3 col-6 mb-3 mb-lg-0">
        <div class="card border-0 shadow-sm h-100 bg-white" style="border-radius: 12px; border-left: 4px solid #8b5cf6 !important;">
          <div class="card-body p-3 d-flex align-items-center justify-content-between">
            <div>
              <div class="text-muted text-uppercase font-weight-bold" style="font-size: 0.72rem; letter-spacing: 0.5px;">Lab Bersama / Umum</div>
              <div class="font-weight-bold text-dark mt-1" style="font-size: 1.7rem; line-height: 1.1;">{{ $countUmum }} <span style="font-size: 0.85rem; font-weight: 500; color: #64748b;">Ruang</span></div>
              <small class="text-muted mt-1 d-block"><i class="fas fa-network-wired text-purple mr-1"></i> Penggunaan Fleksibel</small>
            </div>
            <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 46px; height: 46px; font-size: 1.25rem; background: #f5f3ff; color: #8b5cf6; flex-shrink: 0;">
              <i class="fas fa-warehouse"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Filter Toolbar Card -->
    <div class="card border-0 shadow-sm mb-3" style="border-radius: 12px;">
      <div class="card-body p-3">
        <div class="d-flex align-items-center justify-content-between flex-wrap" style="gap: 8px;">
          <div class="d-flex align-items-center flex-wrap" style="gap: 8px;">
            <a href="/admin/lab/create" class="btn btn-primary btn-sm shadow-sm font-weight-bold">
              <i class="fas fa-plus mr-1"></i> Tambah Ruang Lab Baru
            </a>
            <a href="/admin/kejuruan/inventaris-lab" class="btn btn-outline-secondary btn-sm shadow-sm">
              <i class="fas fa-boxes mr-1"></i> Inventaris & Bahan Lab
            </a>
          </div>
          <div class="text-muted small">
            <i class="fas fa-info-circle mr-1 text-primary"></i> Ruang lab menjadi lokasi pelaksanaan jadwal praktik & penempatan aset laboratorium
          </div>
        </div>
      </div>
    </div>

    <!-- Data Table Card -->
    <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
      <div class="card-header bg-white py-3 border-0 d-flex align-items-center justify-content-between flex-wrap">
        <div>
          <h3 class="card-title text-dark font-weight-bold mb-0" style="font-size: 1rem;">
            <i class="fas fa-list-ul text-primary mr-2"></i> Daftar Laboratorium & Bengkel Praktik
          </h3>
          <span class="badge badge-light border text-secondary ml-2 px-2 py-1" style="font-size: 0.75rem;">
            Total: {{ $labs->count() }} Ruang
          </span>
        </div>
      </div>

      <div class="card-body p-0">
        <div class="table-responsive">
          <table id="example2" class="table table-hover align-middle mb-0" style="font-size: 0.88rem;">
            <thead class="bg-light text-secondary text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">
              <tr>
                <th style="width: 50px" class="text-center">No</th>
                <th style="width: 130px">Kode Lab</th>
                <th>Nama Laboratorium / Bengkel</th>
                <th>Program Keahlian</th>
                <th style="width: 120px" class="text-center">Kapasitas</th>
                <th>Lokasi Ruangan</th>
                <th style="width: 120px" class="text-center">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($labs as $lab)
                <tr>
                  <td class="text-center align-middle font-weight-bold text-muted">{{ $loop->iteration }}</td>
                  <td class="align-middle">
                    @if($lab->kode_lab)
                      <span class="badge badge-light border text-primary px-2 py-1 font-weight-bold" style="font-size: 0.82rem;">
                        {{ $lab->kode_lab }}
                      </span>
                    @else
                      <span class="text-muted font-italic">-</span>
                    @endif
                  </td>
                  <td class="align-middle font-weight-600 text-dark">{{ $lab->nama_lab }}</td>
                  <td class="align-middle">
                    @if($lab->jurusan)
                      <span class="badge badge-info px-2 py-1">
                        {{ $lab->jurusan->nama_jurusan }}
                      </span>
                    @else
                      <span class="badge badge-secondary px-2 py-1">Umum</span>
                    @endif
                  </td>
                  <td class="text-center align-middle">
                    <span class="font-weight-500">{{ $lab->kapasitas ? $lab->kapasitas . ' Siswa' : '-' }}</span>
                  </td>
                  <td class="align-middle text-muted">{{ $lab->lokasi ?: '-' }}</td>
                  <td class="text-center align-middle">
                    <div class="btn-group" role="group">
                      <a href="/admin/lab/{{ $lab->id_lab }}/edit" class="btn btn-sm btn-outline-primary" title="Ubah Data">
                        <i class="fas fa-edit"></i>
                      </a>
                      <form action="/admin/lab/{{ $lab->id_lab }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus laboratorium ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus Data">
                          <i class="fas fa-trash-alt"></i>
                        </button>
                      </form>
                    </div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="7" class="text-center py-4 text-muted">
                    <i class="fas fa-info-circle mr-1"></i> Belum ada data laboratorium untuk program keahlian Anda.
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
