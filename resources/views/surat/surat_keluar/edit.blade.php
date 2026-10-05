@extends($layout)

@section('content')
<!-- Header -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row align-items-center mb-2">
      <div class="col-sm-7 d-flex align-items-center">
        <a href="{{ route('admin.surat-keluar.index') }}" class="btn btn-light rounded-circle shadow-sm mr-3 text-secondary" style="width: 42px; height: 42px; display: inline-flex; align-items: center; justify-content: center;">
          <i class="fas fa-arrow-left"></i>
        </a>
        <div class="d-flex flex-column justify-content-center">
          <h1 class="m-0 font-weight-bold text-dark" style="line-height: 1.2; font-size: 1.35rem;">Edit Data Surat Keluar</h1>
          <p class="text-muted mt-1 mb-0" style="line-height: 1.2; font-size: 0.85rem;">Perbarui rincian perihal, tujuan, penerima, atau berkas lampiran arsip</p>
        </div>
      </div>
      <div class="col-sm-5">
        <ol class="breadcrumb float-sm-right bg-transparent p-0 mb-0">
          <li class="breadcrumb-item"><a href="/admin/dashboard" class="text-primary font-weight-500">Dashboard</a></li>
          <li class="breadcrumb-item"><a href="{{ route('admin.surat-keluar.index') }}" class="text-primary font-weight-500">Surat Keluar</a></li>
          <li class="breadcrumb-item active">Edit Surat</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<!-- Main Content -->
