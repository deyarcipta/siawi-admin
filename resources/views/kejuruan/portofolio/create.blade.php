@extends($layout)

@section('content')
<!-- Content Header -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row align-items-center mb-2">
      <div class="col-sm-7 d-flex align-items-center">
        <i class="fas fa-file-upload text-primary mr-3" style="font-size: 2rem; flex-shrink: 0;"></i>
        <div class="d-flex flex-column justify-content-center">
          <h1 class="m-0 font-weight-bold text-dark" style="line-height: 1.2;">Unggah Portofolio & Sertifikat</h1>
          <p class="text-muted mt-1 mb-0" style="line-height: 1.2; font-size: 0.85rem;">Dokumentasikan sertifikasi industri, hasil proyek karya, atau piagam lomba siswa</p>
        </div>
      </div>
      <div class="col-sm-5">
        <ol class="breadcrumb float-sm-right bg-transparent p-0 mb-0">
          <li class="breadcrumb-item"><a href="/admin/dashboard" class="text-primary font-weight-500">Dashboard</a></li>
          <li class="breadcrumb-item"><a href="/admin/kejuruan/portofolio" class="text-primary font-weight-500">Portofolio</a></li>
          <li class="breadcrumb-item active">Unggah Berkas</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<!-- Main content -->
<div class="content">
  <div class="container-fluid">
    @if($errors->any())
      <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert" style="border-radius: 12px;">
        <i class="fas fa-exclamation-triangle mr-2"></i> <strong>Terdapat kesalahan input:</strong>
        <ul class="mb-0 mt-2 pl-3">
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    @endif

    <div class="row">
      <div class="col-lg-8 mx-auto">
        <div class="card border-0 shadow-sm" style="border-radius: 12px;">
          <div class="card-header bg-white py-3 border-0">
            <h6 class="m-0 font-weight-bold text-dark">
              <i class="fas fa-upload mr-2 text-primary"></i> Formulir Pengunggahan Berkas Portofolio
            </h6>
          </div>
          <form action="/admin/kejuruan/portofolio" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card-body p-4">
              <!-- Siswa -->
              <div class="form-group mb-3">
                <label class="font-weight-bold text-secondary" style="font-size: 0.85rem;">
                  Pilih Siswa <span class="text-danger">*</span>
                </label>
                <select name="id_siswa" class="form-control select2bs4" required>
                  <option value="">-- Pilih Siswa Pemilik Portofolio --</option>
                  @foreach($siswas as $s)
                    <option value="{{ $s->id_siswa }}" {{ old('id_siswa') == $s->id_siswa ? 'selected' : '' }}>
                      {{ $s->nama_siswa }} ({{ $s->kelas->nama_kelas ?? 'Tanpa Kelas' }}) - NIS: {{ $s->nis }}
                    </option>
                  @endforeach
                </select>
              </div>

              <!-- Kategori & Tanggal -->
              <div class="row">
                <div class="col-md-6 form-group mb-3">
                  <label class="font-weight-bold text-secondary" style="font-size: 0.85rem;">
                    Kategori Dokumen <span class="text-danger">*</span>
                  </label>
                  <select name="kategori" class="form-control" required>
                    <option value="sertifikat" {{ old('kategori') == 'sertifikat' ? 'selected' : '' }}>Sertifikat Kompetensi / Pelatihan</option>
                    <option value="proyek_karya" {{ old('kategori') == 'proyek_karya' ? 'selected' : '' }}>Proyek / Hasil Karya Fisik & Digital</option>
                    <option value="prestasi_lomba" {{ old('kategori') == 'prestasi_lomba' ? 'selected' : '' }}>Prestasi Kejuaraan / Lomba</option>
                  </select>
                </div>
                <div class="col-md-6 form-group mb-3">
                  <label class="font-weight-bold text-secondary" style="font-size: 0.85rem;">
                    Tanggal Kegiatan / Terbit <span class="text-danger">*</span>
                  </label>
                  <input type="date" name="tanggal_kegiatan" class="form-control" value="{{ old('tanggal_kegiatan', date('Y-m-d')) }}" required>
                </div>
              </div>

              <!-- Judul Karya / Sertifikat -->
              <div class="form-group mb-3">
                <label class="font-weight-bold text-secondary" style="font-size: 0.85rem;">
                  Judul Portofolio / Nama Sertifikat <span class="text-danger">*</span>
                </label>
                <input type="text" name="judul" class="form-control" value="{{ old('judul') }}" placeholder="Contoh: Sertifikasi Junior Web Developer BNSP / Rancang Bangun IoT Smart Garden" required>
              </div>

              <!-- Penyelenggara & Kaitan Kompetensi -->
              <div class="row">
                <div class="col-md-6 form-group mb-3">
                  <label class="font-weight-bold text-secondary" style="font-size: 0.85rem;">
                    Penyelenggara / Lembaga Penerbit
                  </label>
                  <input type="text" name="penyelenggara" class="form-control" value="{{ old('penyelenggara') }}" placeholder="Contoh: BNSP / LSP SMK / Google Indonesia / LKS Nasional">
                </div>
                <div class="col-md-6 form-group mb-3">
                  <label class="font-weight-bold text-secondary" style="font-size: 0.85rem;">
                    Relevansi Standar Kompetensi (Opsional)
                  </label>
                  <select name="id_kompetensi" class="form-control select2bs4">
                    <option value="">-- Tidak Terkait Kompetensi Khusus --</option>
                    @foreach($kompetensis as $k)
                      <option value="{{ $k->id_kompetensi }}" {{ old('id_kompetensi') == $k->id_kompetensi ? 'selected' : '' }}>
                        [{{ $k->kode_kompetensi }}] {{ Str::limit($k->nama_kompetensi, 45) }}
                      </option>
                    @endforeach
                  </select>
                </div>
              </div>

              <!-- File Unggahan (Batas 5MB, format aman) -->
              <div class="form-group mb-3">
                <label class="font-weight-bold text-secondary" style="font-size: 0.85rem;">
                  Unggah Berkas Bukti (PDF, JPG, JPEG, PNG) <span class="text-danger">*</span>
                </label>
                <div class="custom-file">
                  <input type="file" name="file_dokumen" class="custom-file-input" id="customFile" accept=".pdf,.jpg,.jpeg,.png" required onchange="updateFileLabel(this)">
                  <label class="custom-file-label" for="customFile" id="customFileLabel">Pilih file sertifikat atau bukti karya...</label>
                </div>
                <small class="form-text text-muted">
                  <i class="fas fa-shield-alt text-info mr-1"></i> Format berkas yang didukung: <strong>PDF, JPG, JPEG, PNG</strong>. Ukuran maksimum: <strong>5 MB</strong>.
                </small>
              </div>

              <!-- Tautan Karya -->
              <div class="form-group mb-3">
                <label class="font-weight-bold text-secondary" style="font-size: 0.85rem;">
                  Tautan Karya / Demo Online (Opsional)
                </label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-link"></i></span>
                  </div>
                  <input type="url" name="tautan_karya" class="form-control" value="{{ old('tautan_karya') }}" placeholder="https://github.com/... atau https://youtube.com/watch?v=...">
                </div>
                <small class="form-text text-muted">Tautan repository kode, video demonstrasi karya di YouTube/Google Drive, atau website portofolio.</small>
              </div>

              <!-- Deskripsi Ringkas -->
              <div class="form-group mb-0">
                <label class="font-weight-bold text-secondary" style="font-size: 0.85rem;">
                  Deskripsi Singkat Karya / Pencapaian
                </label>
                <textarea name="deskripsi" class="form-control" rows="3" placeholder="Tuliskan gambaran ringkas karya yang dibuat, peran siswa, atau rincian materi sertifikasi yang ditempuh...">{{ old('deskripsi') }}</textarea>
              </div>
            </div>

            <!-- Card Footer -->
            <div class="card-footer bg-light border-0 py-3 d-flex justify-content-between align-items-center">
              <a href="/admin/kejuruan/portofolio" class="btn btn-outline-secondary" style="border-radius: 8px;">
                <i class="fas fa-arrow-left mr-1"></i> Kembali ke Daftar
              </a>
              <button type="submit" class="btn btn-primary" style="border-radius: 8px;">
                <i class="fas fa-cloud-upload-alt mr-1"></i> Simpan & Unggah Portofolio
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  function updateFileLabel(input) {
    if (input.files && input.files.length > 0) {
      document.getElementById('customFileLabel').innerText = input.files[0].name;
    }
  }
</script>
@endsection
