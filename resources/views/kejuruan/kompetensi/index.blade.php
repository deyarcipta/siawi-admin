@extends($layout)

@section('content')
<!-- Content Header -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row align-items-center mb-2">
      <div class="col-sm-7 d-flex align-items-center">
        <i class="fas fa-award text-primary mr-3" style="font-size: 2rem; flex-shrink: 0;"></i>
        <div class="d-flex flex-column justify-content-center">
          <h1 class="m-0 font-weight-bold text-dark" style="line-height: 1.2;">Capaian & Tujuan Pembelajaran (CP / TP)</h1>
          <p class="text-muted mt-1 mb-0" style="line-height: 1.2; font-size: 0.85rem;">Standar Capaian Pembelajaran Kejuruan & Alur Tujuan Pembelajaran per Fase (Kurikulum Merdeka)</p>
        </div>
      </div>
      <div class="col-sm-5">
        <ol class="breadcrumb float-sm-right bg-transparent p-0 mb-0">
          <li class="breadcrumb-item"><a href="/admin/dashboard" class="text-primary font-weight-500">Dashboard</a></li>
          <li class="breadcrumb-item"><a href="/admin/kompetensi" class="text-primary font-weight-500">Kejuruan</a></li>
          <li class="breadcrumb-item active">Capaian & Tujuan (CP/TP)</li>
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
      $countTotal = $kompetensis->count();
      $countX = $kompetensis->where('tingkat', '10')->count() + $kompetensis->where('tingkat', 'X')->count();
      $countXI = $kompetensis->where('tingkat', '11')->count() + $kompetensis->where('tingkat', 'XI')->count();
      $countXII = $kompetensis->where('tingkat', '12')->count() + $kompetensis->where('tingkat', 'XII')->count();
    @endphp

    <!-- Summary Stat Cards -->
    <div class="row mb-3">
      <!-- Total CP / TP -->
      <div class="col-lg-3 col-6 mb-3 mb-lg-0">
        <div class="card border-0 shadow-sm h-100 bg-white" style="border-radius: 12px; border-left: 4px solid #0284c7 !important;">
          <div class="card-body p-3 d-flex align-items-center justify-content-between">
            <div>
              <div class="text-muted text-uppercase font-weight-bold" style="font-size: 0.72rem; letter-spacing: 0.5px;">Total CP & TP</div>
              <div class="font-weight-bold text-dark mt-1" style="font-size: 1.7rem; line-height: 1.1;">{{ $countTotal }} <span style="font-size: 0.85rem; font-weight: 500; color: #64748b;">Unit</span></div>
              <small class="text-muted mt-1 d-block"><i class="fas fa-award text-primary mr-1"></i> Kurikulum Merdeka</small>
            </div>
            <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 46px; height: 46px; font-size: 1.25rem; background: #f0f9ff; color: #0284c7; flex-shrink: 0;">
              <i class="fas fa-award"></i>
            </div>
          </div>
        </div>
      </div>

      <!-- Fase E (Kelas X) -->
      <div class="col-lg-3 col-6 mb-3 mb-lg-0">
        <div class="card border-0 shadow-sm h-100 bg-white" style="border-radius: 12px; border-left: 4px solid #16a34a !important;">
          <div class="card-body p-3 d-flex align-items-center justify-content-between">
            <div>
              <div class="text-muted text-uppercase font-weight-bold" style="font-size: 0.72rem; letter-spacing: 0.5px;">Fase E (Kelas X)</div>
              <div class="font-weight-bold text-dark mt-1" style="font-size: 1.7rem; line-height: 1.1;">{{ $countX }} <span style="font-size: 0.85rem; font-weight: 500; color: #64748b;">Unit</span></div>
              <small class="text-muted mt-1 d-block"><i class="fas fa-layer-group text-success mr-1"></i> Dasar Kejuruan</small>
            </div>
            <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 46px; height: 46px; font-size: 1.25rem; background: #dcfce7; color: #16a34a; flex-shrink: 0;">
              <i class="fas fa-book-open"></i>
            </div>
          </div>
        </div>
      </div>

      <!-- Fase F (Kelas XI) -->
      <div class="col-lg-3 col-6 mb-3 mb-lg-0">
        <div class="card border-0 shadow-sm h-100 bg-white" style="border-radius: 12px; border-left: 4px solid #f59e0b !important;">
          <div class="card-body p-3 d-flex align-items-center justify-content-between">
            <div>
              <div class="text-muted text-uppercase font-weight-bold" style="font-size: 0.72rem; letter-spacing: 0.5px;">Fase F (Kelas XI)</div>
              <div class="font-weight-bold text-dark mt-1" style="font-size: 1.7rem; line-height: 1.1;">{{ $countXI }} <span style="font-size: 0.85rem; font-weight: 500; color: #64748b;">Unit</span></div>
              <small class="text-muted mt-1 d-block"><i class="fas fa-cogs text-warning mr-1"></i> Konsentrasi Keahlian</small>
            </div>
            <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 46px; height: 46px; font-size: 1.25rem; background: #fffbeb; color: #f59e0b; flex-shrink: 0;">
              <i class="fas fa-cogs"></i>
            </div>
          </div>
        </div>
      </div>

      <!-- Fase F (Kelas XII) -->
      <div class="col-lg-3 col-6 mb-3 mb-lg-0">
        <div class="card border-0 shadow-sm h-100 bg-white" style="border-radius: 12px; border-left: 4px solid #8b5cf6 !important;">
          <div class="card-body p-3 d-flex align-items-center justify-content-between">
            <div>
              <div class="text-muted text-uppercase font-weight-bold" style="font-size: 0.72rem; letter-spacing: 0.5px;">Fase F (Kelas XII)</div>
              <div class="font-weight-bold text-dark mt-1" style="font-size: 1.7rem; line-height: 1.1;">{{ $countXII }} <span style="font-size: 0.85rem; font-weight: 500; color: #64748b;">Unit</span></div>
              <small class="text-muted mt-1 d-block"><i class="fas fa-user-graduate text-purple mr-1"></i> Proyek & Sertifikasi</small>
            </div>
            <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 46px; height: 46px; font-size: 1.25rem; background: #f5f3ff; color: #8b5cf6; flex-shrink: 0;">
              <i class="fas fa-user-graduate"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Filter Toolbar Card -->
    <div class="card border-0 shadow-sm mb-3" style="border-radius: 12px;">
      <div class="card-body p-3">
        <!-- Lower Action Toolbar -->
        <div class="d-flex align-items-center justify-content-between flex-wrap" style="gap: 8px;">
          <div class="d-flex align-items-center flex-wrap" style="gap: 8px;">
            <a href="/admin/kompetensi/create" class="btn btn-primary btn-sm shadow-sm font-weight-bold">
              <i class="fas fa-plus mr-1"></i> Tambah Capaian / TP Baru
            </a>
          </div>
          <div class="text-muted small">
            <i class="fas fa-info-circle mr-1 text-primary"></i> Capaian & Tujuan Pembelajaran terintegrasi dengan Bank Modul dan Penilaian Praktik
          </div>
        </div>
      </div>
    </div>

    <!-- Data Table Card -->
    <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
      <div class="card-header bg-white py-3 border-0 d-flex align-items-center justify-content-between flex-wrap">
        <div>
          <h3 class="card-title text-dark font-weight-bold mb-0" style="font-size: 1rem;">
            <i class="fas fa-list-ul text-primary mr-2"></i> Daftar Capaian & Tujuan Pembelajaran (CP / TP)
          </h3>
          <span class="badge badge-light border text-secondary ml-2 px-2 py-1" style="font-size: 0.75rem;">
            Total: {{ $kompetensis->count() }} Data
          </span>
        </div>
      </div>

      <div class="card-body p-0">
        <div class="table-responsive">
          <table id="example2" class="table table-hover align-middle mb-0" style="font-size: 0.88rem;">
            <thead class="bg-light text-secondary text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">
              <tr>
                <th style="width: 45px" class="text-center">No</th>
                <th style="width: 130px">Kode CP/TP</th>
                <th>Capaian / Tujuan Pembelajaran</th>
                <th style="width: 130px" class="text-center">Program Keahlian</th>
                <th style="width: 130px" class="text-center">Fase / Kelas</th>
                <th style="width: 100px" class="text-center">Semester</th>
                <th style="width: 100px" class="text-center">Batas KKTP</th>
                <th style="width: 110px" class="text-center">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($kompetensis as $k)
                <tr>
                  <td class="text-center align-middle font-weight-bold text-muted">{{ $loop->iteration }}</td>
                  <td class="align-middle">
                    <span class="badge badge-light border text-primary px-2 py-1 font-weight-bold" style="font-size: 0.82rem;">
                      {{ $k->kode_kompetensi }}
                    </span>
                  </td>
                  <td class="align-middle font-weight-600 text-dark">{{ $k->nama_kompetensi }}</td>
                  <td class="text-center align-middle">
                    <span class="badge badge-info px-2 py-1 font-weight-bold" title="{{ $k->jurusan->nama_jurusan ?? '-' }}" style="font-size: 0.82rem; cursor: help;">
                      {{ $k->jurusan->nama_singkat ?? '-' }}
                    </span>
                  </td>
                  <td class="text-center align-middle">
                    @if(in_array($k->tingkat, ['X', '10']))
                      <span class="badge badge-success px-2 py-1" title="Fase E (Dasar Kejuruan)">Fase E (X)</span>
                    @elseif(in_array($k->tingkat, ['XI', '11']))
                      <span class="badge badge-warning text-dark px-2 py-1" title="Fase F (Konsentrasi Keahlian)">Fase F (XI)</span>
                    @elseif(in_array($k->tingkat, ['XII', '12']))
                      <span class="badge text-white px-2 py-1" style="background: #8b5cf6;" title="Fase F (Proyek & UKK)">Fase F (XII)</span>
                    @else
                      <span class="badge badge-secondary px-2 py-1">{{ $k->tingkat }}</span>
                    @endif
                  </td>
                  <td class="text-center align-middle">
                    <span class="badge badge-light border px-2 py-1">Sem. {{ $k->semester }}</span>
                  </td>
                  <td class="text-center align-middle">
                    <span class="badge badge-light border font-weight-bold text-primary px-2 py-1">
                      {{ floatval($k->kkm ?? 75) }}
                    </span>
                  </td>
                  <td class="text-center align-middle">
                    <div class="btn-group" role="group">
                      <a href="/admin/kompetensi/{{ $k->id_kompetensi }}/edit" class="btn btn-sm btn-outline-primary" title="Ubah Data">
                        <i class="fas fa-edit"></i>
                      </a>
                      <form action="/admin/kompetensi/{{ $k->id_kompetensi }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data capaian ini?')">
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
                  <td colspan="8" class="text-center py-4 text-muted">
                    <i class="fas fa-info-circle mr-1"></i> Belum ada data Capaian Pembelajaran atau Tujuan Pembelajaran untuk program keahlian Anda.
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
