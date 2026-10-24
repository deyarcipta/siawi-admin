@extends($layout)

@section('content')
<!-- Content Header -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row align-items-center mb-2">
      <div class="col-sm-7 d-flex align-items-center">
        <i class="fas fa-file-invoice text-primary mr-3" style="font-size: 2rem; flex-shrink: 0;"></i>
        <div class="d-flex flex-column justify-content-center">
          <h1 class="m-0 font-weight-bold text-dark" style="line-height: 1.2;">Rekapitulasi TKK Siswa</h1>
          <p class="text-muted mt-1 mb-0" style="line-height: 1.2; font-size: 0.85rem;">Laporan perolehan Tanda Kecakapan Khusus per rombel kelas untuk Kaprog dan Kesiapan Kerja Hubin</p>
        </div>
      </div>
      <div class="col-sm-5">
        <ol class="breadcrumb float-sm-right bg-transparent p-0 mb-0">
          <li class="breadcrumb-item"><a href="/admin/dashboard" class="text-primary font-weight-500">Dashboard</a></li>
          <li class="breadcrumb-item"><a href="/admin/kejuruan/tkk" class="text-primary font-weight-500">Master TKK</a></li>
          <li class="breadcrumb-item active">Rekap TKK</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<!-- Main content -->
<div class="content">
  <div class="container-fluid">
    <!-- Stat Cards -->
    <div class="row mb-3">
      <!-- Total Piagam Disahkan -->
      <div class="col-lg-3 col-6 mb-3 mb-lg-0">
        <div class="card border-0 shadow-sm h-100 bg-white" style="border-radius: 12px; border-left: 4px solid #16a34a !important;">
          <div class="card-body p-3 d-flex align-items-center justify-content-between">
            <div>
              <div class="text-muted text-uppercase font-weight-bold" style="font-size: 0.72rem; letter-spacing: 0.5px;">Piagam Disahkan</div>
              <div class="font-weight-bold text-dark mt-1" style="font-size: 1.7rem; line-height: 1.1;">{{ $totalDiberikan }} <span style="font-size: 0.85rem; font-weight: 500; color: #64748b;">Piagam</span></div>
              <small class="text-muted mt-1 d-block"><i class="fas fa-check-circle text-success mr-1"></i> Telah Terbit & Sah</small>
            </div>
            <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 46px; height: 46px; font-size: 1.25rem; background: #dcfce7; color: #16a34a; flex-shrink: 0;">
              <i class="fas fa-certificate"></i>
            </div>
          </div>
        </div>
      </div>

      <!-- Siswa Penerima TKK -->
      <div class="col-lg-3 col-6 mb-3 mb-lg-0">
        <div class="card border-0 shadow-sm h-100 bg-white" style="border-radius: 12px; border-left: 4px solid #0284c7 !important;">
          <div class="card-body p-3 d-flex align-items-center justify-content-between">
            <div>
              <div class="text-muted text-uppercase font-weight-bold" style="font-size: 0.72rem; letter-spacing: 0.5px;">Siswa Penerima</div>
              <div class="font-weight-bold text-dark mt-1" style="font-size: 1.7rem; line-height: 1.1;">{{ $totalSiswaPenerima }} <span style="font-size: 0.85rem; font-weight: 500; color: #64748b;">Siswa</span></div>
              <small class="text-muted mt-1 d-block"><i class="fas fa-user-graduate text-primary mr-1"></i> Memiliki Lencana TKK</small>
            </div>
            <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 46px; height: 46px; font-size: 1.25rem; background: #f0f9ff; color: #0284c7; flex-shrink: 0;">
              <i class="fas fa-medal"></i>
            </div>
          </div>
        </div>
      </div>

      <!-- Rombel Kelas -->
      <div class="col-lg-3 col-6 mb-3 mb-lg-0">
        <div class="card border-0 shadow-sm h-100 bg-white" style="border-radius: 12px; border-left: 4px solid #8b5cf6 !important;">
          <div class="card-body p-3 d-flex align-items-center justify-content-between">
            <div>
              <div class="text-muted text-uppercase font-weight-bold" style="font-size: 0.72rem; letter-spacing: 0.5px;">Rombel Kelas</div>
              <div class="font-weight-bold text-dark mt-1" style="font-size: 1.7rem; line-height: 1.1;">{{ $kelas->count() }} <span style="font-size: 0.85rem; font-weight: 500; color: #64748b;">Kelas</span></div>
              <small class="text-muted mt-1 d-block"><i class="fas fa-chalkboard text-purple mr-1"></i> Cakupan Jurusan</small>
            </div>
            <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 46px; height: 46px; font-size: 1.25rem; background: #f5f3ff; color: #8b5cf6; flex-shrink: 0;">
              <i class="fas fa-users"></i>
            </div>
          </div>
        </div>
      </div>

      <!-- Kesiapan Kerja Hubin -->
      <div class="col-lg-3 col-6 mb-3 mb-lg-0">
        <div class="card border-0 shadow-sm h-100 bg-white" style="border-radius: 12px; border-left: 4px solid #0d9488 !important;">
          <div class="card-body p-3 d-flex align-items-center justify-content-between">
            <div>
              <div class="text-muted text-uppercase font-weight-bold" style="font-size: 0.72rem; letter-spacing: 0.5px;">Kesiapan Hubin</div>
              <div class="font-weight-bold text-dark mt-1" style="font-size: 1.7rem; line-height: 1.1;">100% <span style="font-size: 0.85rem; font-weight: 500; color: #64748b;">Tervalidasi</span></div>
              <small class="text-muted mt-1 d-block"><i class="fas fa-briefcase text-teal mr-1"></i> Siap Portofolio DU-DI</small>
            </div>
            <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 46px; height: 46px; font-size: 1.25rem; background: #f0fdfa; color: #0d9488; flex-shrink: 0;">
              <i class="fas fa-briefcase"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Filter Toolbar Card -->
    <div class="card border-0 shadow-sm mb-3" style="border-radius: 12px;">
      <div class="card-body p-3">
        <form method="GET" action="/admin/kejuruan/tkk/rekap">
          <div class="row align-items-end">
            @if($jurusans->count() > 1)
              <div class="col-md-3 col-sm-6 mb-2 mb-md-0">
                <label class="font-weight-bold text-secondary mb-1" style="font-size: 0.78rem; text-transform: uppercase;">Jurusan</label>
                <select name="id_jurusan" class="form-control form-control-sm select2bs4">
                  <option value="">Semua Jurusan</option>
                  @foreach($jurusans as $j)
                    <option value="{{ $j->id_jurusan }}" {{ request('id_jurusan') == $j->id_jurusan ? 'selected' : '' }}>
                      {{ $j->nama_jurusan }}
                    </option>
                  @endforeach
                </select>
              </div>
            @endif

            <div class="col-md-3 col-sm-6 mb-2 mb-md-0">
              <label class="font-weight-bold text-secondary mb-1" style="font-size: 0.78rem; text-transform: uppercase;">Rombel Kelas</label>
              <select name="id_kelas" class="form-control form-control-sm select2bs4">
                <option value="">Semua Kelas</option>
                @foreach($kelas as $k)
                  <option value="{{ $k->id_kelas }}" {{ request('id_kelas') == $k->id_kelas ? 'selected' : '' }}>
                    {{ $k->nama_kelas }}
                  </option>
                @endforeach
              </select>
            </div>

            <div class="col-md-3 col-sm-6 mb-2 mb-md-0">
              <label class="font-weight-bold text-secondary mb-1" style="font-size: 0.78rem; text-transform: uppercase;">Status TKK</label>
              <select name="status" class="form-control form-control-sm">
                <option value="">Semua Status</option>
                <option value="diberikan" {{ request('status') == 'diberikan' ? 'selected' : '' }}>Diberikan (Resmi)</option>
                <option value="proses" {{ request('status') == 'proses' ? 'selected' : '' }}>Dalam Proses Syarat</option>
                <option value="dicabut" {{ request('status') == 'dicabut' ? 'selected' : '' }}>Dicabut</option>
              </select>
            </div>

            <div class="col-md-3 col-sm-6 d-flex align-items-center">
              <button type="submit" class="btn btn-sm btn-primary mr-2" style="border-radius: 8px;">
                <i class="fas fa-filter mr-1"></i> Filter
              </button>
              <a href="/admin/kejuruan/tkk/rekap" class="btn btn-sm btn-outline-secondary" style="border-radius: 8px;">
                <i class="fas fa-undo mr-1"></i> Reset
              </a>
            </div>
          </div>
        </form>

        <!-- Lower Action Toolbar -->
        <div class="pt-3 mt-3 border-top d-flex flex-wrap justify-content-between align-items-center">
          <div class="d-flex align-items-center mb-2 mb-md-0">
            <span class="text-muted mr-3" style="font-size: 0.85rem;">
              <i class="fas fa-info-circle mr-1 text-info"></i> Menampilkan rekapitulasi data riwayat TKK siswa terintegrasi Buku Induk Capaian.
            </span>
          </div>
          <div class="d-flex align-items-center">
            <a href="/admin/kejuruan/tkk/pemberian" class="btn btn-sm btn-outline-primary mr-2" style="border-radius: 8px;">
              <i class="fas fa-user-check mr-1"></i> Matriks Kelayakan Siswa
            </a>
            <a href="/admin/kejuruan/tkk" class="btn btn-sm btn-outline-secondary mr-2" style="border-radius: 8px;">
              <i class="fas fa-medal mr-1"></i> Master Lencana TKK
            </a>
            <button type="button" onclick="window.print()" class="btn btn-sm btn-secondary" style="border-radius: 8px;">
              <i class="fas fa-print mr-1"></i> Cetak Laporan
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Data Table Card -->
    <div class="card border-0 shadow-sm" style="border-radius: 12px;">
      <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-dark">
          <i class="fas fa-table mr-2 text-primary"></i> Data Capaian TKK Siswa
        </h6>
        <span class="badge badge-light border px-2 py-1" style="font-size: 0.8rem;">
          Total Data: {{ $rekapData->count() }}
        </span>
      </div>

      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0" style="font-size: 0.88rem;">
            <thead class="bg-light text-secondary text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">
              <tr>
                <th class="py-3 px-3 text-center" style="width: 50px;">No</th>
                <th class="py-3 px-3">Nama Siswa / NIS</th>
                <th class="py-3 px-3">Kelas & Jurusan</th>
                <th class="py-3 px-3">Lencana TKK</th>
                <th class="py-3 px-3 text-center">Tingkat</th>
                <th class="py-3 px-3 text-center">Tgl Sah</th>
                <th class="py-3 px-3">No. Piagam</th>
                <th class="py-3 px-3">Penguji / Pemberi</th>
                <th class="py-3 px-3 text-center">Bukti Dokumen</th>
                <th class="py-3 px-3 text-center">Status</th>
              </tr>
            </thead>
            <tbody>
              @forelse($rekapData as $idx => $r)
                <tr>
                  <td class="text-center font-weight-bold text-muted px-3">
                    {{ $loop->iteration }}
                  </td>
                  <td class="px-3">
                    <div class="font-weight-bold text-dark">{{ $r->siswa->nama_siswa ?? '-' }}</div>
                    <small class="text-muted">NIS: {{ $r->siswa->nis ?? '-' }}</small>
                  </td>
                  <td class="px-3">
                    <div class="badge badge-light border text-dark font-weight-bold">
                      {{ $r->siswa->kelas->nama_kelas ?? '-' }}
                    </div>
                    <div class="text-muted mt-1" style="font-size: 0.78rem;">
                      {{ $r->jenisTkk->jurusan->nama_jurusan ?? '-' }}
                    </div>
                  </td>
                  <td class="px-3">
                    @if(optional($r->jenisTkk)->nomor_klaster)
                      <span class="badge badge-primary px-2 py-0 font-weight-bold mr-1" style="font-size: 0.72rem;">
                        Klaster #{{ $r->jenisTkk->nomor_klaster }}
                      </span>
                    @endif
                    <div class="font-weight-bold text-primary d-inline-block">{{ $r->jenisTkk->nama_tkk ?? '-' }}</div>
                    <div class="mt-1">
                      @if(optional($r->jenisTkk)->kode_tkk)
                        <span class="badge badge-light border text-monospace mr-1" style="font-size: 0.7rem;">{{ $r->jenisTkk->kode_tkk }}</span>
                      @endif
                      <small class="text-muted">Tingkat {{ ucfirst(optional($r->jenisTkk)->tingkat ?? '-') }}</small>
                    </div>
                  </td>
                  <td class="text-center px-3">
                    @if(optional($r->jenisTkk)->tingkat == 'utama')
                      <span class="badge badge-danger px-2 py-1" style="font-size: 0.75rem;">Utama</span>
                    @elseif(optional($r->jenisTkk)->tingkat == 'madya')
                      <span class="badge badge-warning px-2 py-1 text-white" style="font-size: 0.75rem;">Madya</span>
                    @else
                      <span class="badge badge-info px-2 py-1" style="font-size: 0.75rem;">Purwa</span>
                    @endif
                  </td>
                  <td class="text-center px-3 text-nowrap">
                    {{ $r->tanggal_perolehan ? \Carbon\Carbon::parse($r->tanggal_perolehan)->format('d/m/Y') : '-' }}
                  </td>
                  <td class="px-3">
                    <span class="font-mono text-secondary font-weight-500" style="font-size: 0.82rem;">
                      {{ $r->nomor_piagam ?: '-' }}
                    </span>
                  </td>
                  <td class="px-3">
                    <span class="text-dark">{{ $r->pemberi->nama_guru ?? '-' }}</span>
                  </td>
                  <td class="text-center px-3">
                    @if($r->file_bukti)
                      <a href="{{ asset('storage/' . $r->file_bukti) }}" target="_blank" class="btn btn-xs btn-outline-info" style="border-radius: 6px;">
                        <i class="fas fa-file-alt mr-1"></i> Bukti
                      </a>
                    @else
                      <span class="text-muted" style="font-size: 0.8rem;">(Tidak ada)</span>
                    @endif
                  </td>
                  <td class="text-center px-3">
                    @if($r->status == 'diberikan')
                      <span class="badge badge-success px-2 py-1" style="font-size: 0.75rem;">
                        <i class="fas fa-check-circle mr-1"></i> Diberikan
                      </span>
                    @elseif($r->status == 'proses')
                      <span class="badge badge-warning text-white px-2 py-1" style="font-size: 0.75rem;">
                        <i class="fas fa-clock mr-1"></i> Proses
                      </span>
                    @else
                      <span class="badge badge-secondary px-2 py-1" style="font-size: 0.75rem;">
                        {{ ucfirst($r->status) }}
                      </span>
                    @endif
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="10" class="text-center py-5 text-muted">
                    <i class="fas fa-inbox fa-3x mb-3 text-muted" style="opacity: 0.4;"></i>
                    <p class="mb-0 font-weight-500">Belum ada riwayat penganugerahan TKK yang sesuai dengan filter.</p>
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