<div class="content">
  <div class="container-fluid">

    <!-- Error Messages -->
    @if($errors->any())
      <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0" role="alert" style="border-radius: 10px;">
        <h6 class="font-weight-bold mb-1"><i class="fas fa-exclamation-triangle mr-2"></i> Mohon lengkapi data berikut:</h6>
        <ul class="mb-0 pl-3">
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    @endif

    <form action="{{ route('admin.surat-keluar.update', $surat->id) }}" method="POST" enctype="multipart/form-data">
      @csrf
      @method('PUT')

      <div class="row">
        <!-- Kolom Kiri: Form Input -->
        <div class="col-lg-8">
          <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px;">
            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
              <div class="d-flex align-items-center">
                <i class="fas fa-edit text-primary mr-2"></i>
                <h5 class="font-weight-bold text-dark mb-0" style="font-size: 1rem;">Perbarui Rincian Surat</h5>
              </div>
              <span class="badge badge-light border text-primary font-monospace px-2 py-1" style="font-size: 0.85rem;">
                {{ $surat->nomor_surat }}
              </span>
            </div>

            <div class="card-body">
              <div class="row">
                <!-- Klasifikasi Surat (Disabled/Read-only untuk integritas nomor) -->
                <div class="col-md-6 form-group">
                  <label for="kode_klasifikasi" class="font-weight-bold text-dark mb-1">
                    Klasifikasi Surat
                  </label>
                  <input type="text" class="form-control bg-light font-weight-bold" value="[{{ $surat->kode_klasifikasi }}] {{ $surat->nama_klasifikasi }}" readonly>
                  <small class="text-muted">Klasifikasi terikat dengan penomoran yang telah diterbitkan.</small>
                </div>

                <!-- Tanggal Surat -->
                <div class="col-md-6 form-group">
                  <label for="tanggal_surat" class="font-weight-bold text-dark mb-1">
                    Tanggal Surat <span class="text-danger">*</span>
                  </label>
                  <input type="date" class="form-control @error('tanggal_surat') is-invalid @enderror" id="tanggal_surat" name="tanggal_surat" value="{{ old('tanggal_surat', $surat->tanggal_surat ? \Carbon\Carbon::parse($surat->tanggal_surat)->format('Y-m-d') : '') }}" required>
                </div>
              </div>

              <!-- Perihal -->
              <div class="form-group">
                <label for="perihal" class="font-weight-bold text-dark mb-1">
                  Perihal / Hal Surat <span class="text-danger">*</span>
                </label>
                <input type="text" class="form-control @error('perihal') is-invalid @enderror" id="perihal" name="perihal" value="{{ old('perihal', $surat->perihal) }}" required>
              </div>

              <!-- Tujuan Surat -->
              <div class="form-group">
                <label for="tujuan_surat" class="font-weight-bold text-dark mb-1">
                  Tujuan / Penerima Surat <span class="text-danger">*</span>
                </label>
                <input type="text" class="form-control @error('tujuan_surat') is-invalid @enderror" id="tujuan_surat" name="tujuan_surat" value="{{ old('tujuan_surat', $surat->tujuan_surat) }}" required>
              </div>

              <div class="row">
                <!-- Penandatangan -->
                <div class="col-md-6 form-group">
                  <label for="penandatangan" class="font-weight-bold text-dark mb-1">
                    Penandatangan Surat
                  </label>
                  <input type="text" class="form-control @error('penandatangan') is-invalid @enderror" id="penandatangan" name="penandatangan" value="{{ old('penandatangan', $surat->penandatangan) }}">
                </div>

                <!-- Kategori Terkait: Siswa -->
                <div class="col-md-6 form-group">
                  <label for="id_siswa" class="font-weight-bold text-dark mb-1">
                    Siswa Terkait <small class="text-muted">(Opsional)</small>
                  </label>
                  <select class="form-control select2 @error('id_siswa') is-invalid @enderror" id="id_siswa" name="id_siswa">
                    <option value="">-- Bukan Surat Khusus Siswa --</option>
                    @foreach($siswaList as $s)
                      <option value="{{ $s->id_siswa }}" {{ old('id_siswa', $surat->id_siswa) == $s->id_siswa ? 'selected' : '' }}>
                        {{ $s->nama_siswa }} ({{ $s->kelas->nama_kelas ?? 'Tanpa Kelas' }})
                      </option>
                    @endforeach
                  </select>
                </div>
              </div>

              <div class="row">
                <!-- Guru Terkait -->
                <div class="col-md-6 form-group">
                  <label for="id_guru" class="font-weight-bold text-dark mb-1">
                    Guru / Pegawai Terkait <small class="text-muted">(Opsional)</small>
                  </label>
                  <select class="form-control select2 @error('id_guru') is-invalid @enderror" id="id_guru" name="id_guru">
                    <option value="">-- Bukan Surat Tugas Guru --</option>
                    @foreach($guruList as $g)
                      <option value="{{ $g->id_guru }}" {{ old('id_guru', $surat->id_guru) == $g->id_guru ? 'selected' : '' }}>
                        {{ $g->nama_guru }}
                      </option>
                    @endforeach
                  </select>
                </div>

                <!-- Ganti File Lampiran -->
                <div class="col-md-6 form-group">
                  <label for="file_lampiran" class="font-weight-bold text-dark mb-1">
                    Ganti Berkas / Arsip <small class="text-muted">(Biarkan kosong jika tidak diubah)</small>
                  </label>
                  <div class="custom-file">
                    <input type="file" class="custom-file-input @error('file_lampiran') is-invalid @enderror" id="file_lampiran" name="file_lampiran" accept=".pdf,.jpg,.jpeg,.png">
                    <label class="custom-file-label" for="file_lampiran">Pilih berkas baru...</label>
                  </div>
                </div>
              </div>

              <!-- Catatan Tambahan -->
              <div class="form-group mb-0">
                <label for="keterangan" class="font-weight-bold text-dark mb-1">
                  Catatan / Keterangan Tambahan
                </label>
                <textarea class="form-control @error('keterangan') is-invalid @enderror" id="keterangan" name="keterangan" rows="3">{{ old('keterangan', $surat->keterangan) }}</textarea>
              </div>

            </div>
          </div>
        </div>

        <!-- Kolom Kanan: Info Arsip & Aksi -->
        <div class="col-lg-4">
          <!-- Info Terdaftar -->
          <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px; background: #ffffff; border-top: 4px solid #17a2b8 !important;">
            <div class="card-body p-4">
              <h6 class="font-weight-bold text-dark mb-3">
                <i class="fas fa-info-circle text-info mr-1"></i> Informasi Arsip
              </h6>

              <div class="mb-2">
                <small class="text-muted d-block">Nomor Surat Resmi:</small>
                <strong class="font-monospace text-primary" style="font-size: 1.05rem;">{{ $surat->nomor_surat }}</strong>
              </div>

              <div class="row mt-2">
                <div class="col-6 mb-2">
                  <small class="text-muted d-block">No. Urut:</small>
                  <span class="font-weight-bold text-dark">#{{ str_pad($surat->no_urut, 3, '0', STR_PAD_LEFT) }}</span>
                </div>
                <div class="col-6 mb-2">
                  <small class="text-muted d-block">Tahun Arsip:</small>
                  <span class="font-weight-bold text-dark">{{ $surat->tahun }}</span>
                </div>
                <div class="col-12 mb-2">
                  <small class="text-muted d-block">Dicatat Oleh:</small>
                  <span class="text-dark">{{ $surat->creator->nama_guru ?? 'Administrator' }}</span>
                </div>
                <div class="col-12">
                  <small class="text-muted d-block">Waktu Pembuatan:</small>
                  <span class="text-muted small">{{ $surat->created_at ? $surat->created_at->format('d M Y, H:i') : '-' }}</span>
                </div>
              </div>

              @if($surat->file_lampiran)
                <div class="border-top pt-3 mt-3">
                  <small class="text-muted d-block mb-1">Berkas Lampiran Saat Ini:</small>
                  @php
                    $ext = pathinfo($surat->file_lampiran, PATHINFO_EXTENSION);
                    $isPdf = strtolower($ext) === 'pdf';
                    $fileUrl = asset('storage/lampiran_surat_keluar/' . $surat->file_lampiran);
                  @endphp
                  <a href="{{ $fileUrl }}" target="_blank" class="btn btn-outline-info btn-sm btn-block rounded-pill">
                    <i class="fas {{ $isPdf ? 'fa-file-pdf text-danger' : 'fa-file-image' }} mr-1"></i> Buka Lampiran Tersimpan
                  </a>
                </div>
              @endif
            </div>
          </div>

          <!-- Card Tombol Aksi -->
          <div class="card border-0 shadow-sm" style="border-radius: 12px;">
            <div class="card-body p-4">
              <button type="submit" class="btn btn-primary btn-block py-2 font-weight-bold shadow-sm" style="border-radius: 8px;">
                <i class="fas fa-save mr-1"></i> Simpan Perubahan
              </button>
              <a href="{{ route('admin.surat-keluar.index') }}" class="btn btn-outline-secondary btn-block mt-2" style="border-radius: 8px;">
                <i class="fas fa-arrow-left mr-1"></i> Kembali ke Buku Agenda
              </a>
            </div>
          </div>
        </div>
      </div>

    </form>

  </div>
</div>

@section('scripts')
<script>
$(document).ready(function() {
  bsCustomFileInput.init();
});
</script>
@endsection
@endsection
