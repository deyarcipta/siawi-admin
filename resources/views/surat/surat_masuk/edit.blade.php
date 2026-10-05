@extends($layout)

@section('content')
<!-- Header -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row align-items-center mb-2">
      <div class="col-sm-7 d-flex align-items-center">
        <a href="{{ route('admin.surat-masuk.index') }}" class="btn btn-light rounded-circle shadow-sm mr-3 text-secondary" style="width: 42px; height: 42px; display: inline-flex; align-items: center; justify-content: center;">
          <i class="fas fa-arrow-left"></i>
        </a>
        <div class="d-flex flex-column justify-content-center">
          <h1 class="m-0 font-weight-bold text-dark" style="line-height: 1.2; font-size: 1.35rem;">Edit Data Surat Masuk</h1>
          <p class="text-muted mt-1 mb-0" style="line-height: 1.2; font-size: 0.85rem;">Perbarui data instansi pengirim, perihal, disposisi pimpinan, atau berkas arsip</p>
        </div>
      </div>
      <div class="col-sm-5">
        <ol class="breadcrumb float-sm-right bg-transparent p-0 mb-0">
          <li class="breadcrumb-item"><a href="/admin/dashboard" class="text-primary font-weight-500">Dashboard</a></li>
          <li class="breadcrumb-item"><a href="{{ route('admin.surat-masuk.index') }}" class="text-primary font-weight-500">Surat Masuk</a></li>
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

    <form action="{{ route('admin.surat-masuk.update', $surat->id) }}" method="POST" enctype="multipart/form-data">
      @csrf
      @method('PUT')

      <div class="row">
        <!-- Kolom Kiri: Form Input -->
        <div class="col-lg-8">
          <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px;">
            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
              <div class="d-flex align-items-center">
                <i class="fas fa-edit text-success mr-2"></i>
                <h5 class="font-weight-bold text-dark mb-0" style="font-size: 1rem;">Perbarui Rincian Surat Masuk</h5>
              </div>
              <span class="badge badge-light border text-success font-monospace px-2 py-1" style="font-size: 0.85rem;">
                {{ $surat->nomor_agenda }}
              </span>
            </div>

            <div class="card-body">
              <div class="row">
                <!-- Nomor Surat Asli -->
                <div class="col-md-6 form-group">
                  <label for="nomor_surat_asal" class="font-weight-bold text-dark mb-1">
                    Nomor Surat Asal / Pengirim <span class="text-danger">*</span>
                  </label>
                  <input type="text" class="form-control @error('nomor_surat_asal') is-invalid @enderror" id="nomor_surat_asal" name="nomor_surat_asal" value="{{ old('nomor_surat_asal', $surat->nomor_surat_asal) }}" required>
                </div>

                <!-- Instansi / Pengirim -->
                <div class="col-md-6 form-group">
                  <label for="asal_surat" class="font-weight-bold text-dark mb-1">
                    Asal / Instansi Pengirim <span class="text-danger">*</span>
                  </label>
                  <input type="text" class="form-control @error('asal_surat') is-invalid @enderror" id="asal_surat" name="asal_surat" value="{{ old('asal_surat', $surat->asal_surat) }}" required>
                </div>
              </div>

              <!-- Perihal -->
              <div class="form-group">
                <label for="perihal" class="font-weight-bold text-dark mb-1">
                  Perihal / Isi Ringkas Surat <span class="text-danger">*</span>
                </label>
                <input type="text" class="form-control @error('perihal') is-invalid @enderror" id="perihal" name="perihal" value="{{ old('perihal', $surat->perihal) }}" required>
              </div>

              <div class="row">
                <!-- Tanggal Surat Asli -->
                <div class="col-md-6 form-group">
                  <label for="tanggal_surat" class="font-weight-bold text-dark mb-1">
                    Tanggal Surat Terbit <span class="text-danger">*</span>
                  </label>
                  <input type="date" class="form-control @error('tanggal_surat') is-invalid @enderror" id="tanggal_surat" name="tanggal_surat" value="{{ old('tanggal_surat', $surat->tanggal_surat ? \Carbon\Carbon::parse($surat->tanggal_surat)->format('Y-m-d') : '') }}" required>
                </div>

                <!-- Tanggal Diterima di Sekolah -->
                <div class="col-md-6 form-group">
                  <label for="tanggal_diterima" class="font-weight-bold text-dark mb-1">
                    Tanggal Diterima di Sekolah <span class="text-danger">*</span>
                  </label>
                  <input type="date" class="form-control @error('tanggal_diterima') is-invalid @enderror" id="tanggal_diterima" name="tanggal_diterima" value="{{ old('tanggal_diterima', $surat->tanggal_diterima ? \Carbon\Carbon::parse($surat->tanggal_diterima)->format('Y-m-d') : '') }}" required>
                </div>
              </div>

              <div class="row">
                <!-- Disposisi Kepada -->
                <div class="col-md-6 form-group">
                  <label for="disposisi_kepada" class="font-weight-bold text-dark mb-1">
                    Disposisi / Diteruskan Kepada
                  </label>
                  <input type="text" class="form-control @error('disposisi_kepada') is-invalid @enderror" id="disposisi_kepada" name="disposisi_kepada" value="{{ old('disposisi_kepada', $surat->disposisi_kepada) }}">
                </div>

                <!-- Ganti Berkas Scan -->
                <div class="col-md-6 form-group">
                  <label for="file_lampiran" class="font-weight-bold text-dark mb-1">
                    Ganti Berkas Scan <small class="text-muted">(Kosongkan jika tidak diubah)</small>
                  </label>
                  <div class="custom-file">
                    <input type="file" class="custom-file-input @error('file_lampiran') is-invalid @enderror" id="file_lampiran" name="file_lampiran" accept=".pdf,.jpg,.jpeg,.png">
                    <label class="custom-file-label" for="file_lampiran">Pilih berkas baru...</label>
                  </div>
                </div>
              </div>

              <!-- Petunjuk / Isi Disposisi -->
              <div class="form-group">
                <label for="isi_disposisi" class="font-weight-bold text-dark mb-1">
                  Instruksi / Catatan Disposisi
                </label>
                <textarea class="form-control @error('isi_disposisi') is-invalid @enderror" id="isi_disposisi" name="isi_disposisi" rows="2">{{ old('isi_disposisi', $surat->isi_disposisi) }}</textarea>
              </div>

              <!-- Keterangan -->
              <div class="form-group mb-0">
                <label for="keterangan" class="font-weight-bold text-dark mb-1">
                  Keterangan Tambahan
                </label>
                <textarea class="form-control @error('keterangan') is-invalid @enderror" id="keterangan" name="keterangan" rows="2">{{ old('keterangan', $surat->keterangan) }}</textarea>
              </div>

            </div>
          </div>
        </div>

        <!-- Kolom Kanan: Info Agenda & Aksi -->
        <div class="col-lg-4">
          <!-- Info Agenda -->
          <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px; background: #ffffff; border-top: 4px solid #28a745 !important;">
            <div class="card-body p-4">
              <h6 class="font-weight-bold text-dark mb-3">
                <i class="fas fa-info-circle text-success mr-1"></i> Informasi Agenda
              </h6>

              <div class="mb-2">
                <small class="text-muted d-block">Nomor Agenda Buku Masuk:</small>
                <strong class="font-monospace text-success" style="font-size: 1.15rem;">{{ $surat->nomor_agenda }}</strong>
              </div>

              <div class="row mt-2">
                <div class="col-6 mb-2">
                  <small class="text-muted d-block">No. Urut:</small>
                  <span class="font-weight-bold text-dark">#{{ str_pad($surat->no_urut, 3, '0', STR_PAD_LEFT) }}</span>
                </div>
                <div class="col-6 mb-2">
                  <small class="text-muted d-block">Tahun Buku:</small>
                  <span class="font-weight-bold text-dark">{{ $surat->tahun }}</span>
                </div>
                <div class="col-12 mb-2">
                  <small class="text-muted d-block">Dicatat Oleh:</small>
                  <span class="text-dark">{{ $surat->creator->nama_guru ?? 'Administrator' }}</span>
                </div>
                <div class="col-12">
                  <small class="text-muted d-block">Waktu Input:</small>
                  <span class="text-muted small">{{ $surat->created_at ? $surat->created_at->format('d M Y, H:i') : '-' }}</span>
                </div>
              </div>

              @if($surat->file_lampiran)
                <div class="border-top pt-3 mt-3">
                  <small class="text-muted d-block mb-1">Berkas Lampiran Saat Ini:</small>
                  @php
                    $ext = pathinfo($surat->file_lampiran, PATHINFO_EXTENSION);
                    $isPdf = strtolower($ext) === 'pdf';
                    $fileUrl = asset('storage/lampiran_surat_masuk/' . $surat->file_lampiran);
                  @endphp
                  <a href="{{ $fileUrl }}" target="_blank" class="btn btn-outline-success btn-sm btn-block rounded-pill">
                    <i class="fas {{ $isPdf ? 'fa-file-pdf text-danger' : 'fa-file-image' }} mr-1"></i> Buka Lampiran Tersimpan
                  </a>
                </div>
              @endif
            </div>
          </div>

          <!-- Card Tombol Aksi -->
          <div class="card border-0 shadow-sm" style="border-radius: 12px;">
            <div class="card-body p-4">
              <button type="submit" class="btn btn-success btn-block py-2 font-weight-bold shadow-sm" style="border-radius: 8px;">
                <i class="fas fa-save mr-1"></i> Simpan Perubahan
              </button>
              <a href="{{ route('admin.surat-masuk.index') }}" class="btn btn-outline-secondary btn-block mt-2" style="border-radius: 8px;">
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
