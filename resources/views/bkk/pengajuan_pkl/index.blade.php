@extends($layout)
@section('content')
<!-- Content Header -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row align-items-center mb-2">
      <div class="col-sm-7 d-flex align-items-center">
        <i class="fas fa-file-signature text-primary mr-3" style="font-size: 2rem; flex-shrink: 0;"></i>
        <div class="d-flex flex-column justify-content-center">
          <h1 class="m-0 font-weight-bold text-dark" style="line-height: 1.2;">Pengajuan Surat Permohonan PKL</h1>
          <p class="text-muted mt-1 mb-0" style="line-height: 1.2; font-size: 0.85rem;">Verifikasi, persetujuan (ACC), dan penerbitan surat resmi permohonan PKL siswa</p>
        </div>
      </div>
      <div class="col-sm-5">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="/admin/dashboard">Dashboard</a></li>
          <li class="breadcrumb-item"><a href="#">BKK &amp; Hubin</a></li>
          <li class="breadcrumb-item active">Pengajuan Surat PKL</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<!-- Main Content -->
<div class="content">
  <div class="container-fluid">

    <!-- Stat Summary Cards -->
    <div class="row mb-3">
      <div class="col-lg-3 col-md-6 col-sm-6 mb-2">
        <div class="info-box shadow-sm border-0" style="border-radius: 12px;">
          <span class="info-box-icon bg-primary text-white" style="border-radius: 10px;"><i class="fas fa-mail-bulk text-white" style="color: #ffffff !important;"></i></span>
          <div class="info-box-content">
            <span class="info-box-text text-muted font-weight-bold" style="font-size: 0.78rem;">TOTAL PENGAJUAN</span>
            <span class="info-box-number text-dark font-weight-bold" style="font-size: 1.35rem;">{{ $totalSemua ?? 0 }} <small class="font-weight-normal text-muted" style="font-size: 0.8rem;">Surat</small></span>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 col-sm-6 mb-2">
        <div class="info-box shadow-sm border-0" style="border-radius: 12px;">
          <span class="info-box-icon bg-warning text-white" style="border-radius: 10px;"><i class="fas fa-clock text-white" style="color: #ffffff !important;"></i></span>
          <div class="info-box-content">
            <span class="info-box-text text-muted font-weight-bold" style="font-size: 0.78rem;">MENUNGGU ACC</span>
            <span class="info-box-number text-warning font-weight-bold" style="font-size: 1.35rem;">{{ $totalMenunggu ?? 0 }} <small class="font-weight-normal text-muted" style="font-size: 0.8rem;">Pengajuan</small></span>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 col-sm-6 mb-2">
        <div class="info-box shadow-sm border-0" style="border-radius: 12px;">
          <span class="info-box-icon bg-success text-white" style="border-radius: 10px;"><i class="fas fa-check-circle text-white" style="color: #ffffff !important;"></i></span>
          <div class="info-box-content">
            <span class="info-box-text text-muted font-weight-bold" style="font-size: 0.78rem;">DISETUJUI (SIAP PRINT)</span>
            <span class="info-box-number text-success font-weight-bold" style="font-size: 1.35rem;">{{ $totalDisetujui ?? 0 }} <small class="font-weight-normal text-muted" style="font-size: 0.8rem;">Surat</small></span>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 col-sm-6 mb-2">
        <div class="info-box shadow-sm border-0" style="border-radius: 12px;">
          <span class="info-box-icon bg-danger text-white" style="border-radius: 10px;"><i class="fas fa-times-circle text-white" style="color: #ffffff !important;"></i></span>
          <div class="info-box-content">
            <span class="info-box-text text-muted font-weight-bold" style="font-size: 0.78rem;">DITOLAK</span>
            <span class="info-box-number text-danger font-weight-bold" style="font-size: 1.35rem;">{{ $totalDitolak ?? 0 }} <small class="font-weight-normal text-muted" style="font-size: 0.8rem;">Pengajuan</small></span>
          </div>
        </div>
      </div>
    </div>

    <!-- Card Main Content -->
    <div class="card shadow-sm border-0" style="border-radius: 14px;">
      <div class="card-header bg-white py-3 border-0 d-flex align-items-center justify-content-between flex-wrap" style="gap: 10px;">
        <h3 class="card-title text-dark font-weight-bold mb-0">
          <i class="fas fa-table text-primary mr-2"></i> Daftar Pengajuan Surat PKL Siswa
        </h3>
        <div class="d-flex align-items-center flex-wrap" style="gap: 8px;">
          <a href="{{ route('admin.pengajuan-pkl.create') }}" class="btn btn-primary btn-sm shadow-sm">
            <i class="fas fa-plus mr-1"></i> Buat Pengajuan Baru
          </a>
        </div>
      </div>

      <!-- Filter & Search Toolbar -->
      <div class="card-body border-top border-light pb-2 pt-3">
        <form action="{{ route('admin.pengajuan-pkl.index') }}" method="GET" class="form-row align-items-center">
          <div class="col-md-3 col-sm-6 mb-2">
            <select name="status" class="form-control form-control-sm" onchange="this.form.submit()">
              <option value="">-- Semua Status --</option>
              <option value="menunggu" {{ request('status') === 'menunggu' ? 'selected' : '' }}>Menunggu ACC (Pending)</option>
              <option value="disetujui" {{ request('status') === 'disetujui' ? 'selected' : '' }}>Disetujui (ACC)</option>
              <option value="ditolak" {{ request('status') === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
            </select>
          </div>
          <div class="col-md-5 col-sm-6 mb-2">
            <div class="input-group input-group-sm">
              <input type="text" name="search" class="form-control" placeholder="Cari perusahaan, nomor surat, siswa, kode..." value="{{ request('search') }}">
              <div class="input-group-append">
                <button class="btn btn-primary" type="submit">
                  <i class="fas fa-search"></i> Cari
                </button>
              </div>
            </div>
          </div>
          @if(request('status') || request('search'))
          <div class="col-md-2 mb-2">
            <a href="{{ route('admin.pengajuan-pkl.index') }}" class="btn btn-light btn-sm text-secondary border">
              <i class="fas fa-undo mr-1"></i> Reset Filter
            </a>
          </div>
          @endif
        </form>
      </div>

      <!-- Table Section -->
      <div class="card-body table-responsive pt-1">
        <table class="table table-bordered table-hover align-middle">
          <thead class="bg-light">
            <tr>
              <th style="width: 50px;" class="text-center">No</th>
              <th style="width: 140px;">Kode &amp; Tanggal</th>
              <th>Perusahaan Tujuan &amp; Penerima</th>
              <th>Daftar Siswa (Kelompok)</th>
              <th>Periode PKL</th>
              <th>Nomor Surat Resmi</th>
              <th class="text-center" style="width: 130px;">Status</th>
              <th class="text-center" style="width: 140px;">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($pengajuanList as $item)
            <tr>
              <td class="text-center font-weight-bold text-muted">{{ $loop->iteration + ($pengajuanList->currentPage() - 1) * $pengajuanList->perPage() }}</td>
              <td>
                <span class="badge badge-light border text-dark font-weight-bold" style="font-size: 0.78rem;">{{ $item->kode_pengajuan }}</span>
                <div class="text-muted small mt-1">
                  <i class="fas fa-calendar-alt mr-1"></i> {{ $item->created_at->format('d/m/Y H:i') }}
                </div>
              </td>
              <td>
                <div class="font-weight-bold text-dark" style="font-size: 0.95rem;">
                  <i class="fas fa-building text-primary mr-1"></i> {{ $item->nama_perusahaan }}
                </div>
                <div class="text-muted small">
                  Yth: <span class="text-dark font-weight-500">{{ $item->ditujukan_kepada }}</span>
                  @if($item->jabatan_tujuan)
                    ({{ $item->jabatan_tujuan }})
                  @endif
                </div>
                @if($item->perusahaan)
                  <span class="badge badge-soft-info px-2 py-0" style="font-size: 0.7rem; background-color: #e0f2fe; color: #0284c7;">Mitra DU/DI Terdaftar</span>
                @endif
              </td>
              <td>
                <div class="d-flex align-items-center mb-1">
                  <span class="badge badge-primary px-2 py-1 mr-2" style="font-size: 0.75rem;">
                    {{ $item->siswaList->count() }} Siswa
                  </span>
                  @if($item->kontak_pemohon)
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $item->kontak_pemohon) }}" target="_blank" class="text-success small font-weight-600">
                      <i class="fab fa-whatsapp mr-1"></i>{{ $item->kontak_pemohon }}
                    </a>
                  @endif
                </div>
                <ul class="list-unstyled mb-0 small text-secondary" style="max-height: 80px; overflow-y: auto;">
                  @foreach($item->siswaList->take(3) as $s)
                    <li>&bull; <strong>{{ $s->nama_siswa }}</strong> ({{ $s->program_keahlian }})</li>
                  @endforeach
                  @if($item->siswaList->count() > 3)
                    <li class="text-muted italic">... dan {{ $item->siswaList->count() - 3 }} siswa lainnya</li>
                  @endif
                </ul>
              </td>
              <td>
                <span class="text-dark font-weight-500">{{ $item->periode_teks }}</span>
              </td>
              <td>
                @if($item->nomor_surat)
                  <span class="text-dark font-weight-bold font-monospace" style="font-size: 0.85rem;">{{ $item->nomor_surat }}</span>
                @else
                  <span class="text-muted italic small">(Belum diterbitkan)</span>
                @endif
              </td>
              <td class="text-center">
                {!! $item->status_badge !!}
                @if($item->status === 'ditolak' && $item->catatan_bkk)
                  <div class="text-danger small mt-1" title="{{ $item->catatan_bkk }}">
                    <i class="fas fa-info-circle mr-1"></i> Catatan BKK
                  </div>
                @endif
              </td>
              <td class="text-center">
                <div class="btn-group btn-group-sm">
                  <a href="{{ route('admin.pengajuan-pkl.show', $item->id_pengajuan) }}" class="btn btn-info" title="Detail &amp; Proses ACC">
                    <i class="fas fa-eye"></i>
                  </a>
                  @if($item->status === 'disetujui')
                    <a href="{{ route('admin.pengajuan-pkl.cetak', $item->id_pengajuan) }}" class="btn btn-primary" title="Cetak Surat Permohonan" target="_blank">
                      <i class="fas fa-print"></i>
                    </a>
                  @endif
                  <form action="{{ route('admin.pengajuan-pkl.destroy', $item->id_pengajuan) }}" method="POST" class="d-inline form-delete" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data pengajuan ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" title="Hapus Pengajuan">
                      <i class="fas fa-trash"></i>
                    </button>
                  </form>
                </div>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="8" class="text-center py-4 text-muted">
                <i class="fas fa-inbox text-secondary fa-2x mb-2 d-block"></i>
                Tidak ada data pengajuan surat PKL yang sesuai.
              </td>
            </tr>
            @endforelse
          </tbody>
        </table>

        <div class="d-flex justify-content-between align-items-center mt-3">
          <div class="text-muted small">
            Menampilkan {{ $pengajuanList->firstItem() ?? 0 }} - {{ $pengajuanList->lastItem() ?? 0 }} dari {{ $pengajuanList->total() }} data
          </div>
          <div>
            {{ $pengajuanList->links() }}
          </div>
        </div>
      </div>
      <!-- /.card-body -->
    </div>

  </div>
</div>
@endsection
