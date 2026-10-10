@extends($layout)
@section('content')
<!-- Content Header -->
<div class="content-header pb-2">
  <div class="container-fluid">
    <div class="row align-items-center mb-2">
      <div class="col-sm-7 d-flex align-items-center">
        <i class="fas fa-file-contract text-primary mr-3" style="font-size: 2rem; flex-shrink: 0;"></i>
        <div class="d-flex flex-column justify-content-center">
          <h1 class="m-0 font-weight-bold text-dark" style="line-height: 1.2;">Detail Pengajuan Surat PKL</h1>
          <p class="text-muted mt-1 mb-0" style="line-height: 1.2; font-size: 0.85rem;">Kode Pengajuan: <strong class="text-dark">{{ $pengajuan->kode_pengajuan }}</strong></p>
        </div>
      </div>
      <div class="col-sm-5">
        <ol class="breadcrumb float-sm-right bg-transparent p-0 mb-0">
          <li class="breadcrumb-item"><a href="/admin/dashboard">Dashboard</a></li>
          <li class="breadcrumb-item"><a href="{{ route('admin.pengajuan-pkl.index') }}">Pengajuan Surat PKL</a></li>
          <li class="breadcrumb-item active">Detail</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<div class="content">
  <div class="container-fluid">

    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
        <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert">&times;</button>
      </div>
    @endif

    @if(session('info'))
      <div class="alert alert-info alert-dismissible fade show shadow-sm" role="alert">
        <i class="fas fa-info-circle mr-2"></i> {{ session('info') }}
        <button type="button" class="close" data-dismiss="alert">&times;</button>
      </div>
    @endif

    <div class="row">
      <!-- Left Column: Informasi Surat & Perusahaan -->
      <div class="col-lg-8">
        <div class="card shadow-sm border-0 mb-3" style="border-radius: 14px;">
          <div class="card-header bg-white py-3 border-0 d-flex align-items-center justify-content-between flex-wrap">
            <h5 class="card-title font-weight-bold text-dark mb-0">
              <i class="fas fa-building text-primary mr-2"></i> Data Tujuan &amp; Pelaksanaan PKL
            </h5>
            <span class="ml-auto">{!! $pengajuan->status_badge !!}</span>
          </div>
          <div class="card-body pt-0">
            <table class="table table-bordered table-striped">
              <tbody>
                <tr>
                  <th style="width: 200px;" class="bg-light">Perusahaan Tujuan</th>
                  <td>
                    <strong class="text-dark" style="font-size: 1.05rem;">{{ $pengajuan->nama_perusahaan }}</strong>
                    @if($pengajuan->perusahaan)
                      <span class="badge badge-success ml-2 px-2 py-0.5" style="font-size: 0.72rem;">Mitra Terverifikasi</span>
                    @endif
                  </td>
                </tr>
                <tr>
                  <th class="bg-light">Ditujukan Kepada</th>
                  <td>
                    <strong>{{ $pengajuan->ditujukan_kepada }}</strong>
                    <span class="text-muted">({{ $pengajuan->jabatan_tujuan ?: 'H.R & Learning Manager' }})</span>
                  </td>
                </tr>
                <tr>
                  <th class="bg-light">Alamat Perusahaan</th>
                  <td>{{ $pengajuan->alamat_perusahaan ?: '-' }}</td>
                </tr>
                <tr>
                  <th class="bg-light">Periode Pelaksanaan</th>
                  <td>
                    <span class="font-weight-bold text-primary">{{ $pengajuan->periode_teks }}</span>
                    @if($pengajuan->tanggal_mulai && $pengajuan->tanggal_selesai)
                      <div class="text-muted small mt-0.5">
                        <i class="fas fa-calendar-day mr-1"></i> Tanggal: {{ $pengajuan->tanggal_mulai->format('d/m/Y') }} s/d {{ $pengajuan->tanggal_selesai->format('d/m/Y') }}
                      </div>
                    @endif
                  </td>
                </tr>
                <tr>
                  <th class="bg-light">Nomor Surat Resmi</th>
                  <td>
                    @if($pengajuan->nomor_surat)
                      <span class="badge badge-light border text-dark font-monospace font-weight-bold px-2 py-1" style="font-size: 0.9rem;">
                        {{ $pengajuan->nomor_surat }}
                      </span>
                    @else
                      <span class="text-muted italic">(Belum di-ACC / belum terbit nomor surat)</span>
                    @endif
                  </td>
                </tr>
                <tr>
                  <th class="bg-light">Tanggal Surat</th>
                  <td>{{ $pengajuan->tanggal_surat_formatted }}</td>
                </tr>
                <tr>
                  <th class="bg-light">Penandatangan Surat</th>
                  <td>
                    <strong>{{ $pengajuan->nama_penandatangan }}</strong> &bull; {{ $pengajuan->jabatan_penandatangan }}
                    @if($pengajuan->kontak_penandatangan)
                      <span class="text-muted">({{ $pengajuan->kontak_penandatangan }})</span>
                    @endif
                  </td>
                </tr>
                <tr>
                  <th class="bg-light">Perwakilan Siswa (Pemohon)</th>
                  <td>
                    <strong>{{ $pengajuan->nama_pemohon ?: '-' }}</strong>
                    @if($pengajuan->kontak_pemohon)
                      <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $pengajuan->kontak_pemohon) }}" target="_blank" class="badge badge-success ml-2 px-2 py-1">
                        <i class="fab fa-whatsapp mr-1"></i> Hubungi: {{ $pengajuan->kontak_pemohon }}
                      </a>
                    @endif
                  </td>
                </tr>
                @if($pengajuan->catatan_siswa)
                <tr>
                  <th class="bg-light">Catatan dari Siswa</th>
                  <td class="text-secondary italic">{{ $pengajuan->catatan_siswa }}</td>
                </tr>
                @endif
                @if($pengajuan->catatan_bkk)
                <tr>
                  <th class="bg-light">Catatan dari BKK</th>
                  <td class="text-danger font-weight-500">{{ $pengajuan->catatan_bkk }}</td>
                </tr>
                @endif
              </tbody>
            </table>
          </div>
        </div>

        <!-- Tabel Daftar Siswa yang Diajukan -->
        <div class="card shadow-sm border-0 mb-3" style="border-radius: 14px;">
          <div class="card-header bg-white py-3 border-0 d-flex align-items-center justify-content-between">
            <h5 class="card-title font-weight-bold text-dark mb-0">
              <i class="fas fa-users text-primary mr-2"></i> Daftar Siswa Dalam Surat Permohonan ({{ $pengajuan->siswaList->count() }} Orang)
            </h5>
          </div>
          <div class="card-body p-0 table-responsive">
            <table class="table table-bordered table-striped mb-0">
              <thead class="bg-light">
                <tr>
                  <th style="width: 50px;" class="text-center">NO</th>
                  <th>NAMA LENGKAP SISWA</th>
                  <th>PROGRAM KEAHLIAN</th>
                  <th>KELAS</th>
                  <th>NIS</th>
                </tr>
              </thead>
              <tbody>
                @forelse($pengajuan->siswaList as $idx => $s)
                <tr>
                  <td class="text-center font-weight-bold">{{ $idx + 1 }}</td>
                  <td class="font-weight-bold text-dark">{{ $s->nama_siswa }}</td>
                  <td>
                    <span class="badge badge-info px-2 py-1" style="font-size: 0.8rem;">
                      {{ $s->program_keahlian }}
                    </span>
                  </td>
                  <td>{{ $s->kelas ?: '-' }}</td>
                  <td>{{ $s->nis ?: '-' }}</td>
                </tr>
                @empty
                <tr>
                  <td colspan="5" class="text-center py-3 text-muted">(Belum ada siswa dalam pengajuan ini)</td>
                </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Right Column: Aksi & Pengesahan BKK -->
      <div class="col-lg-4">
        
        <!-- Panel Cetak Surat (Jika sudah disetujui) -->
        @if($pengajuan->status === 'disetujui')
        <div class="card shadow-sm border mb-3 text-center" style="border-radius: 14px; background-color: #ffffff !important;">
          <div class="card-body p-4">
            <div class="mb-2">
              <span class="badge badge-success px-3 py-1 font-weight-bold" style="font-size: 0.85rem; border-radius: 20px;">
                <i class="fas fa-check-circle mr-1"></i> SURAT TELAH DI-ACC
              </span>
            </div>
            <h5 class="font-weight-bold text-dark mt-2 mb-1" style="font-size: 1.15rem;">Surat Permohonan PKL Siap Dicetak</h5>
            <p class="text-muted small mb-3">Format surat resmi lengkap dengan kop sekolah dari setting, tabel siswa, dan pengesahan wakahubin.</p>
            
            <!-- Ringkasan Info Surat -->
            <div class="bg-light rounded p-2 mb-3 text-left border" style="font-size: 0.85rem;">
              <div class="d-flex justify-content-between py-1 border-bottom">
                <span class="text-muted">Nomor Surat:</span>
                <strong class="text-dark">{{ $pengajuan->nomor_surat ?: '-' }}</strong>
              </div>
              <div class="d-flex justify-content-between py-1 border-bottom">
                <span class="text-muted">Tanggal Surat:</span>
                <span class="text-dark font-weight-500">{{ $pengajuan->tanggal_surat_formatted }}</span>
              </div>
              <div class="d-flex justify-content-between py-1">
                <span class="text-muted">Penandatangan:</span>
                <span class="text-dark font-weight-500">{{ $pengajuan->nama_penandatangan ?: 'Nanan Supriatna' }}</span>
              </div>
            </div>

            <div class="d-flex flex-column" style="gap: 8px;">
              <a href="{{ route('admin.pengajuan-pkl.cetak', $pengajuan->id_pengajuan) }}" target="_blank" class="btn btn-warning font-weight-bold shadow-sm" style="border-radius: 8px;">
                <i class="fas fa-print mr-1"></i> Buka &amp; Cetak Surat
              </a>
              <a href="{{ route('admin.pengajuan-pkl.pdf', $pengajuan->id_pengajuan) }}" target="_blank" class="btn btn-pdf-action shadow-sm">
                <i class="fas fa-file-pdf mr-1"></i> Cetak / Unduh File PDF
              </a>
            </div>
          </div>
        </div>
        @endif

        <!-- Card Tindakan Verifikasi BKK -->
        <div class="card shadow-sm border-0 mb-3" style="border-radius: 14px;">
          <div class="card-header bg-white py-3 border-0">
            <h5 class="card-title font-weight-bold text-dark mb-0">
              <i class="fas fa-tasks text-primary mr-2"></i> Tindakan BKK / Hubin
            </h5>
          </div>
          <div class="card-body">
            @if($pengajuan->status === 'menunggu')
              <div class="alert alert-warning mb-3 small">
                <i class="fas fa-clock mr-1"></i> Pengajuan ini sedang menunggu verifikasi dan persetujuan (ACC) dari pihak BKK.
              </div>

              <!-- Tombol Buka Modal ACC -->
              <button type="button" class="btn btn-success btn-block font-weight-bold mb-2 shadow-sm" data-toggle="modal" data-target="#modalAcc">
                <i class="fas fa-check-circle mr-1"></i> ACC / Setujui Pengajuan
              </button>

              <!-- Tombol Buka Modal Tolak -->
              <button type="button" class="btn btn-outline-danger btn-block font-weight-bold mb-2" data-toggle="modal" data-target="#modalTolak">
                <i class="fas fa-times-circle mr-1"></i> Tolak Pengajuan
              </button>
            @elseif($pengajuan->status === 'disetujui')
              <div class="alert alert-success mb-3 small">
                <i class="fas fa-check-circle mr-1"></i> Disetujui oleh: <strong>{{ $pengajuan->penyetuju->nama_guru ?? 'BKK' }}</strong><br>
                Pada: {{ $pengajuan->disetujui_pada ? $pengajuan->disetujui_pada->format('d/m/Y H:i') : '-' }}
              </div>

              <!-- Tombol Edit Data ACC -->
              <button type="button" class="btn btn-outline-secondary btn-block font-weight-bold mb-2" data-toggle="modal" data-target="#modalAcc">
                <i class="fas fa-edit mr-1"></i> Ubah Nomor Surat / Penandatangan
              </button>
            @else
              <div class="alert alert-danger mb-3 small">
                <i class="fas fa-times-circle mr-1"></i> Pengajuan ini berstatus <strong>Ditolak</strong>.<br>
                Catatan: {{ $pengajuan->catatan_bkk }}
              </div>
              <button type="button" class="btn btn-outline-success btn-block font-weight-bold mb-2" data-toggle="modal" data-target="#modalAcc">
                <i class="fas fa-redo mr-1"></i> Buka &amp; ACC Ulang
              </button>
            @endif

            <hr>
            <div class="d-flex flex-column" style="gap: 6px;">
              <a href="{{ route('admin.pengajuan-pkl.edit', $pengajuan->id_pengajuan) }}" class="btn btn-light border btn-block text-left text-dark">
                <i class="fas fa-pen mr-2 text-warning"></i> Edit Seluruh Data Pengajuan
              </a>
              <a href="{{ route('admin.pengajuan-pkl.index') }}" class="btn btn-light border btn-block text-left text-dark">
                <i class="fas fa-arrow-left mr-2 text-secondary"></i> Kembali ke Daftar Pengajuan
              </a>
            </div>
          </div>
        </div>

      </div>
    </div>

  </div>
