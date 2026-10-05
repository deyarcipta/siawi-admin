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
          <h1 class="m-0 font-weight-bold text-dark" style="line-height: 1.2; font-size: 1.35rem;">Catat Surat Masuk Baru</h1>
          <p class="text-muted mt-1 mb-0" style="line-height: 1.2; font-size: 0.85rem;">Input surat dari dinas, instansi, atau orang tua & terbitkan nomor agenda buku masuk</p>
        </div>
      </div>
      <div class="col-sm-5">
        <ol class="breadcrumb float-sm-right bg-transparent p-0 mb-0">
          <li class="breadcrumb-item"><a href="/admin/dashboard" class="text-primary font-weight-500">Dashboard</a></li>
          <li class="breadcrumb-item"><a href="{{ route('admin.surat-masuk.index') }}" class="text-primary font-weight-500">Surat Masuk</a></li>
          <li class="breadcrumb-item active">Catat Baru</li>
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

    <form action="{{ route('admin.surat-masuk.store') }}" method="POST" enctype="multipart/form-data">
      @csrf

      <div class="row">
        <!-- Kolom Kiri: Form Input -->
        <div class="col-lg-8">
          <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px;">
            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center">
              <i class="fas fa-envelope-open-text text-success mr-2"></i>
              <h5 class="font-weight-bold text-dark mb-0" style="font-size: 1rem;">Rincian Surat Masuk</h5>
            </div>

            <div class="card-body">
              <div class="row">
                <!-- Nomor Surat Asli -->
                <div class="col-md-6 form-group">
                  <label for="nomor_surat_asal" class="font-weight-bold text-dark mb-1">
                    Nomor Surat Asal / Pengirim <span class="text-danger">*</span>
                  </label>
                  <input type="text" class="form-control @error('nomor_surat_asal') is-invalid @enderror" id="nomor_surat_asal" name="nomor_surat_asal" value="{{ old('nomor_surat_asal') }}" placeholder="Contoh: 421.5/120/Disdik/2026" required>
                  <small class="text-muted">Nomor surat yang tertera pada berkas fisik pengirim.</small>
                </div>

                <!-- Instansi / Pengirim -->
                <div class="col-md-6 form-group">
                  <label for="asal_surat" class="font-weight-bold text-dark mb-1">
                    Asal / Instansi Pengirim <span class="text-danger">*</span>
                  </label>
                  <input type="text" class="form-control @error('asal_surat') is-invalid @enderror" id="asal_surat" name="asal_surat" value="{{ old('asal_surat') }}" placeholder="Contoh: Dinas Pendidikan Provinsi / PT. Telkom Indonesia" required>
                </div>
              </div>

              <!-- Perihal -->
              <div class="form-group">
                <label for="perihal" class="font-weight-bold text-dark mb-1">
                  Perihal / Isi Ringkas Surat <span class="text-danger">*</span>
                </label>
                <input type="text" class="form-control @error('perihal') is-invalid @enderror" id="perihal" name="perihal" value="{{ old('perihal') }}" placeholder="Contoh: Undangan Sosialisasi Kurikulum SMK / Penawaran Kerjasama Magang" required>
              </div>

              <div class="row">
                <!-- Tanggal Surat Asli -->
                <div class="col-md-6 form-group">
                  <label for="tanggal_surat" class="font-weight-bold text-dark mb-1">
                    Tanggal Surat Terbit <span class="text-danger">*</span>
                  </label>
                  <input type="date" class="form-control @error('tanggal_surat') is-invalid @enderror" id="tanggal_surat" name="tanggal_surat" value="{{ old('tanggal_surat', $today) }}" required>
                </div>

                <!-- Tanggal Diterima di Sekolah -->
                <div class="col-md-6 form-group">
                  <label for="tanggal_diterima" class="font-weight-bold text-dark mb-1">
                    Tanggal Diterima di Sekolah <span class="text-danger">*</span>
                  </label>
                  <input type="date" class="form-control @error('tanggal_diterima') is-invalid @enderror" id="tanggal_diterima" name="tanggal_diterima" value="{{ old('tanggal_diterima', $today) }}" required>
                  <small class="text-muted">Digunakan untuk urutan tahun buku agenda.</small>
                </div>
              </div>

              <div class="row">
                <!-- Disposisi Kepada -->
                <div class="col-md-6 form-group">
                  <label for="disposisi_kepada" class="font-weight-bold text-dark mb-1">
                    Disposisi / Diteruskan Kepada <small class="text-muted">(Opsional)</small>
                  </label>
                  <input type="text" class="form-control @error('disposisi_kepada') is-invalid @enderror" id="disposisi_kepada" name="disposisi_kepada" value="{{ old('disposisi_kepada') }}" placeholder="Contoh: Kepala Sekolah / Waka Kurikulum / Waka Kesiswaan">
                </div>

                <!-- Berkas Scan -->
                <div class="col-md-6 form-group">
                  <label for="file_lampiran" class="font-weight-bold text-dark mb-1">
                    Upload Berkas Scan <small class="text-muted">(PDF / JPG / PNG, Max 5MB)</small>
                  </label>
                  <div class="custom-file">
                    <input type="file" class="custom-file-input @error('file_lampiran') is-invalid @enderror" id="file_lampiran" name="file_lampiran" accept=".pdf,.jpg,.jpeg,.png">
                    <label class="custom-file-label" for="file_lampiran">Pilih berkas...</label>
                  </div>
                </div>
              </div>

              <!-- Petunjuk / Isi Disposisi -->
              <div class="form-group">
                <label for="isi_disposisi" class="font-weight-bold text-dark mb-1">
                  Instruksi / Catatan Disposisi <small class="text-muted">(Opsional)</small>
                </label>
                <textarea class="form-control @error('isi_disposisi') is-invalid @enderror" id="isi_disposisi" name="isi_disposisi" rows="2" placeholder="Contoh: Mohon dipelajari dan ditindaklanjuti untuk rapat besok pagi...">{{ old('isi_disposisi') }}</textarea>
              </div>

              <!-- Keterangan -->
              <div class="form-group mb-0">
                <label for="keterangan" class="font-weight-bold text-dark mb-1">
                  Keterangan Tambahan <small class="text-muted">(Opsional)</small>
                </label>
                <textarea class="form-control @error('keterangan') is-invalid @enderror" id="keterangan" name="keterangan" rows="2" placeholder="Lokasi simpan fisik arsip, map lemari, dll...">{{ old('keterangan') }}</textarea>
              </div>

            </div>
          </div>
        </div>

        <!-- Kolom Kanan: Preview No Agenda & Aksi -->
        <div class="col-lg-4">
          <!-- Preview Box Agenda -->
          <div class="card border-0 shadow-sm mb-4 bg-white" style="border-radius: 12px; border-top: 4px solid #16a34a !important;">
            <div class="card-body p-4 text-center">
              <div class="text-success text-uppercase font-weight-bold" style="font-size: 0.75rem; letter-spacing: 1px;">
                <i class="fas fa-barcode mr-1"></i> Nomor Agenda Masuk
              </div>
              
              <div class="my-3 p-3 rounded text-dark" style="background: #f8fafc; border: 1px dashed #cbd5e1;">
                <div class="text-muted small mb-1" style="font-size: 0.78rem;">No. Agenda Otomatis:</div>
                <div class="font-weight-bold text-success font-monospace" style="font-size: 1.3rem;">
                  {{ $nextAgendaPreview['nomor_agenda'] }}
                </div>
              </div>

              <div class="row text-left mt-3">
                <div class="col-6 mb-2">
                  <small class="text-muted d-block" style="font-size: 0.75rem;">Urutan Agenda:</small>
                  <span class="font-weight-bold text-dark font-monospace" style="font-size: 0.95rem;">#{{ str_pad($nextAgendaPreview['no_urut'], 3, '0', STR_PAD_LEFT) }}</span>
                </div>
                <div class="col-6 mb-2">
                  <small class="text-muted d-block" style="font-size: 0.75rem;">Tahun Buku:</small>
                  <span class="font-weight-bold text-dark font-monospace" style="font-size: 0.95rem;">{{ $nextAgendaPreview['tahun'] }}</span>
                </div>
              </div>

              <div class="border-top pt-3 mt-3 text-left">
                <small class="text-muted" style="font-size: 0.75rem; line-height: 1.3; display: block;">
                  <i class="fas fa-check-circle text-success mr-1"></i> Nomor agenda dicatat secara berurutan dan direset otomatis per awal tahun baru.
                </small>
              </div>
            </div>
          </div>

          <!-- Card Tombol Aksi -->
          <div class="card border-0 shadow-sm" style="border-radius: 12px;">
            <div class="card-body p-4">
              <button type="submit" class="btn btn-success btn-block py-2 font-weight-bold shadow-sm" style="border-radius: 8px;">
                <i class="fas fa-save mr-1"></i> Simpan Buku Surat Masuk
              </button>
              <a href="{{ route('admin.surat-masuk.index') }}" class="btn btn-outline-secondary btn-block mt-2" style="border-radius: 8px;">
                <i class="fas fa-times mr-1"></i> Batalkan
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
