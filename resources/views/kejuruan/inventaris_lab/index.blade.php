@extends($layout)

@section('content')
<!-- Content Header -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row align-items-center mb-2">
      <div class="col-sm-7 d-flex align-items-center">
        <i class="fas fa-boxes text-primary mr-3" style="font-size: 2rem; flex-shrink: 0;"></i>
        <div class="d-flex flex-column justify-content-center">
          <h1 class="m-0 font-weight-bold text-dark" style="line-height: 1.2;">Inventaris Laboratorium</h1>
          <p class="text-muted mt-1 mb-0" style="line-height: 1.2; font-size: 0.85rem;">Pengelolaan aset alat, bahan habis pakai, sirkulasi peminjaman, dan perawatan lab</p>
        </div>
      </div>
      <div class="col-sm-5">
        <ol class="breadcrumb float-sm-right bg-transparent p-0 mb-0">
          <li class="breadcrumb-item"><a href="/admin/dashboard" class="text-primary font-weight-500">Dashboard</a></li>
          <li class="breadcrumb-item"><a href="/admin/kejuruan/inventaris-lab" class="text-primary font-weight-500">Kejuruan</a></li>
          <li class="breadcrumb-item active">Inventaris Lab</li>
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

    <!-- Summary Stat Cards -->
    <div class="row mb-3">
      <!-- Total Item Alat -->
      <div class="col-lg-3 col-6 mb-3 mb-lg-0">
        <div class="card border-0 shadow-sm h-100 bg-white" style="border-radius: 12px; border-left: 4px solid #0284c7 !important;">
          <div class="card-body p-3 d-flex align-items-center justify-content-between">
            <div>
              <div class="text-muted text-uppercase font-weight-bold" style="font-size: 0.72rem; letter-spacing: 0.5px;">Total Item Alat</div>
              <div class="font-weight-bold text-dark mt-1" style="font-size: 1.7rem; line-height: 1.1;">{{ $totalAlat }} <span style="font-size: 0.85rem; font-weight: 500; color: #64748b;">Unit</span></div>
              <small class="text-muted mt-1 d-block"><i class="fas fa-tools text-primary mr-1"></i> Alat & Peralatan</small>
            </div>
            <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 46px; height: 46px; font-size: 1.25rem; background: #f0f9ff; color: #0284c7; flex-shrink: 0;">
              <i class="fas fa-tools"></i>
            </div>
          </div>
        </div>
      </div>

      <!-- Bahan Habis Pakai -->
      <div class="col-lg-3 col-6 mb-3 mb-lg-0">
        <div class="card border-0 shadow-sm h-100 bg-white" style="border-radius: 12px; border-left: 4px solid #16a34a !important;">
          <div class="card-body p-3 d-flex align-items-center justify-content-between">
            <div>
              <div class="text-muted text-uppercase font-weight-bold" style="font-size: 0.72rem; letter-spacing: 0.5px;">Bahan Habis Pakai</div>
              <div class="font-weight-bold text-dark mt-1" style="font-size: 1.7rem; line-height: 1.1;">{{ $totalBahan }} <span style="font-size: 0.85rem; font-weight: 500; color: #64748b;">Jenis</span></div>
              <small class="text-muted mt-1 d-block"><i class="fas fa-vial text-success mr-1"></i> Konsumsi Praktik</small>
            </div>
            <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 46px; height: 46px; font-size: 1.25rem; background: #dcfce7; color: #16a34a; flex-shrink: 0;">
              <i class="fas fa-vial"></i>
            </div>
          </div>
        </div>
      </div>

      <!-- Sedang Dipinjam -->
      <div class="col-lg-3 col-6 mb-3 mb-lg-0">
        <div class="card border-0 shadow-sm h-100 bg-white" style="border-radius: 12px; border-left: 4px solid #f59e0b !important;">
          <div class="card-body p-3 d-flex align-items-center justify-content-between">
            <div>
              <div class="text-muted text-uppercase font-weight-bold" style="font-size: 0.72rem; letter-spacing: 0.5px;">Sedang Dipinjam</div>
              <div class="font-weight-bold text-dark mt-1" style="font-size: 1.7rem; line-height: 1.1;">{{ $totalPinjamAktif }} <span style="font-size: 0.85rem; font-weight: 500; color: #64748b;">Transaksi</span></div>
              <small class="text-muted mt-1 d-block"><i class="fas fa-hand-holding text-warning mr-1"></i> Belum Kembali</small>
            </div>
            <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 46px; height: 46px; font-size: 1.25rem; background: #fffbeb; color: #f59e0b; flex-shrink: 0;">
              <i class="fas fa-hand-holding"></i>
            </div>
          </div>
        </div>
      </div>

      <!-- Servis / Kerusakan -->
      <div class="col-lg-3 col-6 mb-3 mb-lg-0">
        <div class="card border-0 shadow-sm h-100 bg-white" style="border-radius: 12px; border-left: 4px solid #dc2626 !important;">
          <div class="card-body p-3 d-flex align-items-center justify-content-between">
            <div>
              <div class="text-muted text-uppercase font-weight-bold" style="font-size: 0.72rem; letter-spacing: 0.5px;">Servis / Kerusakan</div>
              <div class="font-weight-bold text-dark mt-1" style="font-size: 1.7rem; line-height: 1.1;">{{ $totalPerawatanAktif }} <span style="font-size: 0.85rem; font-weight: 500; color: #64748b;">Unit</span></div>
              <small class="text-muted mt-1 d-block"><i class="fas fa-wrench text-danger mr-1"></i> Perlu Penanganan</small>
            </div>
            <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 46px; height: 46px; font-size: 1.25rem; background: #fef2f2; color: #dc2626; flex-shrink: 0;">
              <i class="fas fa-wrench"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Alert Banner Peringatan Stok Menipis (jika ada) -->
    @if($bahanMenipis->count() > 0)
      <div class="alert alert-warning border-0 shadow-sm mb-3" style="border-radius: 12px; background-color: #fff9e6; border-left: 4px solid #f59e0b !important;">
        <div class="d-flex align-items-center mb-1">
          <i class="fas fa-exclamation-triangle text-warning mr-2"></i>
          <span class="font-weight-bold text-dark" style="font-size: 0.88rem;">Peringatan: Terdapat {{ $bahanMenipis->count() }} Bahan Praktik dengan Stok Menipis!</span>
        </div>
        <div class="row mt-2">
          @foreach($bahanMenipis as $bm)
            <div class="col-md-4 col-sm-6 mb-1">
              <span class="badge badge-danger mr-1 font-weight-bold">{{ $bm->stok_tersedia }} {{ $bm->satuan }}</span>
              <strong class="text-dark small">{{ $bm->nama_aset }}</strong>
              <span class="text-muted small">({{ $bm->lab->nama_lab ?? '-' }})</span>
            </div>
          @endforeach
        </div>
      </div>
    @endif

    <!-- Filter & Action Toolbar Card -->
    <div class="card border-0 shadow-sm mb-3" style="border-radius: 12px;">
      <div class="card-body p-3">
        <form method="GET" action="/admin/kejuruan/inventaris-lab" class="row align-items-end">
          <input type="hidden" name="tab" value="{{ $tab }}">

          <div class="col-md-3 col-sm-6 mb-2 mb-md-0">
            <label class="font-weight-bold text-secondary mb-1" style="font-size: 0.78rem; text-transform: uppercase;">Cari Nama / Kode Aset</label>
            <input type="text" name="q" value="{{ request('q') }}" class="form-control form-control-sm" placeholder="Ketik kata kunci...">
          </div>

          <div class="col-md-3 col-sm-6 mb-2 mb-md-0">
            <label class="font-weight-bold text-secondary mb-1" style="font-size: 0.78rem; text-transform: uppercase;">Filter Laboratorium</label>
            <select name="lab_id" class="form-control form-control-sm">
              <option value="">-- Semua Laboratorium --</option>
              @foreach($labs as $l)
                <option value="{{ $l->id_lab }}" {{ request('lab_id') == $l->id_lab ? 'selected' : '' }}>
                  {{ $l->nama_lab }} ({{ $l->jurusan->kode_jurusan ?? '-' }})
                </option>
              @endforeach
            </select>
          </div>

          <div class="col-md-3 col-sm-6 mb-2 mb-md-0">
            <label class="font-weight-bold text-secondary mb-1" style="font-size: 0.78rem; text-transform: uppercase;">Kategori</label>
            <select name="kategori" class="form-control form-control-sm">
              <option value="">-- Semua Kategori --</option>
              <option value="alat" {{ request('kategori') === 'alat' ? 'selected' : '' }}>Alat / Perangkat</option>
              <option value="bahan_habis_pakai" {{ request('kategori') === 'bahan_habis_pakai' ? 'selected' : '' }}>Bahan Habis Pakai</option>
            </select>
          </div>

          <div class="col-md-3 col-sm-6 mb-2 mb-md-0">
            <label class="font-weight-bold text-secondary mb-1" style="font-size: 0.78rem; text-transform: uppercase;">Kondisi</label>
            <div class="d-flex align-items-center" style="gap: 8px;">
              <select name="kondisi" class="form-control form-control-sm" style="flex: 1;">
                <option value="">-- Semua Kondisi --</option>
                <option value="baik" {{ request('kondisi') === 'baik' ? 'selected' : '' }}>Baik</option>
                <option value="rusak_ringan" {{ request('kondisi') === 'rusak_ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                <option value="rusak_berat" {{ request('kondisi') === 'rusak_berat' ? 'selected' : '' }}>Rusak Berat</option>
              </select>
              <button type="submit" class="btn btn-primary btn-sm px-3 d-flex align-items-center justify-content-center shadow-sm" style="height: 31px; flex-shrink: 0;" title="Terapkan Filter">
                <i class="fas fa-search"></i>
              </button>
            </div>
          </div>
        </form>

        <!-- Baris Tombol Aksi (Persis seperti data siswa tidak hadir) -->
        <div class="d-flex align-items-center justify-content-between mt-3 pt-2 border-top flex-wrap" style="gap: 8px;">
          <div class="d-flex align-items-center flex-wrap" style="gap: 8px;">
            <a href="/admin/kejuruan/inventaris-lab/aset/create" class="btn btn-sm btn-primary shadow-sm">
              <i class="fas fa-plus mr-1"></i> Tambah Aset Baru
            </a>
            <a href="/admin/kejuruan/inventaris-lab/laporan" class="btn btn-sm btn-success shadow-sm">
              <i class="fas fa-file-excel mr-1"></i> Rekap Laporan & Ekspor
            </a>
            <button type="button" class="btn btn-sm btn-outline-info" data-toggle="modal" data-target="#modalPeminjaman">
              <i class="fas fa-hand-holding mr-1"></i> Catat Peminjaman
            </button>
            <button type="button" class="btn btn-sm btn-outline-warning text-dark" data-toggle="modal" data-target="#modalPemakaianBahan">
              <i class="fas fa-minus-circle mr-1"></i> Catat Pakai Bahan
            </button>
            <button type="button" class="btn btn-sm btn-outline-danger" data-toggle="modal" data-target="#modalPerawatan">
              <i class="fas fa-wrench mr-1"></i> Lapor Kerusakan
            </button>
          </div>
          <div>
            @if(request()->anyFilled(['q', 'lab_id', 'kategori', 'kondisi']))
              <a href="/admin/kejuruan/inventaris-lab?tab={{ $tab }}" class="btn btn-link text-muted btn-sm p-0">
                <i class="fas fa-undo mr-1"></i> Reset Filter
              </a>
            @endif
          </div>
        </div>
      </div>
    </div>

    <!-- Data Table Card dengan Nav Tabs -->
    <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
      <div class="card-header bg-white py-2 px-3 border-bottom d-flex align-items-center justify-content-between flex-wrap">
        <div class="d-flex align-items-center my-1">
          <h3 class="card-title text-dark font-weight-bold mb-0" style="font-size: 1rem;">
            <i class="fas fa-list-ul text-primary mr-2"></i> 
            @if($tab === 'aset')
              Katalog Aset & Peralatan Lab
            @elseif($tab === 'peminjaman')
              Log Sirkulasi Peminjaman Alat
            @elseif($tab === 'bahan')
              Riwayat Pemakaian Bahan Habis Pakai
            @else
              Jadwal Perawatan & Riwayat Kerusakan
            @endif
          </h3>
        </div>

        <!-- Tab Pills -->
        <ul class="nav nav-pills my-1" id="inventarisTab" role="tablist">
          <li class="nav-item">
            <a class="nav-link py-1 px-3 {{ $tab === 'aset' ? 'active' : '' }}" href="/admin/kejuruan/inventaris-lab?tab=aset" style="border-radius: 6px; font-size: 0.82rem; font-weight: 500;">
              <i class="fas fa-boxes mr-1"></i> Katalog Aset
            </a>
          </li>
          <li class="nav-item ml-1">
            <a class="nav-link py-1 px-3 {{ $tab === 'peminjaman' ? 'active' : '' }}" href="/admin/kejuruan/inventaris-lab?tab=peminjaman" style="border-radius: 6px; font-size: 0.82rem; font-weight: 500;">
              <i class="fas fa-hand-holding mr-1"></i> Peminjaman
              @if($totalPinjamAktif > 0)
                <span class="badge badge-light text-primary ml-1">{{ $totalPinjamAktif }}</span>
              @endif
            </a>
          </li>
          <li class="nav-item ml-1">
            <a class="nav-link py-1 px-3 {{ $tab === 'bahan' ? 'active' : '' }}" href="/admin/kejuruan/inventaris-lab?tab=bahan" style="border-radius: 6px; font-size: 0.82rem; font-weight: 500;">
              <i class="fas fa-layer-group mr-1"></i> Bahan Pakai
              @if($bahanMenipis->count() > 0)
                <span class="badge badge-danger ml-1">{{ $bahanMenipis->count() }}</span>
              @endif
            </a>
          </li>
          <li class="nav-item ml-1">
            <a class="nav-link py-1 px-3 {{ $tab === 'perawatan' ? 'active' : '' }}" href="/admin/kejuruan/inventaris-lab?tab=perawatan" style="border-radius: 6px; font-size: 0.82rem; font-weight: 500;">
              <i class="fas fa-tools mr-1"></i> Servis
              @if($totalPerawatanAktif > 0)
                <span class="badge badge-warning text-dark ml-1">{{ $totalPerawatanAktif }}</span>
              @endif
            </a>
          </li>
        </ul>
      </div>

      <!-- ============================================================== -->
      <!-- TABEL 1: KATALOG ASET & ALAT                                   -->
      <!-- ============================================================== -->
      @if($tab === 'aset')
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="font-size: 0.88rem;">
              <thead class="bg-light">
                <tr>
                  <th style="width: 45px; text-align: center;">No</th>
                  <th style="width: 140px;">Kode Aset</th>
                  <th>Nama Aset & Spesifikasi</th>
                  <th>Laboratorium</th>
                  <th style="width: 120px; text-align: center;">Kategori</th>
                  <th style="width: 120px; text-align: center;">Total / Sisa</th>
                  <th style="width: 110px; text-align: center;">Kondisi</th>
                  <th style="width: 80px; text-align: center;">Tahun</th>
                  <th style="width: 150px; text-align: center;">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @forelse($asets as $a)
                  <tr>
                    <td class="text-center font-weight-bold text-muted">{{ $loop->iteration }}</td>
                    <td>
                      <span class="badge badge-light border text-primary font-weight-bold px-2 py-1">
                        {{ $a->kode_aset }}
                      </span>
                    </td>
                    <td>
                      <a href="/admin/kejuruan/inventaris-lab/aset/{{ $a->id_aset }}" class="font-weight-600 text-dark d-block">
                        {{ $a->nama_aset }}
                      </a>
                      @if($a->spesifikasi)
                        <small class="text-muted text-truncate d-block" style="max-width: 250px;">{{ $a->spesifikasi }}</small>
                      @endif
                    </td>
                    <td>
                      <span class="small font-weight-500 text-dark">
                        {{ $a->lab->nama_lab ?? '-' }}
                      </span>
                      <small class="text-muted d-block">{{ $a->lab->jurusan->nama_jurusan ?? '' }}</small>
                    </td>
                    <td class="text-center">
                      @if($a->kategori === 'alat')
                        <span class="badge badge-info px-2 py-1 font-weight-500">
                          <i class="fas fa-tools mr-1"></i> Alat
                        </span>
                      @else
                        <span class="badge badge-warning px-2 py-1 font-weight-500">
                          <i class="fas fa-vial mr-1"></i> Bahan
                        </span>
                      @endif
                    </td>
                    <td class="text-center">
                      <span class="font-weight-bold text-dark">{{ $a->jumlah }}</span> / 
                      <span class="font-weight-bold {{ $a->stok_tersedia <= $a->stok_minimum ? 'text-danger' : 'text-success' }}">
                        {{ $a->stok_tersedia }}
                      </span>
                      <small class="text-muted d-block">{{ $a->satuan }}</small>
                    </td>
                    <td class="text-center">
                      @if($a->kondisi === 'baik')
                        <span class="badge badge-success px-2 py-1">Baik</span>
                      @elseif($a->kondisi === 'rusak_ringan')
                        <span class="badge badge-warning px-2 py-1">Rusak Ringan</span>
                      @else
                        <span class="badge badge-danger px-2 py-1">Rusak Berat</span>
                      @endif
                    </td>
                    <td class="text-center text-muted">{{ $a->tahun_perolehan }}</td>
                    <td class="text-center">
                      <a href="/admin/kejuruan/inventaris-lab/cetak-qr?aset_id={{ $a->id_aset }}" class="btn btn-xs btn-outline-secondary mr-1" title="Cetak Stiker QR Code" target="_blank">
                        <i class="fas fa-qrcode"></i>
                      </a>
                      <a href="/admin/kejuruan/inventaris-lab/aset/{{ $a->id_aset }}" class="btn btn-xs btn-outline-info mr-1" title="Detail Aset">
                        <i class="fas fa-eye"></i>
                      </a>
                      <a href="/admin/kejuruan/inventaris-lab/aset/{{ $a->id_aset }}/edit" class="btn btn-xs btn-outline-primary mr-1" title="Ubah Aset">
                        <i class="fas fa-edit"></i>
                      </a>
                      <form action="/admin/kejuruan/inventaris-lab/aset/{{ $a->id_aset }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus aset ini dari inventaris lab?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-xs btn-outline-danger" title="Hapus Aset">
                          <i class="fas fa-trash"></i>
                        </button>
                      </form>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="9" class="text-center py-5 text-muted">
                      <i class="fas fa-boxes fa-3x mb-3 d-block text-secondary"></i>
                      Belum ada aset laboratorium yang terdaftar.
                    </td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>

      @endif

      <!-- ============================================================== -->
      <!-- TABEL 2: SIRKULASI PEMINJAMAN ALAT                             -->
      <!-- ============================================================== -->
      @if($tab === 'peminjaman')
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="font-size: 0.88rem;">
              <thead class="bg-light">
                <tr>
                  <th style="width: 45px; text-align: center;">No</th>
                  <th style="width: 140px;">Kode Transaksi</th>
                  <th>Nama Alat</th>
                  <th>Peminjam</th>
                  <th style="width: 80px; text-align: center;">Jumlah</th>
                  <th style="width: 110px;">Tgl Pinjam</th>
                  <th style="width: 110px;">Rencana Balik</th>
                  <th style="width: 110px; text-align: center;">Status</th>
                  <th style="width: 130px; text-align: center;">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @forelse($peminjamans as $p)
                  <tr>
                    <td class="text-center font-weight-bold text-muted">{{ $loop->iteration }}</td>
                    <td>
                      <span class="badge badge-light border text-dark font-weight-bold px-2 py-1">
                        {{ $p->kode_peminjaman }}
                      </span>
                    </td>
                    <td>
                      <span class="font-weight-600 text-dark d-block">
                        {{ $p->aset->nama_aset ?? '-' }}
                      </span>
                      <small class="text-muted">Lab: {{ $p->aset->lab->nama_lab ?? '-' }}</small>
                    </td>
                    <td>
                      @if($p->tipe_peminjam === 'guru')
                        <span class="font-weight-bold text-primary d-block">
                          <i class="fas fa-chalkboard-teacher mr-1"></i> {{ $p->peminjamGuru->nama_guru ?? 'Guru' }}
                        </span>
                        <small class="text-muted">Guru Pengampu</small>
                      @else
                        <span class="font-weight-bold text-dark d-block">
                          <i class="fas fa-user-graduate mr-1"></i> {{ $p->peminjamSiswa->nama_siswa ?? 'Siswa' }}
                        </span>
                        <small class="text-muted">Kelas: {{ $p->peminjamSiswa->kelas->nama_kelas ?? '-' }}</small>
                      @endif
                    </td>
                    <td class="text-center font-weight-bold">
                      {{ $p->jumlah }} {{ $p->aset->satuan ?? 'Unit' }}
                    </td>
                    <td>{{ \Carbon\Carbon::parse($p->tanggal_pinjam)->format('d/m/Y') }}</td>
                    <td>
                      <span class="{{ \Carbon\Carbon::parse($p->rencana_kembali)->isPast() && $p->status === 'dipinjam' ? 'text-danger font-weight-bold' : '' }}">
                        {{ \Carbon\Carbon::parse($p->rencana_kembali)->format('d/m/Y') }}
                      </span>
                    </td>
                    <td class="text-center">
                      @if($p->status === 'dipinjam')
                        <span class="badge badge-warning text-dark px-2 py-1 font-weight-bold">Dipinjam</span>
                      @elseif($p->status === 'kembali')
                        <span class="badge badge-success px-2 py-1 font-weight-bold">Kembali</span>
                      @elseif($p->status === 'terlambat')
                        <span class="badge badge-danger px-2 py-1 font-weight-bold">Terlambat</span>
                      @else
                        <span class="badge badge-secondary px-2 py-1 font-weight-bold">Hilang</span>
                      @endif
                    </td>
                    <td class="text-center">
                      @if($p->status === 'dipinjam')
                        <button type="button" class="btn btn-xs btn-success font-weight-600 px-2" style="border-radius: 4px;" 
                          data-toggle="modal" 
                          data-target="#modalKembalikan-{{ $p->id_peminjaman }}">
                          <i class="fas fa-undo mr-1"></i> Kembalikan
                        </button>
                      @else
                        <span class="small text-muted font-weight-500">
                          Selesai ({{ $p->tanggal_kembali ? \Carbon\Carbon::parse($p->tanggal_kembali)->format('d/m/Y') : '-' }})
                        </span>
                      @endif
                    </td>
                  </tr>

                  <!-- Modal Pengembalian -->
                  <div class="modal fade" id="modalKembalikan-{{ $p->id_peminjaman }}" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                      <div class="modal-content border-0 shadow" style="border-radius: 12px;">
                        <form action="/admin/kejuruan/inventaris-lab/peminjaman/{{ $p->id_peminjaman }}/kembalikan" method="POST">
                          @csrf
                          <div class="modal-header bg-light">
                            <h5 class="modal-title font-weight-bold text-dark" style="font-size: 1rem;">
                              <i class="fas fa-undo text-success mr-2"></i> Proses Pengembalian Alat
                            </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body p-4">
                            <p class="text-muted small mb-3">
                              Alat: <strong>{{ $p->aset->nama_aset ?? '-' }}</strong> ({{ $p->jumlah }} {{ $p->aset->satuan ?? 'Unit' }})<br>
                              Peminjam: <strong>{{ $p->tipe_peminjam === 'guru' ? ($p->peminjamGuru->nama_guru ?? '') : ($p->peminjamSiswa->nama_siswa ?? '') }}</strong>
                            </p>

                            <div class="form-group mb-3">
                              <label class="font-weight-bold text-dark small mb-1">Tanggal Pengembalian Fisik <span class="text-danger">*</span></label>
                              <input type="date" name="tanggal_kembali" value="{{ date('Y-m-d') }}" class="form-control" required style="border-radius: 6px;">
                            </div>

                            <div class="form-group mb-3">
                              <label class="font-weight-bold text-dark small mb-1">Kondisi Fisik Saat Dikembalikan <span class="text-danger">*</span></label>
                              <select name="kondisi_sesudah" class="form-control" required style="border-radius: 6px;">
                                <option value="baik">Kondisi Baik & Lengkap</option>
                                <option value="rusak_ringan">Rusak Ringan (Perlu Servis)</option>
                                <option value="rusak_berat">Rusak Berat (Tidak Berfungsi)</option>
                              </select>
                            </div>

                            <div class="form-group mb-0">
                              <label class="font-weight-bold text-dark small mb-1">Catatan Pengecekan</label>
                              <textarea name="catatan" rows="2" class="form-control" placeholder="Contoh: Kabel adaptor lengkap dan berfungsi normal..." style="border-radius: 6px;"></textarea>
                            </div>
                          </div>
                          <div class="modal-footer bg-light py-2">
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-dismiss="modal" style="border-radius: 6px;">Batal</button>
                            <button type="submit" class="btn btn-success btn-sm px-3 font-weight-600" style="border-radius: 6px;">
                              <i class="fas fa-check mr-1"></i> Konfirmasi Pengembalian
                            </button>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>
                @empty
                  <tr>
                    <td colspan="9" class="text-center py-5 text-muted">
                      Belum ada riwayat peminjaman alat laboratorium.
                    </td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>

      @endif

      <!-- ============================================================== -->
      <!-- TABEL 3: BAHAN HABIS PAKAI                                     -->
      <!-- ============================================================== -->
      @if($tab === 'bahan')
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="font-size: 0.88rem;">
              <thead class="bg-light">
                <tr>
                  <th style="width: 45px; text-align: center;">No</th>
                  <th style="width: 110px;">Tanggal</th>
                  <th>Nama Bahan</th>
                  <th>Laboratorium</th>
                  <th style="width: 100px; text-align: center;">Pemakaian</th>
                  <th style="width: 100px; text-align: center;">Sisa Stok</th>
                  <th>Keperluan Praktikum</th>
                  <th>Guru / Pengguna</th>
                  <th>Pencatat</th>
                </tr>
              </thead>
              <tbody>
                @forelse($pemakaians as $pm)
                  <tr>
                    <td class="text-center font-weight-bold text-muted">{{ $loop->iteration }}</td>
                    <td>{{ \Carbon\Carbon::parse($pm->tanggal_pakai)->format('d/m/Y') }}</td>
                    <td class="font-weight-600 text-dark">{{ $pm->aset->nama_aset ?? '-' }}</td>
                    <td>{{ $pm->aset->lab->nama_lab ?? '-' }}</td>
                    <td class="text-center font-weight-bold text-danger">
                      -{{ $pm->jumlah_pakai }} {{ $pm->aset->satuan ?? 'Pcs' }}
                    </td>
                    <td class="text-center font-weight-bold text-dark">
                      {{ $pm->sisa_stok_saat_itu }} {{ $pm->aset->satuan ?? 'Pcs' }}
                    </td>
                    <td>{{ $pm->keperluan }}</td>
                    <td>{{ $pm->penggunaGuru->nama_guru ?? '-' }}</td>
                    <td><small class="text-muted">{{ $pm->petugas->nama_guru ?? '-' }}</small></td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="9" class="text-center py-5 text-muted">
                      Belum ada catatan konsumsi bahan habis pakai.
                    </td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>

      @endif

      <!-- ============================================================== -->
      <!-- TABEL 4: PERAWATAN & KERUSAKAN                                 -->
      <!-- ============================================================== -->
      @if($tab === 'perawatan')
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="font-size: 0.88rem;">
              <thead class="bg-light">
                <tr>
                  <th style="width: 45px; text-align: center;">No</th>
                  <th style="width: 110px;">Tgl Jadwal</th>
                  <th>Nama Aset</th>
                  <th>Lab</th>
                  <th style="width: 120px; text-align: center;">Jenis</th>
                  <th>Kendala / Kerusakan</th>
                  <th style="width: 120px; text-align: center;">Status</th>
                  <th>Teknisi / Biaya</th>
                  <th style="width: 120px; text-align: center;">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @forelse($perawatans as $pw)
                  <tr>
                    <td class="text-center font-weight-bold text-muted">{{ $loop->iteration }}</td>
                    <td>{{ \Carbon\Carbon::parse($pw->tanggal_jadwal)->format('d/m/Y') }}</td>
                    <td class="font-weight-600 text-dark">{{ $pw->aset->nama_aset ?? '-' }}</td>
                    <td>{{ $pw->aset->lab->nama_lab ?? '-' }}</td>
                    <td class="text-center text-capitalize">
                      <span class="badge badge-light border text-dark font-weight-500">
                        {{ str_replace('_', ' ', $pw->jenis) }}
                      </span>
                    </td>
                    <td>{{ $pw->deskripsi_kendala }}</td>
                    <td class="text-center">
                      @if($pw->status === 'terjadwal')
                        <span class="badge badge-secondary px-2 py-1">Terjadwal</span>
                      @elseif($pw->status === 'dalam_pengerjaan')
                        <span class="badge badge-warning text-dark px-2 py-1">Pengerjaan</span>
                      @elseif($pw->status === 'selesai')
                        <span class="badge badge-success px-2 py-1">Selesai</span>
                      @else
                        <span class="badge badge-danger px-2 py-1">Rusak Berat</span>
                      @endif
                    </td>
                    <td>
                      <span class="d-block small">{{ $pw->teknisi ?: 'Teknisi internal' }}</span>
                      <strong class="text-dark small">Rp {{ number_format($pw->biaya, 0, ',', '.') }}</strong>
                    </td>
                    <td class="text-center">
                      @if($pw->status !== 'selesai' && $pw->status !== 'tidak_dapat_diperbaiki')
                        <button type="button" class="btn btn-xs btn-primary font-weight-600 px-2" style="border-radius: 4px;"
                          data-toggle="modal" 
                          data-target="#modalUpdatePerawatan-{{ $pw->id_perawatan }}">
                          <i class="fas fa-edit mr-1"></i> Update
                        </button>
                      @else
                        <span class="badge badge-light border text-muted">Selesai</span>
                      @endif
                    </td>
                  </tr>

                  <!-- Modal Update Perawatan -->
                  <div class="modal fade" id="modalUpdatePerawatan-{{ $pw->id_perawatan }}" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                      <div class="modal-content border-0 shadow" style="border-radius: 12px;">
                        <form action="/admin/kejuruan/inventaris-lab/perawatan/{{ $pw->id_perawatan }}" method="POST">
                          @csrf
                          <div class="modal-header bg-light">
                            <h5 class="modal-title font-weight-bold text-dark" style="font-size: 1rem;">
                              <i class="fas fa-wrench text-warning mr-2"></i> Update Perbaikan Aset
                            </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body p-4">
                            <p class="text-muted small mb-3">
                              Aset: <strong>{{ $pw->aset->nama_aset ?? '-' }}</strong><br>
                              Kendala: {{ $pw->deskripsi_kendala }}
                            </p>

                            <div class="form-group mb-3">
                              <label class="font-weight-bold text-dark small mb-1">Status Pengerjaan <span class="text-danger">*</span></label>
                              <select name="status" class="form-control" required style="border-radius: 6px;">
                                <option value="dalam_pengerjaan" {{ $pw->status === 'dalam_pengerjaan' ? 'selected' : '' }}>Masih Dalam Perbaikan</option>
                                <option value="selesai">Selesai Diperbaiki</option>
                                <option value="tidak_dapat_diperbaiki">Tidak Dapat Diperbaiki (Rusak Berat)</option>
                              </select>
                            </div>

                            <div class="form-group mb-3">
                              <label class="font-weight-bold text-dark small mb-1">Kondisi Akhir Aset (Jika Selesai)</label>
                              <select name="kondisi_akhir" class="form-control" style="border-radius: 6px;">
                                <option value="baik">Kondisi Baik (Normal Kembali)</option>
                                <option value="rusak_ringan">Rusak Ringan (Bisa Digunakan Sebagian)</option>
                              </select>
                            </div>

                            <div class="form-group mb-3">
                              <label class="font-weight-bold text-dark small mb-1">Biaya Servis Riil (Rp)</label>
                              <input type="number" step="0.01" name="biaya" value="{{ $pw->biaya }}" class="form-control" style="border-radius: 6px;">
                            </div>

                            <div class="form-group mb-0">
                              <label class="font-weight-bold text-dark small mb-1">Tindakan / Komponen yang Diganti</label>
                              <textarea name="tindakan_perbaikan" rows="2" class="form-control" placeholder="Contoh: Penggantian bearing dan oli pelumas..." style="border-radius: 6px;">{{ $pw->tindakan_perbaikan }}</textarea>
                            </div>
                          </div>
                          <div class="modal-footer bg-light py-2">
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-dismiss="modal" style="border-radius: 6px;">Batal</button>
                            <button type="submit" class="btn btn-primary btn-sm px-3 font-weight-600" style="border-radius: 6px;">
                              <i class="fas fa-save mr-1"></i> Simpan Status
                            </button>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>
                @empty
                  <tr>
                    <td colspan="9" class="text-center py-5 text-muted">
                      Belum ada catatan kerusakan atau jadwal servis perawatan.
                    </td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>

      @endif

    </div>

    <!-- ============================================================== -->
    <!-- MODAL 1: FORM PEMINJAMAN ALAT                                  -->
    <!-- ============================================================== -->
    <div class="modal fade" id="modalPeminjaman" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content border-0 shadow" style="border-radius: 12px;">
          <form action="/admin/kejuruan/inventaris-lab/peminjaman" method="POST">
            @csrf
            <div class="modal-header bg-light">
              <h5 class="modal-title font-weight-bold text-dark" style="font-size: 1.05rem;">
                <i class="fas fa-hand-holding text-primary mr-2"></i> Formulir Peminjaman Alat Laboratorium
              </h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body p-4">
              <div class="row">
                <div class="col-md-8 mb-3">
                  <label class="font-weight-bold text-dark small mb-1">Pilih Alat yang Dipinjam <span class="text-danger">*</span></label>
                  <select name="id_aset" class="form-control select2bs4" required style="border-radius: 6px;">
                    <option value="">-- Pilih Alat Laboratorium --</option>
                    @foreach($asetAlatList as $al)
                      <option value="{{ $al->id_aset }}">
                        {{ $al->nama_aset }} (Stok Tersedia: {{ $al->stok_tersedia }} {{ $al->satuan }}) - {{ $al->lab->nama_lab ?? '' }}
                      </option>
                    @endforeach
                  </select>
                </div>
                <div class="col-md-4 mb-3">
                  <label class="font-weight-bold text-dark small mb-1">Jumlah Unit <span class="text-danger">*</span></label>
                  <input type="number" name="jumlah" min="1" value="1" class="form-control" required style="border-radius: 6px;">
                </div>
              </div>

              <div class="row">
                <div class="col-md-4 mb-3">
                  <label class="font-weight-bold text-dark small mb-1">Tipe Peminjam <span class="text-danger">*</span></label>
                  <select name="tipe_peminjam" id="tipePeminjamSelect" class="form-control" required style="border-radius: 6px;">
                    <option value="guru">Guru / Instruktur</option>
                    <option value="siswa">Siswa</option>
                  </select>
                </div>
                <div class="col-md-8 mb-3" id="peminjamGuruWrapper">
                  <label class="font-weight-bold text-dark small mb-1">Pilih Guru Peminjam</label>
                  <select name="id_peminjam_guru" class="form-control select2bs4" style="border-radius: 6px;">
                    <option value="">-- Pilih Guru --</option>
                    @foreach($gurus as $g)
                      <option value="{{ $g->id_guru }}">{{ $g->nama_guru }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-md-8 mb-3 d-none" id="peminjamSiswaWrapper">
                  <label class="font-weight-bold text-dark small mb-1">Pilih Siswa Peminjam</label>
                  <select name="id_peminjam_siswa" class="form-control select2bs4" style="border-radius: 6px;">
                    <option value="">-- Pilih Siswa --</option>
                    @foreach($siswas as $s)
                      <option value="{{ $s->id_siswa }}">
                        {{ $s->nama_siswa }} ({{ $s->kelas->nama_kelas ?? 'Tanpa Kelas' }})
                      </option>
                    @endforeach
                  </select>
                </div>
              </div>

              <div class="row">
                <div class="col-md-6 mb-3">
                  <label class="font-weight-bold text-dark small mb-1">Tanggal Pinjam <span class="text-danger">*</span></label>
                  <input type="date" name="tanggal_pinjam" value="{{ date('Y-m-d') }}" class="form-control" required style="border-radius: 6px;">
                </div>
                <div class="col-md-6 mb-3">
                  <label class="font-weight-bold text-dark small mb-1">Rencana Kembali <span class="text-danger">*</span></label>
                  <input type="date" name="rencana_kembali" value="{{ date('Y-m-d') }}" class="form-control" required style="border-radius: 6px;">
                </div>
              </div>

              <div class="mb-0">
                <label class="font-weight-bold text-dark small mb-1">Keperluan Peminjaman</label>
                <textarea name="keperluan" rows="2" class="form-control" placeholder="Contoh: Praktik Pengukuran Multimeter Kelas X TKJ 1" style="border-radius: 6px;"></textarea>
              </div>
            </div>
            <div class="modal-footer bg-light py-2">
              <button type="button" class="btn btn-outline-secondary btn-sm" data-dismiss="modal" style="border-radius: 6px;">Batal</button>
              <button type="submit" class="btn btn-primary btn-sm px-3 font-weight-600" style="border-radius: 6px;">
                <i class="fas fa-save mr-1"></i> Simpan Peminjaman
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- ============================================================== -->
    <!-- MODAL 2: FORM PEMAKAIAN BAHAN HABIS PAKAI                      -->
    <!-- ============================================================== -->
    <div class="modal fade" id="modalPemakaianBahan" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow" style="border-radius: 12px;">
          <form action="/admin/kejuruan/inventaris-lab/bahan/pakai" method="POST">
            @csrf
            <div class="modal-header bg-light">
              <h5 class="modal-title font-weight-bold text-dark" style="font-size: 1.05rem;">
                <i class="fas fa-minus-circle text-success mr-2"></i> Catat Pemakaian Bahan Praktik
              </h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body p-4">
              <div class="form-group mb-3">
                <label class="font-weight-bold text-dark small mb-1">Pilih Bahan Habis Pakai <span class="text-danger">*</span></label>
                <select name="id_aset" class="form-control select2bs4" required style="border-radius: 6px;">
                  <option value="">-- Pilih Bahan --</option>
                  @foreach($asetBahanList as $bh)
                    <option value="{{ $bh->id_aset }}">
                      {{ $bh->nama_aset }} (Stok Tersedia: {{ $bh->stok_tersedia }} {{ $bh->satuan }}) - {{ $bh->lab->nama_lab ?? '' }}
                    </option>
                  @endforeach
                </select>
              </div>

              <div class="row">
                <div class="col-md-6 mb-3">
                  <label class="font-weight-bold text-dark small mb-1">Jumlah Pemakaian <span class="text-danger">*</span></label>
                  <input type="number" name="jumlah_pakai" min="1" value="1" class="form-control" required style="border-radius: 6px;">
                </div>
                <div class="col-md-6 mb-3">
                  <label class="font-weight-bold text-dark small mb-1">Tanggal Pakai <span class="text-danger">*</span></label>
                  <input type="date" name="tanggal_pakai" value="{{ date('Y-m-d') }}" class="form-control" required style="border-radius: 6px;">
                </div>
              </div>

              <div class="form-group mb-3">
                <label class="font-weight-bold text-dark small mb-1">Keperluan Praktik <span class="text-danger">*</span></label>
                <input type="text" name="keperluan" class="form-control" placeholder="Contoh: Praktik Pengelasan Modul 3 Kelas XI" required style="border-radius: 6px;">
              </div>

              <div class="form-group mb-3">
                <label class="font-weight-bold text-dark small mb-1">Guru Pengampu</label>
                <select name="id_pengguna_guru" class="form-control select2bs4" style="border-radius: 6px;">
                  <option value="">-- Pilih Guru Pengampu --</option>
                  @foreach($gurus as $g)
                    <option value="{{ $g->id_guru }}">{{ $g->nama_guru }}</option>
                  @endforeach
                </select>
              </div>

              <div class="form-group mb-0">
                <label class="font-weight-bold text-dark small mb-1">Catatan Tambahan</label>
                <textarea name="catatan" rows="2" class="form-control" placeholder="Keterangan sisa potongan kabel, dll..." style="border-radius: 6px;"></textarea>
              </div>
            </div>
            <div class="modal-footer bg-light py-2">
              <button type="button" class="btn btn-outline-secondary btn-sm" data-dismiss="modal" style="border-radius: 6px;">Batal</button>
              <button type="submit" class="btn btn-success btn-sm px-3 font-weight-600" style="border-radius: 6px;">
                <i class="fas fa-check mr-1"></i> Simpan Pemakaian
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- ============================================================== -->
    <!-- MODAL 3: LAPORAN KERUSAKAN & JADWAL PERAWATAN                  -->
    <!-- ============================================================== -->
    <div class="modal fade" id="modalPerawatan" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow" style="border-radius: 12px;">
          <form action="/admin/kejuruan/inventaris-lab/perawatan" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-header bg-light">
              <h5 class="modal-title font-weight-bold text-dark" style="font-size: 1.05rem;">
                <i class="fas fa-tools text-warning mr-2"></i> Lapor Kerusakan / Jadwal Servis
              </h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body p-4">
              <div class="form-group mb-3">
                <label class="font-weight-bold text-dark small mb-1">Pilih Alat / Aset Lab <span class="text-danger">*</span></label>
                <select name="id_aset" class="form-control select2bs4" required style="border-radius: 6px;">
                  <option value="">-- Pilih Aset --</option>
                  @foreach($asetAllList as $all)
                    <option value="{{ $all->id_aset }}">
                      {{ $all->nama_aset }} ({{ $all->kode_aset }}) - {{ $all->lab->nama_lab ?? '' }}
                    </option>
                  @endforeach
                </select>
              </div>

              <div class="row">
                <div class="col-md-6 mb-3">
                  <label class="font-weight-bold text-dark small mb-1">Jenis Layanan <span class="text-danger">*</span></label>
                  <select name="jenis" class="form-control" required style="border-radius: 6px;">
                    <option value="perbaikan">Perbaikan Kerusakan</option>
                    <option value="perawatan_rutin">Perawatan Rutin / Servis</option>
                    <option value="kalibrasi">Kalibrasi Alat</option>
                  </select>
                </div>
                <div class="col-md-6 mb-3">
                  <label class="font-weight-bold text-dark small mb-1">Tanggal Jadwal / Lapor <span class="text-danger">*</span></label>
                  <input type="date" name="tanggal_jadwal" value="{{ date('Y-m-d') }}" class="form-control" required style="border-radius: 6px;">
                </div>
              </div>

              <div class="form-group mb-3">
                <label class="font-weight-bold text-dark small mb-1">Deskripsi Kendala / Kerusakan <span class="text-danger">*</span></label>
                <textarea name="deskripsi_kendala" rows="2" class="form-control" placeholder="Contoh: Mesin bubut bergetar kencang dan bearing bunyi..." required style="border-radius: 6px;"></textarea>
              </div>

              <div class="row">
                <div class="col-md-6 mb-3">
                  <label class="font-weight-bold text-dark small mb-1">Teknisi / Bengkel Servis</label>
                  <input type="text" name="teknisi" class="form-control" placeholder="Contoh: Vendor Resmi / Internal" style="border-radius: 6px;">
                </div>
                <div class="col-md-6 mb-3">
                  <label class="font-weight-bold text-dark small mb-1">Estimasi Biaya (Rp)</label>
                  <input type="number" step="0.01" name="biaya" value="0" class="form-control" style="border-radius: 6px;">
                </div>
              </div>

              <div class="form-group mb-0">
                <label class="font-weight-bold text-dark small mb-1">Foto Bukti Kerusakan</label>
                <input type="file" name="foto_sebelum" class="form-control-file" accept="image/*">
                <small class="text-muted">Maksimal 3MB (JPG/PNG).</small>
              </div>
            </div>
            <div class="modal-footer bg-light py-2">
              <button type="button" class="btn btn-outline-secondary btn-sm" data-dismiss="modal" style="border-radius: 6px;">Batal</button>
              <button type="submit" class="btn btn-warning text-dark btn-sm px-3 font-weight-600" style="border-radius: 6px;">
                <i class="fas fa-save mr-1"></i> Simpan Laporan
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

  </div>
</div>

@push('scripts')
<script>
  $(document).ready(function() {
    $('#tipePeminjamSelect').on('change', function() {
      if ($(this).val() === 'siswa') {
        $('#peminjamGuruWrapper').addClass('d-none');
        $('#peminjamSiswaWrapper').removeClass('d-none');
      } else {
        $('#peminjamGuruWrapper').removeClass('d-none');
        $('#peminjamSiswaWrapper').addClass('d-none');
      }
    });
  });
</script>
@endpush
@endsection