</div>

<!-- Modal ACC / Persetujuan Surat -->
<div class="modal fade" id="modalAcc" tabindex="-1" role="dialog" aria-labelledby="modalAccLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content" style="border-radius: 14px; overflow: hidden;">
      <form action="{{ route('admin.pengajuan-pkl.acc', $pengajuan->id_pengajuan) }}" method="POST">
        @csrf
        <div class="modal-header bg-success text-white py-3">
          <h5 class="modal-title font-weight-bold" id="modalAccLabel">
            <i class="fas fa-check-circle mr-2"></i> Form Persetujuan (ACC) Surat PKL
          </h5>
          <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body p-4">
          <p class="text-muted small mb-3">
            Lengkapi nomor surat resmi dan pejabat penandatangan sebelum menerbitkan surat permohonan PKL ke perusahaan <strong>{{ $pengajuan->nama_perusahaan }}</strong>.
          </p>

          <div class="form-group">
            <label class="font-weight-bold text-dark small">Nomor Surat Resmi <span class="text-danger">*</span></label>
            <input type="text" name="nomor_surat" class="form-control" value="{{ old('nomor_surat', $pengajuan->nomor_surat ?: $suggestedNomorSurat) }}" required placeholder="Contoh: 4/OJT/SMK-WI/X/2024">
            <small class="text-muted">Nomor surat otomatis sesuai format penomoran resmi sekolah.</small>
          </div>

          <div class="form-group">
            <label class="font-weight-bold text-dark small">Tanggal Surat <span class="text-danger">*</span></label>
            <input type="date" name="tanggal_surat" class="form-control" value="{{ old('tanggal_surat', $pengajuan->tanggal_surat ? $pengajuan->tanggal_surat->format('Y-m-d') : now()->toDateString()) }}" required>
          </div>

          <div class="row">
            <div class="col-md-7">
              <div class="form-group">
                <label class="font-weight-bold text-dark small">Nama Penandatangan <span class="text-danger">*</span></label>
                <input type="text" name="nama_penandatangan" class="form-control" value="{{ old('nama_penandatangan', $pengajuan->nama_penandatangan ?: 'Nanan Supriatna') }}" required>
              </div>
            </div>
            <div class="col-md-5">
              <div class="form-group">
                <label class="font-weight-bold text-dark small">Kontak Penandatangan</label>
                <input type="text" name="kontak_penandatangan" class="form-control" value="{{ old('kontak_penandatangan', $pengajuan->kontak_penandatangan ?: '082312261278') }}">
              </div>
            </div>
          </div>

          <div class="form-group">
            <label class="font-weight-bold text-dark small">Jabatan Penandatangan <span class="text-danger">*</span></label>
            <input type="text" name="jabatan_penandatangan" class="form-control" value="{{ old('jabatan_penandatangan', $pengajuan->jabatan_penandatangan ?: 'Koordinator Traning & Wakahubin') }}" required>
          </div>

          @if($pengajuan->id_perusahaan)
          <div class="form-group p-2 bg-light rounded border">
            <div class="custom-control custom-checkbox">
              <input type="checkbox" class="custom-control-input" id="syncPkl" name="sync_to_siswa_pkl" value="1" checked>
              <label class="custom-control-label font-weight-500 text-dark small" for="syncPkl">
                Otomatis masukkan siswa ini ke database monitoring <strong>Siswa PKL</strong> (Status: PKL)
              </label>
            </div>
          </div>
          @endif

          <div class="form-group mb-0">
            <label class="font-weight-bold text-dark small">Catatan Tambahan BKK (Opsional)</label>
            <textarea name="catatan_bkk" class="form-control" rows="2" placeholder="Catatan internal BKK atau instruksi untuk siswa...">{{ old('catatan_bkk', $pengajuan->catatan_bkk) }}</textarea>
          </div>
        </div>
        <div class="modal-footer bg-light py-2">
          <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-success btn-sm font-weight-bold">
            <i class="fas fa-check mr-1"></i> Simpan &amp; ACC Sekarang
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Tolak Pengajuan -->
<div class="modal fade" id="modalTolak" tabindex="-1" role="dialog" aria-labelledby="modalTolakLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content" style="border-radius: 14px; overflow: hidden;">
      <form action="{{ route('admin.pengajuan-pkl.tolak', $pengajuan->id_pengajuan) }}" method="POST">
        @csrf
        <div class="modal-header bg-danger text-white py-3">
          <h5 class="modal-title font-weight-bold" id="modalTolakLabel">
            <i class="fas fa-times-circle mr-2"></i> Tolak Pengajuan Surat PKL
          </h5>
          <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body p-4">
          <p class="text-muted small mb-3">
            Berikan alasan penolakan agar siswa dapat mengetahui hal yang perlu diperbaiki atau mencari perusahaan lain.
          </p>
          <div class="form-group mb-0">
            <label class="font-weight-bold text-dark small">Alasan Penolakan <span class="text-danger">*</span></label>
            <textarea name="alasan_penolakan" class="form-control" rows="4" required placeholder="Contoh: Kuota perusahaan telah penuh, silakan ajukan ke mitra lain / periode PKL tidak sesuai jadwal..."></textarea>
          </div>
        </div>
        <div class="modal-footer bg-light py-2">
          <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-danger btn-sm font-weight-bold">
            <i class="fas fa-ban mr-1"></i> Konfirmasi Tolak Pengajuan
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<style>
  .btn-pdf-action {
    display: inline-block;
    width: 100%;
    padding: 7px 14px;
    font-size: 0.9rem;
    font-weight: 700;
    text-align: center;
    border-radius: 8px;
    border: 1.5px solid #dc2626 !important;
    color: #dc2626 !important;
    background-color: transparent !important;
    text-decoration: none !important;
    transition: all 0.2s ease-in-out;
  }
  .btn-pdf-action i {
    color: #dc2626 !important;
    transition: color 0.2s ease-in-out;
  }
  .btn-pdf-action:hover,
  .btn-pdf-action:focus,
  .btn-pdf-action:active {
    background-color: #dc2626 !important;
    border-color: #dc2626 !important;
    color: #ffffff !important;
    text-decoration: none !important;
    box-shadow: 0 4px 12px rgba(220, 38, 38, 0.25);
  }
  .btn-pdf-action:hover i,
  .btn-pdf-action:focus i,
  .btn-pdf-action:active i {
    color: #ffffff !important;
  }
</style>

@endsection
