@extends($layout)

@section('content')
<!-- Content Header -->
<div class="content-header pb-2">
  <div class="container-fluid">
    <div class="row align-items-center mb-2">
      <div class="col-sm-7 d-flex align-items-center">
        <div class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle shadow-sm mr-3" style="width: 46px; height: 46px; font-size: 1.25rem;">
          <i class="fas fa-file-upload"></i>
        </div>
        <div class="d-flex flex-column justify-content-center">
          <h1 class="m-0 font-weight-bold text-dark" style="line-height: 1.2; font-size: 1.35rem;">Unggah Portofolio & Sertifikat</h1>
          <p class="text-muted mt-1 mb-0" style="font-size: 0.84rem;">Dokumentasikan sertifikasi industri, hasil proyek karya, atau piagam lomba siswa</p>
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
      <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-3" role="alert" style="border-radius: 12px;">
        <div class="d-flex align-items-center">
          <i class="fas fa-exclamation-triangle mr-2 text-danger" style="font-size: 1.2rem;"></i>
          <strong>Terdapat kesalahan pengisian data:</strong>
        </div>
        <ul class="mb-0 mt-2 pl-3 small">
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
      <!-- Kolom Formulir Utama (Kiri) -->
      <div class="col-12 col-lg-8 mb-4">
        <div class="card border-0 shadow-sm" style="border-radius: 14px; overflow: hidden;">
          <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-dark" style="font-size: 1.05rem;">
              <i class="fas fa-upload mr-2 text-primary"></i> Formulir Pengunggahan Berkas Portofolio
            </h6>
            <span class="badge badge-light border text-muted px-2 py-1 font-weight-normal small">
              Dokumen Capaian Siswa
            </span>
          </div>

          <form action="/admin/kejuruan/portofolio" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card-body p-4 pt-3">
              <!-- SEKSI 1: SISWA & KATEGORI -->
              <div class="mb-3 pb-2 border-bottom">
                <span class="badge badge-primary px-2 py-1 mr-1" style="font-size: 0.75rem;">Bagian 1</span>
                <span class="font-weight-bold text-dark" style="font-size: 0.9rem;">Identitas Pemilik & Kategori</span>
              </div>

              <!-- Siswa -->
              <div class="form-group mb-3">
                <label class="font-weight-bold text-dark" style="font-size: 0.85rem;">
                  Pilih Siswa <span class="text-danger">*</span>
                </label>
                <select name="id_siswa" class="form-control select2bs4 @error('id_siswa') is-invalid @enderror" required style="border-radius: 8px;">
                  <option value="">-- Pilih Siswa Pemilik Portofolio --</option>
                  @foreach($siswas as $s)
                    <option value="{{ $s->id_siswa }}" {{ old('id_siswa') == $s->id_siswa ? 'selected' : '' }}>
                      {{ $s->nama_siswa }} ({{ $s->kelas->nama_kelas ?? 'Tanpa Kelas' }}) - NIS: {{ $s->nis }}
                    </option>
                  @endforeach
                </select>
                @error('id_siswa')
                  <small class="text-danger font-weight-500 mt-1 d-block">{{ $message }}</small>
                @enderror
              </div>

              <!-- Kategori & Tanggal -->
              <div class="row">
                <div class="col-md-6 form-group mb-3">
                  <label class="font-weight-bold text-dark" style="font-size: 0.85rem;">
                    Kategori Dokumen <span class="text-danger">*</span>
                  </label>
                  <select name="kategori" class="form-control @error('kategori') is-invalid @enderror" required style="border-radius: 8px;">
                    <option value="sertifikat" {{ old('kategori') == 'sertifikat' ? 'selected' : '' }}>Sertifikat Kompetensi / Pelatihan</option>
                    <option value="proyek_karya" {{ old('kategori') == 'proyek_karya' ? 'selected' : '' }}>Proyek / Hasil Karya Fisik & Digital</option>
                    <option value="prestasi_lomba" {{ old('kategori') == 'prestasi_lomba' ? 'selected' : '' }}>Prestasi Kejuaraan / Lomba</option>
                  </select>
                  @error('kategori')
                    <small class="text-danger font-weight-500 mt-1 d-block">{{ $message }}</small>
                  @enderror
                </div>
                <div class="col-md-6 form-group mb-3">
                  <label class="font-weight-bold text-dark" style="font-size: 0.85rem;">
                    Tanggal Kegiatan / Terbit <span class="text-danger">*</span>
                  </label>
                  <input type="date" name="tanggal_kegiatan" class="form-control @error('tanggal_kegiatan') is-invalid @enderror" value="{{ old('tanggal_kegiatan', date('Y-m-d')) }}" required style="border-radius: 8px;">
                  @error('tanggal_kegiatan')
                    <small class="text-danger font-weight-500 mt-1 d-block">{{ $message }}</small>
                  @enderror
                </div>
              </div>

              <!-- SEKSI 2: RINCIAN KARYA / SERTIFIKAT -->
              <div class="mt-4 mb-3 pb-2 border-bottom">
                <span class="badge badge-primary px-2 py-1 mr-1" style="font-size: 0.75rem;">Bagian 2</span>
                <span class="font-weight-bold text-dark" style="font-size: 0.9rem;">Rincian Portofolio & Lembaga</span>
              </div>

              <!-- Judul Karya / Sertifikat -->
              <div class="form-group mb-3">
                <label class="font-weight-bold text-dark" style="font-size: 0.85rem;">
                  Judul Portofolio / Nama Sertifikat <span class="text-danger">*</span>
                </label>
                <input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror" value="{{ old('judul') }}" placeholder="Contoh: Sertifikasi Junior Web Developer BNSP / Rancang Bangun IoT Smart Garden" required style="border-radius: 8px;">
                @error('judul')
                  <small class="text-danger font-weight-500 mt-1 d-block">{{ $message }}</small>
                @enderror
              </div>

              <!-- Penyelenggara & Kaitan Kompetensi -->
              <div class="row">
                <div class="col-md-6 form-group mb-3">
                  <label class="font-weight-bold text-dark" style="font-size: 0.85rem;">
                    Penyelenggara / Lembaga Penerbit
                  </label>
                  <input type="text" name="penyelenggara" class="form-control" value="{{ old('penyelenggara') }}" placeholder="Contoh: BNSP / LSP SMK / Google Indonesia / LKS" style="border-radius: 8px;">
                </div>
                <div class="col-md-6 form-group mb-3">
                  <label class="font-weight-bold text-dark" style="font-size: 0.85rem;">
                    Relevansi Standar Kompetensi <span class="text-muted font-weight-normal">(Opsional)</span>
                  </label>
                  <select name="id_kompetensi" class="form-control select2bs4" style="border-radius: 8px;">
                    <option value="">-- Tidak Terkait Kompetensi Khusus --</option>
                    @foreach($kompetensis as $k)
                      <option value="{{ $k->id_kompetensi }}" {{ old('id_kompetensi') == $k->id_kompetensi ? 'selected' : '' }}>
                        [{{ $k->kode_kompetensi }}] {{ Str::limit($k->nama_kompetensi, 45) }}
                      </option>
                    @endforeach
                  </select>
                </div>
              </div>

              <!-- SEKSI 3: BERKAS DOKUMEN & TAUTAN -->
              <div class="mt-4 mb-3 pb-2 border-bottom">
                <span class="badge badge-primary px-2 py-1 mr-1" style="font-size: 0.75rem;">Bagian 3</span>
                <span class="font-weight-bold text-dark" style="font-size: 0.9rem;">Berkas Bukti & Tautan Karya</span>
              </div>

              <!-- File Unggahan (Batas 5MB) -->
              <div class="form-group mb-3">
                <label class="font-weight-bold text-dark" style="font-size: 0.85rem;">
                  Unggah Berkas Bukti (PDF, JPG, JPEG, PNG) <span class="text-danger">*</span>
                </label>
                <div class="custom-file">
                  <input type="file" name="file_dokumen" class="custom-file-input @error('file_dokumen') is-invalid @enderror" id="customFile" accept=".pdf,.jpg,.jpeg,.png" required onchange="updateFileLabel(this)">
                  <label class="custom-file-label" for="customFile" id="customFileLabel" style="border-radius: 8px;">Pilih file sertifikat atau bukti karya...</label>
                </div>
                <div class="d-flex align-items-center justify-content-between mt-1">
                  <small class="text-muted">
                    <i class="fas fa-shield-alt text-info mr-1"></i> Format berkas: <strong>PDF, JPG, JPEG, PNG</strong>
                  </small>
                  <small class="text-muted">
                    <i class="fas fa-weight-hanging text-secondary mr-1"></i> Batas ukuran: <strong>Maks. 5 MB</strong>
                  </small>
                </div>
                @error('file_dokumen')
                  <small class="text-danger font-weight-500 mt-1 d-block">{{ $message }}</small>
                @enderror
              </div>

              <!-- Tautan Karya -->
              <div class="form-group mb-3">
                <label class="font-weight-bold text-dark" style="font-size: 0.85rem;">
                  Tautan Karya / Demo Online <span class="text-muted font-weight-normal">(Opsional)</span>
                </label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text bg-light border-right-0"><i class="fas fa-link text-muted"></i></span>
                  </div>
                  <input type="url" name="tautan_karya" class="form-control border-left-0" value="{{ old('tautan_karya') }}" placeholder="https://github.com/... atau https://youtube.com/watch?v=..." style="border-radius: 0 8px 8px 0;">
                </div>
                <small class="form-text text-muted">Tautan repository kode GitHub, video demo produk di YouTube/Google Drive, atau web aplikasi portofolio.</small>
              </div>

              <!-- SEKSI 4: DESKRIPSI -->
              <div class="mt-4 mb-3 pb-2 border-bottom">
                <span class="badge badge-primary px-2 py-1 mr-1" style="font-size: 0.75rem;">Bagian 4</span>
                <span class="font-weight-bold text-dark" style="font-size: 0.9rem;">Deskripsi Singkat Pencapaian</span>
              </div>

              <div class="form-group mb-0">
                <label class="font-weight-bold text-dark" style="font-size: 0.85rem;">
                  Uraian Karya / Rincian Materi Sertifikasi
                </label>
                <textarea name="deskripsi" id="deskripsi" class="form-control summernote-kejuruan" rows="3" placeholder="Tuliskan gambaran ringkas karya yang dibuat, peran siswa, atau rincian materi sertifikasi yang ditempuh...">{{ old('deskripsi') }}</textarea>
                <small class="form-text text-muted">Jelaskan ringkasan materi, peran kontribusi siswa dalam proyek, atau kompetensi inti yang diperoleh.</small>
              </div>
            </div>

            <!-- Card Footer -->
            <div class="card-footer bg-light py-3 px-4 d-flex align-items-center justify-content-end border-top" style="gap: 10px;">
              <a href="/admin/kejuruan/portofolio" class="btn btn-outline-secondary px-3 mr-2" style="border-radius: 8px; font-weight: 500;">
                <i class="fas fa-arrow-left mr-1"></i> Batal
              </a>
              <button type="submit" class="btn btn-primary px-4 shadow-sm" style="border-radius: 8px; font-weight: 600;">
                <i class="fas fa-cloud-upload-alt mr-1"></i> Simpan & Unggah Portofolio
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- Kolom Informasi & Panduan Portofolio (Kanan) -->
      <div class="col-12 col-lg-4 mb-4">
        <!-- Kartu 1: Pedoman Kategori -->
        <div class="card border-0 shadow-sm mb-3" style="border-radius: 14px;">
          <div class="card-header bg-white py-3 border-bottom d-flex align-items-center">
            <div class="rounded-circle d-flex align-items-center justify-content-center bg-primary text-white mr-2" style="width: 32px; height: 32px; font-size: 0.9rem;">
              <i class="fas fa-info"></i>
            </div>
            <h6 class="font-weight-bold text-dark mb-0">Ketentuan Kategori Dokumen</h6>
          </div>
          <div class="card-body p-3 small text-secondary">
            <div class="mb-3">
              <span class="badge badge-success px-2 py-1 mb-1 font-weight-bold">
                <i class="fas fa-certificate mr-1"></i> Sertifikat Kompetensi
              </span>
              <p class="mb-0 text-muted" style="line-height: 1.4;">
                Diterbitkan oleh BNSP, LSP SMK, Vendor Industri (Cisco, Mikrotik, Google, dll.), atau pelatihan kejuruan resmi.
              </p>
            </div>
            <div class="mb-3">
              <span class="badge badge-info px-2 py-1 mb-1 font-weight-bold">
                <i class="fas fa-tools mr-1"></i> Proyek Karya Nyata
              </span>
              <p class="mb-0 text-muted" style="line-height: 1.4;">
                Hasil produk fisik, rekayasa perangkat lunak, sistem IoT, atau jobsheet mandiri disertai foto produk dan link demo.
              </p>
            </div>
            <div>
              <span class="badge badge-warning text-dark px-2 py-1 mb-1 font-weight-bold">
                <i class="fas fa-trophy mr-1"></i> Prestasi Lomba
              </span>
              <p class="mb-0 text-muted" style="line-height: 1.4;">
                Piagam kejuaraan LKS (Lomba Kompetensi Siswa), kompetisi keahlian, atau kejuaraan kejuruan tingkat daerah hingga nasional.
              </p>
            </div>
          </div>
        </div>

        <!-- Kartu 2: Alur Validasi Portofolio -->
        <div class="card border-0 shadow-sm mb-3" style="border-radius: 14px; background: linear-gradient(to bottom, #ffffff, #f8fafc);">
          <div class="card-header bg-transparent py-3 border-bottom d-flex align-items-center">
            <div class="rounded-circle d-flex align-items-center justify-content-center bg-success text-white mr-2" style="width: 32px; height: 32px; font-size: 0.9rem;">
              <i class="fas fa-check-double"></i>
            </div>
            <h6 class="font-weight-bold text-dark mb-0">Alur Pengesahan Berkas</h6>
          </div>
          <div class="card-body p-3 small">
            <div class="d-flex align-items-start mb-3">
              <div class="badge badge-pill badge-primary mr-2 mt-1 px-2">1</div>
              <div>
                <strong class="text-dark d-block">Pengunggahan Berkas:</strong>
                <span class="text-muted">Guru atau staf mendokumentasikan sertifikat/karya siswa ke formulir ini.</span>
              </div>
            </div>
            <div class="d-flex align-items-start mb-3">
              <div class="badge badge-pill badge-warning mr-2 mt-1 px-2">2</div>
              <div>
                <strong class="text-dark d-block">Verifikasi Kaprog:</strong>
                <span class="text-muted">Kepala Program menelaah keaslian berkas dan memberikan persetujuan resmi.</span>
              </div>
            </div>
            <div class="d-flex align-items-start">
              <div class="badge badge-pill badge-success mr-2 mt-1 px-2">3</div>
              <div>
                <strong class="text-dark d-block">Buku Induk & Transkrip:</strong>
                <span class="text-muted">Dokumen yang disetujui tercatat otomatis di Buku Induk Capaian & Transkrip Portofolio kelulusan siswa.</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Kartu 3: Kiat Kualitas Berkas -->
        <div class="card border-0 shadow-sm" style="border-radius: 14px;">
          <div class="card-body p-3 small bg-light rounded text-muted">
            <div class="d-flex align-items-center mb-1 text-dark font-weight-bold">
              <i class="fas fa-lightbulb text-warning mr-2" style="font-size: 1rem;"></i> Tips Kualitas Berkas
            </div>
            <p class="mb-0" style="font-size: 0.8rem; line-height: 1.45;">
              Pastikan scan atau foto sertifikat beresolusi tajam, tidak berbayang, nomor piagam terbaca jelas, dan tanggal terbit sesuai dengan fisik dokumen.
            </p>
          </div>
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

  document.addEventListener('DOMContentLoaded', function () {
    if (window.jQuery && $.fn.summernote) {
      $('#deskripsi').summernote({
        placeholder: 'Tuliskan gambaran ringkas karya yang dibuat, peran siswa, atau rincian materi sertifikasi yang ditempuh...',
        tabsize: 2,
        height: 140,
        tooltip: false,
        toolbar: [
          ['style', ['bold', 'italic', 'underline', 'strikethrough']],
          ['para', ['ul', 'ol']],
          ['insert', ['link']],
          ['misc', ['clear']]
        ]
      });
    }
  });
</script>
@endsection
