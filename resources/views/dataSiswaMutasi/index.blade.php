@extends($layout)
@section('content')
  <!-- Content Header -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row align-items-center mb-2">
        <div class="col-sm-7 d-flex align-items-center">
          <i class="fas fa-user-times text-primary mr-3" style="font-size: 2rem; flex-shrink: 0;"></i>
          <div class="d-flex flex-column justify-content-center">
            <h1 class="m-0 font-weight-bold text-dark" style="line-height: 1.2;">Data Mutasi</h1>
            <p class="text-muted mt-1 mb-0" style="line-height: 1.2; font-size: 0.85rem;">Pemantauan dan rekapitulasi data siswa mutasi (Pindah Sekolah, Mengundurkan Diri, DO)</p>
          </div>
        </div>
        <div class="col-sm-5">
          <ol class="breadcrumb float-sm-right bg-transparent p-0 mb-0">
            <li class="breadcrumb-item"><a href="/admin/dashboard" class="text-primary font-weight-500">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="/admin/siswa" class="text-primary font-weight-500">Data Siswa</a></li>
            <li class="breadcrumb-item active">Data Mutasi</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <div class="content">
    <div class="container-fluid">
      <!-- Summary Stat Cards (Konsisten sesuai template) -->
      <div class="row mb-3">
        <!-- Total Mutasi -->
        <div class="col-lg-3 col-6 mb-3 mb-lg-0">
          <div class="card border-0 shadow-sm h-100 bg-white" style="border-radius: 12px; border-left: 4px solid #dc2626 !important;">
            <div class="card-body p-3 d-flex align-items-center justify-content-between">
              <div>
                <div class="text-muted text-uppercase font-weight-bold" style="font-size: 0.72rem; letter-spacing: 0.5px;">TOTAL SISWA MUTASI</div>
                <div class="font-weight-bold text-dark mt-1" style="font-size: 1.7rem; line-height: 1.1;">
                  {{ $totalMutasi }} <span style="font-size: 0.85rem; font-weight: 500; color: #64748b;">Siswa</span>
                </div>
                <small class="text-muted mt-1 d-block"><i class="fas fa-calendar-alt text-danger mr-1"></i> Periode Terpilih</small>
              </div>
              <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 46px; height: 46px; font-size: 1.25rem; background: #fef2f2; color: #dc2626; flex-shrink: 0;">
                <i class="fas fa-user-times"></i>
              </div>
            </div>
          </div>
        </div>

        <!-- Pindah Sekolah -->
        <div class="col-lg-3 col-6 mb-3 mb-lg-0">
          <div class="card border-0 shadow-sm h-100 bg-white" style="border-radius: 12px; border-left: 4px solid #f59e0b !important;">
            <div class="card-body p-3 d-flex align-items-center justify-content-between">
              <div>
                <div class="text-muted text-uppercase font-weight-bold" style="font-size: 0.72rem; letter-spacing: 0.5px;">PINDAH SEKOLAH</div>
                <div class="font-weight-bold text-dark mt-1" style="font-size: 1.7rem; line-height: 1.1;">
                  {{ $totalPindah }} <span style="font-size: 0.85rem; font-weight: 500; color: #64748b;">Siswa</span>
                </div>
                <small class="text-muted mt-1 d-block"><i class="fas fa-clinic-medical text-warning mr-1"></i> Ke Sekolah Lain</small>
              </div>
              <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 46px; height: 46px; font-size: 1.25rem; background: #fffbeb; color: #f59e0b; flex-shrink: 0;">
                <i class="fas fa-exchange-alt"></i>
              </div>
            </div>
          </div>
        </div>

        <!-- Mengundurkan Diri -->
        <div class="col-lg-3 col-6 mb-3 mb-lg-0">
          <div class="card border-0 shadow-sm h-100 bg-white" style="border-radius: 12px; border-left: 4px solid #0284c7 !important;">
            <div class="card-body p-3 d-flex align-items-center justify-content-between">
              <div>
                <div class="text-muted text-uppercase font-weight-bold" style="font-size: 0.72rem; letter-spacing: 0.5px;">MENGUNDURKAN DIRI</div>
                <div class="font-weight-bold text-dark mt-1" style="font-size: 1.7rem; line-height: 1.1;">
                  {{ $totalKeluar }} <span style="font-size: 0.85rem; font-weight: 500; color: #64748b;">Siswa</span>
                </div>
                <small class="text-muted mt-1 d-block"><i class="fas fa-envelope-open-text text-info mr-1"></i> Permintaan Orang Tua</small>
              </div>
              <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 46px; height: 46px; font-size: 1.25rem; background: #f0f9ff; color: #0284c7; flex-shrink: 0;">
                <i class="fas fa-file-signature"></i>
              </div>
            </div>
          </div>
        </div>

        <!-- DO / Lainnya -->
        <div class="col-lg-3 col-6 mb-3 mb-lg-0">
          <div class="card border-0 shadow-sm h-100 bg-white" style="border-radius: 12px; border-left: 4px solid #b91c1c !important;">
            <div class="card-body p-3 d-flex align-items-center justify-content-between">
              <div>
                <div class="text-muted text-uppercase font-weight-bold" style="font-size: 0.72rem; letter-spacing: 0.5px;">TANPA KETERANGAN / DO</div>
                <div class="font-weight-bold text-dark mt-1" style="font-size: 1.7rem; line-height: 1.1;">
                  {{ $totalLainnya }} <span style="font-size: 0.85rem; font-weight: 500; color: #64748b;">Siswa</span>
                </div>
                <small class="text-muted mt-1 d-block"><i class="fas fa-exclamation-circle text-danger mr-1"></i> Perlu Tindak Lanjut</small>
              </div>
              <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 46px; height: 46px; font-size: 1.25rem; background: #fee2e2; color: #b91c1c; flex-shrink: 0;">
                <i class="fas fa-user-slash"></i>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Filter Toolbar -->
      <div class="card border-0 shadow-sm mb-3" style="border-radius: 12px;">
        <div class="card-body p-3">
          <form action="/admin/siswaMutasi" method="GET" class="row align-items-end">
            <div class="col-md-3 col-sm-6 mb-2 mb-md-0">
              <label class="font-weight-bold text-secondary mb-1" style="font-size: 0.78rem; text-transform: uppercase;">Jenis Mutasi</label>
              <select name="jenis_mutasi" class="form-control form-control-sm">
                <option value="">-- Semua Jenis Mutasi --</option>
                <option value="Pindah Sekolah" {{ request('jenis_mutasi') == 'Pindah Sekolah' ? 'selected' : '' }}>Pindah Sekolah</option>
                <option value="Mengundurkan Diri" {{ request('jenis_mutasi') == 'Mengundurkan Diri' ? 'selected' : '' }}>Mengundurkan Diri</option>
                <option value="Dikeluarkan (DO)" {{ request('jenis_mutasi') == 'Dikeluarkan (DO)' ? 'selected' : '' }}>Dikeluarkan (DO)</option>
                <option value="Wafat" {{ request('jenis_mutasi') == 'Wafat' ? 'selected' : '' }}>Wafat</option>
                <option value="Lainnya" {{ request('jenis_mutasi') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
              </select>
            </div>

            <div class="col-md-3 col-sm-6 mb-2 mb-md-0">
              <label class="font-weight-bold text-secondary mb-1" style="font-size: 0.78rem; text-transform: uppercase;">Jurusan Terakhir</label>
              <select name="id_jurusan" class="form-control form-control-sm">
                <option value="">-- Semua Jurusan --</option>
                @foreach($jurusan as $j)
                  <option value="{{ $j->id_jurusan }}" {{ request('id_jurusan') == $j->id_jurusan ? 'selected' : '' }}>{{ $j->nama_jurusan }}</option>
                @endforeach
              </select>
            </div>

            <div class="col-md-3 col-sm-6 mb-2 mb-md-0">
              <label class="font-weight-bold text-secondary mb-1" style="font-size: 0.78rem; text-transform: uppercase;">Tahun Mutasi</label>
              <select name="tahun" class="form-control form-control-sm">
                <option value="">-- Semua Tahun --</option>
                @foreach($tahunList as $th)
                  <option value="{{ $th }}" {{ request('tahun') == $th ? 'selected' : '' }}>{{ $th }}</option>
                @endforeach
              </select>
            </div>

            <div class="col-md-3 col-sm-6 d-flex align-items-center" style="gap: 8px;">
              <button type="submit" class="btn btn-primary btn-sm flex-grow-1">
                <i class="fas fa-search mr-1"></i> Terapkan
              </button>
              <a href="/admin/siswaMutasi" class="btn btn-secondary btn-sm" title="Reset Filter">
                <i class="fas fa-undo"></i>
              </a>
            </div>
          </form>
        </div>
      </div>

      <!-- Tabel Siswa Mutasi -->
      <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
        <div class="card-header bg-white py-3 border-0 d-flex align-items-center justify-content-between flex-wrap">
          <h3 class="card-title text-dark font-weight-bold mb-0">
            <i class="fas fa-table text-primary mr-2"></i> Daftar Arsip Siswa Mutasi
          </h3>
          <div class="d-flex align-items-center ml-auto mt-2 mt-md-0" style="gap: 8px;">
            <a href="{{ route('admin.siswaMutasi.download', request()->query()) }}" class="btn btn-primary btn-sm shadow-sm">
              <i class="fas fa-download mr-1"></i> Download Excel
            </a>
            <a href="/admin/siswa" class="btn btn-outline-secondary btn-sm shadow-sm">
              <i class="fas fa-arrow-left mr-1"></i> Data Siswa Aktif
            </a>
          </div>
        </div>

        <div class="card-body">
          @php
            $canManage = $user && $user->hasAnyRole(['admin', 'tata_usaha', 'kurikulum', 'kesiswaan']);
          @endphp
          <div class="table-responsive">
            <table id="example2" class="table table-bordered table-hover table-striped">
              <thead>
                <tr>
                  <th style="width: 10px">No</th>
                  <th>Tanggal Mutasi</th>
                  <th>NIS / NISN</th>
                  <th>Nama Siswa</th>
                  <th>Kelas Terakhir</th>
                  <th>Jenis Mutasi</th>
                  <th>Sekolah Tujuan / Alasan</th>
                  <th style="width: 140px;">Action</th>
                </tr>
              </thead>
              <tbody>
                @forelse($mutasi as $data)
                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>
                    <span class="font-weight-600 text-dark">
                      {{ $data->tanggal_mutasi ? $data->tanggal_mutasi->format('d/m/Y') : '-' }}
                    </span>
                  </td>
                  <td>
                    <code>{{ $data->nis ?? '-' }}</code>
                    @if($data->nisn)
                      <br><small class="text-muted">NISN: {{ $data->nisn }}</small>
                    @endif
                  </td>
                  <td>
                    <span class="font-weight-bold text-dark">{{ $data->nama_siswa }}</span>
                    @if($data->jurusan_terakhir || ($data->jurusan->nama_jurusan ?? null))
                      <br><small class="text-muted">{{ $data->jurusan_terakhir ?? ($data->jurusan->nama_jurusan ?? '') }}</small>
                    @endif
                  </td>
                  <td>
                    <span class="badge badge-light border font-weight-normal px-2 py-1" style="font-size: 0.82rem;">
                      {{ $data->kelas_terakhir ?? ($data->kelas->nama_kelas ?? '-') }}
                    </span>
                  </td>
                  <td>
                    <span class="badge {{ $data->badge_class }} px-2 py-1" style="font-size: 0.78rem;">
                      {{ $data->jenis_mutasi }}
                    </span>
                  </td>
                  <td>
                    @if($data->sekolah_tujuan)
                      <div class="font-weight-600 text-dark" style="font-size: 0.88rem;">
                        <i class="fas fa-school text-muted mr-1"></i>{{ $data->sekolah_tujuan }}
                      </div>
                    @endif
                    @if($data->alasan_mutasi)
                      <small class="text-muted d-block mt-1">{{ Str::limit($data->alasan_mutasi, 60) }}</small>
                    @elseif(!$data->sekolah_tujuan)
                      <span class="text-muted">-</span>
                    @endif
                  </td>
                  <td>
                    <div class="d-flex align-items-center">
                      <!-- Detail -->
                      <a href="/admin/siswaMutasi/{{ $data->id_mutasi }}" class="btn btn-success btn-sm mr-1" title="Detail Siswa Mutasi">
                        <i class="fa fa-eye"></i>
                      </a>

                      @if($canManage)
                      <!-- Edit -->
                      <a href="/admin/siswaMutasi/{{ $data->id_mutasi }}/edit" class="btn btn-warning btn-sm mr-1 text-white" title="Edit Data Mutasi">
                        <i class="fa fa-edit"></i>
                      </a>

                      <!-- Restore -->
                      <form action="{{ route('admin.siswaMutasi.restore', $data->id_mutasi) }}" method="POST" class="d-inline form-restore mr-1">
                        @csrf
                        <button type="button" class="btn btn-info btn-sm btn-action-restore" title="Kembalikan ke Siswa Aktif" data-nama="{{ $data->nama_siswa }}">
                          <i class="fas fa-undo"></i>
                        </button>
                      </form>

                      <!-- Delete Permanen -->
                      <form action="/admin/siswaMutasi/{{ $data->id_mutasi }}" method="POST" class="d-inline form-delete">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-danger btn-sm btn-action-delete" title="Hapus Permanen dari Arsip" data-nama="{{ $data->nama_siswa }}">
                          <i class="fa fa-trash"></i>
                        </button>
                      </form>
                      @endif
                    </div>
                  </td>
                </tr>
                @empty
                <tr>
                  <td colspan="8" class="text-center py-4 text-muted">
                    <i class="fas fa-folder-open fa-2x mb-2 d-block text-secondary" style="opacity: 0.5;"></i>
                    Belum ada data siswa mutasi.
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  // Konfirmasi Kembalikan / Restore Siswa
  const restoreButtons = document.querySelectorAll('.btn-action-restore');
  restoreButtons.forEach(function (button) {
    button.addEventListener('click', function () {
      const form = this.closest('form');
      const nama = this.getAttribute('data-nama') || 'siswa ini';

      Swal.fire({
        title: 'Kembalikan Siswa?',
        text: `Data ${nama} akan dipindahkan kembali ke daftar Siswa Aktif.`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#17a2b8',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Kembalikan!',
        cancelButtonText: 'Batal'
      }).then((result) => {
        if (result.isConfirmed) {
          form.submit();
        }
      });
    });
  });

  // Konfirmasi Hapus Permanen
  const deleteButtons = document.querySelectorAll('.btn-action-delete');
  deleteButtons.forEach(function (button) {
    button.addEventListener('click', function () {
      const form = this.closest('form');
      const nama = this.getAttribute('data-nama') || 'siswa ini';

      Swal.fire({
        title: 'Hapus Permanen?',
        text: `Arsip mutasi untuk ${nama} akan dihapus permanen dan tidak dapat dipulihkan!`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Hapus Permanen!',
        cancelButtonText: 'Batal'
      }).then((result) => {
        if (result.isConfirmed) {
          form.submit();
        }
      });
    });
  });
});
</script>
@endpush
